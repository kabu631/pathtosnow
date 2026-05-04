<template>
<AppLayout>
  <Head>
    <title>PathToSnow — Nepal Travel Packages, Trekking & Gear</title>
    <meta name="description" content="Discover Nepal's best travel packages — trekking, adventure, wildlife safaris, valley visits and more. Book directly with local guides on PathToSnow."/>
    <meta property="og:title" content="PathToSnow — Nepal's #1 Travel Platform"/>
    <meta property="og:description" content="Nepal travel packages, gear shop and travel guides — curated by locals."/>
    <meta property="og:type" content="website"/>
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
                <Link :href="slide.btn_link" class="btn-primary px-8 py-3 text-base shadow-lg shadow-emerald-900/40">
                  {{ slide.btn_text }} →
                </Link>
                <Link v-if="idx === 0" href="/packages/trekking" class="btn-white px-8 py-3 text-base">
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

  <!-- Featured Packages -->
  <section class="py-16 bg-slate-50" aria-labelledby="featured-heading">
    <div class="container-main">
      <div class="flex items-end justify-between mb-10">
        <div>
          <h2 id="featured-heading" class="section-title">Featured packages</h2>
          <p class="section-sub">Hand-picked experiences — from adrenaline to serenity</p>
        </div>
        <Link href="/packages" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 hidden sm:flex items-center gap-1">
          View all <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </Link>
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
    </div>
  </section>

  <!-- Why PathToSnow -->
  <section class="py-16 bg-white" aria-labelledby="why-heading">
    <div class="container-main">
      <div class="text-center mb-12">
        <h2 id="why-heading" class="section-title">Why PathToSnow?</h2>
        <p class="section-sub">We're not a booking platform. We're your local Nepal expert.</p>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="w in whyPoints" :key="w.title"
             class="text-center p-6 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-md transition-all duration-300">
          <div class="text-4xl mb-4">{{ w.icon }}</div>
          <h3 class="font-bold text-slate-900 mb-2">{{ w.title }}</h3>
          <p class="text-sm text-slate-500 leading-relaxed">{{ w.desc }}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Travel Guide preview -->
  <section class="py-16 bg-gradient-to-br from-sky-50 to-emerald-50" aria-labelledby="guide-heading">
    <div class="container-main">
      <div class="flex items-end justify-between mb-10">
        <div>
          <h2 id="guide-heading" class="section-title">Travel guide</h2>
          <p class="section-sub">Food, culture, festivals and city tours — local knowledge only</p>
        </div>
        <Link href="/travel-guide" class="text-sm font-semibold text-sky-600 hover:text-sky-700 hidden sm:flex items-center gap-1">
          All guides <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </Link>
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
    </div>
  </section>

  <!-- Featured Gear Shop -->
  <section v-if="featuredProducts.length" class="py-16 bg-white" aria-labelledby="shop-heading">
    <div class="container-main">
      <div class="flex items-end justify-between mb-10">
        <div>
          <h2 id="shop-heading" class="section-title">Trekking gear shop</h2>
          <p class="section-sub">Everything you need for the Himalayas — shipped from Kathmandu</p>
        </div>
        <Link href="/shop" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 hidden sm:flex items-center gap-1">
          All gear <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </Link>
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
})

const currentSlide = ref(0);
let slideInterval;

onMounted(() => {
  slideInterval = setInterval(() => {
    // Check if slides exist to avoid div by zero
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
  { type:'adventure',        icon:'⚡', label:'Adventure',       href:'/packages/adventure' },
  { type:'trekking',         icon:'🏔', label:'Trekking',        href:'/packages/trekking' },
  { type:'valley_visit',     icon:'🏛', label:'Valley Visit',    href:'/packages/valley-visits' },
  { type:'national_park',    icon:'🌿', label:'National Parks',  href:'/packages/national-parks' },
  { type:'wildlife_reserve', icon:'🐅', label:'Wildlife',        href:'/packages/wildlife' },
  { type:'lake',             icon:'🏞', label:'Lakes',           href:'/packages/lakes' },
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
