<script>

    let email = ''
    let password = ''

    async function login() {

        const response = await fetch('/login', {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

            body: JSON.stringify({

                email,
                password,

            }),

        })

        /*
        |--------------------------------------------------------------------------
        | REDIRECT BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        if (response.redirected) {

            window.location.href = response.url

        }

    }

</script>

<div class="min-h-screen bg-slate-100 flex overflow-hidden">

    <!-- LEFT -->
    <div class="hidden lg:flex w-1/2 bg-[#062B66] text-white items-center justify-center p-16">

        <div class="max-w-lg text-center">

            <div class="text-8xl mb-8">
                🎓
            </div>

            <h1 class="text-6xl font-bold mb-6">
                QR-Attend
            </h1>

            <p class="text-xl text-slate-200 leading-relaxed">
                Smart Attendance System untuk pengelolaan
                presensi dosen dan mahasiswa secara modern.
            </p>

        </div>

    </div>

    <!-- RIGHT -->
    <div class="flex-1 flex items-center justify-center p-10">

        <div class="bg-white w-full max-w-xl rounded-3xl shadow-xl border border-slate-200 p-10">

            <div class="mb-10 text-center">

                <h1 class="text-5xl font-bold text-[#062B66] mb-3">
                    Login Sistem
                </h1>

                <p class="text-slate-500 text-lg">
                    Silakan login menggunakan akun Anda
                </p>

            </div>

            <form on:submit|preventDefault={login}>

                <!-- EMAIL -->
                <div class="mb-6">

                    <label
                        for="email"
                        class="block text-slate-700 font-semibold mb-2"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        bind:value={email}
                        type="email"
                        placeholder="Masukkan email"
                        class="w-full border border-slate-300 rounded-xl p-4 outline-none focus:ring-2 focus:ring-[#062B66]"
                    />

                </div>

                <!-- PASSWORD -->
                <div class="mb-8">

                    <label
                        for="password"
                        class="block text-slate-700 font-semibold mb-2"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        bind:value={password}
                        type="password"
                        placeholder="Masukkan password"
                        class="w-full border border-slate-300 rounded-xl p-4 outline-none focus:ring-2 focus:ring-[#062B66]"
                    />

                </div>

                <button
                    class="w-full bg-[#062B66] hover:bg-[#041f49] text-white py-4 rounded-xl text-lg font-semibold transition"
                >
                    Login
                </button>

            </form>

        </div>

    </div>

</div>