@extends('layouts.app')

@section('title', 'Login - MONSWHEEL')

@section('content')
    <div class="min-h-screen grid grid-rows-[auto_1fr] md:grid-cols-2 md:grid-rows-1 overflow-hidden">
        {{-- LEFT SECTION (BACKGROUND LOGO) --}}
        <div class="relative hidden md:flex items-center justify-center px-6 overflow-hidden bg-black">

            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <img src="{{ asset('img/monswheel.png') }}" alt="MONSWHEEL Background"
                    class="w-[300px] sm:w-[380px] md:w-[460px] opacity-100 rounded-2xl">
            </div>

            <div class="relative z-10">
            </div>
        </div>


        {{-- RIGHT SECTION (FORM WITH BACKGROUND LOGO) --}}
        <div class="relative flex items-center justify-center bg-black px-4 py-8 overflow-hidden">

            {{-- Content --}}
            <div class="relative z-10 w-full max-w-md">

                {{-- Branding --}}
                <div class="flex items-center justify-center mb-6 md:block text-center md:text-center">

                    {{-- Mobile Logo --}}
                    <img src="{{ asset('img/monswheel.png') }}" alt="MONSWHEEL Logo" class="w-24  mr-3 md:hidden">

                    <div>
                        <h1 class="text-white text-2xl font-bold leading-tight">
                            MONSWHEEL
                        </h1>
                        <p class="text-gray-400 text-sm">
                            Monitoring Digital Service A2B
                        </p>
                    </div>
                </div>


                {{-- Form Card --}}
                <div class="bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8">
                    <form action="" method="POST" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">
                                Email or Username
                            </label>
                            <input type="text" name="email" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">
                                Password
                            </label>
                            <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white
                                      focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        {{-- Remember --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded">
                            <label class="ml-2 text-gray-300 text-sm">
                                Remember me
                            </label>
                        </div>

                        {{-- Button --}}
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg
                                   transition transform hover:scale-[1.02] focus:ring-2 focus:ring-blue-500">
                            Sign In
                        </button>

                        {{-- Forgot --}}
                        <div class="text-center">
                            <a href="#" class="text-blue-400 text-sm hover:text-blue-300">
                                Forgot your password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection