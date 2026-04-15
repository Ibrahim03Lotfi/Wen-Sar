@extends('layouts.manager')

@section('title', __('Add Business for') . ' ' . $user->name)
@section('page-title', __('Add Business for') . ' ' . $user->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('manager.owners.show', $user) }}" class="text-gray-500 hover:text-brand-green flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('Back to Owner') }}
    </a>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="mr-3">
                <h3 class="text-sm font-medium text-red-800">{{ __('Error') }}</h3>
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
                <h3 class="text-sm font-medium text-red-800">{{ __('Error') }}</h3>
                <div class="mt-2 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Auto-approve Notice -->
<div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="bg-green-100 p-2 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-green-800">{{ __('Auto-Approved Business') }}</h3>
            <p class="text-green-600 text-sm">{{ __('This business will be automatically approved and added to the owner account.') }}</p>
        </div>
    </div>
</div>

<form action="{{ route('manager.businesses.store-for-owner', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ governorateId: '{{ old('district_id') ? \App\Models\District::find(old('district_id'))?->governorate_id : '' }}', districtId: '{{ old('district_id') }}', subAreas: [], districts: [], async updateDistricts() { if(!this.governorateId) { this.districts = []; return; } const response = await fetch(`/api/governorates/${this.governorateId}/districts`); this.districts = await response.json(); }, async updateSubAreas() { if(!this.districtId) { this.subAreas = []; return; } const response = await fetch(`/api/districts/${this.districtId}/sub-areas`); this.subAreas = await response.json(); } }" x-init="$watch('governorateId', value => updateDistricts())">
    @csrf

    <!-- Section 1: Basic Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('Basic Information') }}
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <!-- Name -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Business Name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="{{ __('Example: Olive Restaurant') }}">
            </div>

            <!-- English Name -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('English Name') }}</label>
                <input type="text" name="english_name" dir="ltr" value="{{ old('english_name') }}" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="Example: Olive Restaurant...">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Description') }}</label>
                <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="{{ __('Describe the business and services...') }}">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Section 2: Location -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ __('Location') }}
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Governorate -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Governorate') }} <span class="text-red-500">*</span></label>
                    <select name="governorate_id" x-model="governorateId" required class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white">
                        <option value="">{{ __('Select Governorate...') }}</option>
                        @foreach(App\Models\Governorate::all() as $governorate)
                            <option value="{{ $governorate->id }}" {{ old('district_id') ? (\App\Models\District::find(old('district_id'))?->governorate_id == $governorate->id ? 'selected' : '') : '' }}>{{ $governorate->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- District -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('District') }} <span class="text-red-500">*</span></label>
                    <select name="district_id" x-model="districtId" @change="updateSubAreas()" required class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white">
                        <option value="">{{ __('Select District...') }}</option>
                        <template x-for="district in districts" :key="district.id">
                            <option :value="district.id" x-text="district.name" :selected="district.id == {{ old('district_id') }}"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Sub-area -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Sub-area') }}</label>
                    <select name="sub_area_id" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white">
                        <option value="">{{ __('Select Sub-area...') }}</option>
                        <template x-for="area in subAreas" :key="area.id">
                            <option :value="area.id" x-text="area.name" :selected="area.id == {{ old('sub_area_id') }}"></option>
                        </template>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Category') }} <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white">
                        <option value="">{{ __('Select Category...') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Detailed Address -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Detailed Address') }}</label>
                <input type="text" name="address" value="{{ old('address') }}" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="{{ __('Street, Building, Floor, Near...') }}">
            </div>
        </div>
    </div>

    <!-- Section 3: Contact -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                {{ __('Contact Information') }}
            </h2>
        </div>
        <div class="p-6">
            <!-- Phone -->
            @php
                $phoneSuffix = '';
                if(old('phone') && str_starts_with(old('phone'), '09')) {
                    $phoneSuffix = substr(old('phone'), 2);
                }
            @endphp
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone Number') }} <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-green font-extrabold text-lg select-none">09</span>
                    <input type="tel" name="phone_suffix" id="phoneInput" required maxlength="8" value="{{ $phoneSuffix }}"
                           class="w-full border-2 border-gray-200 rounded-lg py-3 pl-12 pr-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800 font-bold tracking-wider"
                           placeholder="12345678"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8); document.getElementById('fullPhone').value = '09' + this.value;">
                    <input type="hidden" name="phone" id="fullPhone" value="{{ old('phone', '09') }}">
                </div>
            </div>

            <!-- Opening Hours -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Opening Time') }}</label>
                    <input type="time" name="opening_time" value="{{ old('opening_time') }}" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Closing Time') }}</label>
                    <input type="time" name="closing_time" value="{{ old('closing_time') }}" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800">
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Images -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ __('Images') }}
            </h2>
        </div>
        <div class="p-6 space-y-6">
            <!-- Logo -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Business Logo') }}</label>
                <input type="file" name="logo" accept="image/*" id="logoInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-green file:text-white hover:file:opacity-90">
                <div id="logoPreview" class="mt-4 relative hidden w-32 h-32">
                    <img src="" alt="Logo Preview" class="w-full h-full object-cover rounded-xl border-2 border-gray-200">
                    <button type="button" onclick="removeLogo()" class="absolute -top-2 -right-2 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center hover:bg-red-600 transition text-sm">×</button>
                </div>
            </div>

            <!-- Additional Images -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Additional Images') }}</label>
                <input type="file" name="images[]" accept="image/*" multiple id="imagesInput" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-400 mt-2">{{ __('You can select multiple images (Ctrl for multi-select)') }}</p>
                <div id="imagePreviews" class="mt-4 grid grid-cols-4 gap-3"></div>
            </div>
        </div>
    </div>

    <!-- Section 5: Social Media -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                {{ __('Social Media') }}
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Facebook -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Facebook') }}</label>
                <input type="url" name="facebook" dir="ltr" value="{{ old('facebook') }}" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://facebook.com/...">
            </div>
            <!-- Instagram -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Instagram') }}</label>
                <input type="url" name="instagram" dir="ltr" value="{{ old('instagram') }}" class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white text-gray-800" placeholder="https://instagram.com/...">
            </div>
        </div>
    </div>

    <!-- Section 6: Contract Duration -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ __('Contract Duration') }} <span class="text-red-500">*</span>
            </h2>
        </div>
        <div class="p-6">
            <select name="contract_duration" required class="w-full border-2 border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-brand-green focus:border-brand-green bg-white">
                <option value="14" {{ old('contract_duration') == '14' ? 'selected' : '' }}>14 {{ __('days') }}</option>
                <option value="30" {{ old('contract_duration') == '30' ? 'selected' : '' }}>30 {{ __('days') }}</option>
                <option value="90" {{ old('contract_duration') == '90' ? 'selected' : (old('contract_duration') ? '' : 'selected') }}>90 {{ __('days') }}</option>
                <option value="180" {{ old('contract_duration') == '180' ? 'selected' : '' }}>180 {{ __('days') }}</option>
                <option value="365" {{ old('contract_duration') == '365' ? 'selected' : '' }}>1 {{ __('year') }}</option>
            </select>
            <p class="text-sm text-gray-500 mt-2">{{ __('Select how long the business contract will be valid.') }}</p>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit" class="w-full bg-brand-green text-white font-bold py-4 rounded-xl hover:opacity-90 transition-all shadow-lg text-lg">
            {{ __('Add Business') }}
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logo preview
        const logoInput = document.getElementById('logoInput');
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const preview = document.getElementById('logoPreview');
                    const img = preview.querySelector('img');
                    img.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                }
            });
        }

        window.removeLogo = function() {
            const preview = document.getElementById('logoPreview');
            const input = document.getElementById('logoInput');
            if (preview && input) {
                preview.classList.add('hidden');
                input.value = '';
            }
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
    });
</script>
@endsection
