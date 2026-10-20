{{-- Validation Errors --}}
@if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-400/20 bg-rose-400/10 p-4" role="alert">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-rose-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v4m0 4h.01M10.3 3.8 2.9 17a2 2 0 0 0 1.8 3h14.2a2 2 0 0 0 1.8-3L13.7 3.8a2 2 0 0 0-3.4 0Z" />
            </svg>

            <div>
                <p class="text-sm font-semibold text-rose-300">
                    Periksa kembali formulir
                </p>

                <ul class="mt-2 list-inside list-disc space-y-1 text-sm leading-5 text-rose-300/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Department Name --}}
<div>
    <label for="name" class="mb-2 block text-sm font-semibold text-[#E2E8F0]">
        Nama Departemen
        <span class="text-rose-400">*</span>
    </label>

    <p class="mb-3 text-xs leading-5 text-[#94A3B8]">
        Masukkan nama departemen yang akan digunakan dalam pengelolaan pengguna dan tugas.
    </p>

    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 21h18M5 21V7l8-4v18M13 10h6v11M8 9h1m-1 4h1m-1 4h1" />
            </svg>
        </div>

        <input id="name" name="name" type="text" required maxlength="255" autofocus
            value="{{ old('name', $department?->name) }}" placeholder="Contoh: Gudang" aria-describedby="name-help"
            class="block w-full rounded-xl border bg-[#0B1120] py-3.5 pl-11 pr-4 text-sm text-[#E2E8F0] outline-none transition placeholder:text-slate-600 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/20 {{ $errors->has('name') ? 'border-rose-400/60 focus:border-rose-400 focus:ring-rose-500/20' : 'border-[#29364A]' }}">
    </div>

    <div id="name-help" class="mt-3 flex items-start gap-2 text-xs leading-5 text-slate-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-blue-400" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="9" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5m0-8h.01" />
        </svg>
        Gunakan nama yang jelas, mudah dikenali, dan sesuai dengan struktur organisasi.
    </div>
</div>

{{-- Form Actions --}}
<div class="flex flex-col-reverse gap-3 border-t border-[#29364A] pt-6 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-xs text-slate-500">
        <span class="text-rose-400">*</span>
        Wajib diisi
    </p>

    <div class="flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('departments.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#35445B] bg-[#151F32] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#1B2940] hover:text-white">
            Batal
        </a>

        <button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#3B82F6] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/20 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
            @if ($isEdit)
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
                </svg>
                Simpan Perubahan
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                </svg>
                Simpan Departemen
            @endif
        </button>
    </div>
</div>
