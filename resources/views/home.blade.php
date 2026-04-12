@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-brand-green overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1549136365-5c1a1795f57a?q=80&w=2070&auto=format&fit=crop" alt="Damascus" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24 lg:py-32 sm:px-6 lg:px-8 flex flex-col items-center text-center">
        <h1 class="text-2xl sm:text-4xl lg:text-6xl font-extrabold text-white mb-4 md:mb-6 leading-tight">
            دليلك الشامل في <span class="text-orange-400">سوريا</span>
        </h1>
        <p class="text-base md:text-xl text-brand-white/80 mb-6 md:mb-10 max-w-2xl px-4">
            ابحث عن المحال التجارية، المهن، المدارس، الأطباء وكل ما تحتاجه في دمشق وسوريا.
        </p>

        <!-- Search Box -->
        <div x-data="{ 
                governorateId: '{{ $governorates->first()?->id ?? '' }}',
                districtId: '',
                categoryId: '',
                districts: {{ json_encode($governorates->first()?->districts->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values() ?? []) }},
                get canSearch() {
                    return this.governorateId && this.districtId && this.categoryId;
                },
                async updateDistricts() {
                    if(!this.governorateId) {
                        this.districts = [];
                        return;
                    }
                    const response = await fetch(`/api/governorates/${this.governorateId}/districts`);
                    this.districts = await response.json();
                    this.districtId = '';
                }
            }" 
            class="w-full max-w-4xl bg-white p-3 md:p-4 rounded-xl md:rounded-2xl shadow-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 items-end mx-4">
            
            <form action="{{ route('business.search') }}" method="GET" class="contents">
                <!-- Governorate -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 mr-1">المحافظة <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select x-model="governorateId" @change="updateDistricts()" 
                                class="w-full border-gray-200 rounded-lg focus:ring-brand-green focus:border-brand-green text-sm bg-gray-50 py-2.5 pl-10 pr-3 appearance-none cursor-pointer" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">اختر المحافظة...</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $gov->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <!-- District -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 mr-1">المنطقة <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="district_id" x-model="districtId"
                                class="w-full border-gray-200 rounded-lg focus:ring-brand-green focus:border-brand-green text-sm bg-gray-50 py-2.5 pl-10 pr-3 appearance-none cursor-pointer" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">اختر المنطقة...</option>
                            <template x-for="district in districts" :key="district.id">
                                <option :value="district.id" x-text="district.name"></option>
                            </template>
                        </select>
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 mr-1">التصنيف <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="category_id" x-model="categoryId"
                                class="w-full border-gray-200 rounded-lg focus:ring-brand-green focus:border-brand-green text-sm bg-gray-50 py-2.5 pl-10 pr-3 appearance-none cursor-pointer" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">ماذا تبحث؟</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit" 
                        :disabled="!canSearch"
                        :class="canSearch ? 'bg-brand-green hover:opacity-90 cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                        class="text-white font-bold py-3 px-4 md:py-3.5 md:px-6 rounded-lg md:rounded-xl transition duration-300 shadow-lg text-sm md:text-base">
                    <span x-show="canSearch">ابحث الآن</span>
                    <span x-show="!canSearch">ابحث الآن</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div class="max-w-7xl mx-auto px-4 py-10 md:py-16 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 md:mb-10 gap-3">
        <h2 class="text-xl md:text-3xl font-bold text-gray-800">تصفح حسب التصنيف</h2>
        <a href="{{ route('categories.index') }}" class="text-brand-green hover:underline font-medium text-sm md:text-base">عرض الكل ←</a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
        @foreach($categories as $cat)
        <a href="{{ route('business.category', $cat->id) }}" class="group bg-white p-3 md:p-6 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-brand-green/20 transition-all text-center active:scale-95">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-brand-green/5 rounded-full flex items-center justify-center mx-auto mb-2 md:mb-4 group-hover:bg-brand-green transition-colors">
                <!-- Category Icon Based on Name -->
                @if(str_contains($cat->name, 'مطاعم') || str_contains($cat->name, 'مأكولات'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                @elseif(str_contains($cat->name, 'مقاهي') || str_contains($cat->name, 'كافيه'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zm4-7v3M10 1v3M14 1v3"/>
                    </svg>
                @elseif(str_contains($cat->name, 'محلات') || str_contains($cat->name, 'تجاري'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                @elseif(str_contains($cat->name, 'صحية') || str_contains($cat->name, 'طب') || str_contains($cat->name, 'صيدلية'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                @elseif(str_contains($cat->name, 'تعليم') || str_contains($cat->name, 'مدارس') || str_contains($cat->name, 'جامعة'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                @elseif(str_contains($cat->name, 'سياحة') || str_contains($cat->name, 'فنادق') || str_contains($cat->name, 'سفر'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                @elseif(str_contains($cat->name, 'خدمات') || str_contains($cat->name, 'صيانة'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                @elseif(str_contains($cat->name, 'رياضة') || str_contains($cat->name, 'صالات'))
                    <svg class="w-8 h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                @elseif(str_contains($cat->name, 'تقنية') || str_contains($cat->name, 'إلكترونيات') || str_contains($cat->name, 'موبايل'))
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                @elseif(str_contains($cat->name, 'سيارات') || str_contains($cat->name, 'معارض'))
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                @else
                    <!-- Default Icon -->
                    <svg class="w-6 h-6 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                @endif
            </div>
            <h3 class="font-bold text-sm md:text-base text-gray-800 group-hover:text-brand-green transition-colors truncate">{{ $cat->name }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ $cat->businesses_count ?? $cat->businesses->count() }} مكان</p>
        </a>
        @endforeach
    </div>
</div>

<!-- Featured Businesses -->
<div class="bg-gray-100 py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 md:mb-10 gap-3">
            <div>
                <h2 class="text-xl md:text-3xl font-bold text-gray-800">أماكن مميزة</h2>
                <p class="text-gray-500 mt-1 md:mt-2 text-sm md:text-base">نخبة من أفضل المحال والخدمات في دمشق</p>
            </div>
            <a href="{{ route('business.featured') }}" class="text-brand-green hover:underline font-medium text-sm md:text-base">عرض المزيد ←</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
            @foreach($featuredBusinesses as $business)
            <div class="bg-white rounded-xl md:rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group active:scale-[0.98] transition-transform">
                <div class="h-40 md:h-48 bg-gray-200 relative">
                    @if($business->images && count($business->images) > 0)
                        <img src="{{ asset('storage/' . $business->images[0]) }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @elseif($business->logo)
                        <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                    <div class="absolute top-3 right-3 md:top-4 md:right-4 bg-orange-500 text-white text-xs font-bold px-2 py-1 md:px-3 md:py-1 rounded-full shadow-lg">
                        مميز
                    </div>
                </div>
                <div class="p-4 md:p-6">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-brand-green px-2 py-1 bg-brand-green/5 rounded truncate max-w-[50%]">{{ $business->category->name }}</span>
                        <div class="flex items-center text-yellow-400">
                            <span class="text-xs font-bold text-gray-700 ml-1">4.8</span>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-1 md:mb-2 truncate">{{ $business->name }}</h3>
                    <div class="flex items-center text-gray-500 text-xs md:text-sm mb-3 md:mb-4">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate">{{ $business->subArea?->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-50 pt-3 md:pt-4">
                        <div class="text-xs text-gray-400">
                            <span class="font-bold text-gray-600">{{ $business->views_count }}</span> مشاهدة
                        </div>
                        <a href="#" class="text-brand-green font-bold text-sm flex items-center group-hover:translate-x-[-4px] transition-transform">
                            التفاصيل
                            <svg class="w-4 h-4 mr-1 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
