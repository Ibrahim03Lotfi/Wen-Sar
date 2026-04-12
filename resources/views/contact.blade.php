@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-brand-green overflow-hidden">
    <div class="absolute inset-0">
        <!-- Decorative circles -->
        <div class="absolute top-20 left-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-orange-400/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white/80 text-sm font-bold mb-6 border border-white/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                نحن هنا لمساعدتك
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                تواصل معنا
            </h1>
            <p class="text-xl text-brand-white/80 max-w-2xl mx-auto leading-relaxed">
                لديك سؤال أو اقتراح أو تحتاج لمساعدة؟ فريقنا جاهز لخدمتك
            </p>
        </div>
    </div>
    
    <!-- Wave Decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
        </svg>
    </div>
</div>

<!-- Contact Section -->
<div class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Info Cards -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Email Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 bg-brand-green/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-brand-green group-hover:scale-110 transition-all">
                        <svg class="w-7 h-7 text-brand-green group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">البريد الإلكتروني</h3>
                    <p class="text-gray-500 text-sm">نرد على جميع الرسائل خلال 24 ساعة</p>
                    <a href="mailto:info@wensar.sy" class="text-brand-green font-bold mt-2 inline-block hover:underline">info@wensar.sy</a>
                </div>

                <!-- Phone Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-500 group-hover:scale-110 transition-all">
                        <svg class="w-7 h-7 text-orange-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">الهاتف</h3>
                    <p class="text-gray-500 text-sm">متاح للاتصال من الأحد إلى الخميس</p>
                    <span class="text-brand-green font-bold mt-2 inline-block" dir="ltr">+963 11 123 4567</span>
                </div>

                <!-- Location Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:scale-110 transition-all">
                        <svg class="w-7 h-7 text-blue-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">الموقع</h3>
                    <p class="text-gray-500 text-sm">دمشق، سوريا</p>
                    <span class="text-gray-600 font-medium mt-2 inline-block">المزة - شارع العروبة</span>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 p-8 md:p-12">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-brand-green rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">أرسل لنا رسالة</h2>
                            <p class="text-gray-500">نحب أن نسمع منك</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-2xl mb-6 font-bold">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">الاسم الكامل</label>
                                <input type="text" name="name" required 
                                       class="w-full border-2 border-gray-100 rounded-xl py-4 px-5 focus:ring-2 focus:ring-brand-green focus:border-brand-green transition-all bg-gray-50"
                                       placeholder="أدخل اسمك">
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" required 
                                       class="w-full border-2 border-gray-100 rounded-xl py-4 px-5 focus:ring-2 focus:ring-brand-green focus:border-brand-green transition-all bg-gray-50"
                                       placeholder="example@email.com">
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">الموضوع</label>
                            <select name="subject" required 
                                    class="w-full border-2 border-gray-100 rounded-xl py-4 px-5 focus:ring-2 focus:ring-brand-green focus:border-brand-green transition-all bg-gray-50">
                                <option value="">اختر موضوع الرسالة...</option>
                                <option value="general">استفسار عام</option>
                                <option value="business">تسجيل منشأة</option>
                                <option value="support">دعم فني</option>
                                <option value="suggestion">اقتراح</option>
                                <option value="complaint">شكوى</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">الرسالة</label>
                            <textarea name="message" required rows="5"
                                      class="w-full border-2 border-gray-100 rounded-xl py-4 px-5 focus:ring-2 focus:ring-brand-green focus:border-brand-green transition-all bg-gray-50 resize-none"
                                      placeholder="اكتب رسالتك هنا..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-brand-green text-white font-bold py-4 rounded-2xl hover:opacity-90 transition-all shadow-lg shadow-brand-green/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">الأسئلة الشائعة</h2>
            <p class="text-gray-500">إجابات على أكثر الأسئلة التي تصلنا</p>
        </div>

        <div class="space-y-4" x-data="{ open: null }">
            <!-- FAQ Item 1 -->
            <div class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden">
                <button @click="open === 1 ? open = null : open = 1" 
                        class="w-full flex items-center justify-between p-6 text-right hover:bg-gray-100 transition-colors">
                    <span class="font-bold text-gray-900">كيف يمكنني تسجيل منشأتي؟</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 1" x-collapse class="px-6 pb-6">
                    <p class="text-gray-600 leading-relaxed">
                        يمكنك تسجيل منشأتك بسهولة عبر النقر على زر "إنشاء حساب" واختيار "صاحب منشأة"، ثم ملء بيانات المنشأة المطلوبة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden">
                <button @click="open === 2 ? open = null : open = 2" 
                        class="w-full flex items-center justify-between p-6 text-right hover:bg-gray-100 transition-colors">
                    <span class="font-bold text-gray-900">هل التسجيل في المنصة مجاني؟</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 2" x-collapse class="px-6 pb-6">
                    <p class="text-gray-600 leading-relaxed">
                        نعم، التسجيل الأساسي مجاني بالكامل. هناك خطط مدفوعة إذا كنت ترغب في مميزات إضافية مثل الظهور في النتائج المميزة.
                    </p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden">
                <button @click="open === 3 ? open = null : open = 3" 
                        class="w-full flex items-center justify-between p-6 text-right hover:bg-gray-100 transition-colors">
                    <span class="font-bold text-gray-900">كيف يمكنني التواصل مع صاحب منشأة؟</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === 3" x-collapse class="px-6 pb-6">
                    <p class="text-gray-600 leading-relaxed">
                        عند دخولك لصفحة أي منشأة، ستجد جميع وسائل التواصل المتاحة (رقم الهاتف، مواقع التواصل الاجتماعي) في قسم "معلومات التواصل".
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Social Links -->
<div class="bg-gray-50 py-12 border-t border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-500 mb-6">تابعنا على وسائل التواصل الاجتماعي</p>
        <div class="flex justify-center gap-4">
            <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all group">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978 1.602 0 2.458.21 2.858.26V8.14h-1.654c-1.617 0-1.947.595-1.947 2.12v1.517h3.608l-.507 3.667h-3.1v7.98h-4.32z"/>
                </svg>
            </a>
            <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-red-500 hover:to-purple-600 hover:text-white transition-all group">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.282.15 4.773 1.674 4.924 4.924.058 1.266.069 1.646.069 4.85s-.011 3.584-.069 4.85c-.149 3.248-1.639 4.773-4.924 4.923-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-3.27-.15-4.77-1.673-4.924-4.923-.058-1.266-.069-1.646-.069-4.85s.011-3.584.069-4.85c.153-3.278 1.674-4.773 4.924-4.924 1.266-.058 1.646-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.947s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.059-1.689-.073-4.948-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </a>
            <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center hover:bg-sky-500 hover:text-white transition-all group">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
