<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Dashboard')) - {{ __('Manager') }} | {{ __('Wen Sar') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-link {
            @apply flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all;
        }
        .sidebar-link.active {
            @apply bg-brand-green text-white hover:bg-brand-green hover:text-white;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-xl hidden lg:flex flex-col">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-green rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-gray-800">{{ __('Manager') }}</h1>
                        <p class="text-xs text-gray-500">{{ __('Control Panel') }}</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('manager.dashboard') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <div class="pt-2 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Approvals') }}</p>
                </div>

                <a href="{{ route('manager.approvals.pending') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.approvals.pending') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex items-center gap-2">
                        {{ __('Pending') }}
                        @php
                            $pendingCount = App\Models\Business::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </div>
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <a href="{{ route('manager.approvals.expiring') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.approvals.expiring') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex items-center gap-2">
                        {{ __('Expiring Soon') }}
                        @php
                            $expiringCount = App\Models\Business::where('status', 'approved')
                                ->where('contract_ends_at', '<=', now()->addDays(3))
                                ->where('contract_ends_at', '>=', now())
                                ->count();
                        @endphp
                        @if($expiringCount > 0)
                            <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $expiringCount }}</span>
                        @endif
                    </div>
                </a>

                <hr class="my-3 border-gray-100">

                <div class="pt-2 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Management') }}</p>
                </div>

                <a href="{{ route('manager.businesses.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.businesses.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('Businesses') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <a href="{{ route('manager.owners.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.owners.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ __('Owners') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <a href="{{ route('manager.categories.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    {{ __('Categories') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <hr class="my-2 border-gray-100">

                <div class="pt-2 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Locations') }}</p>
                </div>

                <a href="{{ route('manager.governorates.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.governorates.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('Governorates') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <a href="{{ route('manager.districts.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.districts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('Districts') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <a href="{{ route('manager.sub-areas.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.sub-areas.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('Sub Areas') }}
                </a>

                <div class="h-px bg-gray-100 mx-4"></div>

                <hr class="my-2 border-gray-100">

                <div class="pt-2 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Administration') }}</p>
                </div>

                <a href="{{ route('manager.managers.index') }}" class="sidebar-link flex items-center justify-between gap-3 px-4 py-3 text-gray-600 hover:bg-brand-green/10 hover:text-brand-green rounded-xl transition-all {{ request()->routeIs('manager.managers.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    {{ __('Managers') }}
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('manager.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-between gap-3 px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Mobile Header -->
            <header class="lg:hidden bg-white shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-green rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h1 class="font-bold text-gray-800">{{ __('Manager Panel') }}</h1>
                    </div>
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="p-2 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden mt-4 space-y-2">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4 pt-2 pb-1">{{ __('Dashboard') }}</div>
                    <a href="{{ route('manager.dashboard') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Dashboard') }}</a>

                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4 pt-4 pb-1">{{ __('Approvals') }}</div>
                    <a href="{{ route('manager.approvals.pending') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        {{ __('Pending') }}
                        @php
                            $pendingCount = App\Models\Business::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('manager.approvals.expiring') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        {{ __('Expiring Soon') }}
                        @php
                            $expiringCount = App\Models\Business::where('status', 'approved')
                                ->where('contract_ends_at', '<=', now()->addDays(3))
                                ->where('contract_ends_at', '>=', now())
                                ->count();
                        @endphp
                        @if($expiringCount > 0)
                            <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $expiringCount }}</span>
                        @endif
                    </a>

                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4 pt-4 pb-1">{{ __('Management') }}</div>
                    <a href="{{ route('manager.businesses.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Businesses') }}</a>
                    <a href="{{ route('manager.owners.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Owners') }}</a>
                    <a href="{{ route('manager.categories.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Categories') }}</a>

                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4 pt-4 pb-1">{{ __('Locations') }}</div>
                    <a href="{{ route('manager.governorates.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Governorates') }}</a>
                    <a href="{{ route('manager.districts.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Districts') }}</a>
                    <a href="{{ route('manager.sub-areas.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Sub Areas') }}</a>

                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider px-4 pt-4 pb-1">{{ __('Administration') }}</div>
                    <a href="{{ route('manager.managers.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">{{ __('Managers') }}</a>

                    <div class="pt-4">
                        <form method="POST" action="{{ route('manager.logout') }}" class="px-4 py-2">
                            @csrf
                            <button type="submit" class="text-red-600 font-bold">{{ __('Logout') }}</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Header -->
            <div class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">@yield('page-title', __('Dashboard'))</h2>
                        <div class="flex items-center gap-4">
                            <div class="text-sm text-gray-500">
                                {{ Auth::guard('manager')->user()->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
