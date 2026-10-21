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

    public ?string $editingId = null;

    public $editData = [
        'nama_tanaman' => '',
        'lokasi_lahan' => '',
        'tanggal_tanam' => '',
        'catatan' => '',
    ];

    public function editJadwal(string $id): void
    {
        $this->editingId = $id;
        $item = JadwalTanamModel::find($id);
        
        if ($item) {
            $this->editData = [
                'nama_tanaman' => $item->nama_tanaman,
                'lokasi_lahan' => $item->lokasi_lahan,
                'tanggal_tanam' => $item->tanggal_tanam,
                'catatan' => $item->catatan,
            ];
        }
    }

    public function batalEdit(): void
    {
        $this->editingId = null;
    }

    public function updateJadwal(): void
    {
        if ($this->editingId) {
            $item = JadwalTanamModel::find($this->editingId);
            if ($item) {
                $item->update($this->editData);
            }
            $this->editingId = null;
        }
    }

    public function hapusJadwal(string $id): void
    {
        $item = JadwalTanamModel::find($id);
        if ($item) {
            $item->delete();
        }
        if ($this->editingId === $id) {
            $this->editingId = null;
        }
    }

    public function getJadwalListProperty(): Collection
    {
        return JadwalTanamModel::orderBy('tanggal_tanam', 'desc')->get();
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