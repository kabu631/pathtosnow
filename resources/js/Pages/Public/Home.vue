<template>
<AppLayout>
  <Head>
    <title>PathToSnow — Nepal Travel Packages, Trekking &amp; Gear</title>
    <meta name="description" content="Discover Nepal's best travel packages — trekking, adventure, wildlife safaris, valley visits and more. Book directly with local guides on PathToSnow."/>
    <meta property="og:title" content="PathToSnow — Nepal's #1 Travel Platform"/>
    <meta property="og:description" content="Nepal travel packages, gear shop and travel guides — curated by locals."/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="https://pathtosnow.com/images/logo.png"/>
    <meta property="og:url" content="https://pathtosnow.com/"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:image" content="https://pathtosnow.com/images/logo.png"/>
    <link rel="canonical" href="https://pathtosnow.com/"/>
  </Head>

  <!-- Hero -->
  <section class="relative overflow-hidden bg-slate-900" aria-label="Homepage hero">
    <div class="relative h-[600px] md:h-[700px]">
      <TransitionGroup name="slider" tag="div">
        <div v-for="(slide, idx) in slides" :key="idx" v-show="currentSlide === idx" class="absolute inset-0 w-full h-full">
          <div class="absolute inset-0 bg-cover bg-center opacity-40 transition-transform duration-[10000ms] ease-out transform"
               :class="currentSlide === idx ? 'scale-105' : 'scale-100'"
               :style="{ backgroundImage: `url(${slide.image})` }"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>

          <div class="container-main py-20 md:py-32 relative z-10 h-full flex flex-col justify-end pb-24 md:pb-32">
            <div class="max-w-3xl">
              <div class="inline-flex items-center gap-2 bg-emerald-500/20 backdrop-blur border border-emerald-500/30 text-emerald-100 text-sm font-medium px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"/>
                {{ slide.tag }}
              </div>
              <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6 whitespace-pre-line">{{ slide.title }}</h1>
              <p class="text-emerald-100 text-xl mb-8 leading-relaxed max-w-2xl">
                {{ slide.desc }}
              </p>
              <div class="flex flex-wrap gap-4">
                <Link :href="getSlideUrl(slide.btn_link)" class="btn-primary px-8 py-3 text-base shadow-lg shadow-emerald-900/40">
                  {{ slide.btn_text }} →
                </Link>
                <Link v-if="idx === 0" href="/packages/type/trekking" class="btn-white px-8 py-3 text-base">
                  🏔 Trekking packages
                </Link>
              </div>
            </div>
          </div>
        </div>
      </TransitionGroup>

      <!-- Slider Controls -->
      <div class="absolute bottom-8 left-0 right-0 z-20">
        <div class="container-main flex gap-3">
          <button v-for="(_, idx) in slides" :key="'btn'+idx" @click="setSlide(idx)"
            class="h-1.5 rounded-full transition-all duration-300"
            :class="currentSlide === idx ? 'w-10 bg-emerald-400' : 'w-4 bg-white/40 hover:bg-white/70'"
            :aria-label="`Go to slide ${idx + 1}`"
          ></button>
        </div>
      </div>
    </div>

    <!-- Stats bar -->
    <div class="bg-slate-900 border-t border-slate-800">
      <div class="container-main py-6 grid grid-cols-3 sm:grid-cols-6 gap-4 text-center">
        <div v-for="(count, type) in packageCounts" :key="type" class="p-2">
          <div class="text-3xl font-black text-white mb-1">{{ count }}</div>
          <div class="text-emerald-400 text-xs font-bold uppercase tracking-wider">{{ typeLabels[type] }}</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Experience Categories -->
  <section class="py-16 bg-white" aria-labelledby="categories-heading">
    <div class="container-main">
      <div class="text-center mb-10">
        <h2 id="categories-heading" class="section-title">Choose your adventure</h2>
        <p class="section-sub">Six ways to experience Nepal — each with hand-curated itinerary packages</p>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <Link v-for="cat in categories" :key="cat.type" :href="cat.href"
              class="group relative bg-white border-2 border-slate-100 hover:border-emerald-300 rounded-2xl p-5 text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 block">
          <div class="text-4xl mb-3">{{ cat.icon }}</div>
          <p class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">{{ cat.label }}</p>
          <p class="text-xs text-slate-400 mt-1">{{ packageCounts[cat.type] || 0 }} packages</p>
          <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-transparent to-emerald-50/40 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"/>
        </Link>
      </div>
    </div>
  </section>

  <!-- Top Destinations Grid -->
  <section class="py-16 bg-slate-50 border-t border-slate-100" aria-labelledby="destinations-heading">
    <div class="container-main">
      <div class="text-center mb-12">
        <h2 id="destinations-heading" class="section-title">Top Regions in Nepal</h2>
        <p class="section-sub font-semibold text-emerald-600 uppercase tracking-wider text-xs mb-2">Explore Destinations</p>
        <p class="section-sub">Discover the unique landscapes, culture, and high altitude trails of Nepal's legendary areas</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <Link v-for="dest in locations" :key="dest.id" :href="`/locations/${dest.slug}`"
              class="group relative h-72 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
          <img :src="locationImage(dest)" :alt="dest.name" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"/>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent"></div>

          <div class="absolute bottom-6 left-6 right-6 text-white flex justify-between items-end">
            <div>
              <h3 class="text-xl font-bold tracking-tight mb-1">{{ dest.name }}</h3>
              <p class="text-emerald-300 text-xs font-semibold">
                {{ dest.packages_count }} {{ dest.packages_count === 1 ? 'package' : 'packages' }}
              </p>
            </div>
            <span class="w-8 h-8 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-white font-black group-hover:bg-emerald-500 transition-colors">→</span>
          </div>
        </Link>
      </div>
    </div>
  </section>

  <!-- Featured Packages -->
  <section class="py-16 bg-slate-50" aria-labelledby="featured-heading">
    <div class="container-main">
      <div class="text-center mb-10">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-2">Our Top Picks</span>
        <h2 id="featured-heading" class="section-title">Featured Packages</h2>
        <p class="section-sub">Hand-picked experiences — from adrenaline to serenity</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <PackageCard v-for="pkg in featuredPackages" :key="pkg.id" :package="pkg"/>
        <!-- Skeleton fallback -->
        <div v-if="!featuredPackages.length" v-for="i in 3" :key="i" class="card animate-pulse">
          <div class="h-52 bg-slate-200"/>
          <div class="p-4 space-y-2">
            <div class="h-3 bg-slate-200 rounded w-1/3"/>
            <div class="h-4 bg-slate-200 rounded"/>
            <div class="h-3 bg-slate-200 rounded w-2/3"/>
          </div>
        </div>
      </div>
      <div class="text-center mt-10">
        <Link href="/packages" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition duration-200 text-sm shadow">
          View All Packages
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </Link>
      </div>
    </div>
  </section>

  <!-- Special Offers / Discounted Packages -->
  <section class="py-16 bg-white border-t border-b border-slate-100" aria-labelledby="offers-heading">
    <div class="container-main">
      <div class="text-center mb-12">
        <span class="text-xs font-bold text-orange-600 uppercase tracking-widest block mb-2">Limited Time Deals</span>
        <h2 id="offers-heading" class="section-title">Special Offers &amp; Discounts</h2>
        <p class="section-sub">Grab these exclusive discounts and start your Himalayan journey today</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Link v-for="offer in specialOffers" :key="offer.id" :href="`/packages/${offer.slug}`"
              class="group bg-white rounded-3xl border border-slate-150 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
          <div class="relative h-56 overflow-hidden bg-slate-100">
            <img :src="offer.cover_image" :alt="offer.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            <!-- Discount Badge -->
            <span v-if="offer.discount_label" class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-black px-3 py-1.5 rounded-full shadow">
              🔥 {{ offer.discount_label }}
            </span>
            <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-lg">
              ⏱️ {{ offer.duration_days }} {{ offer.duration_days === 1 ? 'Day' : 'Days' }}
            </span>
          </div>

          <div class="p-6 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider capitalize">{{ offer.difficulty || 'Easy' }}</span>
                <!-- Stars -->
                <div class="flex items-center gap-1 text-amber-400">
                  <span>★</span>
                  <span class="text-slate-700 text-xs font-bold">5.0</span>
                </div>
              </div>

              <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 mb-4">
                {{ offer.name }}
              </h3>
            </div>

            <div class="flex items-end justify-between pt-4 border-t border-slate-100">
              <div>
                <p class="text-xs text-slate-450 line-through mb-0.5">Original NRs {{ offer.original_price_nrs || (offer.original_price * 135) }}</p>
                <p class="text-xl font-black text-orange-600">NRs {{ offer.price_nrs || (offer.price_per_person * 135) }} <span class="text-xs text-slate-400 font-normal">/person</span></p>
              </div>
              <span class="btn-primary text-xs !py-2 !px-4 shadow">Book Now</span>
            </div>
          </div>
        </Link>
      </div>
    </div>
  </section>

  <!-- Why Choose Us / Statistics & Brand Features -->
  <section class="py-20 bg-slate-900 text-white relative overflow-hidden" aria-labelledby="why-heading">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container-main relative z-10">
      <div class="text-center mb-16">
        <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-2">Why PathToSnow</span>
        <h2 id="why-heading" class="text-3xl md:text-4xl font-black mb-4 text-white">Why Choose Us for Your Next Adventure?</h2>
        <p class="text-slate-350 max-w-xl mx-auto text-sm md:text-base">We bridge the gap between global travelers and authentic local Himalayan guides to provide lifetime experiences.</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16 border-b border-slate-800 pb-16">
        <div class="text-center">
          <p class="text-4xl md:text-5xl font-black text-emerald-400 mb-2">1,500+</p>
          <p class="text-sm font-semibold text-slate-300">Happy Adventurers</p>
          <p class="text-xs text-slate-500 mt-1">From 45+ countries worldwide</p>
        </div>
        <div class="text-center">
          <p class="text-4xl md:text-5xl font-black text-sky-450 mb-2">100%</p>
          <p class="text-sm font-semibold text-slate-300">Local Expert Guides</p>
          <p class="text-xs text-slate-500 mt-1">Direct community support</p>
        </div>
        <div class="text-center">
          <p class="text-4xl md:text-5xl font-black text-orange-450 mb-2">4.9/5</p>
          <p class="text-sm font-semibold text-slate-300">Average Rating</p>
          <p class="text-xs text-slate-500 mt-1">Based on traveler reviews</p>
        </div>
        <div class="text-center">
          <p class="text-4xl md:text-5xl font-black text-emerald-400 mb-2">0%</p>
          <p class="text-sm font-semibold text-slate-300">Agency Markups</p>
          <p class="text-xs text-slate-500 mt-1">100% direct local payouts</p>
        </div>
      </div>

      <!-- Features Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="w in whyPoints" :key="w.title"
             class="bg-slate-800/40 border border-slate-800 hover:border-emerald-500/40 p-8 rounded-3xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
          <div class="text-4xl mb-4 bg-slate-800 w-14 h-14 rounded-2xl flex items-center justify-center border border-slate-700">{{ w.icon }}</div>
          <h3 class="font-bold text-white text-lg mb-2">{{ w.title }}</h3>
          <p class="text-xs text-slate-400 leading-relaxed">{{ w.desc }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Travel Guide preview -->
  <section class="py-16 bg-gradient-to-br from-sky-50 to-emerald-50" aria-labelledby="guide-heading">
    <div class="container-main">
      <div class="text-center mb-10">
        <span class="text-xs font-bold text-sky-600 uppercase tracking-widest block mb-2">Local Knowledge</span>
        <h2 id="guide-heading" class="section-title">Travel Guide</h2>
        <p class="section-sub">Food, culture, festivals and city tours — local knowledge only</p>
      </div>
      <!-- Category pills -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <Link v-for="g in guideCategories" :key="g.href" :href="g.href"
              class="bg-white border border-slate-200 hover:border-sky-300 hover:shadow-md rounded-2xl p-5 text-center block group transition-all duration-200">
          <div class="text-3xl mb-2">{{ g.icon }}</div>
          <p class="text-sm font-semibold text-slate-900 group-hover:text-sky-700 transition-colors">{{ g.label }}</p>
        </Link>
      </div>
      <!-- Latest posts -->
      <div v-if="latestPosts.length" class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <Link v-for="post in latestPosts" :key="post.id" :href="`/blog/${post.slug}`" class="card group block bg-white">
          <div class="h-44 bg-slate-100 overflow-hidden relative">
            <img v-if="post.cover_image" :src="post.cover_image" :alt="post.title"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <img v-else :src="'https://images.unsplash.com/photo-1540390769625-2fc3f8b1d50c?w=600&q=80&random=' + post.id" :alt="post.title"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
          </div>
          <div class="p-4 -mt-6 relative z-10">
            <span :class="`post-${post.post_type}`" class="badge mb-2">{{ postTypeLabel(post.post_type) }}</span>
            <h3 class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ post.title }}</h3>
            <p class="text-xs text-slate-400 mt-1.5">{{ post.read_time }} min read</p>
          </div>
        </Link>
      </div>
      <div class="text-center mt-10">
        <Link href="/travel-guide" class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition duration-200 text-sm shadow">
          View All Guides
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </Link>
      </div>
    </div>
  </section>

  <!-- Featured Gear Shop -->
  <section v-if="featuredProducts.length" class="py-16 bg-white" aria-labelledby="shop-heading">
    <div class="container-main">
      <div class="text-center mb-10">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-2">Gear &amp; Equipment</span>
        <h2 id="shop-heading" class="section-title">Trekking Gear Shop</h2>
        <p class="section-sub">Everything you need for the Himalayas — shipped from Kathmandu</p>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <Link v-for="p in featuredProducts" :key="p.id" :href="`/shop/${p.slug}`" class="card group block">
          <div class="h-48 bg-slate-50 overflow-hidden relative">
            <img v-if="p.images?.[0]" :src="p.images[0]" :alt="p.name"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <img v-else :src="'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=600&q=80&random=' + p.id" :alt="p.name"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          </div>
          <div class="p-4">
            <p class="text-xs text-slate-400 mb-1">{{ p.category?.name }}</p>
            <p class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ p.name }}</p>
            <p class="font-black text-emerald-700 mt-2 text-lg">${{ Number(p.price).toFixed(2) }}</p>
          </div>
        </Link>
      </div>
      <div class="text-center mt-10">
        <Link href="/shop" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition duration-200 text-sm shadow">
          View All Gear
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </Link>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section v-if="testimonials && testimonials.length" class="py-20 bg-emerald-50/30 border-t border-slate-100" aria-labelledby="testimonials-heading">
    <div class="container-main">
      <div class="text-center mb-12">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-2">Testimonials</span>
        <h2 id="testimonials-heading" class="section-title">What Our Travelers Say</h2>
        <p class="section-sub">Real stories from adventurers who explored Nepal with PathToSnow</p>
      </div>

      <div class="max-w-4xl mx-auto bg-white rounded-3xl border border-slate-100 p-8 md:p-12 shadow-sm hover:shadow-md transition-shadow relative">
        <!-- Quote icon -->
        <span class="absolute top-6 left-8 text-8xl text-emerald-100 font-serif leading-none select-none">"</span>

        <div class="relative z-10 min-h-[140px] flex flex-col justify-between">
          <!-- Quote text -->
          <p class="text-slate-700 text-lg md:text-xl italic leading-relaxed mb-8">
            {{ testimonials[activeTestimonial].quote }}
          </p>

          <!-- Author details -->
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
              <img :src="testimonials[activeTestimonial].avatar" :alt="testimonials[activeTestimonial].author"
                   class="w-14 h-14 rounded-full object-cover border-2 border-emerald-500 shadow-sm"/>
              <div>
                <h4 class="font-bold text-slate-900">{{ testimonials[activeTestimonial].author }}</h4>
                <p class="text-xs text-slate-500">{{ testimonials[activeTestimonial].location }}</p>
              </div>
            </div>

            <!-- Rating stars -->
            <div class="flex items-center gap-0.5 text-amber-400 text-lg">
              <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </div>
          </div>
        </div>

        <!-- Slider pagination / Avatars -->
        <div class="flex justify-center gap-4 mt-8 pt-6 border-t border-slate-100">
          <button v-for="(t, idx) in testimonials" :key="idx" @click="activeTestimonial = idx"
                  class="w-10 h-10 rounded-full overflow-hidden transition-all border-2 flex-shrink-0"
                  :class="activeTestimonial === idx ? 'border-emerald-500 scale-110 shadow-md' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-105'">
            <img :src="t.avatar" :alt="t.author" class="w-full h-full object-cover"/>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-20 bg-gradient-to-br from-emerald-600 to-sky-700 text-white" aria-labelledby="cta-heading">
    <div class="container-main text-center">
      <h2 id="cta-heading" class="text-4xl font-black mb-4">Ready to explore Nepal?</h2>
      <p class="text-emerald-100 text-lg mb-8 max-w-xl mx-auto">Browse all our packages and book your Himalayan experience today.</p>
      <div class="flex flex-wrap justify-center gap-4">
        <Link href="/packages" class="btn-white px-8 py-3 text-base">Browse all experiences →</Link>
        <Link href="/shop" class="bg-white/10 hover:bg-white/20 border border-white/30 text-white px-8 py-3 rounded-xl text-base font-semibold transition-all">Shop trekking gear</Link>
      </div>
    </div>
  </section>
</AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PackageCard from '@/Components/PackageCard.vue'

defineProps({
  featuredPackages: { type: Array, default: () => [] },
  packageCounts:    { type: Object, default: () => ({}) },
  latestPosts:      { type: Array, default: () => [] },
  featuredProducts: { type: Array, default: () => [] },
  slides:           { type: Array, default: () => [] },
  locations:        { type: Array, default: () => [] },
  specialOffers:    { type: Array, default: () => [] },
  testimonials:     { type: Array, default: () => [] },
})

const currentSlide = ref(0);
let slideInterval;

onMounted(() => {
  slideInterval = setInterval(() => {
    if (usePage().props.slides.length > 0) {
      currentSlide.value = (currentSlide.value + 1) % usePage().props.slides.length;
    }
  }, 6000);
});

onUnmounted(() => {
  if(slideInterval) clearInterval(slideInterval);
});

const setSlide = (idx) => {
  currentSlide.value = idx;
  if(slideInterval) clearInterval(slideInterval);
  slideInterval = setInterval(() => {
    if (usePage().props.slides.length > 0) {
      currentSlide.value = (currentSlide.value + 1) % usePage().props.slides.length;
    }
  }, 6000);
};

const typeLabels = { adventure:'Adventure', valley_visit:'Valley', trekking:'Trekking', national_park:'Parks', wildlife_reserve:'Wildlife', lake:'Lakes' }
const categories = [
  { type:'adventure',        icon:'⚡', label:'Adventure',       href:'/packages/type/adventure' },
  { type:'trekking',         icon:'🏔', label:'Trekking',        href:'/packages/type/trekking' },
  { type:'valley_visit',     icon:'🏛', label:'Valley Visit',    href:'/packages/type/valley-visit' },
  { type:'national_park',    icon:'🌿', label:'National Parks',  href:'/packages/type/national-park' },
  { type:'wildlife_reserve', icon:'🐅', label:'Wildlife',        href:'/packages/wildlife' },
  { type:'lake',             icon:'🏞', label:'Lakes',           href:'/packages/type/lake' },
]
const guideCategories = [
  { icon:'🍜', label:'Food & Drink',  href:'/travel-guide/food' },
  { icon:'🎭', label:'Culture',       href:'/travel-guide/culture' },
  { icon:'🎉', label:'Festivals',     href:'/travel-guide/festivals' },
  { icon:'🏙', label:'City Tours',    href:'/travel-guide/city-tours' },
]
const whyPoints = [
  { icon:'🧭', title:'Local expertise',   desc:'Every package is personally curated by Nepali guides with 10+ years experience.' },
  { icon:'💵', title:'Best price',        desc:'Book directly — no agency markup, no hidden fees.' },
  { icon:'🔒', title:'Secure booking',    desc:'Encrypted payments and free cancellation on most packages.' },
  { icon:'🌱', title:'Sustainable travel',desc:'We partner with eco-conscious operators who give back to local communities.' },
]
const postTypeLabels = { blog:'Blog', food:'Food', culture:'Culture', festival:'Festival', city_tour:'City Tour', travel_guide:'Travel Guide' }
function postTypeLabel(type) { return postTypeLabels[type] || type }

function getSlideUrl(url) {
  if (!url) return '/packages'
  const map = {
    '/packages/adventure': '/packages/type/adventure',
    '/packages/trekking': '/packages/type/trekking',
    '/packages/valley-visits': '/packages/type/valley-visit',
    '/packages/national-parks': '/packages/type/national-park',
    '/packages/wildlife': '/packages/wildlife',
    '/packages/lakes': '/packages/type/lake'
  }
  return map[url] || url
}

const activeTestimonial = ref(0)

function locationImage(location) {
  return location.cover_image || `https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=900&q=80&auto=format&fit=crop&sig=${location.id}`
}
</script>

<style scoped>
.slider-enter-active,
.slider-leave-active {
  transition: opacity 1.5s ease-in-out;
}
.slider-enter-from,
.slider-leave-to {
  opacity: 0;
}
</style>
