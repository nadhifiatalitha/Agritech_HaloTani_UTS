<x-filament-panels::page>
    <p style="color: #6b7280; font-size: 0.875rem; margin-top: -1.25rem; margin-bottom: 1rem;">
        Daftar konsultasi keluhan serta rekomendasi penanganan penyakit tanaman
    </p>

    <div style="display: grid; grid-template-columns: 360px 1fr; gap: 1.5rem; align-items: start;">

        <!-- KOLOM KIRI: Daftar Keluhan -->
        <x-filament::section>
            <x-slot name="heading">Daftar Keluhan</x-slot>

            <!-- Tombol Filter Status -->
            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                @foreach (['semua' => 'Semua', 'selesai' => 'Selesai', 'proses' => 'Proses'] as $key => $label)
                <x-filament::button
                    size="xs"
                    wire:click="setFilter('{{ $key }}')"
                    :color="$filter === $key ? 'success' : 'gray'"
                    :outlined="$filter !== $key">
                    {{ $label }}
                </x-filament::button>
                @endforeach
            </div>

            <!-- List Card Keluhan -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 600px; overflow-y: auto; padding-right: 0.25rem;">
                @forelse ($this->keluhanList as $keluhan)
                @php $isSelected = $selectedId === $keluhan->id_keluhan; @endphp
                <div
                    wire:click="pilih('{{ $keluhan->id_keluhan }}')"
                    style="padding: 0.875rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; border: 1px solid {{ $isSelected ? '#10b981' : '#e5e7eb' }}; background-color: {{ $isSelected ? '#f0fdf4' : '#ffffff' }};">
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #6b7280; margin-bottom: 0.375rem;">
                        <span>{{ \Carbon\Carbon::parse($keluhan->tanggal)->format('d M Y, H:i') }}</span>
                        <span style="text-transform: capitalize; font-weight: 600; padding: 0.125rem 0.5rem; border-radius: 0.25rem; background-color: {{ $keluhan->status === 'selesai' ? '#dcfce7' : '#fef3c7' }}; color: {{ $keluhan->status === 'selesai' ? '#15803d' : '#b45309' }};">
                            {{ $keluhan->status }}
                        </span>
                    </div>
                    <p style="font-size: 0.875rem; font-weight: 500; color: #111827; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; white-space: pre-line; line-height: 1.4;">
                        {{ $keluhan->teks_keluhan }}
                    </p>
                </div>
                @empty
                <p style="color: #6b7280; font-size: 0.875rem; text-align: center; padding: 2rem 0;">Belum ada Keluhan</p>
                @endforelse
            </div>
        </x-filament::section>

        <!-- KOLOM KANAN: Detail & Hasil AI -->
        <x-filament::section>
            <x-slot name="heading">Detail & Hasil Analisis</x-slot>

            @if ($this->selectedKeluhan)
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">

                <!-- Informasi Rincian Input -->
                <div style="padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem; border: 1px solid #f3f4f6;">
                    <p style="font-weight: 600; font-size: 0.875rem; color: #374151; margin-bottom: 0.5rem;">Rincian Keluhan:</p>
                    <p style="white-space: pre-line; color: #1f2937; font-size: 0.875rem; line-height: 1.6;">{{ $this->selectedKeluhan->teks_keluhan }}</p>
                </div>

                <!-- Hasil Analisis AI -->
                <div style="padding: 1rem; border: 1px solid #bbf7d0; border-radius: 0.5rem; background-color: #f0fdf4;">
                    <p style="font-weight: 600; font-size: 0.875rem; color: #15803d; margin-bottom: 0.75rem;">Hasil & Rekomendasi AI:</p>

                    <style>
                        .ai-content ul {
                            list-style-type: disc !important;
                            padding-left: 1.25rem !important;
                            margin-top: 0.5rem !important;
                            margin-bottom: 0.75rem !important;
                        }

                        .ai-content li {
                            margin-bottom: 0.5rem !important;
                        }

                        .ai-content p {
                            margin-bottom: 0.75rem !important;
                        }

                        .ai-content strong {
                            color: #111827;
                        }
                    </style>

                    <div class="ai-content" style="color: #1f2937; font-size: 0.875rem; line-height: 1.6;">
                        {!! \Illuminate\Support\Str::markdown($this->selectedKeluhan->hasil_analisis ?? 'Belum ada hasil analisis.') !!}
                    </div>
                </div>

            </div>
            @else
            <p style="color: #6b7280; font-size: 0.875rem; padding: 2rem 0; text-align: center;">Pilih salah satu keluhan di sebelah kiri untuk melihat detail hasil analisis AI.</p>
            @endif
        </x-filament::section>

    </div>
</x-filament-panels::page>