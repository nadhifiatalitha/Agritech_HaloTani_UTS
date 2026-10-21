<?php

namespace App\Filament\Pages;

use App\Models\JadwalTanam as JadwalTanamModel;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class JadwalTanam extends Page
{
    protected string $view = 'filament.pages.jadwal-tanam';

    protected static ?string $title = 'Jadwal Tanam';
    protected static ?string $navigationLabel = 'Jadwal Tanam';
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public ?string $selectedId = null;

    // State Edit
    public $editData = [
        'nama_tanaman' => '',
        'lokasi_lahan' => '',
        'tanggal_tanam' => '',
        'catatan' => '',
    ];
    public bool $isEditing = false;

    public function mount(): void
    {
        $firstItem = $this->jadwalList->first();
        if ($firstItem) {
            $this->pilih((string) $firstItem->id);
        }
    }

    public function pilih(string $id): void
    {
        $this->selectedId = $id;
        $this->isEditing = false;
        
        $item = $this->selectedJadwal;
        if ($item) {
            $this->editData = [
                'nama_tanaman' => $item->nama_tanaman,
                'lokasi_lahan' => $item->lokasi_lahan,
                'tanggal_tanam' => $item->tanggal_tanam,
                'catatan' => $item->catatan,
            ];
        }
    }

    public function toggleEdit(): void
    {
        $this->isEditing = !$this->isEditing;
    }

    public function updateJadwal(): void
    {
        $item = $this->selectedJadwal;
        if ($item) {
            $item->update($this->editData);
            $this->isEditing = false;
        }
    }

    public function hapusJadwal(): void
    {
        $item = $this->selectedJadwal;
        if ($item) {
            $item->delete();
            $this->selectedId = null;
            $firstItem = $this->jadwalList->first();
            if ($firstItem) {
                $this->pilih((string) $firstItem->id);
            }
        }
    }

    public function getJadwalListProperty(): Collection
    {
        return JadwalTanamModel::orderBy('tanggal_tanam', 'desc')->get();
    }

    public function getSelectedJadwalProperty(): ?JadwalTanamModel
    {
        if (!$this->selectedId) {
            return $this->jadwalList->first();
        }

        return JadwalTanamModel::find($this->selectedId);
    }

    public function getJumlahHariTanamProperty(): int
    {
        $item = $this->selectedJadwal;
        if (!$item || !$item->tanggal_tanam) {
            return 0;
        }

        $tglTanam = Carbon::parse($item->tanggal_tanam)->startOfDay();
        $sekarang = Carbon::now()->startOfDay();

        return (int) $tglTanam->diffInDays($sekarang, false);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('tambahJadwal')
                ->label('Mulai Menanam')
                ->model(JadwalTanamModel::class)
                ->form([
                    TextInput::make('nama_tanaman')
                        ->label('Nama Tanaman')
                        ->required(),
                    TextInput::make('lokasi_lahan')
                        ->label('Lokasi Lahan')
                        ->required(),
                    DatePicker::make('tanggal_tanam')
                        ->label('Tanggal Pertama Menanam')
                        ->default(now())
                        ->required(),
                    Textarea::make('catatan')
                        ->label('Catatan Awal (Opsional)'),
                ])
                ->successNotificationTitle('Jadwal tanam berhasil dibuat!'),
        ];
    }
}