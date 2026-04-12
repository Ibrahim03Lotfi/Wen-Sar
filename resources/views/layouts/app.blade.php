<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'وين صار') }}</title>
    
    <!-- Scripts & Styles -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    <header class="bg-brand-white shadow-sm sticky top-0 z-50 border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 md:h-20 items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/IMG_4032-Picsart-BackgroundRemover.jpeg.png') }}" alt="وين صار" class="h-12 md:h-16 w-auto">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex justify-center items-center gap-6 lg:gap-8">
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">الرئيسية</a>
                    @auth
                        @if(Auth::user()->hasRole('owner'))
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">لوحة التحكم</a>
                        @endif
                    @endauth
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">عن المشروع</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">تواصل معنا</a>
                </nav>

                <!-- Desktop User Actions -->
                <div class="hidden md:flex items-center justify-end gap-3 lg:gap-4">
                    @auth
                        <div class="flex items-center gap-2 lg:gap-3">
                            <span class="bg-brand-green text-white font-bold px-3 py-2 rounded-lg shadow-sm text-sm">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-500 px-3 py-2 rounded-lg border border-red-200 hover:border-red-500 transition-all shadow-sm">خروج</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('select-role') }}" class="text-sm font-medium text-brand-green hover:opacity-80">دخول</a>
                        <a href="{{ route('select-role') }}?action=register" class="bg-brand-green text-white px-4 py-2 rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">حساب جديد</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-t border-gray-100">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ url('/') }}" class="block py-2 px-3 rounded-lg {{ request()->is('/') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">الرئيسية</a>
                @auth
                    @if(Auth::user()->hasRole('owner'))
                        <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">لوحة التحكم</a>
                    @endif
                @endauth
                <a href="{{ route('about') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('about') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">عن المشروع</a>
                <a href="{{ route('contact') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('contact') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">تواصل معنا</a>
                
                <div class="border-t border-gray-100 pt-3 mt-3">
                    @auth
                        <div class="flex items-center justify-between px-3">
                            <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 font-bold">تسجيل الخروج</button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <a href="{{ route('select-role') }}" class="flex-1 py-2 px-3 rounded-lg border border-gray-300 text-center text-gray-700 font-medium">تسجيل الدخول</a>
                            <a href="{{ route('select-role') }}?action=register" class="flex-1 py-2 px-3 rounded-lg bg-brand-green text-center text-white font-bold">إنشاء حساب</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="bg-brand-green text-white py-8 md:py-12 mt-8 md:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 text-center md:text-right">
                <div class="sm:col-span-2 md:col-span-1">
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">وين صار</h3>
                    <p class="text-gray-300 text-sm md:text-base">دليلك الشامل في دمشق وسوريا. نساعدك في الوصول إلى أفضل الخدمات والمحال التجارية.</p>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">روابط سريعة</h3>
                    <ul class="space-y-2 text-sm md:text-base">
                        <li><a href="#" class="text-gray-300 hover:text-white transition">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition">سياسة الخصوصية</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition">شروط الاستخدام</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">تابعنا</h3>
                    <div class="flex justify-center md:justify-start gap-4 text-sm md:text-base">
                        <a href="#" class="text-gray-300 hover:text-white transition">Facebook</a>
                        <a href="#" class="text-gray-300 hover:text-white transition">Instagram</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 mt-6 md:mt-8 pt-6 md:pt-8 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} وين صار. جميع الحقوق محفوظة.
            </div>
        </div>
    </footer>
</body>
</html>
