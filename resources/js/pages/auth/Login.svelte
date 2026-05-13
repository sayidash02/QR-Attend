<script>

    let email = ''
    let password = ''

</script>

<div class="min-h-screen bg-slate-900 flex items-center justify-center">

    <form class="bg-white p-8 rounded-xl w-96 shadow-lg">

        <h1 class="text-3xl font-bold mb-5 text-center">
            Login
        </h1>

        <input
            bind:value={email}
            type="email"
            placeholder="Email"
            class="w-full border p-3 mb-4 rounded-lg"
        />

        <input
            bind:value={password}
            type="password"
            placeholder="Password"
            class="w-full border p-3 mb-4 rounded-lg"
        />

        <button
            class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg"
        >
            Login
        </button>

    </form>

</div>