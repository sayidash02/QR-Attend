<script>
    import { toast } from '../stores/toastStore.js'
    import { router } from '@inertiajs/svelte'

    export let user

    let password = ''
    let fileInput
    let uploading = false

    $: capitalizedRole = user && user.role ? user.role.charAt(0).toUpperCase() + user.role.slice(1) : ''
    $: backUrl = user && user.role === 'admin' ? '/admin/dashboard' : (user && user.role === 'dosen' ? '/dosen/dashboard' : '/mahasiswa/dashboard')

    async function updatePassword() {
        if (!password) {
            toast.warning('Password tidak boleh kosong!')
            return
        }

        if (password.length < 6) {
            toast.warning('Password minimal 6 karakter!')
            return
        }

        try {
            const response = await fetch('/akun/password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    password,
                }),
            })

            if (response.ok) {
                toast.success('Password berhasil diubah! 🔒')
                password = ''
            } else {
                const data = await response.json()
                toast.error(data.message || 'Gagal mengubah password')
            }
        } catch (error) {
            toast.error('Terjadi kesalahan saat mengubah password')
        }
    }

    async function handlePhotoUpload(event) {
        const file = event.target.files[0]
        if (!file) return

        // Validasi ukuran: max 2MB
        if (file.size > 2 * 1024 * 1024) {
            toast.warning('Ukuran foto maksimal 2MB!')
            fileInput.value = ''
            return
        }

        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']
        if (!allowedTypes.includes(file.type)) {
            toast.warning('Format foto harus JPG/JPEG/PNG!')
            fileInput.value = ''
            return
        }

        uploading = true

        try {
            const formData = new FormData()
            formData.append('photo', file)

            const response = await fetch('/akun/photo', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })

            if (response.ok) {
                toast.success('Foto profil berhasil diperbarui! 📸')
                // Reload page to refresh the user prop
                router.reload({
                    only: ['user'],
                    onSuccess: () => {
                        uploading = false
                    },
                    onError: () => {
                        uploading = false
                    }
                })
            } else {
                const data = await response.json().catch(() => ({}))
                toast.error(data.message || 'Gagal mengunggah foto profil')
                uploading = false
            }
        } catch (error) {
            toast.error('Terjadi kesalahan jaringan saat mengunggah foto')
            uploading = false
        } finally {
            fileInput.value = ''
        }
    }

    function triggerFileInput() {
        if (fileInput) {
            fileInput.click()
        }
    }
</script>

<div class="min-h-screen bg-slate-50 flex">
    <!-- SIDEBAR -->
    <aside class="w-72 bg-white border-r border-slate-200 p-6 flex flex-col hidden md:flex">
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-[#062B66]">
                QR-Attend
            </h1>
        </div>

        <nav class="space-y-3 flex-1">
            <!-- DASHBOARD -->
            <a
                href={backUrl}
                class="flex items-center gap-3 text-slate-600 hover:bg-slate-100 px-5 py-4 rounded-xl transition"
            >
                📊 Dashboard
            </a>

            <!-- PROFILE (ACTIVE) -->
            <a
                href="/akun"
                class="flex items-center gap-3 bg-slate-100 text-[#062B66] px-5 py-4 rounded-xl font-semibold"
            >
                ⚙️ Profile
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 md:p-10 overflow-auto">
        <!-- TOPBAR -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-[#062B66] mb-2">
                    Profile {capitalizedRole}
                </h1>
                <p class="text-slate-500 text-base md:text-lg">
                    Kelola informasi pribadi, foto profil, dan kata sandi Anda.
                </p>
            </div>

            <!-- BACK BUTTON -->
            <a
                href={backUrl}
                class="bg-slate-600 hover:bg-slate-700 text-white px-5 py-3 rounded-xl font-semibold transition shadow-sm text-sm md:text-base"
            >
                ← Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- LEFT COLUMN: AVATAR & BASIC DETAILS -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 flex flex-col items-center">
                    
                    <!-- AVATAR CONTAINER -->
                    <div class="relative w-36 h-36 mb-6 group">
                        <!-- UPLOADING GLASS OVERLAY -->
                        {#if uploading}
                            <div class="absolute inset-0 bg-white/70 backdrop-blur-xs rounded-full z-10 flex items-center justify-center">
                                <svg class="animate-spin h-8 w-8 text-[#062B66]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        {/if}

                        <!-- EDIT OVERLAY -->
                        <button 
                            on:click={triggerFileInput}
                            type="button"
                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 rounded-full z-5 transition duration-300 flex flex-col items-center justify-center text-white text-xs font-semibold cursor-pointer border-0"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-1">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            Ubah Foto
                        </button>

                        <!-- ACTUAL IMAGE OR PLACEHOLDER -->
                        {#if user && user.photo}
                            <img
                                src="/profile/{user.photo}"
                                alt={user.name}
                                class="w-full h-full object-cover rounded-full border-4 border-white shadow-md"
                            />
                        {:else}
                            <div class="w-full h-full bg-gradient-to-br from-[#062B66] to-[#0a3d8f] rounded-full flex items-center justify-center text-5xl text-white font-bold border-4 border-white shadow-md">
                                {user && user.name ? user.name.charAt(0).toUpperCase() : '?'}
                            </div>
                        {/if}
                    </div>

                    <!-- HIDDEN FILE INPUT -->
                    <input
                        bind:this={fileInput}
                        on:change={handlePhotoUpload}
                        type="file"
                        accept="image/*"
                        class="hidden"
                    />

                    <!-- UPLOAD BUTTON -->
                    <button
                        on:click={triggerFileInput}
                        disabled={uploading}
                        class="px-5 py-2.5 bg-[#062B66] hover:bg-[#0a3d8f] text-white rounded-xl text-sm font-semibold transition shadow-sm hover:shadow-md cursor-pointer disabled:bg-slate-400 border-0"
                    >
                        📸 Unggah Foto Baru
                    </button>
                    
                    <p class="text-xs text-slate-400 text-center mt-3 leading-relaxed">
                        Maksimal 2MB. Format: JPG, JPEG, PNG.
                    </p>

                    <hr class="w-full border-slate-100 my-6" />

                    <!-- BADGE ROLE -->
                    <div class="px-4 py-1.5 bg-[#062B66]/10 text-[#062B66] rounded-full text-xs font-bold tracking-wider uppercase">
                        {user ? user.role : ''}
                    </div>
                </div>

                <!-- INFO CARDS -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 space-y-4">
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Informasi Akun</h4>
                    
                    {#if user}
                        {#if user.role === 'mahasiswa'}
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <p class="text-slate-500 text-xs font-semibold mb-0.5">NIM</p>
                                <p class="text-slate-800 font-bold text-base">{user.nim || '-'}</p>
                            </div>
                        {:else if user.role === 'dosen'}
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <p class="text-slate-500 text-xs font-semibold mb-0.5">NIDN</p>
                                <p class="text-slate-800 font-bold text-base">{user.nidn || '-'}</p>
                            </div>
                            {#if user.matkul}
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p class="text-slate-500 text-xs font-semibold mb-0.5">Mata Kuliah</p>
                                    <p class="text-slate-800 font-bold text-base">{user.matkul}</p>
                                </div>
                            {/if}
                        {/if}

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-slate-500 text-xs font-semibold mb-0.5">Nama Lengkap</p>
                            <p class="text-slate-800 font-bold text-base">{user.name}</p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-slate-500 text-xs font-semibold mb-0.5">Email</p>
                            <p class="text-slate-800 font-bold text-base break-all">{user.email}</p>
                        </div>
                    {/if}
                </div>
            </div>

            <!-- RIGHT COLUMN: PASSWORD CHANGE -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 h-full">
                    <h3 class="text-2xl font-bold text-[#062B66] mb-6 flex items-center gap-2">
                        <span>🔒</span> Ubah Password Baru
                    </h3>
                    <p class="text-slate-500 mb-8 text-sm leading-relaxed">
                        Masukkan kata sandi baru Anda di bawah ini untuk memperbarui keamanan akun. Disarankan untuk menggunakan kombinasi huruf, angka, dan karakter khusus.
                    </p>

                    <div class="space-y-6">
                        <!-- PASSWORD INPUT -->
                        <div>
                            <label for="newPassword" class="block text-slate-700 font-semibold mb-2 text-sm md:text-base">
                                Password Baru
                            </label>
                            <input
                                id="newPassword"
                                bind:value={password}
                                type="password"
                                placeholder="Masukkan password baru"
                                class="w-full border border-slate-300 p-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#062B66] text-sm md:text-base transition duration-200"
                            />
                        </div>

                        <!-- BUTTON -->
                        <button
                            on:click={updatePassword}
                            class="w-full bg-[#062B66] hover:bg-[#0a3d8f] text-white px-6 py-4 rounded-xl font-semibold transition duration-200 shadow-sm hover:shadow-md cursor-pointer mt-4 text-sm md:text-base flex items-center justify-center gap-2 border-0"
                        >
                            <span>Save Password</span>
                        </button>
                    </div>

                    <!-- TIPS CARD -->
                    <div class="mt-8 p-5 bg-blue-50/70 border border-blue-100 rounded-2xl">
                        <h5 class="text-blue-800 font-bold text-sm mb-2 flex items-center gap-1.5">
                            <span>💡</span> Tips Keamanan Akun:
                        </h5>
                        <ul class="text-blue-700 text-xs md:text-sm space-y-1.5 list-disc list-inside pl-1">
                            <li>Gunakan minimal 6 karakter.</li>
                            <li>Jangan gunakan kata sandi yang sama dengan situs lain.</li>
                            <li>Perbarui kata sandi Anda secara berkala demi keamanan data.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>