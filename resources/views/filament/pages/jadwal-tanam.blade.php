<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 900px; margin: 0 auto; width: 100%;">
        
        @forelse($this->jadwalList as $item)
            @php
                $hari = \Carbon\Carbon::parse($item->tanggal_tanam)->startOfDay()->diffInDays(now()->startOfDay(), false);
            @endphp

            <x-filament::section>
                @if($editingId === (string) $item->id)
                    {{-- FORM EDIT UNTUK TANAMAN INI --}}
                    <div style="border-bottom: 1px solid #e5e7eb; padding-bottom: 0.75rem; margin-bottom: 1rem;">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white" style="margin: 0;">Edit Data: {{ $item->nama_tanaman }}</h3>
                    </div>

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
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan Perkembangan / Kondisi Tanaman</label>
                            <x-filament::input.wrapper>
                                <textarea wire:model="editData.catatan" rows="3" class="w-full text-sm border-none focus:ring-0 bg-transparent dark:text-white" placeholder="Catatan perkembangan..."></textarea>
                            </x-filament::input.wrapper>
                        </div>

                        <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                            <x-filament::button type="submit" color="success" style="flex: 1;">
                                Simpan Perubahan
                            </x-filament::button>
                            <x-filament::button wire:click="batalEdit" color="gray">
                                Batal
                            </x-filament::button>
                        </div>
                    </form>
                @else
                    {{-- TAMPILAN DETAIL DARI MASING-MASING TANAMAN --}}
                    {{-- BARIS HEADER: NAMA TANAMAN & TOMBOL EDIT/HAPUS --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.75rem; margin-bottom: 1rem;">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white" style="margin: 0;">{{ $item->nama_tanaman }}</h2>
                        </div>

                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <x-filament::button 
                                wire:click="editJadwal('{{ $item->id }}')" 
                                color="warning" 
                                size="sm">
                                Edit
                            </x-filament::button>

                            <x-filament::button 
                                wire:click="hapusJadwal('{{ $item->id }}')" 
                                wire:confirm="Yakin ingin menghapus jadwal {{ $item->nama_tanaman }}?"
                                color="danger" 
                                size="sm">
                                Hapus
                            </x-filament::button>
                        </div>
                    </div>

                    {{-- USIA TANAMAN & INFO DETAIL --}}
                    <div class="space-y-4">
                        <div class="p-4 bg-primary-50 dark:bg-primary-950/50 border border-primary-200 dark:border-primary-800 rounded-xl flex items-center justify-between">
                            <div>
                                <p class="text-xs text-primary-700 dark:text-primary-300 font-semibold uppercase" style="margin: 0;">Usia Tanaman Saat Ini</p>
                                <p class="text-3xl font-extrabold text-primary-900 dark:text-primary-100 mt-0.5" style="margin: 0;">
                                    {{ $hari >= 0 ? $hari . ' Hari' : 'Belum Mulai' }}
                                </p>
                            </div>
                            <x-filament::badge color="success">
                                {{ $hari >= 0 ? $hari . ' Hari Tanam' : 'Belum Mulai' }}
                            </x-filament::badge>
                        </div>

                        <div class="grid grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400" style="margin: 0;">Lokasi Lahan</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm mt-0.5" style="margin: 0;">{{ $item->lokasi_lahan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400" style="margin: 0;">Tanggal Pertama Menanam</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm mt-0.5" style="margin: 0;">
                                    {{ \Carbon\Carbon::parse($item->tanggal_tanam)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Catatan Perkembangan / Kondisi Tanaman</h4>
                            <div class="p-3 bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->catatan ?? 'Belum ada catatan perkembangan/kondisi tanaman.' }}
                            </div>
                        </div>
                    </div>
                @endif
            </x-filament::section>
        @empty
            <x-filament::section>
                <div class="p-12 text-center text-gray-400">
                    <p class="text-base" style="margin: 0;">Belum ada jadwal tanaman. Klik tombol "Mulai Menanam" di atas untuk menambahkan.</p>
                </div>
            </x-filament::section>
        @endforelse

    </div>
</x-filament-panels::page>