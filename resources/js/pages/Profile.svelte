<script>

    let name = ''
    let email = ''

    async function updateProfile() {

        await fetch('/profile', {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

            body: JSON.stringify({

                name,
                email,

            }),

        })

        alert('Profile berhasil diupdate')

    }

</script>

<div class="min-h-screen bg-slate-900 text-white p-10">

    <h1 class="text-4xl font-bold mb-10">
        Edit Profile
    </h1>

    <form
        on:submit|preventDefault={updateProfile}
        class="max-w-xl"
    >

        <input
            bind:value={name}
            type="text"
            placeholder="Nama"
            class="w-full p-3 rounded-lg text-black mb-4"
        />

        <input
            bind:value={email}
            type="email"
            placeholder="Email"
            class="w-full p-3 rounded-lg text-black mb-4"
        />

        <button
            class="bg-blue-600 px-5 py-3 rounded-lg"
        >
            Simpan
        </button>

    </form>

</div>