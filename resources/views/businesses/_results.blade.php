@forelse($businesses as $business)
@php
    $rankBorder = '';
    $rankBadge = '';
    $rankBadgeClass = '';
    if (isset($business->manager_rank) && $business->manager_rank <= 10) {
        $rankBadge = $business->manager_rank;
        $rankBadgeClass = match($business->manager_rank) {
            1 => 'bg-yellow-500 border-yellow-600 text-white',
            2 => 'bg-gray-400 border-gray-500 text-white',
            3 => 'bg-orange-500 border-orange-600 text-white',
            4, 5 => 'bg-blue-500 border-blue-600 text-white',
            6, 7 => 'bg-purple-500 border-purple-600 text-white',
            default => 'bg-teal-500 border-teal-600 text-white',
        };
        $rankBorder = match($business->manager_rank) {
            1 => 'border-yellow-400',
            2 => 'border-gray-300',
            3 => 'border-orange-400',
            4, 5 => 'border-blue-300',
            6, 7 => 'border-purple-300',
            default => 'border-teal-300',
        };
    }
@endphp
<div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all border-2 {{ $rankBorder ?: 'border-gray-200' }} overflow-hidden group relative">
    @if($rankBadge)
    <div class="absolute top-0 left-0 z-10 {{ $rankBadgeClass }} text-xs font-bold px-3 py-1 rounded-br-lg shadow-md border-b-2 border-r-2">
        #{{ $rankBadge }}
    </div>
    @endif
    <div class="flex flex-col md:flex-row">
        <!-- Image Section -->
        <div class="w-full md:w-72 h-56 md:h-auto flex-shrink-0 bg-gray-100 relative overflow-hidden border-b-2 md:border-b-0 md:border-l-2 {{ $rankBorder ?: 'border-gray-200' }}">
            @if($business->logo)
                <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @elseif($business->images && count($business->images) > 0)
                <img src="{{ asset('storage/' . $business->images[0]) }}" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1974&auto=format&fit=crop" alt="{{ $business->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @endif
            @if($business->is_featured)
                <div class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg border-2 border-orange-600">{{ __('Featured') }}</div>
            @endif
        </div>

        <!-- Content Section -->
        <div class="flex-1 p-5 flex flex-col justify-between">
            <div>
                <!-- Title & Rating -->
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-brand-green transition-colors">
                        {{ $business->name }}
                    </h3>
                    <div class="flex items-center bg-yellow-50 border border-yellow-200 px-2 py-1 rounded-lg">
                        <span class="text-sm font-bold text-yellow-700 ml-1">{{ number_format($business->averageRating(), 1) }}</span>
                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    </div>
                </div>

                <!-- Location & Category Tags -->
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <div class="flex items-center bg-brand-green/10 text-brand-green px-3 py-1.5 rounded-lg border border-brand-green/20 font-medium text-sm">
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                        {{ $business->subArea?->name ?? __('Not specified') }}
                    </div>
                    <div class="flex items-center bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 font-medium text-sm">
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        {{ $business->category->name }}
                    </div>
                </div>

                <!-- Description -->
                <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 border-l-4 border-brand-green pl-3">
                    {{ Str::limit($business->description, 150) }}
                </p>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row justify-between items-center mt-5 pt-4 border-t-2 border-gray-100 gap-3 sm:gap-0">
                <div class="flex items-center gap-4">
                    @if($business->opening_time || $business->closing_time)
                    <span class="text-xs text-brand-green font-bold flex items-center uppercase tracking-wide bg-brand-green/5 px-3 py-1.5 rounded-lg border border-brand-green/20">
                        <span class="w-2 h-2 bg-brand-green rounded-full ml-2 animate-pulse"></span>
                        {{ $business->opening_time ? substr($business->opening_time, 0, 5) : '--:--' }} - {{ $business->closing_time ? substr($business->closing_time, 0, 5) : '--:--' }}
                    </span>
                    @else
                    <span class="text-xs text-gray-500 font-bold flex items-center uppercase tracking-wide bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
                        {{ __('Working hours not specified') }}
                    </span>
                    @endif
                    <span class="text-xs text-gray-500 font-medium bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
                        {{ $business->views_count }} {{ __('views') }}
                    </span>
                </div>
                <a href="{{ route('business.show', $business->id) }}" class="text-brand-green font-bold text-sm bg-white border-2 border-brand-green px-6 py-2 rounded-lg hover:bg-brand-green hover:text-white hover:shadow-lg transition-all duration-300 text-center w-full sm:w-auto">
                    {{ __('View Details') }}
                </a>
            </div>
        </div>
    </div>
</div>
@empty
<!-- Empty State -->
<div class="text-center py-16 bg-white rounded-xl shadow-md border-2 border-dashed border-gray-300">
    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-gray-200">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-600 mb-2">{{ __('No results found') }}</h3>
    <p class="text-gray-500">{{ __('Try different search terms or another area') }}</p>
</div>
@endforelse
