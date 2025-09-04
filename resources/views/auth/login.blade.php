@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900 px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Logo/Ikon Aplikasi -->
        <div class="flex justify-center mb-6">
            <div class="bg-blue-600 p-3 rounded-full shadow-md">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.975 5.975 0 0112 13a5.975 5.975 0 013 5.197M15 21a6 6 0 00-9-5.197M9 8a3 3 0 016 0v2a3 3 0 11-6 0V8z"></path></svg>
            </div>
        </div>

        <!-- Judul dan Subjudul -->
        <h1 class="text-2xl md:text-3xl font-bold text-center text-gray-800 dark:text-white">
            Sistem Informasi Pegawai
        </h1>
        <p class="text-center text-gray-500 dark:text-gray-400 mb-8">
            Login untuk melanjutkan ke SIP Pegawai
        </p>

        <!-- Card Form Login -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sm:p-8">
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                @csrf

                <!-- Pesan Error -->
                @if($errors->any())
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-700 dark:text-red-400" role="alert">
                   Username atau Password yang Anda masukkan salah.
                </div>
                @endif

                <!-- Input Username atau Email -->
                <div>
                    <label for="login" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username atau Email</label>
                    <input type="text" name="login" id="login" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="contoh: admin atau admin@email.com" required value="{{ old('login') }}" autofocus>
                </div>

                <!-- Input Password dengan Ikon Mata -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">
                            <!-- Ikon Mata (Terlihat) -->
                            <svg id="eyeIcon" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Ikon Mata Coret (Tersembunyi) -->
                            <svg id="eyeSlashIcon" class="h-5 w-5 text-gray-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>


                <!-- Tombol Login -->
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Login
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-sm text-center text-gray-500 dark:text-gray-400 mt-8">
            &copy; {{ date('Y') }} SIP Pegawai. All Rights Reserved.
        </p>
    </div>
</div>

<!-- JavaScript untuk Show/Hide Password -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        togglePasswordButton.addEventListener('click', function () {
            // Ganti tipe input dari password ke text atau sebaliknya
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon mata yang ditampilkan
            eyeIcon.classList.toggle('hidden');
            eyeSlashIcon.classList.toggle('hidden');
        });
    });
</script>
@endsection

