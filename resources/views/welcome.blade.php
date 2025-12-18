<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>San Martín - Sistema de Gestión</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-zinc-950 text-white min-h-screen flex items-center justify-center selection:bg-gray-500 selection:text-white relative overflow-hidden">
        
        {{-- Background Decorations (Silver/Metallic feel) --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            {{-- Radial gradient simulating metallic shine --}}
            <div class="absolute top-[-20%] left-[-10%] w-[70%] h-[70%] rounded-full bg-zinc-700/10 blur-[100px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] rounded-full bg-gray-600/10 blur-[100px]"></div>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8 w-full flex flex-col items-center">
            
            <div class="bg-zinc-900/80 rounded-3xl shadow-2xl p-12 md:p-16 w-full max-w-3xl border border-zinc-800 relative backdrop-blur-md transform transition-all duration-700 hover:border-zinc-700 hover:shadow-zinc-900/50">
                
                {{-- Metallic Border Glow Effect --}}
                <div class="absolute inset-0 rounded-3xl pointer-events-none shadow-[inset_0_0_20px_rgba(255,255,255,0.03)]"></div>

                <div class="flex flex-col items-center text-center">
                    {{-- Logo --}}
                    <div class="mb-10 relative group">
                        {{-- Subtle silver glow behind logo --}}
                        <div class="absolute -inset-4 bg-gradient-to-r from-gray-500 to-zinc-400 rounded-full blur-2xl opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                        <img src="{{ asset('images/logoSanMartin.png') }}" alt="San Martín" class="relative block h-[250px] w-auto drop-shadow-2xl transition-transform duration-500 hover:scale-105">
                    </div>

                    <h3 class="text-zinc-500 mb-12 max-w-lg leading-relaxed font-light">
                        Control Integral de Almacén
                    <h3>

                    {{-- Login Action --}}
                    @if (Route::has('login'))
                        <div class="w-full max-w-sm">
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="group relative w-full flex justify-center py-4 px-6 text-sm font-bold tracking-widest uppercase text-black bg-white hover:bg-gray-200 transition-all duration-300 rounded-sm shadow-[0_0_15px_rgba(255,255,255,0.1)] hover:shadow-[0_0_25px_rgba(255,255,255,0.2)]">
                                    <span class="flex items-center gap-2">
                                        Ir al Dashboard
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="group relative w-full flex justify-center py-4 px-6 text-sm font-bold tracking-widest uppercase text-black bg-gradient-to-r from-gray-100 to-gray-300 hover:from-white hover:to-gray-100 transition-all duration-500 rounded-xl shadow-lg shadow-zinc-900/50 hover:shadow-zinc-800/50 hover:-translate-y-1">
                                    <span class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 opacity-60 group-hover:opacity-100 transition-opacity">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Iniciar Sesión
                                    </span>
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>

                {{-- Footer Info --}}
                <div class="mt-16 text-center pt-6 border-t border-zinc-800/50">
                    <p class="text-xs text-zinc-600 font-mono tracking-wider uppercase">
                        &copy; {{ date('Y') }} San Martín
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
