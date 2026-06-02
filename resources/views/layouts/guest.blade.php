<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('agency_name', config('app.name', 'ImmoPro')) }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ Storage::url(\App\Models\Setting::get('agency_logo', 'logos/YdW7FaP6a6l3kIVTBYa0b77rIwPmJZcPWnXLiKcs.png')) }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .reveal { 
                opacity: 0; 
                transform: translateY(30px); 
                transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1); 
            }
            .reveal.active { 
                opacity: 1; 
                transform: translateY(0); 
            }
            .delay-1 { transition-delay: 0.1s; }
            .delay-2 { transition-delay: 0.2s; }
            .delay-3 { transition-delay: 0.3s; }
            .delay-4 { transition-delay: 0.4s; }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('.reveal').forEach((el) => {
                    observer.observe(el);
                });
            });
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased selection:bg-slate-900 selection:text-white">
        <div class="min-h-screen flex flex-col bg-slate-950 relative overflow-hidden">
            <!-- Background Image with Blur -->
            <div class="absolute inset-0 z-0 overflow-hidden">
                <!-- Replace the URL below with the path to your image, e.g., asset('images/votre-photo.jpg') -->
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center filter blur-sm transform scale-105"></div>
                
                <!-- Overlay sombre pour garantir la lisibilité du formulaire et du footer -->
                <div class="absolute inset-0 bg-slate-900/60 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-slate-900/30 via-transparent to-slate-900/95"></div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-grow flex flex-col sm:justify-center items-center py-12 relative z-10">

                {{-- Logo + Nom agence directement sur l'image --}}
                <div class="mb-8 flex flex-col items-center justify-center">
                    @php $logo = \App\Models\Setting::get('agency_logo'); @endphp
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" alt="Logo" class="w-14 h-14 rounded-2xl object-cover shadow-lg border-2 border-white/20 mb-4">
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm text-white flex items-center justify-center shadow-lg border border-white/20 mb-4">
                            <i class="fa-solid fa-building text-2xl"></i>
                        </div>
                    @endif
                    <h1 class="text-2xl font-black text-white tracking-tight drop-shadow-lg">{{ \App\Models\Setting::get('agency_name', 'ImmoPro') }}</h1>
                    <p class="text-[9px] uppercase tracking-[0.25em] text-white/60 font-bold mt-1.5">Plateforme Immobilière</p>
                </div>

                {{-- Formulaire glassmorphism --}}
                <div class="w-full sm:max-w-sm px-6 py-8 sm:px-8 sm:py-10 bg-white/10 backdrop-blur-xl rounded-3xl border border-white/15 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.5)] mx-4 sm:mx-0">
                    {{ $slot }}
                </div>

                <!-- Lien retour -->
                <a href="/" class="mt-8 text-white/60 hover:text-white text-sm font-medium transition-colors flex items-center">
                    <i class="fa-solid fa-arrow-left-long mr-2"></i> Retour à l'accueil
                </a>
            </div>

            <!-- Footer Section -->
            <footer class="bg-slate-950/80 backdrop-blur-xl border-t border-white/5 pt-10 pb-6 relative z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            @php $logo = \App\Models\Setting::get('agency_logo'); @endphp
                            <div class="flex items-center space-x-3 mb-3">
                                @if($logo)
                                    <img src="{{ Storage::url($logo) }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover border border-slate-800">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center">
                                        <i class="fa-solid fa-building text-white text-xs"></i>
                                    </div>
                                @endif
                                <span class="text-lg font-bold text-white">{{ \App\Models\Setting::get('agency_name', 'ImmoPro') }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Votre agence immobilière de confiance en Guinée. Nous vous accompagnons dans tous vos projets.</p>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-3 text-sm">Liens rapides</h4>
                            <ul class="space-y-2 text-xs text-slate-400">
                                <li><a href="{{ route('biens.index') }}" class="hover:text-white transition">Catalogue</a></li>
                                <li><a href="{{ route('login') }}" class="hover:text-white transition">Connexion</a></li>
                                <li><a href="{{ route('register') }}" class="hover:text-white transition">Inscription</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-3 text-sm">Réseaux Sociaux</h4>
                            <div class="flex flex-col space-y-2 mt-2">
                                <a href="https://wa.me/224620000000" target="_blank" class="flex items-center text-xs text-slate-400 hover:text-white transition group">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center mr-2 group-hover:bg-slate-700 transition-colors">
                                        <i class="fa-brands fa-whatsapp text-slate-300 text-[10px]"></i>
                                    </div>
                                    WhatsApp
                                </a>
                                <a href="#" target="_blank" class="flex items-center text-xs text-slate-400 hover:text-white transition group">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center mr-2 group-hover:bg-slate-700 transition-colors">
                                        <i class="fa-brands fa-facebook-f text-slate-300 text-[10px]"></i>
                                    </div>
                                    Facebook
                                </a>
                                <a href="#" target="_blank" class="flex items-center text-xs text-slate-400 hover:text-white transition group">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center mr-2 group-hover:bg-slate-700 transition-colors">
                                        <i class="fa-brands fa-instagram text-slate-300 text-[10px]"></i>
                                    </div>
                                    Instagram
                                </a>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold mb-3 text-sm">Contact</h4>
                            <ul class="space-y-2 text-xs text-slate-400">
                                <li><i class="fa-solid fa-location-dot text-slate-500 mr-2"></i>Conakry, Guinée</li>
                                <li><i class="fa-solid fa-phone text-slate-500 mr-2"></i>{{ \App\Models\Setting::get('contact_phone', '+224 620 00 00 00') }}</li>
                                <li><i class="fa-solid fa-envelope text-slate-500 mr-2"></i>{{ \App\Models\Setting::get('contact_email', 'contact@immopro.gn') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-slate-800/50 mt-8 pt-4 text-center text-[10px] text-slate-500">
                        <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('agency_name', 'ImmoPro') }}. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
