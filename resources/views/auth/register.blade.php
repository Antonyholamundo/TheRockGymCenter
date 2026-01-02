<x-guest-layout>
    <div class="mb-3 text-center">
        <h2 class="text-lg font-bold text-gray-800">Crear Cuenta</h2>
        <p class="text-xs text-gray-500">Regístrate para administrar The Rock Gym</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-2 gap-3">
            <!-- Name -->
            <div>
                <label for="name" class="block font-medium text-xs text-gray-700">Nombre</label>
                <input id="name" class="block mt-0.5 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1.5" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-xs text-gray-700">Correo</label>
                <input id="email" class="block mt-0.5 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1.5" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="block font-medium text-xs text-gray-700">Contraseña</label>
                <div class="relative">
                    <input id="password" class="block mt-0.5 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1.5 pr-8"
                                    :type="show ? 'text' : 'password'"
                                    name="password"
                                    required autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-600 hover:text-red-500 transition-colors focus:outline-none">
                        <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block font-medium text-xs text-gray-700">Confirmar</label>
                <div class="relative">
                    <input id="password_confirmation" class="block mt-0.5 w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-xs py-1.5 pr-8"
                                    :type="show ? 'text' : 'password'"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-600 hover:text-red-500 transition-colors focus:outline-none">
                        <i class="fas text-[10px]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-xs text-gray-600 hover:text-red-600" href="{{ route('login') }}">
                ¿Cuenta existente?
            </a>

            <button type="submit" class="bg-[#E31837] hover:bg-[#c41430] text-white font-bold py-1.5 px-3 rounded shadow transition-colors ml-4 text-xs tracking-widest uppercase">
                Registrar
            </button>
        </div>
    </form>
</x-guest-layout>
