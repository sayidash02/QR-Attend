<script>

    export let mahasiswa = []

    let nim = ''
    let name = ''
    let email = ''
    let password = ''

    async function tambahMahasiswa() {

        await fetch('/mahasiswa', {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

            body: JSON.stringify({

                nim,
                name,
                email,
                password,

            }),

        })

        window.location.reload()

    }

    async function hapusMahasiswa(id) {

        await fetch(`/mahasiswa/${id}`, {

            method: 'DELETE',

            headers: {

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

        })

        window.location.reload()

    }

</script>

<div class="min-h-screen bg-slate-900 text-white p-10">

    <div class="flex justify-between items-center mb-10">

        <h1 class="text-4xl font-bold">
            CRUD Mahasiswa
        </h1>

        <a
            href="/dashboard"
            class="bg-blue-600 px-4 py-2 rounded-lg"
        >
            Dashboard
        </a>

    </div>

    <div class="bg-slate-800 p-6 rounded-xl mb-10 max-w-xl">

        <h2 class="text-2xl mb-5">
            Tambah Mahasiswa
        </h2>

        <form
            on:submit|preventDefault={tambahMahasiswa}
        >

            <input
                bind:value={nim}
                type="text"
                placeholder="NIM"
                class="w-full p-3 rounded-lg text-black mb-4"
            />

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

            <input
                bind:value={password}
                type="password"
                placeholder="Password"
                class="w-full p-3 rounded-lg text-black mb-4"
            />

            <button
                class="bg-green-600 px-5 py-3 rounded-lg"
            >
                Tambah
            </button>

        </form>

    </div>

    <div class="bg-slate-800 p-6 rounded-xl">

        <h2 class="text-2xl mb-5">
            Data Mahasiswa
        </h2>

        <table class="w-full">

            <thead>

                <tr class="text-left border-b border-slate-600">

                    <th class="p-3">NIM</th>

                    <th class="p-3">Nama</th>

                    <th class="p-3">Email</th>

                    <th class="p-3">Aksi</th>

                </tr>

            </thead>

            <tbody>

                {#each mahasiswa as m}

                    <tr class="border-b border-slate-700">

                        <td class="p-3">
                            {m.nim}
                        </td>

                        <td class="p-3">
                            {m.name}
                        </td>

                        <td class="p-3">
                            {m.email}
                        </td>

                        <td class="p-3">

                            <button
                                on:click={() => hapusMahasiswa(m.id)}
                                class="bg-red-600 px-4 py-2 rounded-lg"
                            >
                                Hapus
                            </button>

                        </td>

                    </tr>

                {/each}

            </tbody>

        </table>

    </div>

</div>