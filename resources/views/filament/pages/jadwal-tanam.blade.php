<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        {{-- KOLOM KIRI: DAFTAR TANAMAN --}}
        <div class="md:col-span-5 space-y-4">
            <x-filament::section header-heading="Daftar Tanaman">
                <div class="space-y-3 max-h-[600px] overflow-y-auto pr-1">
                    @forelse($this->jadwalList as $item)
                        <div 
                            wire:click="pilih('{{ $item->id }}')"
                            class="p-4 rounded-xl border transition cursor-pointer bg-white dark:bg-gray-800 shadow-sm hover:shadow-md border-gray-200 dark:border-gray-700 {{ (string)$selectedId === (string)$item->id ? 'ring-2 ring-primary-600 border-primary-600' : '' }}">
                            
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ $item->nama_tanaman }}</h3>
                                
                                <x-filament::badge color="success">
                                    {{ \Carbon\Carbon::parse($item->tanggal_tanam)->startOfDay()->diffInDays(now()->startOfDay(), false) >= 0 ? \Carbon\Carbon::parse($item->tanggal_tanam)->startOfDay()->diffInDays(now()->startOfDay(), false) . ' Hari Tanam' : 'Belum Mulai' }}
                                </x-filament::badge>
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                <p class="flex items-center gap-1">
                                    <x-filament::icon icon="heroicon-m-map-pin" class="w-4 h-4 text-gray-400" />
                                    {{ $item->lokasi_lahan }}
                                </p>
                                <p class="flex items-center gap-1">
                                    <x-filament::icon icon="heroicon-m-calendar" class="w-4 h-4 text-gray-400" />
                                    Tgl Tanam: {{ \Carbon\Carbon::parse($item->tanggal_tanam)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500 text-sm">
                            Belum ada data tanaman. Klik tombol "Mulai Menanam" di atas.
                        </div>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        {{-- KOLOM KANAN: DETAIL & KONTROL CRUD --}}
        <div class="md:col-span-7">
            @if($this->selectedJadwal)
                <x-filament::section>
                    <x-slot name="heading">
                        {{ $this->selectedJadwal->nama_tanaman }}
                    </x-slot>

                    <x-slot name="headerEnd">
                        <div class="flex gap-2">
                            <x-filament::button 
                                wire:click="toggleEdit" 
                                color="warning" 
                                size="sm" 
                                icon="heroicon-m-pencil-square">
                                {{ $isEditing ? 'Batal' : 'Edit / Update' }}
                            </x-filament::button>

                            <x-filament::button 
                                wire:click="hapusJadwal" 
                                wire:confirm="Tidak jadi menanam? Data ini akan dihapus."
                                color="danger" 
                                size="sm" 
                                icon="heroicon-m-trash">
                                Hapus
                            </x-filament::button>
                        </div>
                    </x-slot>

                    @if($isEditing)
                        {{-- FORM UPDATE --}}
                        <form wire:submit.prevent="updateJadwal" class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tanaman</label>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" wire:model="editData.nama_tanaman" required />
                                </x-filament::input.wrapper>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi Lahan</label>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="text" wire:model="editData.lokasi_lahan" required />
                                </x-filament::input.wrapper>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Tanam</label>
                                <x-filament::input.wrapper>
                                    <x-filament::input type="date" wire:model="editData.tanggal_tanam" required />
                                </x-filament::input.wrapper>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Update Catatan (Kondisi / Rusak / Gagal)</label>
                                <x-filament::input.wrapper>
                                    <textarea wire:model="editData.catatan" rows="3" class="w-full text-sm border-none focus:ring-0 bg-transparent dark:text-white" placeholder="Misal: Tanaman terserang hama / gagal panen"></textarea>
                                </x-filament::input.wrapper>
                            </div>

                            <x-filament::button type="submit" color="success" class="w-full">
                                Simpan Perubahan
                            </x-filament::button>
                        </form>
                    @else
                        {{-- TAMPILAN DETAIL --}}
                        <div class="space-y-6">
                            <div class="p-4 bg-primary-50 dark:bg-primary-950/50 border border-primary-200 dark:border-primary-800 rounded-xl flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-primary-700 dark:text-primary-300 font-semibold uppercase">Usia Tanaman Saat Ini</p>
                                    <p class="text-3xl font-extrabold text-primary-900 dark:text-primary-100 mt-0.5">
                                        {{ $this->jumlahHariTanam >= 0 ? $this->jumlahHariTanam . ' Hari' : 'Belum Mulai' }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Mulai Tanam: {{ \Carbon\Carbon::parse($this->selectedJadwal->tanggal_tanam)->translatedFormat('d F Y') }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Lokasi Lahan</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm mt-0.5">{{ $this->selectedJadwal->lokasi_lahan }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Tanam</p>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm mt-0.5">
                                        {{ \Carbon\Carbon::parse($this->selectedJadwal->tanggal_tanam)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Catatan Perkembangan / Kondisi Tanaman</h4>
                                <div class="p-4 bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 rounded-lg text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $this->selectedJadwal->catatan ?? 'Belum ada catatan perkembangan/kondisi tanaman.' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </x-filament::section>
            @else
                <x-filament::section>
                    <div class="p-12 text-center text-gray-400">
                        <p class="text-base">Pilih salah satu tanaman di sebelah kiri untuk melihat detail & aksi hapus.</p>
                    </div>
                </x-filament::section>
            @endif
        </div>

    </div>
</x-filament-panels::page>