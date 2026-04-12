@extends('layouts.app')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-12" x-data="{
    searchQuery: '{{ request('query') }}',
    districtId: '{{ request('district_id') }}',
    subAreaId: '{{ request('sub_area_id') }}',
    categoryId: '{{ request('category_id') ?? (isset($category) ? $category->id : '') }}',
    resultsHtml: '',
    resultsCount: {{ $businesses->count() }},
    isLoading: false,
    searchTimeout: null,
    subAreas: [],
    hasSearched: false,
    
    async init() {
        // Wait for DOM to be fully ready
        await $nextTick;
        
        // Load sub-areas if district is selected
        if(this.districtId) {
            await this.updateSubAreas();
            this.subAreaId = '{{ request('sub_area_id') }}';
        }
        
        // Store initial results HTML
        this.resultsHtml = $refs.resultsContainer.innerHTML;
    },
    
    async updateSubAreas() {
        if(!this.districtId) {
            this.subAreas = [];
            this.subAreaId = '';
            return;
        }
        const response = await fetch(`/api/districts/${this.districtId}/sub-areas`);
        this.subAreas = await response.json();
    },
    
    debouncedSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.performSearch();
        }, 300);
    },
    
    async performSearch() {
        this.isLoading = true;
        
        const params = new URLSearchParams();
        if(this.searchQuery) params.append('query', this.searchQuery);
        if(this.districtId) params.append('district_id', this.districtId);
        if(this.subAreaId) params.append('sub_area_id', this.subAreaId);
        if(this.categoryId) params.append('category_id', this.categoryId);
        
        try {
            const isCategoryPage = {{ isset($category) ? 'true' : 'false' }};
            const searchUrl = isCategoryPage 
                ? `/category/{{ $category->id ?? 0 }}?${params.toString()}`
                : `/api/search?${params.toString()}`;
            const response = await fetch(searchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            this.resultsHtml = data.html;
            this.resultsCount = data.count;
            this.hasSearched = true;
            
            // Update URL without page reload
            const url = new URL(window.location);
            if(this.searchQuery) url.searchParams.set('query', this.searchQuery);
            else url.searchParams.delete('query');
            if(this.districtId) url.searchParams.set('district_id', this.districtId);
            else url.searchParams.delete('district_id');
            if(this.subAreaId) url.searchParams.set('sub_area_id', this.subAreaId);
            else url.searchParams.delete('sub_area_id');
            if(this.categoryId) url.searchParams.set('category_id', this.categoryId);
            else url.searchParams.delete('category_id');
            window.history.replaceState({}, '', url);
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            this.isLoading = false;
        }
    },
    
    async onDistrictChange() {
        await this.updateSubAreas();
        this.performSearch();
    }
}">
    <!-- Search Bar Section -->
    <div class="bg-brand-green py-6 md:py-8 shadow-lg relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <div class="bg-white/10 rounded-lg md:rounded-xl p-3 md:p-4 mb-4 md:mb-6 border border-white/20">
                <h1 class="text-white text-base md:text-xl font-bold flex items-center gap-2 md:gap-3">
                    <svg class="w-5 h-5 md:w-6 md:h-6 border-2 border-white/30 rounded-full p-0.5 md:p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    @php
                        $selectedCategory = request('category_id') ? $categories->firstWhere('id', request('category_id')) : null;
                        $selectedDistrict = request('district_id') ? $districts->firstWhere('id', request('district_id')) : null;
                    @endphp
                    @if(isset($category))
                        تصنيف: <span class="text-orange-400">{{ $category->name }}</span>
                        @if($selectedDistrict)
                            في <span class="text-orange-400">{{ $selectedDistrict->name }}</span>
                        @endif
                    @elseif($selectedCategory && $selectedDistrict)
                        نتائج البحث عن: <span class="text-orange-400">{{ $selectedCategory->name }}</span> 
                        في <span class="text-orange-400">{{ $selectedDistrict->name }}</span>
                    @elseif($selectedCategory)
                        نتائج البحث عن: <span class="text-orange-400">{{ $selectedCategory->name }}</span>
                    @elseif($selectedDistrict)
                        نتائج البحث عن: <span class="text-orange-400">{{ $selectedDistrict->name }}</span>
                    @else
                        نتائج البحث
                    @endif
                </h1>
            </div>

            <!-- Search Form Box -->
            <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-4 shadow-xl border border-gray-200 md:border-2 md:border-gray-100">
                <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1 md:mb-2 mr-1">البحث</label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchQuery" 
                                   @input="debouncedSearch()"
                                   placeholder="ماذا تبحث؟" 
                                   class="w-full border-2 border-gray-200 rounded-lg py-2.5 md:py-3 px-3 md:px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green text-gray-800 placeholder-gray-400 bg-white text-sm md:text-base">
                        </div>
                    </div>

                    <!-- District Select -->
                    <div class="md:w-64">
                        <label class="block text-xs font-bold text-gray-500 mb-1 md:mb-2 mr-1">المنطقة</label>
                        <div class="relative">
                            <select x-model="districtId" @change="onDistrictChange()"
                                    class="w-full border-2 border-gray-200 rounded-lg py-2.5 md:py-3 pl-10 md:pl-12 pr-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green text-gray-800 bg-white cursor-pointer appearance-none text-sm md:text-base" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                <option value="">كل المناطق</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div class="hidden md:flex md:w-auto items-end">
                        <div x-show="isLoading" x-transition class="py-3 px-4">
                            <svg class="animate-spin h-5 w-5 text-brand-green" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        <div class="flex flex-col lg:flex-row gap-4 md:gap-6">
            <!-- Mobile Filter Toggle -->
            <div class="lg:hidden mb-2">
                <button @click="mobileFiltersOpen = !mobileFiltersOpen" 
                        class="w-full bg-white rounded-lg p-3 shadow-md border border-gray-200 flex items-center justify-between font-bold text-gray-700">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        تصفية النتائج
                    </span>
                    <svg class="w-5 h-5 transition-transform" :class="mobileFiltersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Filters -->
            <div class="w-full lg:w-1/4" x-data="{ mobileFiltersOpen: false }" :class="mobileFiltersOpen || 'hidden lg:block'">
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 lg:sticky lg:top-24 overflow-hidden">
                    <!-- Sidebar Header -->
                    <div class="bg-gray-50 px-4 md:px-5 py-3 md:py-4 border-b-2 border-gray-200 hidden lg:block">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            تصفية النتائج
                        </h3>
                    </div>

                    <!-- Sidebar Content -->
                    <div class="p-4 md:p-5 space-y-4 md:space-y-5">
                        <!-- District Filter -->
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    المنطقة
                                </span>
                            </label>
                            <div class="relative">
                                <select x-model="districtId" @change="onDistrictChange()" 
                                        class="w-full border-2 border-gray-300 rounded-lg py-2.5 pl-10 pr-3 text-sm focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white font-medium cursor-pointer appearance-none" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                    <option value="">كل المناطق</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 text-gray-500 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Sub-area (الحي) Filter -->
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    الحي
                                </span>
                            </label>
                            <div class="relative">
                                <select x-model="subAreaId" @change="performSearch()"
                                        class="w-full border-2 border-gray-300 rounded-lg py-2.5 pl-10 pr-3 text-sm focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white font-medium cursor-pointer appearance-none" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                    <option value="">كل الأحياء</option>
                                    <template x-for="area in subAreas" :key="area.id">
                                        <option :value="area.id" x-text="area.name"></option>
                                    </template>
                                </select>
                                <svg class="w-4 h-4 text-gray-500 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t-2 border-gray-200 my-4"></div>

                        <!-- Categories -->
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-3">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    التصنيفات
                                </span>
                            </label>
                            <div class="space-y-2 max-h-72 overflow-y-auto pr-1 custom-scrollbar">
                                @foreach($categories as $category)
                                <label class="flex items-center group cursor-pointer p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all border border-transparent hover:border-gray-200"
                                       @click="categoryId = {{ $category->id }}; performSearch()">
                                    <input type="checkbox" 
                                           :checked="categoryId == {{ $category->id }}"
                                           class="rounded text-brand-green focus:ring-brand-green w-4 h-4 border-2 border-gray-300 cursor-pointer">
                                    <span class="mr-3 text-sm text-gray-700 group-hover:text-gray-900 font-medium transition-colors">{{ $category->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results List -->
            <div class="w-full lg:w-3/4">
                <!-- Results Header -->
                <div class="bg-white rounded-xl p-3 md:p-4 mb-4 md:mb-6 shadow-md border-2 border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-3 md:gap-4">
                    <h2 class="text-base md:text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="bg-brand-green text-white text-xs md:text-sm px-2.5 md:px-3 py-1 rounded-lg" x-text="resultsCount"></span>
                        نتيجة بحث
                    </h2>
                    <div class="flex items-center gap-2">
                        <label class="text-xs md:text-sm font-bold text-gray-600">ترتيب حسب:</label>
                        <div class="relative">
                            <select class="border-2 border-gray-300 rounded-lg text-xs md:text-sm font-medium text-gray-700 focus:ring-2 focus:ring-brand-green focus:border-brand-green py-2 pl-8 md:pl-10 pr-3 md:pr-4 bg-white cursor-pointer appearance-none" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                <option>الأكثر صلة</option>
                                <option>الأعلى تقييماً</option>
                                <option>الأحدث</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-500 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Results Grid -->
                <div class="grid grid-cols-1 gap-5">
                    <!-- Initial results from server (shown when no AJAX search yet) -->
                    <div x-show="!hasSearched" x-cloak>
                        @include('businesses._results', ['businesses' => $businesses, 'categories' => $categories, 'districts' => $districts])
                    </div>
                    <!-- Dynamic results from AJAX (shown after first search) -->
                    <template x-if="hasSearched">
                        <div x-html="resultsHtml"></div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
