<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
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
    <!-- Splash Screen -->
    <x-splash-screen />
    
    <header class="bg-brand-white shadow-sm sticky top-0 z-50 border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 md:h-20 items-center justify-between">
                <!-- Logo (Right in RTL) -->
                <div class="flex items-center flex-1 justify-end relative -mr-4 md:-mr-6 lg:-mr-8">
                    <a href="{{ url('/') }}" class="flex items-center absolute right-0">
                       <img src="{{ asset('images/IMG_20260414_145531_107.png') }}" alt="وين صار" class="h-16 md:h-20 w-auto ml-3"> 
                       <!-- <img src="{{ asset('images/full logo green png.png') }}" alt="وين صار" class="h-20 md:h-32 w-auto"> -->
                    </a>
                </div>

                <!-- Desktop Navigation (Center) -->
                <nav class="hidden md:flex justify-center items-center gap-6 lg:gap-8">
                    {{-- Language Switcher - Temporarily hidden
                    <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                       class="flex items-center gap-1 text-sm font-medium px-3 py-1.5 rounded-lg border border-gray-200 hover:border-brand-green hover:text-brand-green transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                        {{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}
                    </a>
                    --}}
                    <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">{{ __('Home') }}</a>
                    @auth
                        <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.index') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">{{ __('Favorites') }}</a>
                        @if(Auth::user()->hasRole('owner'))
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">{{ __('Dashboard') }}</a>
                        @endif
                    @endauth
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">{{ __('About') }}</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-brand-green font-bold text-base lg:text-lg' : 'text-gray-500 text-sm font-medium hover:text-brand-green' }}">{{ __('Contact') }}</a>
                </nav>

                <!-- Desktop User Actions (Left in RTL) -->
                <div class="hidden md:flex items-center justify-end gap-3 lg:gap-4 flex-1">
                    @auth
                        <div class="flex items-center gap-2 lg:gap-3">
                            <span class="bg-brand-green text-white font-bold px-3 py-2 rounded-lg shadow-sm text-sm">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-white font-bold bg-red-50 hover:bg-red-500 px-3 py-2 rounded-lg border border-red-200 hover:border-red-500 transition-all shadow-sm">{{ __('Logout') }}</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('select-role') }}" class="text-sm font-medium text-brand-green hover:opacity-80">{{ __('Login') }}</a>
                        <a href="{{ route('select-role') }}?action=register" class="bg-brand-green text-white px-4 py-2 rounded-xl text-sm font-bold hover:opacity-90 transition-all shadow-sm">{{ __('Register') }}</a>
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
                {{-- Mobile Language Switcher - Temporarily hidden
                <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                   class="flex items-center gap-2 py-2 px-3 rounded-lg border border-gray-200 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    {{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}
                </a>
                --}}
                <a href="{{ url('/') }}" class="block py-2 px-3 rounded-lg {{ request()->is('/') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">{{ __('Home') }}</a>
                @auth
                    <a href="{{ route('favorites.index') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('favorites.index') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">{{ __('Favorites') }}</a>
                    @if(Auth::user()->hasRole('owner'))
                        <a href="{{ route('dashboard') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">{{ __('Dashboard') }}</a>
                    @endif
                @endauth
                <a href="{{ route('about') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('about') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">{{ __('About') }}</a>
                <a href="{{ route('contact') }}" class="block py-2 px-3 rounded-lg {{ request()->routeIs('contact') ? 'bg-brand-green/10 text-brand-green font-bold' : 'text-gray-700 hover:bg-gray-50' }}">{{ __('Contact') }}</a>
                
                <div class="border-t border-gray-100 pt-3 mt-3">
                    @auth
                        <div class="flex items-center justify-between px-3">
                            <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 font-bold">{{ __('Logout') }}</button>
                            </form>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <a href="{{ route('select-role') }}" class="flex-1 py-2 px-3 rounded-lg border border-gray-300 text-center text-gray-700 font-medium">{{ __('Login') }}</a>
                            <a href="{{ route('select-role') }}?action=register" class="flex-1 py-2 px-3 rounded-lg bg-brand-green text-center text-white font-bold">{{ __('Register') }}</a>
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

    <footer class="bg-brand-green text-white py-8 md:py-12 mt-8 md:mt-12" x-data="{ termsOpen: false, privacyOpen: false, fbToast: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 text-center md:text-right">
                <div class="sm:col-span-2 md:col-span-1">
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">{{ __('Wen Sar') }}</h3>
                    <p class="text-gray-300 text-sm md:text-base">{{ __('Your comprehensive guide in Damascus and Syria. We help you reach the best services and businesses.') }}</p>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">{{ __('Quick Links') }}</h3>
                    <ul class="space-y-2 text-sm md:text-base">
                        <li><a href="{{ route('contact') }}#faq" class="text-gray-300 hover:text-white transition">الأسئلة الشائعة</a></li>
                        <li><button @click="privacyOpen = true" class="text-gray-300 hover:text-white transition">سياسة الخصوصية</button></li>
                        <li><button @click="termsOpen = true" class="text-gray-300 hover:text-white transition">شروط الاستخدام</button></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">{{ __('Follow Us') }}</h3>
                    <div class="flex justify-center md:justify-start gap-4 text-sm md:text-base">
                        <button @click="fbToast = true; setTimeout(() => fbToast = false, 3000)" class="text-gray-300 hover:text-white transition">Facebook</button>
                        <a href="https://www.instagram.com/wen_sar_sy?igsh=N2Zsa2hlZW03M2Vm&utm_source=qr" target="_blank" rel="noopener noreferrer" class="text-gray-300 hover:text-white transition">Instagram</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 mt-6 md:mt-8 pt-6 md:pt-8 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} {{ __('Wen Sar') }}. {{ __('All rights reserved.') }}
            </div>
        </div>

        <!-- Facebook Coming Soon Toast -->
        <div x-show="fbToast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50" x-cloak>
            <div class="bg-gray-900 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <span class="font-medium">حساب الفيسبوك قريباً!</span>
            </div>
        </div>

        <!-- Terms of Use Modal -->
        <div x-show="termsOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="termsOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="termsOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="termsOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                                <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-6" id="modal-title">شروط الاستخدام</h3>
                                <div class="mt-2 space-y-4 text-gray-600 max-h-96 overflow-y-auto">
                                    <p>مرحباً بك في منصة "وين صار". باستخدامك للمنصة، فإنك توافق على الشروط والأحكام التالية:</p>
                                    <div class="space-y-3">
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">1. قبول الشروط</h4>
                                            <p class="text-sm">باستخدامك لمنصة "وين صار"، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي جزء من هذه الشروط، يرجى عدم استخدام المنصة.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">2. استخدام المنصة</h4>
                                            <p class="text-sm">يجب استخدام المنصة لأغراض مشروعة فقط. يُحظر استخدام المنصة لأي نشاط غير قانوني أو احتيالي.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">3. حسابات المستخدمين</h4>
                                            <p class="text-sm">أنت مسؤول عن الحفاظ على سرية معلومات حسابك وكلمة المرور. يجب إخطارنا فوراً بأي استخدام غير مصرح به لحسابك.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">4. المحتوى</h4>
                                            <p class="text-sm">المحتوى المنشور على المنصة هو مسؤولية أصحابه. نحن غير مسؤولين عن دقة أو اكتمال أي معلومات منشورة.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">5. التعديلات</h4>
                                            <p class="text-sm">نحتفظ بالحق في تعديل هذه الشروط في أي وقت. سيتم إخطار المستخدمين بأي تغييرات جوهرية.</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">آخر تحديث: 14 أبريل 2026</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="termsOpen = false" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-brand-green text-base font-medium text-white hover:opacity-90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            فهمت
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Privacy Policy Modal -->
        <div x-show="privacyOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="privacyOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="privacyOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="privacyOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-right w-full">
                                <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-6" id="modal-title">سياسة الخصوصية</h3>
                                <div class="mt-2 space-y-4 text-gray-600 max-h-96 overflow-y-auto">
                                    <p>نحن في "وين صار" نولي أهمية كبيرة لخصوصيتك. هذه السياسة توضح كيفية جمع واستخدام وحماية معلوماتك الشخصية:</p>
                                    <div class="space-y-3">
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">1. المعلومات التي نجمعها</h4>
                                            <p class="text-sm">نجمع المعلومات التي تقدمها لنا مباشرة (الاسم، البريد الإلكتروني، رقم الهاتف) عند التسجيل أو التواصل معنا.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">2. كيفية استخدام المعلومات</h4>
                                            <p class="text-sm">نستخدم معلوماتك لتقديم خدماتنا، وتحسين تجربة المستخدم، والتواصل معك بشأن تحديثات المنصة.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">3. حماية المعلومات</h4>
                                            <p class="text-sm">نستخدم إجراءات أمنية مناسبة لحماية معلوماتك من الوصول غير المصرح به أو التعديل أو الإفصاح.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">4. مشاركة المعلومات</h4>
                                            <p class="text-sm">لا نبيع أو نؤجر معلوماتك الشخصية لطرف ثالث. قد نشاركها فقط مع مقدمي الخدمات الذين يساعدوننا في تشغيل المنصة.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">5. حقوقك</h4>
                                            <p class="text-sm">لديك الحق في الوصول إلى معلوماتك الشخصية، تصحيحها، أو حذفها. يمكنك التواصل معنا لأي استفسارات بخصوص الخصوصية.</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">آخر تحديث: 14 أبريل 2026</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="privacyOpen = false" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-brand-green text-base font-medium text-white hover:opacity-90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            فهمت
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
