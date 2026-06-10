@extends('layouts.manager')

@section('title', __('Businesses'))
@section('page-title', __('All Approved Businesses'))

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('manager.businesses.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search businesses...') }}"
                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors">
        </div>
        <div class="w-48">
            <select name="district_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors bg-white">
                <option value="">{{ __('All Districts') }}</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-48">
            <select name="category_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors bg-white">
                <option value="">{{ __('All Categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- Filter: featured only --}}
        <div class="w-48">
            <select name="featured" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:border-brand-green focus:outline-none transition-colors bg-white">
                <option value="">{{ __('All') }}</option>
                <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>{{ __('Featured Only') }}</option>
                <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>{{ __('Not Featured') }}</option>
            </select>
        </div>
        <button type="submit" class="bg-brand-green text-white font-bold py-2.5 px-6 rounded-xl hover:opacity-90 transition-all">
            {{ __('Filter') }}
        </button>
        <a href="{{ route('manager.businesses.index') }}" class="bg-gray-100 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-200 transition-all">
            {{ __('Reset') }}
        </a>
    </form>
</div>

<!-- Featured Count Badge -->
@php $featuredCount = $businesses->where('is_featured', true)->count(); @endphp
@if(request('featured') !== '1')
<div class="mb-4 flex items-center gap-2">
    <a href="{{ route('manager.businesses.index', array_merge(request()->all(), ['featured' => '1'])) }}"
       class="inline-flex items-center gap-2 bg-orange-50 border border-orange-200 text-orange-700 text-sm font-bold px-4 py-2 rounded-full hover:bg-orange-100 transition-all">
        <svg class="w-4 h-4 fill-orange-400" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
        {{ \App\Models\Business::where('is_featured', true)->count() }}{{ __('Featured Places') }}
    </a>
    <a href="{{ route('business.featured') }}" target="_blank"
       class="inline-flex items-center gap-1 text-xs text-brand-green hover:underline font-medium">
        {{ __('View on site') }}
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
    </a>
</div>
@endif

<!-- Businesses List -->
<div class="bg-white rounded-xl shadow-sm">
    @if($businesses->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600">{{ __('Business') }}</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600">{{ __('Owner') }}</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-gray-600">{{ __('Location') }}</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600">
                            <span class="flex items-center justify-center gap-1">
                                <svg class="w-4 h-4 text-orange-400 fill-orange-400" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ __('Featured') }}
                            </span>
                        </th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-gray-600">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($businesses as $business)
                    <tr class="hover:bg-gray-50 transition-colors {{ $business->is_featured ? 'bg-orange-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                                    @if($business->logo)
                                        <img src="{{ asset('storage/' . $business->logo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 flex items-center gap-2">
                                        {{ $business->name }}
                                        @if($business->is_featured)
                                            <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                                <svg class="w-2.5 h-2.5 fill-orange-500" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                {{ __('Featured') }} #{{ $business->featured_rank }}
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $business->category->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">{{ $business->owner?->name ?? __('Unknown') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $business->district?->name ?? '-' }}</span>
                            @if($business->subArea)
                                <p class="text-xs text-gray-400">{{ $business->subArea->name }}</p>
                            @endif
                        </td>

                        {{-- Featured Toggle Column --}}
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('manager.businesses.toggleFeatured', $business) }}">
                                @csrf
                                @if($business->is_featured)
                                    <button type="submit"
                                            title="{{ __('Remove from featured') }}"
                                            onclick="return confirm('{{ __('Remove this business from featured places?') }}')"
                                            class="inline-flex items-center gap-1.5 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full hover:bg-orange-600 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5 fill-white" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('Remove') }}
                                    </button>
                                @else
                                    <button type="submit"
                                            title="{{ __('Add to featured') }}"
                                            class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1.5 rounded-full hover:bg-orange-100 hover:text-orange-600 transition-all border border-gray-200 hover:border-orange-300">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        {{ __('Feature') }}
                                    </button>
                                @endif
                            </form>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('manager.businesses.show', $business) }}" class="bg-brand-green/10 text-brand-green p-2 rounded-lg hover:bg-brand-green hover:text-white transition-all" title="{{ __('View') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('manager.businesses.edit', $business) }}" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-500 hover:text-white transition-all" title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('manager.businesses.destroy', $business) }}" onsubmit="return confirm('{{ __('Are you sure you want to delete this business?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all" title="{{ __('Delete') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $businesses->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2">{{ __('No Businesses Found') }}</h3>
            <p class="text-gray-500">{{ __('Try adjusting your filters') }}</p>
        </div>
    @endif
</div>
@endsection