<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="relative bg-brand-green overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1549136365-5c1a1795f57a?q=80&w=2070&auto=format&fit=crop" alt="Damascus" class="w-full h-full object-cover">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24 lg:py-32 sm:px-6 lg:px-8 flex flex-col items-center text-center">
        <h1 class="text-2xl sm:text-4xl lg:text-6xl font-extrabold text-white mb-4 md:mb-6 leading-tight">
            <?php echo e(__('Your comprehensive guide in')); ?> <span class="text-orange-400"><?php echo e(__('Syria')); ?></span>
        </h1>
        <p class="text-base md:text-xl text-brand-white/80 mb-6 md:mb-10 max-w-2xl px-4">
            <?php echo e(__('Search for shops, professions, schools, doctors and everything you need in Damascus and Syria.')); ?>

        </p>

        <!-- Search Box -->
        <div x-data="{ 
                governorateId: '<?php echo e($governorates->first()?->id ?? ''); ?>',
                districtId: '',
                categoryId: '',
                districts: <?php echo e(json_encode($governorates->first()?->districts->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values() ?? [])); ?>,
                governorates: <?php echo e(json_encode($governorates->map(fn($g) => ['id' => $g->id, 'name' => $g->name])->values())); ?>,
                get canSearch() {
                    return this.governorateId && this.districtId && this.categoryId && this.isAvailableGovernorate;
                },
                get isAvailableGovernorate() {
                    const selectedGov = this.governorates.find(g => g.id == this.governorateId);
                    return selectedGov && (selectedGov.name === 'دمشق' || selectedGov.name === 'ريف دمشق');
                },
                get selectedGovernorateName() {
                    const selectedGov = this.governorates.find(g => g.id == this.governorateId);
                    return selectedGov ? selectedGov.name : '';
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
            
            <form action="<?php echo e(route('business.search')); ?>" method="GET" class="contents">
                <!-- Governorate -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 mr-1"><?php echo e(__('Governorate')); ?> <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select x-model="governorateId" @change="updateDistricts()" 
                                class="w-full border-gray-200 rounded-lg focus:ring-brand-green focus:border-brand-green text-sm bg-gray-50 py-2.5 pl-10 pr-3 appearance-none cursor-pointer" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                            <option value=""><?php echo e(__('Select Governorate...')); ?></option>
                            <?php $__currentLoopData = $governorates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($gov->id); ?>" <?php echo e($loop->first ? 'selected' : ''); ?>><?php echo e($gov->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <!-- District -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1 mr-1"><?php echo e(__('District')); ?> <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="district_id" x-model="districtId"
                                class="w-full border-gray-200 rounded-lg focus:ring-brand-green focus:border-brand-green text-sm bg-gray-50 py-2.5 pl-10 pr-3 appearance-none cursor-pointer" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                            <option value=""><?php echo e(__('Select District...')); ?></option>
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
                    <label class="block text-xs font-bold text-gray-500 mb-1 mr-1"><?php echo e(__('Category')); ?> <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="category_id" x-model="categoryId"
                                class="w-full border-gray-200 rounded-lg focus:ring-brand-green focus:border-brand-green text-sm bg-gray-50 py-2.5 pl-10 pr-3 appearance-none cursor-pointer" style="background-image: none !important; -webkit-appearance: none; -moz-appearance: none;">
                            <option value=""><?php echo e(__('What are you looking for?')); ?></option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mainCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($mainCategory->subcategories->count() > 0): ?>
                                    <optgroup label="<?php echo e($mainCategory->name); ?>">
                                        <?php $__currentLoopData = $mainCategory->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($subcategory->id); ?>"><?php echo e($subcategory->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                <?php else: ?>
                                    <option value="<?php echo e($mainCategory->id); ?>"><?php echo e($mainCategory->name); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <span x-show="canSearch"><?php echo e(__('Search Now')); ?></span>
                    <span x-show="!canSearch"><?php echo e(__('Search Now')); ?></span>
                </button>
            </form>

            <!-- Coming Soon Message -->
            <div x-show="governorateId && !isAvailableGovernorate" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="sm:col-span-2 lg:col-span-4 mt-4">
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border-2 border-orange-200 rounded-xl p-4 md:p-6 text-center shadow-lg">
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-2xl">📍</span>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-orange-700 mb-2">
                        <?php echo e(__('Coming Soon')); ?>

                    </h3>
                    <p class="text-orange-600 text-base md:text-lg font-medium">
                        المحافظة <span x-text="selectedGovernorateName" class="font-bold text-orange-800"></span> ستتواجد قريباً
                    </p>
                    <p class="text-orange-500 text-sm mt-2">
                        <?php echo e(__('Currently available only in Damascus and Rural Damascus')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ads Carousel -->
<div class="max-w-7xl mx-auto px-4 pt-8 md:pt-12 sm:px-6 lg:px-8">
    <?php
        $adSlides = $ads->values()->reverse()->values()->pad(6, null)->take(6);
    ?>
    <div class="flex items-center justify-between mb-4 md:mb-6">
        <h2 class="text-lg md:text-2xl font-extrabold text-gray-800"><?php echo e(__('Ads')); ?></h2>
        <span class="text-xs md:text-sm font-bold text-brand-green bg-brand-green/10 px-3 py-1 rounded-full"><?php echo e(__('Sponsored')); ?></span>
    </div>
    <div id="ads-carousel" class="relative">
        <div class="overflow-hidden rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 bg-white">
            <div id="ads-track" class="flex transition-transform duration-700 ease-in-out">
                <?php $__currentLoopData = $adSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="ads-slide shrink-0 p-2 md:p-3" style="width: 100%;" data-index="<?php echo e($index); ?>">
                    <div class="relative rounded-xl md:rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 bg-white" style="height: clamp(11rem, 36vw, 20rem); min-height: 11rem;">
                        <?php if($ad): ?>
                            <div class="ad-image-fallback h-full w-full bg-gradient-to-r from-brand-green via-emerald-700 to-brand-green flex items-center justify-center px-6 text-center">
                                <p class="text-white text-lg md:text-3xl font-extrabold tracking-wide"><?php echo e('استفيد من نشر اعلانك مع وين صار!'); ?></p>
                            </div>
                            <img
                                src="<?php echo e(asset('storage/' . $ad->image_path)); ?>"
                                alt="Ad <?php echo e($index + 1); ?>"
                                loading="eager"
                                onerror="this.style.display='none';"
                                class="absolute inset-0 z-10 w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                            >
                            <div class="absolute inset-0 z-20 pointer-events-none bg-gradient-to-r from-black/20 via-transparent to-black/10"></div>
                        <?php else: ?>
                            <div class="h-full w-full bg-gradient-to-r from-brand-green via-emerald-700 to-brand-green flex items-center justify-center px-6 text-center">
                                <p class="text-white text-lg md:text-3xl font-extrabold tracking-wide"><?php echo e('استفيد من نشر اعلانك مع وين صار!'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <button id="ads-right" type="button" class="absolute top-1/2 -translate-y-1/2 right-3 md:right-5 bg-white/95 hover:bg-white text-brand-green border border-brand-green/20 w-10 h-10 md:w-11 md:h-11 rounded-full shadow-xl flex items-center justify-center transition-all z-30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        <button id="ads-left" type="button" class="absolute top-1/2 -translate-y-1/2 left-3 md:left-5 bg-white/95 hover:bg-white text-brand-green border border-brand-green/20 w-10 h-10 md:w-11 md:h-11 rounded-full shadow-xl flex items-center justify-center transition-all z-30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <div id="ads-dots" class="flex justify-center mt-5 gap-4">
            <?php $__currentLoopData = $adSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button
                type="button"
                class="ads-dot w-2.5 h-2.5 md:w-3 md:h-3 rounded-full transition-all <?php echo e($index === 0 ? 'bg-brand-green scale-110' : 'bg-gray-300 hover:bg-gray-400'); ?>"
                data-index="<?php echo e($index); ?>"
                aria-label="Go to slide <?php echo e($index + 1); ?>"
            ></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<script>
    (function() {
        var carousel = document.getElementById('ads-carousel');
        if (!carousel) return;

        var track = document.getElementById('ads-track');
        var slides = Array.from(carousel.querySelectorAll('.ads-slide'));
        var dots = Array.from(carousel.querySelectorAll('.ads-dot'));
        var rightBtn = document.getElementById('ads-right');
        var leftBtn = document.getElementById('ads-left');
        var current = 0;
        var intervalId = null;
        var total = slides.length;
        var isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        var touchStartX = 0;
        var touchEndX = 0;

        function perView() {
            return 1;
        }

        function maxIndex() {
            return Math.max(0, total - perView());
        }

        function updateSlideWidths() {
            var width = 100 / perView();
            slides.forEach(function(slide) {
                slide.style.width = width + '%';
            });
        }

        function render() {
            if (current > maxIndex()) current = maxIndex();
            var shift = (100 / perView()) * current;
            track.style.transform = isRtl
                ? 'translateX(' + shift + '%)'
                : 'translateX(-' + shift + '%)';

            dots.forEach(function(dot, index) {
                var pageIndex = Math.min(index, maxIndex());
                dot.classList.toggle('bg-brand-green', index === current);
                dot.classList.toggle('scale-110', index === current);
                dot.classList.toggle('bg-gray-300', index !== current);
                dot.classList.toggle('hover:bg-gray-400', index !== current);
                dot.style.display = '';
                dot.setAttribute('data-index', pageIndex);
            });
        }

        function next() {
            // Move visually from right to left.
            current = current >= maxIndex() ? 0 : current + 1;
            render();
        }

        function prev() {
            current = current <= 0 ? maxIndex() : current - 1;
            render();
        }

        function startAuto() {
            stopAuto();
            intervalId = setInterval(next, 5000);
        }

        function stopAuto() {
            if (!intervalId) return;
            clearInterval(intervalId);
            intervalId = null;
        }

        // Left arrow moves slides visually to the left.
        leftBtn.addEventListener('click', next);
        // Right arrow moves slides visually to the right.
        rightBtn.addEventListener('click', prev);
        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var index = Number(dot.getAttribute('data-index'));
                if (!Number.isNaN(index)) {
                    current = index;
                    render();
                }
            });
        });

        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 40) next();
            if (touchEndX - touchStartX > 40) prev();
        }, { passive: true });
        window.addEventListener('resize', function() {
            updateSlideWidths();
            render();
        });

        updateSlideWidths();
        current = 0;
        render();
        startAuto();
    })();
</script>

<!-- Categories Grid -->
<div class="max-w-7xl mx-auto px-4 py-10 md:py-16 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 md:mb-10 gap-3">
        <h2 class="text-xl md:text-3xl font-bold text-gray-800"><?php echo e(__('Browse by Category')); ?></h2>
        <a href="<?php echo e(route('categories.index')); ?>" class="text-brand-green hover:underline font-medium text-sm md:text-base"><?php echo e(__('View All')); ?> ←</a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
        <?php $__currentLoopData = $categories->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('business.category', $cat->id)); ?>" class="group bg-white p-3 md:p-6 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-brand-green/20 transition-all text-center active:scale-95">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-brand-green/5 rounded-full flex items-center justify-center mx-auto mb-2 md:mb-4 group-hover:bg-brand-green transition-colors">
                <!-- Category Icon Based on Name -->
                <?php if(str_contains($cat->name, 'طبية') || str_contains($cat->name, 'صحية') || str_contains($cat->name, 'صيدلية')): ?>
                    <!-- Medical - Heart with pulse -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v4m-2-2h4"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'مطاعم') || str_contains($cat->name, 'مقاهي')): ?>
                    <!-- Restaurants - Fork and Knife -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'تعليم') || str_contains($cat->name, 'مدارس')): ?>
                    <!-- Education - Graduation Cap -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm4 6v-7"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'تجارية') || str_contains($cat->name, 'محلات')): ?>
                    <!-- Shops - Shopping Bag -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'حرفية') || str_contains($cat->name, 'صيانة')): ?>
                    <!-- Crafts - Wrench -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'سيارات') || str_contains($cat->name, 'نقل')): ?>
                    <!-- Cars - Car -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'حلاقة') || str_contains($cat->name, 'عناية')): ?>
                    <!-- Barber - Scissors -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0 0L12 12m0 0h7.5"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'مهنية') || str_contains($cat->name, 'عقارية')): ?>
                    <!-- Professional - Building -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'سياحة') || str_contains($cat->name, 'ترفيه')): ?>
                    <!-- Tourism - Map/Location -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'مالية') || str_contains($cat->name, 'اجتماعية')): ?>
                    <!-- Financial - Dollar/Coins -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                <?php elseif(str_contains($cat->name, 'رياضية') || str_contains($cat->name, 'رياضة')): ?>
                    <!-- Sports - Trophy -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                <?php else: ?>
                    <!-- Default Icon - Grid -->
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                <?php endif; ?>
            </div>
            <h3 class="font-bold text-sm md:text-base text-gray-800 group-hover:text-brand-green transition-colors truncate"><?php echo e($cat->name); ?></h3>
            <p class="text-xs text-gray-400 mt-1"><?php echo e($cat->total_businesses_count); ?> <?php echo e(__('place')); ?></p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Featured Businesses -->
<div class="bg-gray-100 py-10 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 md:mb-10 gap-3">
            <div>
                <h2 class="text-xl md:text-3xl font-bold text-gray-800"><?php echo e(__('Featured Places')); ?></h2>
                <p class="text-gray-500 mt-1 md:mt-2 text-sm md:text-base"><?php echo e(__('A selection of the best shops and services in Damascus')); ?></p>
            </div>
            <a href="<?php echo e(route('business.featured')); ?>" class="text-brand-green hover:underline font-medium text-sm md:text-base"><?php echo e(__('View More')); ?> ←</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
            <?php $__currentLoopData = $featuredBusinesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl md:rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-gray-100 group active:scale-[0.98] transition-transform">
                <div class="h-40 md:h-48 bg-gray-200 relative">
                    <?php if($business->logo): ?>
                        <img src="<?php echo e(asset('storage/' . $business->logo)); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php elseif($business->images && count($business->images) > 0): ?>
                        <img src="<?php echo e(asset('storage/' . $business->images[0])); ?>" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="<?php echo e($business->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php endif; ?>
                    <div class="absolute top-3 right-3 md:top-4 md:right-4 bg-orange-500 text-white text-xs font-bold px-2 py-1 md:px-3 md:py-1 rounded-full shadow-lg">
                        <?php echo e(__('Featured')); ?>

                    </div>
                </div>
                <div class="p-4 md:p-6">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-brand-green px-2 py-1 bg-brand-green/5 rounded truncate max-w-[50%]"><?php echo e($business->category->name); ?></span>
                        <div class="flex items-center text-yellow-400">
                            <span class="text-xs font-bold text-gray-700 ml-1"><?php echo e(number_format($business->averageRating(), 1)); ?></span>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-1 md:mb-2 truncate"><?php echo e($business->name); ?></h3>
                    <div class="flex items-center text-gray-500 text-xs md:text-sm mb-3 md:mb-4">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate"><?php echo e($business->subArea?->name ?? __('Not specified')); ?></span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-50 pt-3 md:pt-4">
                        <div class="text-xs text-gray-400">
                            <span class="font-bold text-gray-600"><?php echo e($business->views_count); ?></span> <?php echo e(__('views')); ?>

                        </div>
                        <a href="<?php echo e(route('business.show', $business)); ?>" class="text-brand-green font-bold text-sm flex items-center group-hover:translate-x-[-4px] transition-transform">
                            <?php echo e(__('Details')); ?>

                            <svg class="w-4 h-4 mr-1 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/home.blade.php ENDPATH**/ ?>