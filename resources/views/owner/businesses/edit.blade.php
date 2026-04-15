@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <div class="bg-brand-green py-8 shadow-inner relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">تعديل بيانات المنشأة</h1>
            <p class="text-brand-white/70 font-medium">تحديث معلومات "{{ $business->name }}"</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <form action="{{ route('owner.businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        المعلومات الأساسية
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المنشأة <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ $business->name }}" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                    </div>

                    <!-- English Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المنشأة بالإنجليزي</label>
                        <input type="text" name="english_name" dir="ltr" value="{{ $business->english_name }}" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="Example: Olive Restaurant, Luxury Salon...">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الوصف <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">{{ $business->description }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Location -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                 x-data="{ 
                    governorateId: '{{ $business->district->governorate_id ?? '' }}',
                    districtId: '{{ $business->district_id }}',
                    subAreaId: '{{ $business->sub_area_id ?? '' }}',
                    subAreas: [],
                    districts: [],
                    categoryId: '{{ $business->category->parent_id ?? $business->category_id ?? '' }}',
                    subcategories: [],
                    allCategories: {{ json_encode($categories) }},
                    async init() {
                        if(this.governorateId) await this.updateDistricts();
                        if(this.districtId) await this.updateSubAreas();
                        this.updateSubcategories();
                    },
                    async updateDistricts() {
                        if(!this.governorateId) {
                            this.districts = [];
                            return;
                        }
                        const response = await fetch(`/api/governorates/${this.governorateId}/districts`);
                        this.districts = await response.json();
                    },
                    async updateSubAreas() {
                        if(!this.districtId) {
                            this.subAreas = [];
                            return;
                        }
                        const response = await fetch(`/api/districts/${this.districtId}/sub-areas`);
                        this.subAreas = await response.json();
                    },
                    updateSubcategories() {
                        const category = this.allCategories.find(c => c.id == this.categoryId);
                        this.subcategories = category ? category.subcategories : [];
                    }
                 }"
                 x-init="init(); $watch('categoryId', value => updateSubcategories())">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        الموقع
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Governorate -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">المحافظة <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="governorate_id" x-model="governorateId" @change="updateDistricts()" required 
                                        class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر المحافظة...</option>
                                    @foreach($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" {{ ($business->district->governorate_id ?? '') == $governorate->id ? 'selected' : '' }}>{{ $governorate->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- District -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">المنطقة <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="district_id" x-model="districtId" @change="updateSubAreas()" required 
                                        class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر المنطقة...</option>
                                    <template x-for="district in districts" :key="district.id">
                                        <option :value="district.id" x-text="district.name" :selected="district.id == districtId"></option>
                                    </template>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Sub-area -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">الحي / المنطقة الفرعية</label>
                            <div class="relative">
                                <select name="sub_area_id" x-model="subAreaId" class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر الحي...</option>
                                    <template x-for="area in subAreas" :key="area.id">
                                        <option :value="area.id" x-text="area.name" :selected="area.id == subAreaId"></option>
                                    </template>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">التصنيف الرئيسي <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category_id" x-model="categoryId" required class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر التصنيف...</option>
                                    <template x-for="category in allCategories" :key="category.id">
                                        <option :value="category.id" x-text="category.name"></option>
                                    </template>
                                </select>
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subcategory -->
                    <div x-show="subcategories.length > 0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">التصنيف الفرعي</label>
                        <div class="relative">
                            <select name="subcategory_id" class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                <option value="">اختر التصنيف الفرعي...</option>
                                <template x-for="subcategory in subcategories" :key="subcategory.id">
                                    <option :value="subcategory.id" x-text="subcategory.name"></option>
                                </template>
                            </select>
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Address -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">العنوان التفصيلي <span class="text-red-500">*</span></label>
                        <input type="text" name="address" value="{{ $business->address ?? '' }}" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="الشارع، المبنى، الطابق، بالقرب من...">
                    </div>

                    <!-- Google Maps Link -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">رابط خرائط غوغل</label>
                        <input type="url" name="google_maps_link" dir="ltr" value="{{ $business->google_maps_link ?? '' }}" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://maps.google.com/...">
                        <p class="text-xs text-gray-400 mt-2">انسخ رابط الموقع من خرائط غوغل والصقه هنا</p>
                    </div>
                </div>
            </div>

            <!-- Section 3: Contact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        معلومات التواصل
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Phone with 09 prefix -->
                    @php
                        $phoneSuffix = '';
                        if($business->phone && str_starts_with($business->phone, '09')) {
                            $phoneSuffix = substr($business->phone, 2);
                        }
                    @endphp
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">رقم الهاتف <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">09</span>
                            <input type="tel" name="phone_suffix" id="phoneInput" required maxlength="8" value="{{ $phoneSuffix }}"
                                   class="w-full border-gray-300 rounded-lg py-3 pl-12 pr-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800 font-bold tracking-wider"
                                   placeholder="١٢٣٤٥٦٧٨"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8); document.getElementById('fullPhone').value = '09' + this.value;">
                            <input type="hidden" name="phone" id="fullPhone" value="{{ $business->phone ?? '09' }}">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">مثال: <span class="font-bold text-gray-600">09</span><span class="text-gray-400">12345678</span></p>
                    </div>

                    <!-- Opening Hours -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">وقت الفتح <span class="text-red-500">*</span></label>
                            <input type="time" name="opening_time" value="{{ $business->opening_time }}" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">وقت الإغلاق <span class="text-red-500">*</span></label>
                            <input type="time" name="closing_time" value="{{ $business->closing_time }}" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        الصور
                    </h2>
                </div>
                <div class="p-6 space-y-6" data-storage-url="{{ asset('storage/') }}">
                    <input type="hidden" name="images_to_delete" id="imagesToDelete" value="">
                    
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">صورة شعار المنشأة</label>
                        <input type="file" name="logo" accept="image/*" id="logoInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-green file:text-white hover:file:opacity-90">
                        <div id="logoPreview" class="mt-4 relative hidden w-32 h-32">
                            <img src="" alt="Logo Preview" class="w-full h-full object-cover rounded-xl border-2 border-gray-200">
                            <button type="button" onclick="removeLogo()" class="absolute -top-2 -right-2 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm">×</button>
                        </div>
                        @if($business->logo)
                            <div id="existingLogo" class="mt-4 relative w-32 h-32">
                                <img src="{{ asset('storage/' . $business->logo) }}" alt="Existing Logo" class="w-full h-full object-cover rounded-xl border-2 border-gray-200">
                                <button type="button" onclick="deleteExistingLogo()" class="absolute -top-2 -right-2 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm">×</button>
                            </div>
                        @endif
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">صور إضافية للمنشأة</label>
                        <input type="file" name="images[]" accept="image/*" multiple id="imagesInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <p class="text-xs text-gray-400 mt-2">يمكنك اختيار عدة صور (اضغط Ctrl للاختيار المتعدد)</p>
                        <div id="imagePreviews" class="mt-4 grid grid-cols-4 gap-3">
                            @if($business->images && count($business->images) > 0)
                                @foreach($business->images as $index => $image)
                                <div class="relative group existing-image" data-path="{{ $image }}">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Existing Image" class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-brand-green transition">
                                    <button type="button" onclick="deleteExistingImage(this)" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm">×</button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Social Media -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        حسابات التواصل الاجتماعي
                    </h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Facebook -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">فيسبوك</label>
                        <input type="url" name="facebook" value="{{ $business->social_links['facebook'] ?? '' }}" dir="ltr" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://facebook.com/...">
                    </div>
                    <!-- Instagram -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">إنستغرام</label>
                        <input type="url" name="instagram" value="{{ $business->social_links['instagram'] ?? '' }}" dir="ltr" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://instagram.com/...">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 flex gap-4">
                <button type="submit" class="flex-1 bg-brand-green text-white font-bold py-4 rounded-xl hover:opacity-90 transition-all shadow-lg text-lg">
                    تحديث البيانات
                </button>
                <a href="{{ route('dashboard') }}" class="py-4 px-8 rounded-xl border border-gray-300 font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('logoPreview');
            const img = preview.querySelector('img');
            img.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            const existing = document.getElementById('existingLogo');
            if (existing) existing.remove();
        }
    });

    function removeLogo() {
        const preview = document.getElementById('logoPreview');
        const input = document.getElementById('logoInput');
        preview.classList.add('hidden');
        input.value = '';
    }

    function deleteExistingLogo() {
        document.getElementById('existingLogo').remove();
    }

    document.getElementById('imagesInput').addEventListener('change', function(e) {
        const files = e.target.files;
        const container = document.getElementById('imagePreviews');
        
        for (let file of files) {
            const div = document.createElement('div');
            div.className = 'relative group new-image';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-full h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-brand-green transition';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = '×';
            btn.className = 'absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm';
            btn.onclick = function() { div.remove(); };
            div.appendChild(img);
            div.appendChild(btn);
            container.appendChild(div);
        }
    });

    function deleteExistingImage(button) {
        const container = button.parentElement;
        const imagePath = container.dataset.path;
        const imagesToDelete = document.getElementById('imagesToDelete');
        const current = imagesToDelete.value ? JSON.parse(imagesToDelete.value) : [];
        current.push(imagePath);
        imagesToDelete.value = JSON.stringify(current);
        container.remove();
    }
</script>
@endsection
