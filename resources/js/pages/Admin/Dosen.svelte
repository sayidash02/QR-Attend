<script>

    export let dosen = []

    let name = ''
    let email = ''
    let password = ''

    async function tambahDosen() {

        await fetch('/admin/dosen', {

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
                password,

            }),

        })

        window.location.reload()

    }

</script>

<div class="min-h-screen bg-slate-900 text-white p-10">

    <h1 class="text-5xl font-bold mb-10">
        Kelola Dosen
    </h1>

    <div class="bg-slate-800 p-8 rounded-3xl max-w-xl mb-10">

        <input
            bind:value={name}
            type="text"
            placeholder="Nama Dosen"
            class="w-full p-4 rounded-xl text-black mb-4"
        />

        <input
            bind:value={email}
            type="email"
            placeholder="Email"
            class="w-full p-4 rounded-xl text-black mb-4"
        />

        <input
            bind:value={password}
            type="password"
            placeholder="Password"
            class="w-full p-4 rounded-xl text-black mb-4"
        />

        <button
            on:click={tambahDosen}
            class="bg-blue-600 px-6 py-4 rounded-xl"
        >
            Tambah Dosen
        </button>

    </div>

    <div class="bg-slate-800 p-8 rounded-3xl">

        <table class="w-full">

            <thead>

                <tr>

                    <th class="text-left p-4">
                        Nama
                    </th>

                    <th class="text-left p-4">
                        Email
                    </th>

                </tr>

            </thead>

            <tbody>

                {#each dosen as d}

                    <tr>

                        <td class="p-4">
                            {d.name}
                        </td>

                        <td class="p-4">
                            {d.email}
                        </td>

                    </tr>

                {/each}

            </tbody>

        </table>

    </div>

</div>