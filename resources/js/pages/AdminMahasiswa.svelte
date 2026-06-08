<script>

    import { toast } from '../stores/toastStore.js'

    export let mahasiswa = []

    let name = ''
    let nim = ''
    let email = ''
    let password = ''

    async function tambahMahasiswa() {

        if (!name || !nim || !email || !password) {

            toast.warning('Semua field wajib diisi!')
            return

        }

        try {

            const response = await fetch('/admin/mahasiswa', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,

                },

                body: JSON.stringify({

                    name,
                    nim,
                    email,
                    password,

                }),

            })

            if (response.ok) {

                toast.success(`Mahasiswa ${name} berhasil ditambahkan! 🎓`)
                setTimeout(() => window.location.reload(), 1200)

            } else {

                const data = await response.json()
                toast.error(data.message || 'Gagal menambahkan mahasiswa')

            }

        } catch (error) {

            toast.error('Terjadi kesalahan jaringan')

        }

    }

    async function hapusMahasiswa(id) {

        try {

            const response = await fetch(`/mahasiswa/${id}`, {

                method: 'DELETE',

                headers: {

                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,

                },

            })

            if (response.ok) {

                toast.success('Mahasiswa berhasil dihapus!')
                setTimeout(() => window.location.reload(), 1200)

            } else {

                toast.error('Gagal menghapus mahasiswa')

            }

        } catch (error) {

            toast.error('Terjadi kesalahan jaringan')

        }

    }

</script>

<div class="min-h-screen bg-slate-100 p-10">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-10">

        <div>

            <h1 class="text-5xl font-bold text-[#062B66] mb-2">
                Kelola Mahasiswa
            </h1>

            <p class="text-slate-500 text-lg">
                Tambah dan hapus data mahasiswa
            </p>

        </div>

        <a
            href="/admin/dashboard"
            class="bg-[#062B66] text-white px-6 py-3 rounded-xl"
        >
            Kembali
        </a>

    </div>

    <!-- FORM -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 mb-10">

        <h2 class="text-3xl font-bold text-[#062B66] mb-8">
            Tambah Mahasiswa
        </h2>

        <input
            bind:value={name}
            placeholder="Nama Mahasiswa"
            class="w-full border p-4 rounded-xl mb-4"
        />

        <input
            bind:value={nim}
            placeholder="NIM"
            class="w-full border p-4 rounded-xl mb-4"
        />

        <input
            bind:value={email}
            placeholder="Email"
            class="w-full border p-4 rounded-xl mb-4"
        />

        <input
            bind:value={password}
            type="password"
            placeholder="Password"
            class="w-full border p-4 rounded-xl mb-6"
        />

        <button
            on:click={tambahMahasiswa}
            class="bg-green-600 text-white px-6 py-4 rounded-xl"
        >
            Tambah Mahasiswa
        </button>

    </div>

    <!-- TABLE -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">

        <h2 class="text-3xl font-bold text-[#062B66] mb-8">
            Data Mahasiswa
        </h2>

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left p-4">NIM</th>

                    <th class="text-left p-4">Nama</th>

                    <th class="text-left p-4">Email</th>

                    <th class="text-left p-4">Aksi</th>

                </tr>

            </thead>

            <tbody>

                {#each mahasiswa as m}

                    <tr class="border-b">

                        <td class="p-4">
                            {m.nim}
                        </td>

                        <td class="p-4">
                            {m.name}
                        </td>

                        <td class="p-4">
                            {m.email}
                        </td>

                        <td class="p-4">

                            <button
                                on:click={() => hapusMahasiswa(m.id)}
                                class="bg-red-600 text-white px-4 py-2 rounded-lg"
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