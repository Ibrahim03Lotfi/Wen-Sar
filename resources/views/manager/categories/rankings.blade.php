@extends('layouts.manager')

@section('title', __('Category Rankings'))
@section('page-title', __('Rankings for :category', ['category' => $category->name]))

@section('content')
@php
$rankingsData = collect(range(1,10))->mapWithKeys(fn($r) => [$r => (string)($rankings[$r]->business_id ?? '')])->all();
$businessesData = $businesses->map(fn($b) => ['id' => (string)$b->id, 'name' => $b->name])->values()->all();
@endphp
<script>
    window.rankingsApp = function(rankingsData, businessesData) {
        return {
            rankings: rankingsData,
            businesses: businessesData,
            get usedIds() {
                return Object.values(this.rankings)
                    .filter(function(v) { return v !== ''; })
                    .map(function(v) { return String(v); });
            },
            availableBusinesses: function(rank) {
                var currentId = this.rankings[rank] ? String(this.rankings[rank]) : '';

                return this.businesses.filter(function(business) {
                    var businessId = String(business.id);
                    return businessId === currentId || !this.usedIds.includes(businessId);
                }, this);
            },
            selectedName: function(rank) {
                var id = this.rankings[rank];
                if (!id) return null;
                var b = this.businesses.find(function(b) { return b.id === id; });
                return b ? b.name : null;
            }
        };
    };
</script>
<div class="space-y-6" x-data="rankingsApp(@js($rankingsData), @js($businessesData))">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ __('Choose Top 10 Places') }}</h3>
                <p class="text-gray-500 mt-1">{{ __('Select the businesses that will appear first in this category. The rest will be shown randomly.') }}</p>
            </div>
            <a href="{{ route('manager.categories.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm">
                {{ __('Back to Categories') }}
            </a>
        </div>
    </div>

    @if($businesses->count() > 0)
    <form method="POST" action="{{ route('manager.categories.rankings.update', $category) }}" class="space-y-6">
        @csrf

        <!-- Rankings Grid -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-bold text-gray-700">{{ __('Ranked Places (1-10)') }}</h4>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @for($rank = 1; $rank <= 10; $rank++)
                @php
                    $borderClass = match($rank) {
                        1 => 'border-yellow-400 bg-yellow-50',
                        2 => 'border-gray-300 bg-gray-50',
                        3 => 'border-orange-400 bg-orange-50',
                        4, 5 => 'border-blue-300 bg-blue-50',
                        6, 7 => 'border-purple-300 bg-purple-50',
                        default => 'border-teal-300 bg-teal-50',
                    };
                    $badgeClass = match($rank) {
                        1 => 'bg-yellow-500 text-white',
                        2 => 'bg-gray-500 text-white',
                        3 => 'bg-orange-500 text-white',
                        4, 5 => 'bg-blue-500 text-white',
                        6, 7 => 'bg-purple-500 text-white',
                        default => 'bg-teal-500 text-white',
                    };
                    $label = match($rank) {
                        1 => __('Gold'),
                        2 => __('Silver'),
                        3 => __('Bronze'),
                        4, 5 => __('Blue'),
                        6, 7 => __('Purple'),
                        default => __('Teal'),
                    };
                @endphp
                <div class="border-2 rounded-xl p-4 {{ $borderClass }}">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="w-8 h-8 rounded-full {{ $badgeClass }} flex items-center justify-center text-sm font-bold">{{ $rank }}</span>
                        <span class="text-sm font-bold text-gray-700">{{ $label }}</span>
                    </div>
                    <select name="rankings[{{ $rank }}]" x-model="rankings[{{ $rank }}]" class="w-full border border-gray-200 rounded-lg py-2.5 px-3 text-sm focus:ring-brand-green focus:border-brand-green bg-white">
                        <option value="">{{ __('-- Select a place --') }}</option>
                        <template x-for="business in availableBusinesses({{ $rank }})" :key="business.id">
                            <option :value="business.id" x-text="business.name"></option>
                        </template>
                    </select>
                    <p x-show="selectedName({{ $rank }})" class="text-xs text-gray-500 mt-2" x-cloak>
                        {{ __('Currently selected') }}: <span class="font-bold" x-text="selectedName({{ $rank }})"></span>
                    </p>
                    @if(isset($rankings[$rank]) && $rankings[$rank]->expires_at)
                        @php
                            $isExpired = $rankings[$rank]->expires_at->isPast();
                            $daysLeft = max(0, now()->diffInDays($rankings[$rank]->expires_at, false));
                        @endphp
                        <p class="text-xs mt-2 {{ $isExpired ? 'text-red-600' : 'text-gray-500' }}">
                            {{ __('Ranking timer') }}:
                            @if($isExpired)
                                <span class="font-bold">{{ __('Expired') }}</span>
                            @else
                                <span class="font-bold">{{ __(':days days left', ['days' => $daysLeft]) }}</span>
                            @endif
                        </p>
                    @endif

                    @if(isset($rankings[$rank]))
                        <div class="mt-3 flex items-center gap-2">
                            <button
                                type="button"
                                onclick="if(confirm('{{ __('Are you sure?') }}')) document.getElementById('remove-ranking-{{ $rankings[$rank]->id }}').submit();"
                                class="bg-red-100 text-red-700 text-xs font-bold py-2 px-3 rounded-lg hover:bg-red-200 transition-all"
                            >
                                {{ __('Remove from rank') }}
                            </button>

                            @if($rankings[$rank]->expires_at && $rankings[$rank]->expires_at->isPast())
                                <button
                                    type="button"
                                    onclick="document.getElementById('extend-ranking-{{ $rankings[$rank]->id }}').submit();"
                                    class="bg-brand-green text-white text-xs font-bold py-2 px-3 rounded-lg hover:opacity-90 transition-all"
                                >
                                    {{ __('Keep 14 more days') }}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                @endfor
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-brand-green text-white font-bold py-3 px-8 rounded-xl hover:opacity-90 transition-all">
                {{ __('Save Rankings') }}
            </button>
            <a href="{{ route('manager.categories.index') }}" class="bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 transition-all">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>

    @foreach($rankings as $ranking)
        <form id="remove-ranking-{{ $ranking->id }}" method="POST" action="{{ route('manager.rankings.remove', $ranking) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <form id="extend-ranking-{{ $ranking->id }}" method="POST" action="{{ route('manager.rankings.extend', $ranking) }}" class="hidden">
            @csrf
        </form>
    @endforeach
    @else
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-600 mb-2">{{ __('No businesses found') }}</h3>
        <p class="text-gray-500">{{ __('There are no approved businesses in this category yet.') }}</p>
    </div>
    @endif
</div>
@endsection
