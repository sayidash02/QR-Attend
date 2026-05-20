<script>

    export let user

    let password = ''
    let photo = null

    async function updatePassword() {

        await fetch('/akun/password', {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

            body: JSON.stringify({

                password,

            }),

        })

        alert('Password berhasil diubah')

    }

    async function uploadPhoto() {

        const formData = new FormData()

        formData.append('photo', photo)

        await fetch('/akun/photo', {

            method: 'POST',

            headers: {

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

            body: formData,

        })

        window.location.reload()

    }

</script>

<div class="min-h-screen bg-slate-100 p-10">

    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 p-10">

        <h1 class="text-5xl font-bold text-[#062B66] mb-10">
            Akun Mahasiswa
        </h1>

        <!-- FOTO PROFILE -->
        <div class="mb-8 flex items-center gap-5">

            {#if user.photo}

                <img
                    src={`/profile/${user.photo}`}
                    alt="profile"
                    class="w-28 h-28 rounded-full object-cover border-4 border-slate-200"
                />

            {:else}

                <div class="w-28 h-28 rounded-full bg-slate-200 flex items-center justify-center text-4xl">
                    👤
                </div>

            {/if}

            <div>

                <input
                    id="photo"
                    type="file"
                    on:change={(e) => photo = e.target.files[0]}
                    class="mb-3"
                />

                <button
                    on:click={uploadPhoto}
                    class="bg-[#062B66] text-white px-5 py-2 rounded-xl"
                >
                    Upload Foto
                </button>

            </div>

        </div>

        <!-- NIM -->
        <div class="mb-6">

            <label
                for="nim"
                class="block text-slate-500 mb-2"
            >
                NIM
            </label>

            <input
                id="nim"
                value={user.nim}
                disabled
                class="w-full border border-slate-300 rounded-xl p-4 bg-slate-100"
            />

        </div>

        <!-- NAMA -->
        <div class="mb-6">

            <label
                for="nama"
                class="block text-slate-500 mb-2"
            >
                Nama
            </label>

            <input
                id="nama"
                value={user.name}
                disabled
                class="w-full border border-slate-300 rounded-xl p-4 bg-slate-100"
            />

        </div>

        <!-- PASSWORD -->
        <div class="mb-8">

            <label
                for="password"
                class="block text-slate-500 mb-2"
            >
                Password Baru
            </label>

            <input
                id="password"
                bind:value={password}
                type="password"
                placeholder="Masukkan password baru"
                class="w-full border border-slate-300 rounded-xl p-4"
            />

        </div>

        <button
            on:click={updatePassword}
            class="bg-[#062B66] text-white px-6 py-4 rounded-xl"
        >
            Simpan Password
        </button>

    </div>

</div>