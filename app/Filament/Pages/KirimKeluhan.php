<?php

namespace App\Filament\Pages;

use App\Filament\Pages\RiwayatAnalisis;
use App\Models\Keluhan;
use App\Services\KiraAIService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
// use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Textarea;

class KirimKeluhan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-plus';
    protected static ?string $navigationLabel = 'Kirim Keluhan';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Kirim Keluhan Tanaman';
    protected string $view = 'filament.pages.kirim-keluhan';

    public string $jenisTanaman = '';
    public string $lokasiLahan = '';
    public string $urgensi = 'Sedang';
    public string $deskripsi = '';

    public function submit()
    {
        // Validasi input sederhana agar tidak kosong
        $this->validate([
            'jenisTanaman' => 'required',
            'lokasiLahan'  => 'required',
            'deskripsi'    => 'required',
        ]);

        try {
            // 1. Format Teks Keluhan dengan Prefix yang Konsisten untuk Table Parser
            $teksKeluhanLengkap = "Jenis Tanaman: {$this->jenisTanaman}\nLokasi: {$this->lokasiLahan}\nUrgensi: {$this->urgensi}\nKeluhan: {$this->deskripsi}";

        // 5. Panggil AI Service
        $hasilAi = KiraAIService::generateSolusi(
            $this->deskripsi,
            $this->lokasiLahan
        );

        // 6. Simpan ke Database
        $keluhan = Keluhan::create([
            'id_keluhan'      => (string) Str::uuid(),
            'id_petani'       => auth()->id() ?? '01a11165-ec48-7296-ab63-1d1a601c3cec',
            'teks_keluhan'    => $teksKeluhanLengkap,
            'tingkat_urgensi' => $this->urgensi,
            'hasil_analisis'  => $hasilAi,
            'tanggal'         => now(),
            'status'          => 'selesai',
        ]);

        Notification::make()
            ->title('Analisis AI Selesai!')
            ->body('Hasil analisis keluhan Anda telah berhasil disimpan.')
            ->success()
            ->send();

        $this->reset(['jenisTanaman', 'lokasiLahan', 'urgensi', 'deskripsi']);

        return $this->redirect(RiwayatAnalisis::getUrl());

    } catch (\Exception $e) {
        Notification::make()
            ->title('Gagal Menyimpan Laporan')
            ->body($e->getMessage())
            ->danger()
            ->send();
    }
}

    public function batal(): void
    {
        $this->reset(['jenisTanaman', 'lokasiLahan', 'urgensi', 'deskripsi']);
    }

    public function setUrgensi(string $level): void
    {
        $this->urgensi = $level;
    }

}