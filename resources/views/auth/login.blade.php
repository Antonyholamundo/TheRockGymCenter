<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800">Iniciar Sesión</h2>
        <p class="text-xs text-gray-500">Bienvenido a The Rock Gym</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-xs text-gray-700">Correo Electrónico</label>
            <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm py-1.5" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-3" x-data="{ show: false }">
            <label for="password" class="block font-medium text-xs text-gray-700">Contraseña</label>
            <div class="relative">
                <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm py-1.5 pr-10"
                                :type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600 hover:text-red-500 transition-colors focus:outline-none">
                    <i class="fas text-xs" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                <span class="ms-2 text-xs text-gray-600">Recordar mi sesión</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-600 hover:text-red-600" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <button type="submit" class="bg-[#E31837] hover:bg-[#c41430] text-white font-bold py-1.5 px-4 rounded-lg shadow transition-colors ml-4 text-sm">
                Iniciar Sesión
            </button>
        </div>
    </form>
</x-guest-layout>
