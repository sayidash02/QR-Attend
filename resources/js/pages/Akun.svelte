<script>
    import { toast } from "../stores/toastStore.js";
    import { router } from "@inertiajs/svelte";

    export let user;

    let password = "";
    let fileInput;
    let uploading = false;
    let sidebarOpen = false;

    $: capitalizedRole =
        user && user.role
            ? user.role.charAt(0).toUpperCase() + user.role.slice(1)
            : "";
    $: backUrl =
        user && user.role === "admin"
            ? "/admin/dashboard"
            : user && user.role === "dosen"
              ? "/dosen/dashboard"
              : "/mahasiswa/dashboard";

    async function updatePassword() {
        if (!password) {
            toast.warning("Password tidak boleh kosong!");
            return;
        }
        if (password.length < 6) {
            toast.warning("Password minimal 6 karakter!");
            return;
        }
        try {
            const response = await fetch("/akun/password", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({ password }),
            });
            if (response.ok) {
                toast.success("Password berhasil diubah!");
                password = "";
            } else {
                const data = await response.json();
                toast.error(data.message || "Gagal mengubah password");
            }
        } catch (error) {
            toast.error("Terjadi kesalahan saat mengubah password");
        }
    }

    async function handlePhotoUpload(event) {
        const file = event.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            toast.warning("Ukuran foto maksimal 2MB!");
            fileInput.value = "";
            return;
        }
        const allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
        if (!allowedTypes.includes(file.type)) {
            toast.warning("Format foto harus JPG/JPEG/PNG!");
            fileInput.value = "";
            return;
        }
        uploading = true;
        try {
            const formData = new FormData();
            formData.append("photo", file);
            const response = await fetch("/akun/photo", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: formData,
            });
            if (response.ok) {
                toast.success("Foto profil berhasil diperbarui!");
                router.reload({
                    only: ["user"],
                    onSuccess: () => {
                        uploading = false;
                    },
                    onError: () => {
                        uploading = false;
                    },
                });
            } else {
                const data = await response.json().catch(() => ({}));
                toast.error(data.message || "Gagal mengunggah foto profil");
                uploading = false;
            }
        } catch (error) {
            toast.error("Terjadi kesalahan jaringan");
            uploading = false;
        } finally {
            fileInput.value = "";
        }
    }

    function triggerFileInput() {
        if (fileInput) fileInput.click();
    }
    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
    }
</script>

<div class="min-h-screen bg-[var(--color-bg)] flex font-[var(--font-sans)]">
    {#if sidebarOpen}
        <div
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden"
            on:click={toggleSidebar}
            on:keydown={(e) => e.key === "Escape" && toggleSidebar()}
            role="button"
            tabindex="-1"
            aria-label="Tutup sidebar"
        ></div>
    {/if}

    <aside
        class="fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-[var(--color-border)] p-6 flex flex-col z-50 transition-transform duration-300 ease-in-out {sidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full'} lg:translate-x-0"
    >
        <button
            class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-slate-100 text-slate-500 cursor-pointer border-0 bg-transparent"
            on:click={toggleSidebar}
            aria-label="Tutup sidebar"
        >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg
            >
        </button>
        <div class="mb-10">
            <h1 class="text-2xl font-bold text-[var(--color-primary)]">
                QR-Attend
            </h1>
            <p class="text-xs text-[var(--color-text-muted)] mt-1">
                {capitalizedRole}
            </p>
        </div>
        <nav class="space-y-1.5 flex-1">
            <a
                href={backUrl}
                class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><rect x="3" y="3" width="7" height="7" rx="1" /><rect
                        x="14"
                        y="3"
                        width="7"
                        height="7"
                        rx="1"
                    /><rect x="3" y="14" width="7" height="7" rx="1" /><rect
                        x="14"
                        y="14"
                        width="7"
                        height="7"
                        rx="1"
                    /></svg
                >
                Dashboard
            </a>
            <a
                href="/akun"
                class="flex items-center gap-3 bg-[var(--color-primary-light)] text-[var(--color-primary)] px-4 py-3 rounded-xl font-semibold text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><circle cx="12" cy="12" r="3" /><path
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"
                    /></svg
                >
                Pengaturan
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 lg:p-10 overflow-auto min-w-0">
        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <div class="flex items-center gap-3">
                <button
                    class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-[var(--color-text-secondary)] cursor-pointer border-0 bg-transparent"
                    on:click={toggleSidebar}
                    aria-label="Buka sidebar"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        ><path d="M4 6h16M4 12h16M4 18h16" /></svg
                    >
                </button>
                <div>
                    <h1
                        class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)]"
                    >
                        Profile {capitalizedRole}
                    </h1>
                    <p class="text-sm text-[var(--color-text-muted)] mt-0.5">
                        Kelola informasi pribadi dan kata sandi Anda.
                    </p>
                </div>
            </div>
            <a
                href={backUrl}
                class="flex items-center gap-2 bg-white hover:bg-slate-50 text-[var(--color-text-secondary)] font-medium px-4 py-2.5 rounded-xl transition shadow-sm border border-[var(--color-border)] text-sm hidden sm:flex"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7" /></svg
                >
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT: AVATAR & INFO -->
            <div
                class="lg:col-span-1 flex flex-col gap-6 animate-slide-in-left"
            >
                <div
                    class="bg-white rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] p-6 flex flex-col items-center"
                >
                    <div class="relative w-28 h-28 mb-5 group">
                        {#if uploading}
                            <div
                                class="absolute inset-0 bg-white/70 backdrop-blur-xs rounded-full z-10 flex items-center justify-center"
                            >
                                <svg
                                    class="animate-spin h-7 w-7 text-[var(--color-primary)]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    ><circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle><path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path></svg
                                >
                            </div>
                        {/if}
                        <button
                            on:click={triggerFileInput}
                            type="button"
                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 rounded-full z-5 transition duration-300 flex flex-col items-center justify-center text-white text-xs font-semibold cursor-pointer border-0"
                        >
                            <svg
                                class="w-5 h-5 mb-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                ><path
                                    d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"
                                /><circle cx="12" cy="13" r="4" /></svg
                            >
                            Ubah
                        </button>
                        {#if user && user.photo}
                            <img
                                src="/profile/{user.photo}"
                                alt={user.name}
                                class="w-full h-full object-cover rounded-full border-3 border-white shadow-md"
                            />
                        {:else}
                            <div
                                class="w-full h-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-muted)] rounded-full flex items-center justify-center text-4xl text-white font-bold border-3 border-white shadow-md"
                            >
                                {user && user.name
                                    ? user.name.charAt(0).toUpperCase()
                                    : "?"}
                            </div>
                        {/if}
                    </div>

                    <input
                        bind:this={fileInput}
                        on:change={handlePhotoUpload}
                        type="file"
                        accept="image/*"
                        class="hidden"
                    />

                    <button
                        on:click={triggerFileInput}
                        disabled={uploading}
                        class="px-4 py-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer disabled:opacity-50 border-0 flex items-center gap-1.5"
                    >
                        <svg
                            class="w-3.5 h-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            ><path
                                d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"
                            /><circle cx="12" cy="13" r="4" /></svg
                        >
                        Unggah Foto
                    </button>
                    <p
                        class="text-xs text-[var(--color-text-muted)] text-center mt-2"
                    >
                        Maks 2MB · JPG/PNG
                    </p>

                    <hr
                        class="w-full border-[var(--color-border-light)] my-5"
                    />

                    <div
                        class="px-3 py-1 bg-[var(--color-primary-light)] text-[var(--color-primary)] rounded-full text-xs font-bold tracking-wider uppercase"
                    >
                        {user ? user.role : ""}
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] p-6 space-y-3"
                >
                    <h4
                        class="text-xs font-bold text-[var(--color-text-muted)] uppercase tracking-wider"
                    >
                        Informasi Akun
                    </h4>
                    {#if user}
                        {#if user.role === "mahasiswa"}
                            <div
                                class="p-3.5 bg-[var(--color-bg)] rounded-xl border border-[var(--color-border-light)]"
                            >
                                <p
                                    class="text-[var(--color-text-muted)] text-xs font-semibold mb-0.5"
                                >
                                    NIM
                                </p>
                                <p
                                    class="text-[var(--color-text-primary)] font-bold text-sm"
                                >
                                    {user.nim || "-"}
                                </p>
                            </div>
                        {:else if user.role === "dosen"}
                            <div
                                class="p-3.5 bg-[var(--color-bg)] rounded-xl border border-[var(--color-border-light)]"
                            >
                                <p
                                    class="text-[var(--color-text-muted)] text-xs font-semibold mb-0.5"
                                >
                                    NIDN
                                </p>
                                <p
                                    class="text-[var(--color-text-primary)] font-bold text-sm"
                                >
                                    {user.nidn || "-"}
                                </p>
                            </div>
                            {#if user.matkul}
                                <div
                                    class="p-3.5 bg-[var(--color-bg)] rounded-xl border border-[var(--color-border-light)]"
                                >
                                    <p
                                        class="text-[var(--color-text-muted)] text-xs font-semibold mb-0.5"
                                    >
                                        Mata Kuliah
                                    </p>
                                    <p
                                        class="text-[var(--color-text-primary)] font-bold text-sm"
                                    >
                                        {user.matkul}
                                    </p>
                                </div>
                            {/if}
                        {/if}
                        <div
                            class="p-3.5 bg-[var(--color-bg)] rounded-xl border border-[var(--color-border-light)]"
                        >
                            <p
                                class="text-[var(--color-text-muted)] text-xs font-semibold mb-0.5"
                            >
                                Nama Lengkap
                            </p>
                            <p
                                class="text-[var(--color-text-primary)] font-bold text-sm"
                            >
                                {user.name}
                            </p>
                        </div>
                        <div
                            class="p-3.5 bg-[var(--color-bg)] rounded-xl border border-[var(--color-border-light)]"
                        >
                            <p
                                class="text-[var(--color-text-muted)] text-xs font-semibold mb-0.5"
                            >
                                Email
                            </p>
                            <p
                                class="text-[var(--color-text-primary)] font-bold text-sm break-all"
                            >
                                {user.email}
                            </p>
                        </div>
                    {/if}
                </div>
            </div>

            <!-- RIGHT: PASSWORD -->
            <div class="lg:col-span-2 animate-slide-up">
                <div
                    class="bg-white rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] p-6 lg:p-8"
                >
                    <h3
                        class="text-lg font-bold text-[var(--color-text-primary)] mb-1 flex items-center gap-2"
                    >
                        <svg
                            class="w-5 h-5 text-[var(--color-primary)]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            ><rect
                                x="3"
                                y="11"
                                width="18"
                                height="11"
                                rx="2"
                                ry="2"
                            /><path d="M7 11V7a5 5 0 0110 0v4" /></svg
                        >
                        Ubah Password
                    </h3>
                    <p class="text-sm text-[var(--color-text-muted)] mb-6">
                        Masukkan kata sandi baru. Gunakan kombinasi huruf,
                        angka, dan karakter khusus.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <label
                                for="newPassword"
                                class="block text-sm font-semibold text-[var(--color-text-primary)] mb-1.5"
                                >Password Baru</label
                            >
                            <input
                                id="newPassword"
                                bind:value={password}
                                type="password"
                                placeholder="Masukkan password baru"
                                class="w-full border border-[var(--color-border)] bg-[var(--color-bg)] p-3.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 focus:border-[var(--color-accent)] text-sm transition"
                            />
                        </div>
                        <button
                            on:click={updatePassword}
                            class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white px-6 py-3.5 rounded-xl font-semibold transition-all shadow-sm hover:shadow-md cursor-pointer text-sm flex items-center justify-center gap-2 border-0"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                ><path d="M5 13l4 4L19 7" /></svg
                            >
                            Simpan Password
                        </button>
                    </div>

                    <div
                        class="mt-6 p-4 bg-[var(--color-info-light)] border border-blue-100 rounded-xl"
                    >
                        <h5
                            class="text-blue-800 font-bold text-xs mb-2 flex items-center gap-1.5"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                ><circle cx="12" cy="12" r="10" /><path
                                    d="M12 16v-4M12 8h.01"
                                /></svg
                            >
                            Tips Keamanan:
                        </h5>
                        <ul
                            class="text-blue-700 text-xs space-y-1 list-disc list-inside pl-1"
                        >
                            <li>Gunakan minimal 6 karakter.</li>
                            <li>
                                Jangan gunakan kata sandi yang sama dengan situs
                                lain.
                            </li>
                            <li>Perbarui secara berkala demi keamanan data.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
