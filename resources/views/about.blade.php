@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-brand-green overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 py-24 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
            من نحن؟
        </h1>
        <p class="text-xl text-brand-white/80 max-w-3xl mx-auto leading-relaxed">
            منصة "وين صار" هي دليلك الشامل للعثور على كل ما تحتاجه في سوريا
        </p>
    </div>
    <!-- Wave Decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
        </svg>
    </div>
</div>

<!-- Mission Section -->
<div class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-orange-400 rounded-2xl opacity-20 rotate-12"></div>
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-brand-green rounded-full opacity-10"></div>
                <div class="relative bg-white p-8 rounded-[2.5rem] shadow-xl border-2 border-gray-100">
                    <div class="w-16 h-16 bg-brand-green/10 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">مهمتنا</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        نسعى لبناء أكبر دليل إلكتروني شامل في سوريا، يربط بين الناس والمحال التجارية، المهن، والخدمات بكل سهولة ويسر.
                    </p>
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    نحن نؤمن بقوة <span class="text-brand-green">الاتصال</span>
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    في عالم يتجه نحو الرقمية، نريد أن نكون الجسر الذي يربط بين أصحاب الأعمال والزبائن، بين المهنيين ومن يحتاجون خدماتهم.
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl font-bold text-brand-green mb-2">+1000</div>
                        <div class="text-sm text-gray-500 font-bold">منشأة مسجلة</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="text-3xl font-bold text-orange-500 mb-2">+50K</div>
                        <div class="text-sm text-gray-500 font-bold">زيارة شهرياً</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Grid -->
<div class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">ما يميزنا</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">نقدم لك تجربة فريدة للبحث والتواصل</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="group bg-gray-50 rounded-[2rem] p-8 hover:bg-brand-green transition-all duration-500 border-2 border-transparent hover:border-brand-green/20">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-brand-green group-hover:text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">بحث ذكي</h3>
                <p class="text-gray-600 group-hover:text-white/80 transition-colors">ابحث بسهولة عن أي منشأة حسب المنطقة، التصنيف، أو الاسم</p>
            </div>

            <!-- Feature 2 -->
            <div class="group bg-gray-50 rounded-[2rem] p-8 hover:bg-brand-green transition-all duration-500 border-2 border-transparent hover:border-brand-green/20">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">تقييمات موثوقة</h3>
                <p class="text-gray-600 group-hover:text-white/80 transition-colors">اقرأ تجارب المستخدمين الحقيقية وشارك رأيك</p>
            </div>

            <!-- Feature 3 -->
            <div class="group bg-gray-50 rounded-[2rem] p-8 hover:bg-brand-green transition-all duration-500 border-2 border-transparent hover:border-brand-green/20">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors">تغطية شاملة</h3>
                <p class="text-gray-600 group-hover:text-white/80 transition-colors">جميع المحافظات والمناطق السورية في مكان واحد</p>
            </div>
        </div>
    </div>
</div>

<!-- Values Section -->
<div class="bg-brand-green py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-orange-400 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-white mb-4">قيمنا</h2>
            <p class="text-brand-white/70">المبادئ التي نؤمن بها ونعمل بها</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-white mb-2">الشفافية</h4>
                <p class="text-sm text-brand-white/70">نظام تقييمات واضح وموثوق</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-white mb-2">السرعة</h4>
                <p class="text-sm text-brand-white/70">بحث فوري ونتائج دقيقة</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-white mb-2">المجتمع</h4>
                <p class="text-sm text-brand-white/70">نساعد في دعم الاقتصاد المحلي</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center border border-white/20">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-white mb-2">الشغف</h4>
                <p class="text-sm text-brand-white/70">نحب ما نفعله ونؤمن به</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gray-50 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">هل أنت صاحب منشأة؟</h2>
        <p class="text-gray-600 text-lg mb-8">
            انضم إلى آلاف المنشآت المسجلة في "وين صار" واستفد من مميزات العضوية المجانية
        </p>
        <a href="{{ route('select-role') }}?action=register" class="inline-flex items-center gap-3 bg-brand-green text-white font-bold py-4 px-10 rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-brand-green/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            سجل منشأتك الآن
        </a>
    </div>
</div>
@endsection
