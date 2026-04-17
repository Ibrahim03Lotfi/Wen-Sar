@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <div class="bg-brand-green py-8 shadow-inner relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">إضافة منشأة جديدة</h1>
            <p class="text-brand-white/70 font-medium">أدخل بيانات منشأتك لتظهر لآلاف الزوار يومياً</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">حدث خطأ في الحفظ</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">حدث خطأ</h3>
                        <div class="mt-2 text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('owner.businesses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

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
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="مثال: مطعم زيتون، صالون الفخامة...">
                    </div>

                    <!-- English Name -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">اسم المنشأة بالإنجليزي</label>
                        <input type="text" name="english_name" dir="ltr" value="{{ old('english_name') }}" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="Example: Olive Restaurant, Luxury Salon...">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">الوصف <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="تحدث عن منشأتك والخدمات التي تقدمها...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Location -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                 x-data="{ 
                    governorateId: '{{ $governorates->first()?->id ?? '' }}',
                    districtId: '',
                    subAreas: [],
                    districts: {{ json_encode($governorates->first()?->districts ?? []) }},
                    categoryId: '',
                    subcategories: [],
                    allCategories: {{ json_encode($categories) }},
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
                 x-init="$watch('governorateId', value => updateDistricts()); $watch('districtId', value => updateSubAreas()); $watch('categoryId', value => updateSubcategories())">
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
                                <select name="governorate_id" x-model="governorateId" required 
                                        class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر المحافظة...</option>
                                    @foreach($governorates as $index => $governorate)
                                        <option value="{{ $governorate->id }}" {{ $index === 0 ? 'selected' : '' }}>{{ $governorate->name }}</option>
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
                                <select name="district_id" x-model="districtId" required
                                        class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر المنطقة...</option>
                                    <template x-for="district in districts" :key="district.id">
                                        <option :value="district.id" x-text="district.name"></option>
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
                            <label class="block text-sm font-bold text-gray-700 mb-2">الحي / المنطقة الفرعية <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="sub_area_id" required class="w-full border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white appearance-none cursor-pointer">
                                    <option value="">اختر الحي...</option>
                                    <template x-for="area in subAreas" :key="area.id">
                                        <option :value="area.id" x-text="area.name"></option>
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
                        <input type="text" name="address" value="{{ old('address') }}" required class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="الشارع، المبنى، الطابق، بالقرب من...">
                    </div>

                    <!-- Google Maps Link -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">رابط خرائط غوغل</label>
                        <input type="url" name="google_maps_link" dir="ltr" value="{{ old('google_maps_link') }}" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://maps.google.com/...">
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
                        if(old('phone') && str_starts_with(old('phone'), '09')) {
                            $phoneSuffix = substr(old('phone'), 2);
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
                            <input type="hidden" name="phone" id="fullPhone" value="{{ old('phone', '09') }}">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">مثال: <span class="font-bold text-gray-600">09</span><span class="text-gray-400">12345678</span></p>
                    </div>

                    <!-- Landline with 011 prefix -->
                    @php
                        $landlineSuffix = '';
                        if(old('landline')) {
                            $landlineSuffix = old('landline');
                        }
                    @endphp
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">الرقم الأرضي</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">011</span>
                            <input type="tel" name="landline_suffix" id="landlineInput" maxlength="7" value="{{ $landlineSuffix }}"
                                   class="w-full border-gray-300 rounded-lg py-3 pl-14 pr-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800 font-bold tracking-wider"
                                   placeholder="١٢٣٤٥٦٧"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 7);">
                        </div>
                        <p class="text-xs text-gray-400 mt-2">مثال: <span class="font-bold text-gray-600">011</span><span class="text-gray-400">1234567</span> (اختياري)</p>
                    </div>

                </div>
            </div>

            <!-- Section 4: Business Hours -->
            <x-business-hours :business="null" />

            <!-- Section 5: Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        الصور
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">صورة شعار المنشأة <span class="text-red-500">*</span></label>
                        <input type="file" name="logo" accept="image/*" id="logoInput" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-green file:text-white hover:file:opacity-90">
                        <div id="logoPreview" class="mt-4 relative hidden w-32 h-32">
                            <img src="" alt="Logo Preview" class="w-full h-full object-cover rounded-xl border-2 border-gray-200">
                            <button type="button" onclick="removeLogo()" class="absolute -top-2 -right-2 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm">×</button>
                        </div>
                    </div>

                    <!-- Additional Images -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">صور إضافية للمنشأة <span class="text-red-500">*</span></label>
                        <input type="file" name="images[]" accept="image/*" multiple id="imagesInput" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <p class="text-xs text-gray-400 mt-2">مطلوب صورة واحدة على الأقل - الحد الأقصى 3 صور، كل صورة بحد أقصى 2 ميغابايت</p>
                        <div id="imagePreviews" class="mt-4 grid grid-cols-4 gap-3"></div>
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
                        <input type="url" name="facebook" dir="ltr" value="{{ old('facebook') }}" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://facebook.com/...">
                    </div>
                    <!-- Instagram -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">إنستغرام</label>
                        <input type="url" name="instagram" dir="ltr" value="{{ old('instagram') }}" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://instagram.com/...">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-brand-green text-white font-bold py-4 rounded-xl hover:opacity-90 transition-all shadow-lg text-lg">
                    إضافة المنشأة الآن
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logo preview
        const logoInput = document.getElementById('logoInput');
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const preview = document.getElementById('logoPreview');
                    if (preview) {
                        const img = preview.querySelector('img');
                        if (img) img.src = URL.createObjectURL(file);
                        preview.classList.remove('hidden');
                    }
                }
            });
        }

        window.removeLogo = function() {
            const preview = document.getElementById('logoPreview');
            const input = document.getElementById('logoInput');
            if (preview) preview.classList.add('hidden');
            if (input) input.value = '';
        };

        // Images preview
        const imagesInput = document.getElementById('imagesInput');
        if (imagesInput) {
            imagesInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const container = document.getElementById('imagePreviews');
                if (container) {
                    container.innerHTML = '';
                    for (let file of files) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${URL.createObjectURL(file)}" class="w-full h-24 object-cover rounded-lg border-2 border-gray-200 group-hover:border-brand-green transition">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm">×</button>
                        `;
                        container.appendChild(div);
                    }
                }
            });
        }

        // Phone preview update
        const phoneInput = document.getElementById('phoneInput');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                const val = e.target.value;
                const preview = document.getElementById('phonePreview');
                if (preview) preview.textContent = val;
            });
        }
    });
</script>
@endsection
