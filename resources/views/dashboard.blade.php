@extends('layouts.app')

@section('content')
@auth
    @if(Auth::user()->hasRole('owner'))
        <!-- Business Owner Dashboard -->
        <div class="bg-gray-50 min-h-screen py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">لوحة تحكم صاحب النشاط</h1>
                        <p class="text-gray-500 mt-2">إدارة منشآتك التجارية</p>
                    </div>
                    <a href="{{ route('owner.businesses.create') }}" 
                       class="bg-brand-green text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إضافة منشأة جديدة
                    </a>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">عدد المنشآت</p>
                                <p class="text-3xl font-bold text-brand-green mt-1">{{ $businesses->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-brand-green/10 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">إجمالي المشاهدات</p>
                                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $businesses->sum('views_count') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-brand-green/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">تغيير كلمة المرور</h2>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 text-green-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-start gap-2 text-red-700">
                                <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('owner.password.update') }}" onsubmit="return confirm('هل أنت متأكد من تغيير كلمة المرور؟')">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">كلمة المرور الحالية <span class="text-red-500">*</span></label>
                                <input type="password" name="current_password" required class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">كلمة المرور الجديدة <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required minlength="8" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">تأكيد كلمة المرور الجديدة <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required minlength="8" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="••••••••">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-brand-green text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                تحديث كلمة المرور
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Businesses Grid -->
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">منشآتي</h2>
                </div>

                @if($businesses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($businesses as $business)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-gray-100 group">
                            <div class="h-48 bg-gray-100 relative">
                                @if($business->logo)
                                    <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                                @elseif($business->images && count($business->images) > 0)
                                    <img src="{{ asset('storage/' . $business->images[0]) }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="{{ $business->name }}" class="w-full h-full object-cover">
                                @endif
                                <div class="absolute top-4 right-4 bg-brand-green text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">
                                    {{ $business->category->name }}
                                </div>
                                <!-- Status Badge -->
                                <div class="absolute top-4 left-4 shadow-lg">
                                    @if($business->status === 'pending')
                                        <span class="bg-yellow-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">بانتظار الموافقة</span>
                                    @elseif($business->status === 'approved' && ($business->contract_ends_at === null || $business->contract_ends_at > now()))
                                        <span class="bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">منشور</span>
                                    @elseif($business->status === 'rejected')
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">مرفوض</span>
                                    @elseif($business->contract_ends_at && $business->contract_ends_at <= now())
                                        <span class="bg-gray-500 text-white text-[10px] font-bold px-3 py-1 rounded-full">منتهي</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-4">{{ $business->name }}</h3>
                                <div class="flex items-center text-gray-400 text-sm gap-4 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $business->subArea?->name ?? 'غير محدد' }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        {{ $business->views_count }}
                                    </div>
                                </div>

                                <!-- Contract Info for Approved Businesses -->
                                @if($business->status === 'approved' && $business->contract_ends_at)
                                <div class="mb-4 text-xs">
                                    @php
                                        $daysLeft = $business->daysUntilExpiry();
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500">ينتهي العقد:</span>
                                        <span class="font-bold {{ $daysLeft <= 3 ? 'text-red-500' : 'text-green-600' }}">
                                            {{ $business->contract_ends_at->format('Y-m-d') }}
                                            @if($daysLeft !== null)
                                                ({{ $daysLeft > 0 ? $daysLeft . ' يوم متبقي' : 'منتهي' }})
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @endif

                                <div class="flex gap-3 pt-4 border-t border-gray-50">
                                    <a href="{{ route('owner.businesses.edit', $business->id) }}" class="flex-1 bg-brand-green/5 text-brand-green font-bold text-center py-3 rounded-xl hover:bg-brand-green hover:text-white transition-all">
                                        تعديل البيانات
                                    </a>
                                    <a href="{{ route('business.show', $business->id) }}" class="p-3 bg-gray-50 text-gray-400 rounded-xl hover:bg-brand-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-400">لا تملك أي منشآت مسجلة حالياً</h3>
                        <p class="text-gray-400 mt-2 mb-8">ابدأ بإضافة أول منشأة لك لتظهر في الدليل</p>
                        <a href="{{ route('owner.businesses.create') }}" class="bg-brand-green text-white font-bold py-4 px-10 rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-brand-green/20">
                            أضف منشأتك الآن
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Regular User Dashboard -->
        <div class="bg-gray-50 min-h-screen py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">مرحباً {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600">يمكنك الآن البحث عن المحال التجارية وإضافتها إلى المفضلة.</p>
                    <a href="{{ route('home') }}" class="mt-6 inline-block bg-brand-green text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all">
                        ابدأ البحث
                    </a>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection
