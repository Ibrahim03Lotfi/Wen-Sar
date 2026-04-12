@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gray-50 overflow-hidden">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%2306402b\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-200/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-brand-green/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-orange-100 px-4 py-2 rounded-full text-orange-700 text-sm font-bold mb-3 border border-orange-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            نخبة مميزة
        </div>
        
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2 leading-tight">
            أماكن <span class="text-brand-green">مميزة</span>
        </h1>
        <p class="text-gray-500 max-w-xl mx-auto mb-4">
            أفضل 25 محل وخدمة مختارة بعناية في سوريا
        </p>
        
        <!-- Stats -->
        <div class="flex justify-center gap-4">
            <div class="bg-white px-5 py-2 rounded-xl shadow-sm border border-gray-200">
                <span class="text-xl font-bold text-brand-green">25</span>
                <span class="text-gray-500 text-sm mr-1">منشأة مميزة</span>
            </div>
        </div>
    </div>
</div>

<!-- Featured Businesses Grid -->
<div class="bg-white py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Count Badge -->
        <div class="flex justify-center mb-10">
            <div class="bg-white px-6 py-3 rounded-full shadow-md border border-gray-100 flex items-center gap-3">
                <span class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></span>
                <span class="text-gray-600 font-medium">
                    معروض <span class="text-brand-green font-bold text-lg">{{ $businesses->count() }}</span> منشأة مميزة
                </span>
            </div>
        </div>

        <!-- Grid - 5 columns on xl for 25 items -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
            @forelse($businesses as $index => $business)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 group hover:-translate-y-2">
                <!-- Image -->
                <div class="h-40 bg-gray-200 relative overflow-hidden">
                    @if($business->images && count($business->images) > 0)
                        <img src="{{ asset('storage/' . $business->images[0]) }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @elseif($business->logo)
                        <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @endif
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- Featured Badge -->
                    <div class="absolute top-3 right-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        #{{ $index + 1 }}
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-4">
                    <!-- Category & Rating -->
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-[10px] font-bold text-brand-green px-2 py-1 bg-brand-green/5 rounded-lg truncate max-w-[60%]">{{ $business->category->name }}</span>
                        <div class="flex items-center bg-yellow-50 px-2 py-1 rounded-lg border border-yellow-100">
                            <span class="text-xs font-bold text-yellow-700 ml-1">{{ number_format($business->averageRating(), 1) }}</span>
                            <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        </div>
                    </div>
                    
                    <!-- Name -->
                    <h3 class="text-sm font-bold text-gray-800 mb-2 truncate group-hover:text-brand-green transition-colors line-clamp-2" style="min-height: 2.5rem;">{{ $business->name }}</h3>
                    
                    <!-- Location -->
                    <div class="flex items-center text-gray-400 text-xs mb-3">
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="truncate">{{ $business->subArea?->name ?? 'غير محدد' }}</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <div class="flex items-center text-xs text-gray-400">
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $business->views_count }}
                        </div>
                        <a href="{{ route('business.show', $business->id) }}" class="text-brand-green font-bold text-xs flex items-center gap-1 hover:gap-2 transition-all">
                            عرض
                            <svg class="w-3 h-3 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-20">
                <div class="w-32 h-32 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 mb-3">لا توجد منشآت مميزة حالياً</h3>
                <p class="text-gray-500 max-w-md mx-auto">سيتم إضافة منشآت مميزة قريباً. تفقد الصفحة لاحقاً!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-6 bg-brand-green text-white font-bold px-6 py-3 rounded-xl hover:opacity-90 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    العودة للرئيسية
                </a>
            </div>
            @endforelse
        </div>
        
        <!-- Bottom CTA -->
        @if($businesses->count() > 0)
        <div class="mt-16 text-center">
            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 max-w-2xl mx-auto">
                <h3 class="text-xl font-bold text-gray-800 mb-3">هل أنت صاحب منشأة؟</h3>
                <p class="text-gray-500 mb-6">انضم إلى قائمة المنشآت المميزة وزد من ظهورك وزبائنك</p>
                <a href="{{ route('select-role') }}?action=register" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-4 rounded-xl hover:opacity-90 transition-all shadow-lg shadow-brand-green/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    سجل منشأتك الآن
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
