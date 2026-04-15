@extends('layouts.app')

@section('content')
<div class="bg-brand-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-10 sm:px-6 lg:px-8">
        <nav class="flex text-xs font-bold text-gray-400 mb-4 md:mb-8 gap-2 uppercase tracking-widest flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">الرئيسية</a>
            <span class="opacity-30">/</span>
            <a href="#" class="hover:text-brand-green transition-colors">{{ $business->category->name }}</a>
            <span class="opacity-30">/</span>
            <span class="text-brand-green truncate">{{ $business->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row justify-between items-start gap-4 md:gap-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-8 w-full">
                <div class="w-20 h-20 md:w-36 md:h-36 bg-white rounded-2xl md:rounded-3xl overflow-hidden border-2 md:border-4 border-white shadow-xl relative group flex-shrink-0">
                    @if($business->logo)
                        <img src="{{ asset('storage/' . $business->logo) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($business->name) }}&background=06402b&color=faf9f6&size=512" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-brand-green/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2 md:mb-3">
                         <span class="bg-brand-green text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-tighter">موثق</span>
                         <span class="text-xs font-bold text-gray-400">{{ $business->category->name }}</span>
                    </div>
                    <h1 class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2 md:mb-4 tracking-tight break-words">{{ $business->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3 md:gap-6 text-sm font-bold">
                        <div class="flex items-center gap-1.5 text-yellow-500">
                            <svg class="w-4 h-4 md:w-5 md:h-5 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            <span class="text-gray-900">{{ number_format($business->averageRating(), 1) }}</span>
                            <span class="text-gray-400 font-medium text-xs">({{ $business->reviews->count() }})</span>
                        </div>
                        <div class="hidden sm:block text-gray-300">|</div>
                        <div class="flex items-center gap-1.5 text-brand-green">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="text-gray-900 text-xs md:text-sm">{{ $business->subArea?->name ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 md:gap-4 w-full md:w-auto mt-4 md:mt-0"
                 x-data="{ showLoginAlert: false, isAuth: {{ auth()->check() ? 'true' : 'false' }} }">
                <a href="tel:{{ $business->phone }}" class="flex-grow md:flex-none flex items-center justify-center gap-2 md:gap-3 bg-brand-green text-white font-bold py-3 md:py-4 px-6 md:px-10 rounded-xl md:rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-brand-green/20 text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span class="hidden sm:inline">اتصل الآن</span>
                    <span class="sm:hidden">اتصل</span>
                </a>
                @auth
                    @php($isFav = auth()->user()->favorites->contains($business->id))
                    <form action="{{ $isFav ? route('favorites.destroy', $business) : route('favorites.store', $business) }}" method="POST" class="inline">
                        @csrf
                        @if($isFav)
                            @method('DELETE')
                        @endif
                        <button type="submit"
                                class="p-3 md:p-4 border rounded-xl md:rounded-2xl {{ $isFav ? 'bg-red-50 text-red-500 border-red-100' : 'bg-white text-gray-400 border-gray-100' }} hover:bg-red-50 transition-all group active:scale-90">
                            <svg class="w-5 h-5 md:w-6 md:h-6 {{ $isFav ? 'fill-current' : '' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </form>
                @else
                    <button x-on:click.prevent="showLoginAlert = true; console.log('clicked!')"
                            class="p-3 md:p-4 border rounded-xl md:rounded-2xl bg-white text-gray-400 border-gray-100 hover:bg-red-50 transition-all group active:scale-90">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </button>
                @endauth

                <!-- Login Required Modal -->
                <div x-show="showLoginAlert" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                     style="display: none;">
                    <div x-show="showLoginAlert"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-90"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-90"
                         @click.away="showLoginAlert = false"
                         class="bg-white rounded-2xl md:rounded-3xl p-6 md:p-10 max-w-md w-full text-center shadow-2xl">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 md:w-12 md:h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3">تسجيل الدخول مطلوب</h3>
                        <p class="text-gray-500 text-sm md:text-base mb-8">يجب تسجيل الدخول أو إنشاء حساب جديد لإضافة الأماكن إلى قائمة المفضلة</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('login') }}" class="flex-1 bg-brand-green text-white font-bold py-3 px-6 rounded-xl hover:opacity-90 transition-all">تسجيل الدخول</a>
                            <a href="{{ route('register') }}" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-xl hover:bg-gray-200 transition-all">إنشاء حساب</a>
                        </div>
                        <button @click="showLoginAlert = false" class="mt-4 text-gray-400 text-sm hover:text-gray-600 transition-colors">إغلاق</button>
                    </div>
                </div>
            </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 md:py-16 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6 md:space-y-12 order-2 lg:order-1">
            <!-- Gallery -->
            <div class="main-gallery relative rounded-2xl md:rounded-[2.5rem] h-64 md:h-[30rem] overflow-hidden shadow-2xl group border-4 md:border-8 border-white">
                 @if($business->images && count($business->images) > 0)
                     <img src="{{ asset('storage/' . $business->images[0]) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                 @elseif($business->logo)
                     <img src="{{ asset('storage/' . $business->logo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                 @else
                     <img src="https://images.unsplash.com/photo-1549136365-5c1a1795f57a?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                 @endif
                 <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                 <div class="absolute bottom-8 right-8 flex gap-3">
                     <button class="bg-white/20 backdrop-blur-xl border border-white/30 text-white px-6 py-3 rounded-2xl text-sm font-bold hover:bg-white/40 transition-all">
                         تصفح الصور ({{ $business->images ? count($business->images) : 0 }})
                     </button>
                 </div>
            </div>

            <!-- Image Thumbnails -->
            @if($business->images && count($business->images) > 1)
            <div class="grid grid-cols-4 gap-2 md:gap-4">
                @foreach($business->images as $index => $image)
                <div class="relative rounded-lg md:rounded-xl overflow-hidden cursor-pointer group" onclick="document.querySelector('.main-gallery img').src = this.querySelector('img').src">
                    <img src="{{ asset('storage/' . $image) }}" alt="Image {{ $index + 1 }}" class="w-full h-16 md:h-24 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-brand-green/0 group-hover:bg-brand-green/20 transition-colors"></div>
                </div>
                @endforeach
            </div>
            @endif

            <section class="bg-white p-5 md:p-10 rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-50">
                <h2 class="text-xl md:text-2xl font-bold mb-4 md:mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 md:h-8 bg-brand-green rounded-full"></span>
                    عن المنشأة
                </h2>
                <div class="text-gray-600 leading-relaxed text-sm md:text-lg font-medium">
                    {{ $business->description }}
                </div>
            </section>

            <!-- Reviews Section -->
            <section class="bg-white p-5 md:p-10 rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-6 md:mb-10">
                    <h2 class="text-xl md:text-2xl font-bold flex items-center gap-3">
                        <span class="w-1.5 h-6 md:h-8 bg-brand-green rounded-full"></span>
                        التقييمات والآراء
                    </h2>
                </div>

                @if(session('success'))
                    <div class="bg-green-50 text-green-700 p-4 rounded-2xl mb-8 font-bold text-center border border-green-100 italic">
                        {{ session('success') }}
                    </div>
                @endif

                @auth
                <div x-data="{ rating: 0 }" class="bg-brand-white/50 p-5 md:p-8 rounded-2xl md:rounded-3xl border border-dashed border-gray-200 mb-6 md:mb-10">
                    <h3 class="font-bold text-gray-800 mb-4">أضف تجربتك للمكان</h3>
                    <form action="{{ route('reviews.store', $business->id) }}" method="POST">
                        @csrf
                        <div class="flex gap-2 mb-4">
                            <template x-for="i in 5">
                                <button type="button" @click="rating = i" class="focus:outline-none transition-transform active:scale-90">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 cursor-pointer" :class="i <= rating ? 'text-yellow-400 fill-current' : 'text-gray-300'" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                </button>
                            </template>
                            <input type="hidden" name="rating" :value="rating" required>
                        </div>
                        <textarea name="comment" required rows="3" class="w-full border-gray-100 rounded-2xl py-3 md:py-4 px-4 md:px-6 focus:ring-2 focus:ring-brand-green bg-white text-gray-800 text-sm md:text-base" placeholder="ما رأيك بالخدمة والأسعار؟"></textarea>
                        <button type="submit" class="mt-4 bg-brand-green text-white font-bold py-2.5 md:py-3 px-6 md:px-8 rounded-xl hover:opacity-90 transition-all text-sm md:text-base">نشر التقييم</button>
                    </form>
                </div>
                @else
                <div class="text-center py-5 md:py-6 border rounded-2xl md:rounded-3xl mb-6 md:mb-10 bg-gray-50">
                    <p class="text-gray-500 font-bold mb-3 md:mb-4 text-sm md:text-base">يجب عليك تسجيل الدخول للمشاركة برأيك</p>
                    <a href="{{ route('login') }}" class="text-brand-green font-bold underline text-sm md:text-base">تسجيل الدخول</a>
                </div>
                @endauth
                
                <div class="space-y-8">
                    @forelse($business->reviews as $review)
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center font-bold text-brand-green shadow-sm border border-gray-100">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                <div class="flex items-center text-yellow-400 text-[10px] gap-0.5">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current {{ $i <= $review->rating ? '' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                    <span class="text-gray-400 font-bold mr-2">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 font-medium leading-relaxed">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <p class="text-gray-400 font-bold italic">لا يوجد تقييمات بعد. كن أول من يقيم!</p>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6 md:space-y-8 order-1 lg:order-2">
            <div class="bg-white p-5 md:p-8 rounded-2xl md:rounded-[2.5rem] shadow-sm border border-gray-50 space-y-6 md:space-y-8 lg:sticky lg:top-24">
                <h3 class="text-lg md:text-xl font-bold text-gray-900 border-b border-gray-50 pb-3 md:pb-4">معلومات التواصل</h3>
                
                <div class="space-y-4 md:space-y-6">
                    <div class="flex items-start gap-3 md:gap-5 p-3 md:p-4 rounded-xl md:rounded-2xl hover:bg-brand-white transition-colors">
                         <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-green text-white rounded-xl md:rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-brand-green/20 font-bold">
                             <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                         </div>
                         <div class="min-w-0">
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">رقم الهاتف</p>
                             <p class="font-black text-base md:text-lg text-gray-800 truncate" dir="ltr">{{ $business->phone }}</p>
                         </div>
                    </div>

                    @if($business->english_name)
                    <div class="flex items-start gap-3 md:gap-5 p-3 md:p-4 rounded-xl md:rounded-2xl hover:bg-brand-white transition-colors">
                         <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-50 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center shrink-0 font-bold">
                             <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                         </div>
                         <div class="min-w-0">
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">الاسم بالإنجليزي</p>
                             <p class="font-bold text-gray-800 text-base md:text-lg truncate" dir="ltr">{{ $business->english_name }}</p>
                         </div>
                    </div>
                    @endif

                    @if($business->opening_time || $business->closing_time)
                    <div class="flex items-start gap-3 md:gap-5 p-3 md:p-4 rounded-xl md:rounded-2xl hover:bg-brand-white transition-colors">
                         <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-50 text-purple-600 rounded-xl md:rounded-2xl flex items-center justify-center shrink-0 font-bold">
                             <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         </div>
                         <div class="min-w-0">
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">أوقات العمل</p>
                             <p class="font-bold text-gray-800 text-base md:text-lg truncate" dir="ltr">
                                {{ $business->opening_time ? substr($business->opening_time, 0, 5) : '--:--' }} - {{ $business->closing_time ? substr($business->closing_time, 0, 5) : '--:--' }}
                             </p>
                         </div>
                    </div>
                    @endif

                    @if($business->google_maps_link)
                    <div class="flex items-start gap-3 md:gap-5 p-3 md:p-4 rounded-xl md:rounded-2xl hover:bg-brand-white transition-colors">
                         <div class="w-10 h-10 md:w-12 md:h-12 bg-green-50 text-green-600 rounded-xl md:rounded-2xl flex items-center justify-center shrink-0 font-bold">
                             <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                         </div>
                         <div class="min-w-0 flex-1">
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">رابط خرائط غوغل</p>
                             <a href="{{ $business->google_maps_link }}" target="_blank" class="font-bold text-brand-green text-sm md:text-base hover:underline truncate block" dir="ltr">افتح في خرائط غوغل</a>
                         </div>
                    </div>
                    @endif

                    <div class="flex items-start gap-3 md:gap-5 p-3 md:p-4 rounded-xl md:rounded-2xl hover:bg-brand-white transition-colors">
                         <div class="w-10 h-10 md:w-12 md:h-12 bg-orange-50 text-orange-600 rounded-xl md:rounded-2xl flex items-center justify-center shrink-0 font-bold">
                             <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 0 1111.314 0z"></path></svg>
                         </div>
                         <div class="min-w-0">
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">الموقع</p>
                             <p class="font-bold text-gray-800 text-base md:text-lg truncate">{{ $business->subArea?->name ?? 'غير محدد' }}، دمشق</p>
                             @if($business->address)
                                 <p class="text-xs md:text-sm text-gray-500 mt-1 truncate">{{ $business->address }}</p>
                             @endif
                         </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50 space-y-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">التواصل الاجتماعي</p>
                        <div class="flex gap-4">
                             @if(isset($business->social_links['facebook']))
                             <a href="{{ $business->social_links['facebook'] }}" class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                 <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978 1.602 0 2.458.21 2.858.26V8.14h-1.654c-1.617 0-1.947.595-1.947 2.12v1.517h3.608l-.507 3.667h-3.1v7.98h-4.32z"/></svg>
                             </a>
                             @endif
                             @if(isset($business->social_links['instagram']))
                             <a href="{{ $business->social_links['instagram'] }}" class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-red-500 hover:to-purple-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.282.15 4.773 1.674 4.924 4.924.058 1.266.069 1.646.069 4.85s-.011 3.584-.069 4.85c-.149 3.248-1.639 4.773-4.924 4.923-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-3.27-.15-4.77-1.673-4.924-4.923-.058-1.266-.069-1.646-.069-4.85s.011-3.584.069-4.85c.153-3.278 1.674-4.773 4.924-4.924 1.266-.058 1.646-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.947s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                             </a>
                             @endif
                        </div>
                    </div>
                </div>

                @if($business->google_maps_link)
                <a href="{{ $business->google_maps_link }}" target="_blank" class="w-full bg-orange-500 text-white font-bold py-4 md:py-5 rounded-xl md:rounded-2xl hover:bg-orange-600 transition shadow-xl shadow-orange-200 flex items-center justify-center gap-2 text-sm md:text-base">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    الاتجاهات عبر الخرائط
                </a>
                @else
                <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-4 md:py-5 rounded-xl md:rounded-2xl cursor-not-allowed flex items-center justify-center gap-2 text-sm md:text-base">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    الاتجاهات عبر الخرائط (غير متوفر)
                </button>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
