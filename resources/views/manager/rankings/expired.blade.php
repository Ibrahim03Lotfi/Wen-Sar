@extends('layouts.manager')

@section('title', __('Expired Rankings'))
@section('page-title', __('Expired Rankings'))

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800">{{ __('Ranked places requiring action') }}</h3>
        <p class="text-gray-500 mt-1">{{ __('These places completed their 14-day ranking period. You can remove them or keep each one for another 14 days.') }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($expiredRankings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">{{ __('Category') }}</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">{{ __('Place') }}</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">{{ __('Rank') }}</th>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">{{ __('Expired on') }}</th>
                            <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiredRankings as $ranking)
                            <tr class="border-b border-gray-100 last:border-b-0">
                                <td class="py-3 px-4 text-sm text-gray-700">{{ $ranking->category?->name }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-800">{{ $ranking->business?->name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-700">#{{ $ranking->rank }}</td>
                                <td class="py-3 px-4 text-sm text-red-600">{{ optional($ranking->expires_at)->format('Y-m-d H:i') }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('manager.rankings.extend', $ranking) }}">
                                            @csrf
                                            <button type="submit" class="bg-brand-green text-white text-xs font-bold py-2 px-3 rounded-lg hover:opacity-90 transition-all">
                                                {{ __('Keep 14 more days') }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('manager.rankings.remove', $ranking) }}" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-700 text-xs font-bold py-2 px-3 rounded-lg hover:bg-red-200 transition-all">
                                                {{ __('Remove from rank') }}
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
                {{ $expiredRankings->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ __('No expired ranked places') }}</h3>
                <p class="text-gray-500">{{ __('All ranked places are still within their active 14-day period.') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
