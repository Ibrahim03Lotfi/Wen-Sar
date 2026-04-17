<?php $__env->startPush('styles'); ?>
<style>
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $initialResultsHtml = view('businesses._results', ['businesses' => $businesses, 'categories' => $categories, 'districts' => $districts])->render();
?>
<div class="bg-gray-50 min-h-screen pb-12" x-data="{
    searchQuery: '<?php echo e(request('query')); ?>',
    districtId: '<?php echo e(request('district_id')); ?>',
    subAreaId: '<?php echo e(request('sub_area_id')); ?>',
    categoryId: '<?php echo e(request('category_id') ?? (isset($category) ? $category->id : '')); ?>',
    resultsHtml: <?php echo e(Illuminate\Support\Js::encode($initialResultsHtml)); ?>,
    resultsCount: <?php echo e($businesses->count()); ?>,
    isLoading: false,
    searchTimeout: null,
    subAreas: [],
    mobileFiltersOpen: false,

    async init() {
        // Load sub-areas if district is selected
        if(this.districtId) {
            await this.updateSubAreas();
            this.subAreaId = '<?php echo e(request('sub_area_id')); ?>';
        }
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
            const isCategoryPage = <?php echo e(isset($category) ? 'true' : 'false'); ?>;
            const searchUrl = isCategoryPage
                ? `/category/<?php echo e($category->id ?? 0); ?>?${params.toString()}`
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
                    <?php
                        $selectedCategory = request('category_id') ? $categories->firstWhere('id', request('category_id')) : null;
                        $selectedDistrict = request('district_id') ? $districts->firstWhere('id', request('district_id')) : null;
                    ?>
                    <?php if(isset($category)): ?>
                        <?php echo e(__('Category')); ?>: <span class="text-orange-400"><?php echo e($category->name); ?></span>
                        <?php if($selectedDistrict): ?>
                            <?php echo e(__('in')); ?> <span class="text-orange-400"><?php echo e($selectedDistrict->name); ?></span>
                        <?php endif; ?>
                    <?php elseif($selectedCategory && $selectedDistrict): ?>
                        <?php echo e(__('Search Results for')); ?>: <span class="text-orange-400"><?php echo e($selectedCategory->name); ?></span> 
                        <?php echo e(__('in')); ?> <span class="text-orange-400"><?php echo e($selectedDistrict->name); ?></span>
                    <?php elseif($selectedCategory): ?>
                        <?php echo e(__('Search Results for')); ?>: <span class="text-orange-400"><?php echo e($selectedCategory->name); ?></span>
                    <?php elseif($selectedDistrict): ?>
                        <?php echo e(__('Search Results for')); ?>: <span class="text-orange-400"><?php echo e($selectedDistrict->name); ?></span>
                    <?php else: ?>
                        <?php echo e(__('Search Results')); ?>

                    <?php endif; ?>
                </h1>
            </div>

            <!-- Search Form Box -->
            <div class="bg-white rounded-lg md:rounded-xl p-3 md:p-4 shadow-xl border border-gray-200 md:border-2 md:border-gray-100">
                <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1 md:mb-2 mr-1"><?php echo e(__('Search')); ?></label>
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchQuery" 
                                   @input="debouncedSearch()"
                                   placeholder="<?php echo e(__('What are you looking for?')); ?>" 
                                   class="w-full border-2 border-gray-200 rounded-lg py-2.5 md:py-3 px-3 md:px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green text-gray-800 placeholder-gray-400 bg-white text-sm md:text-base">
                        </div>
                    </div>

                    <!-- District Select -->
                    <div class="md:w-48">
                        <label class="block text-xs font-bold text-gray-500 mb-1 md:mb-2 mr-1"><?php echo e(__('District')); ?></label>
                        <div class="relative">
                            <select x-model="districtId" @change="onDistrictChange()"
                                    class="w-full border-2 border-gray-200 rounded-lg py-2.5 md:py-3 pl-10 md:pl-12 pr-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green text-gray-800 bg-white cursor-pointer appearance-none text-sm md:text-base" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                <option value=""><?php echo e(__('All Districts')); ?></option>
                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($district->id); ?>"><?php echo e($district->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Sub Area Select (الحي) -->
                    <div class="md:w-48" x-show="subAreas.length > 0" x-cloak>
                        <label class="block text-xs font-bold text-gray-500 mb-1 md:mb-2 mr-1"><?php echo e(__('Sub Area')); ?></label>
                        <div class="relative">
                            <select x-model="subAreaId" @change="performSearch()"
                                    class="w-full border-2 border-gray-200 rounded-lg py-2.5 md:py-3 pl-10 md:pl-12 pr-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green text-gray-800 bg-white cursor-pointer appearance-none text-sm md:text-base" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                <option value=""><?php echo e(__('All Sub Areas')); ?></option>
                                <template x-for="area in subAreas" :key="area.id">
                                    <option :value="area.id" x-text="area.name"></option>
                                </template>
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
                        <?php echo e(__('Filter Results')); ?>

                    </span>
                    <svg class="w-5 h-5 transition-transform" :class="mobileFiltersOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Filters -->
            <div class="w-full lg:w-1/4" :class="mobileFiltersOpen ? '' : 'hidden lg:block'">
                <div class="bg-white rounded-xl shadow-md border-2 border-gray-200 lg:sticky lg:top-24 overflow-hidden">
                    <!-- Sidebar Header -->
                    <div class="bg-gray-50 px-4 md:px-5 py-3 md:py-4 border-b-2 border-gray-200 hidden lg:block">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <?php echo e(__('Filter Results')); ?>

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
                                    <?php echo e(__('District')); ?>

                                </span>
                            </label>
                            <div class="relative">
                                <select x-model="districtId" @change="onDistrictChange()" 
                                        class="w-full border-2 border-gray-300 rounded-lg py-2.5 pl-10 pr-3 text-sm focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white font-medium cursor-pointer appearance-none" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                    <option value=""><?php echo e(__('All Districts')); ?></option>
                                    <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($district->id); ?>"><?php echo e($district->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php echo e(__('Sub Area')); ?>

                                </span>
                            </label>
                            <div class="relative">
                                <select x-model="subAreaId" @change="performSearch()"
                                        class="w-full border-2 border-gray-300 rounded-lg py-2.5 pl-10 pr-3 text-sm focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white font-medium cursor-pointer appearance-none" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                                    <option value=""><?php echo e(__('All Sub Areas')); ?></option>
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
                                    <?php echo e(__('Categories')); ?>

                                </span>
                            </label>
                            <div class="space-y-2 max-h-72 overflow-y-auto pr-1 custom-scrollbar">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center group cursor-pointer p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all border border-transparent hover:border-gray-200"
                                       @click="categoryId = <?php echo e($category->id); ?>; performSearch()">
                                    <input type="checkbox" 
                                           :checked="categoryId == <?php echo e($category->id); ?>"
                                           class="rounded text-brand-green focus:ring-brand-green w-4 h-4 border-2 border-gray-300 cursor-pointer">
                                    <span class="mr-3 text-sm text-gray-700 group-hover:text-gray-900 font-medium transition-colors"><?php echo e($category->name); ?></span>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <div class="grid grid-cols-1 gap-5" x-html="resultsHtml"></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/businesses/index.blade.php ENDPATH**/ ?>