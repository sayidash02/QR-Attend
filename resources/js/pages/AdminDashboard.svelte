<script>

    let dosen = {

        name: '',
        nidn: '',
        matkul: '',
        email: '',
        password: '',

    }

    let mahasiswa = {

        name: '',
        nim: '',
        email: '',
        password: '',

    }

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

                ...dosen,

            }),

        })

        alert('Dosen berhasil ditambahkan')

    }

    async function tambahMahasiswa() {

        await fetch('/admin/mahasiswa', {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content,

            },

            body: JSON.stringify({

                ...mahasiswa,

            }),

        })

        alert('Mahasiswa berhasil ditambahkan')

    }

</script>

<div class="min-h-screen bg-slate-100 p-10">

    <h1 class="text-5xl font-bold text-[#062B66] mb-10">
        Dashboard Admin
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <!-- DOSEN -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">

            <h2 class="text-3xl font-bold text-[#062B66] mb-8">
                Tambah Dosen
            </h2>

            <input
                bind:value={dosen.name}
                placeholder="Nama Dosen"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={dosen.nidn}
                placeholder="NIDN"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={dosen.matkul}
                placeholder="Mata Kuliah"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={dosen.email}
                placeholder="Email"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={dosen.password}
                type="password"
                placeholder="Password"
                class="w-full border p-4 rounded-xl mb-6"
            />

            <button
                on:click={tambahDosen}
                class="bg-[#062B66] text-white px-6 py-4 rounded-xl"
            >
                Tambah Dosen
            </button>

        </div>

        <!-- MAHASISWA -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">

            <h2 class="text-3xl font-bold text-[#062B66] mb-8">
                Tambah Mahasiswa
            </h2>

            <input
                bind:value={mahasiswa.name}
                placeholder="Nama Mahasiswa"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={mahasiswa.nim}
                placeholder="NIM"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={mahasiswa.email}
                placeholder="Email"
                class="w-full border p-4 rounded-xl mb-4"
            />

            <input
                bind:value={mahasiswa.password}
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

    </div>

</div>