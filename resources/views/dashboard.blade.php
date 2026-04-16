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

                <!-- Businesses List -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800">منشآتي</h2>
                    </div>
                    
                    @if($businesses->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($businesses as $business)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <!-- Business Image -->
                                    <div class="w-20 h-20 rounded-xl bg-gray-200 flex-shrink-0 overflow-hidden">
                                        @if($business->logo)
                                            <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-brand-green/10">
                                                <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Business Info -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="font-bold text-lg text-gray-800">{{ $business->name }}</h3>
                                                    @if($business->status === 'pending')
                                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs font-bold">بانتظار الموافقة</span>
                                                    @elseif($business->status === 'approved')
                                                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">مقبول</span>
                                                    @elseif($business->status === 'rejected')
                                                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs font-bold">مرفوض</span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-500 text-sm mt-1">{{ $business->category->name }} • {{ $business->subArea?->name ?? 'غير محدد' }}</p>
                                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        {{ $business->views_count }} مشاهدة
                                                    </span>
                                                    @if($business->is_featured)
                                                        <span class="bg-orange-100 text-orange-600 px-2 py-0.5 rounded-full text-xs font-bold">مميز</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('owner.businesses.edit', $business) }}" 
                                                   class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    تعديل
                                                </a>
                                                <a href="{{ route('business.show', $business) }}" target="_blank"
                                                   class="bg-brand-green/10 text-brand-green px-4 py-2 rounded-lg font-medium hover:bg-brand-green/20 transition-colors flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    عرض
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">لا توجد منشآت</h3>
                            <p class="text-gray-500 mb-6">ابدأ بإضافة أول منشأة لك</p>
                            <a href="{{ route('owner.businesses.create') }}" 
                               class="bg-brand-green text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                إضافة منشأة
                            </a>
                        </div>
                    @endif
                </div>
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
