@php
    $selectedRole = old('role', $user?->role ?? 'staff');
    $selectedDepartment = old('department_id', $user?->department_id);
    $activeValue = old('is_active', $user?->is_active ?? true);
    $isOwnAccount = $user && (int) $user->id === (int) auth()->id();

    $inputClass =
        'w-full rounded-xl border border-[#334155] bg-[#0B1120] px-4 py-3 text-sm text-slate-100 placeholder-slate-500 outline-none transition duration-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20';
    $labelClass = 'mb-2 block text-sm font-medium text-slate-300';
    $hintClass = 'mt-2 text-xs leading-5 text-slate-500';
@endphp

<div class="space-y-7">

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-400/25 bg-rose-500/10 p-4">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v4m0 4h.01M10.3 3.9L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" />
                </svg>

                <div>
                    <p class="text-sm font-semibold text-rose-300">
                        Data belum dapat disimpan
                    </p>
                    <p class="mt-1 text-xs text-rose-200/80">
                        Periksa kembali kolom berikut:
                    </p>
                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-rose-200">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Section: identity --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-500/10 text-blue-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-13a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-100">Identitas Pengguna</h3>
                <p class="mt-0.5 text-xs text-slate-400">Informasi dasar akun pengguna.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="{{ $labelClass }}">
                    Nama Lengkap <span class="text-rose-400">*</span>
                </label>
                <input id="name" name="name" type="text" required maxlength="255"
                    value="{{ old('name', $user?->name) }}" placeholder="Masukkan nama lengkap" autocomplete="name"
                    class="{{ $inputClass }}">
                @error('name')
                    <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="{{ $labelClass }}">
                    Email <span class="text-rose-400">*</span>
                </label>
                <input id="email" name="email" type="email" required maxlength="255"
                    value="{{ old('email', $user?->email) }}" placeholder="nama@perusahaan.com" autocomplete="email"
                    class="{{ $inputClass }}">
                @error('email')
                    <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <div class="border-t border-[#29364A]"></div>

    {{-- Section: access --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-500/10 text-violet-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-100">Hak Akses & Departemen</h3>
                <p class="mt-0.5 text-xs text-slate-400">Atur peran dan unit kerja pengguna.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="role" class="{{ $labelClass }}">
                    Role Pengguna <span class="text-rose-400">*</span>
                </label>
                <select id="role" name="role" required @disabled($isOwnAccount)
                    class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                    <option value="admin" class="bg-[#111B2E]" @selected($selectedRole === 'admin')>
                        Admin — Administrasi sistem
                    </option>
                    <option value="manager" class="bg-[#111B2E]" @selected($selectedRole === 'manager')>
                        Manager — Pemantauan departemen
                    </option>
                    <option value="staff" class="bg-[#111B2E]" @selected($selectedRole === 'staff')>
                        Staff — Pelaksana tugas
                    </option>
                </select>

                @if ($isOwnAccount)
                    <p class="{{ $hintClass }}">
                        Role akun yang sedang digunakan tidak dapat diubah.
                    </p>
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @else
                    <p class="{{ $hintClass }}">
                        Role menentukan tingkat akses dan tanggung jawab pengguna.
                    </p>
                @endif

                @error('role')
                    <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="department_id" class="{{ $labelClass }}">
                    Departemen
                </label>
                <select id="department_id" name="department_id" class="{{ $inputClass }}">
                    <option value="" class="bg-[#111B2E]">Tanpa departemen</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" class="bg-[#111B2E]" @selected((string) $selectedDepartment === (string) $department->id)>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                <p class="{{ $hintClass }}">
                    Departemen wajib diisi untuk role Manager dan Staff jika disyaratkan oleh validasi sistem.
                </p>
                @error('department_id')
                    <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <div class="border-t border-[#29364A]"></div>

    {{-- Section: credentials --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm10-11V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-100">Keamanan Akun</h3>
                <p class="mt-0.5 text-xs text-slate-400">Atur password untuk autentikasi pengguna.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="password" class="{{ $labelClass }}">
                    Password
                    @if ($isEdit)
                        <span class="font-normal text-slate-500">(opsional)</span>
                    @else
                        <span class="text-rose-400">*</span>
                    @endif
                </label>
                <input id="password" name="password" type="password"
                    @if (!$isEdit) required @endif minlength="8" autocomplete="new-password"
                    placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                    class="{{ $inputClass }}">
                @if ($isEdit)
                    <p class="{{ $hintClass }}">Biarkan kosong jika tidak ingin mengganti password.</p>
                @else
                    <p class="{{ $hintClass }}">Gunakan password minimal 8 karakter.</p>
                @endif
                @error('password')
                    <p class="mt-2 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="{{ $labelClass }}">
                    Konfirmasi Password
                    @if (!$isEdit)
                        <span class="text-rose-400">*</span>
                    @endif
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                    @if (!$isEdit) required @endif minlength="8" autocomplete="new-password"
                    placeholder="Masukkan ulang password" class="{{ $inputClass }}">
                <p class="{{ $hintClass }}">Masukkan password yang sama dengan kolom sebelumnya.</p>
            </div>
        </div>
    </section>

    <div class="border-t border-[#29364A]"></div>

    {{-- Section: account status --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M9 12l2 2 4-4m5-1.5a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-100">Status Akun</h3>
                <p class="mt-0.5 text-xs text-slate-400">Tentukan apakah pengguna dapat login ke sistem.</p>
            </div>
        </div>

        <div class="rounded-xl border border-[#334155] bg-[#0B1120]/70 p-4 sm:p-5">
            <label for="is_active" class="flex cursor-pointer items-start gap-4">
                <input type="hidden" name="is_active" value="0">

                <input id="is_active" type="checkbox" name="is_active" value="1" @checked((bool) $activeValue)
                    @disabled($isOwnAccount)
                    class="mt-1 h-4 w-4 rounded border-slate-600 bg-[#111B2E] text-blue-500 focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-0 disabled:cursor-not-allowed disabled:opacity-50">

                <span class="min-w-0 flex-1">
                    <span class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-semibold text-slate-100">Akun aktif</span>
                        @if ((bool) $activeValue)
                            <span
                                class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-300">
                                Aktif
                            </span>
                        @else
                            <span
                                class="rounded-full border border-rose-400/20 bg-rose-400/10 px-2.5 py-1 text-[11px] font-semibold text-rose-300">
                                Nonaktif
                            </span>
                        @endif
                    </span>
                    <span class="mt-1 block text-xs leading-5 text-slate-400">
                        Pengguna dengan akun aktif dapat login sesuai hak aksesnya. Akun nonaktif tidak dapat login.
                    </span>
                </span>
            </label>

            @if ($isOwnAccount)
                <div class="mt-4 border-t border-[#29364A] pt-3">
                    <p class="text-xs leading-5 text-amber-300">
                        Akun yang sedang digunakan tidak dapat dinonaktifkan melalui form ini.
                    </p>
                    <input type="hidden" name="is_active" value="1">
                </div>
            @endif
        </div>
    </section>

    {{-- Actions --}}
    <div class="flex flex-col-reverse gap-3 border-t border-[#29364A] pt-6 sm:flex-row sm:justify-end">
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-[#334155] bg-[#151F32] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:bg-[#1B2940] hover:text-white">
            Batal
        </a>

        <button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:ring-offset-2 focus:ring-offset-[#111B2E]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if ($isEdit)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" />
                @endif
            </svg>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pengguna' }}
        </button>
    </div>
</div>
