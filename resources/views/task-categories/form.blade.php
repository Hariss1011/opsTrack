{{-- Validation Errors --}}
@if ($errors->any())
    <div class="rounded-xl border border-rose-500/20 bg-rose-500/8 p-4">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-rose-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v4m0 4h.01M10.3 3.9L1.8 18.6A1.6 1.6 0 003.2 21h17.6a1.6 1.6 0 001.4-2.4L13.7 3.9a1.97 1.97 0 00-3.4 0z" />
            </svg>

            <div>
                <p class="text-sm font-semibold text-rose-300">
                    Periksa kembali formulir
                </p>
                <ul class="mt-2 list-inside list-disc space-y-1 text-sm leading-6 text-rose-200/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Category Name --}}
<div>
    <label for="name" class="mb-2 block text-sm font-semibold text-slate-200">
        Nama Kategori <span class="text-rose-400">*</span>
    </label>

    <input id="name" name="name" type="text" required maxlength="255"
        value="{{ old('name', $category?->name) }}" placeholder="Contoh: Pemeriksaan Stok"
        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-slate-200 placeholder:text-slate-500 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
        aria-describedby="name-help">

    <p id="name-help" class="mt-2 text-xs leading-5 text-slate-500">
        Gunakan nama yang singkat, jelas, dan mudah dikenali oleh anggota tim.
    </p>
</div>

{{-- Description --}}
<div>
    <div class="mb-2 flex items-center justify-between gap-3">
        <label for="description" class="block text-sm font-semibold text-slate-200">
            Deskripsi
        </label>
        <span class="text-xs text-slate-500">Opsional</span>
    </div>

    <textarea id="description" name="description" rows="4" maxlength="5000"
        placeholder="Jelaskan jenis pekerjaan yang termasuk kategori ini..."
        class="w-full resize-y rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm leading-6 text-slate-200 placeholder:text-slate-500 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
        aria-describedby="description-help">{{ old('description', $category?->description) }}</textarea>

    <p id="description-help" class="mt-2 text-xs leading-5 text-slate-500">
        Tambahkan konteks agar pengguna memahami cakupan pekerjaan kategori ini.
    </p>
</div>

{{-- Category Status --}}
<div>
    <label for="is_active" class="mb-2 block text-sm font-semibold text-slate-200">
        Status Kategori <span class="text-rose-400">*</span>
    </label>

    <select id="is_active" name="is_active" required
        class="w-full rounded-xl border border-[#29364A] bg-[#0B1120] px-4 py-3 text-sm text-slate-200 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        <option value="1" @selected((string) old('is_active', $category?->is_active ?? 1) === '1')>
            Aktif
        </option>
        <option value="0" @selected((string) old('is_active', $category?->is_active ?? 1) === '0')>
            Nonaktif
        </option>
    </select>

    <div class="mt-3 flex items-start gap-2.5 rounded-xl border border-[#29364A] bg-[#0B1120]/70 p-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-blue-400" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 18h.01M12 14a4 4 0 10-4-4m4 4v-1m0 1a2 2 0 012 2" />
            <circle cx="12" cy="12" r="9" />
        </svg>

        <p class="text-xs leading-5 text-slate-400">
            <span class="font-semibold text-slate-300">Aktif:</span>
            kategori dapat digunakan sesuai aturan sistem.
            <span class="font-semibold text-slate-300">Nonaktif:</span>
            kategori tidak sebaiknya ditawarkan untuk tugas baru.
        </p>
    </div>
</div>

{{-- Form Actions --}}
<div class="flex flex-col-reverse gap-3 border-t border-[#29364A] pt-5 sm:flex-row sm:items-center sm:justify-between">
    <a href="{{ route('task-categories.index') }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#29364A] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#151F32]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Batal
    </a>

    <button type="submit"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
        @if ($isEdit)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Simpan Perubahan
        @else
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
            </svg>
            Simpan Kategori
        @endif
    </button>
</div>
