<template>
<AppLayout>
  <Head>
    <title>{{ country.name }} Tours & Travel Packages — PathToSnow</title>
    <meta name="description" :content="country.description || `Explore our curated travel packages for ${country.name}.`"/>
  </Head>

  <!-- Hero Section -->
  <section class="relative overflow-hidden min-h-[400px] flex items-center" 
           :style="{ backgroundImage: `url(${country.cover_image || '/images/hero.jpg'})`, backgroundSize: 'cover', backgroundPosition: 'center' }">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
    <div class="relative container-main py-20 text-center text-white">
      <span class="text-emerald-300 font-bold tracking-wider uppercase text-sm mb-4 block">Abroad Destinations</span>
      <h1 class="text-5xl md:text-6xl font-black leading-tight mb-4 drop-shadow-lg text-white">
        {{ country.name }}
      </h1>
      <p class="text-white text-xl max-w-2xl mx-auto drop-shadow-md leading-relaxed">
        {{ country.description || `Discover our incredible packages tailored for ${country.name}.` }}
      </p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <nav class="border-b border-slate-100 bg-white shadow-sm" aria-label="Breadcrumb">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500 font-medium">
      <Link href="/" class="hover:text-emerald-600 transition-colors">Home</Link>
      <span>/</span>
      <span class="text-slate-400">Abroad</span>
      <span>/</span>
      <span class="text-emerald-700">{{ country.name }}</span>
    </div>
  </nav>

  <!-- Packages Section -->
  <div class="container-main py-16">
    <div class="flex items-end justify-between mb-8">
      <div>
        <h2 class="text-3xl font-bold text-slate-900">Packages in {{ country.name }}</h2>
        <p class="text-slate-500 mt-2 text-lg">Browse {{ packages.total }} amazing experiences.</p>
      </div>
    </div>

    <div v-if="packages.data.length" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <Link v-for="pkg in packages.data" :key="pkg.id" :href="`/packages/${pkg.slug}`" class="card group flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-xl transition-all duration-300">
        <!-- Thumbnail -->
        <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
          <img v-if="pkg.cover_image" :src="pkg.cover_image" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"/>
          <div v-else class="w-full h-full flex items-center justify-center text-4xl bg-slate-200">🏔️</div>
          
          <div v-if="pkg.featured" class="absolute top-3 left-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded shadow-sm">
            Top Pick
          </div>
        </div>

        <!-- Content -->
        <div class="p-5 flex-1 flex flex-col">
          <p class="text-xs font-bold text-emerald-600 mb-2 uppercase tracking-wide">{{ pkg.type }}</p>
          <h3 class="font-bold text-lg text-slate-900 group-hover:text-emerald-700 transition-colors mb-2 line-clamp-2 leading-snug">
            {{ pkg.name }}
          </h3>
          <p class="text-sm text-slate-500 mb-4 line-clamp-2 leading-relaxed flex-1">
            {{ pkg.short_description }}
          </p>

          <div class="grid grid-cols-2 gap-3 mb-4 pt-4 border-t border-slate-100">
            <div class="flex flex-col">
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Duration</span>
              <span class="text-sm font-semibold text-slate-700 flex items-center gap-1.5"><span class="text-emerald-500">⏱</span> {{ pkg.duration_days }} Days</span>
            </div>
            <div class="flex flex-col">
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Difficulty</span>
              <span class="text-sm font-semibold text-slate-700 flex items-center gap-1.5 capitalize"><span class="text-sky-500">🧗</span> {{ pkg.difficulty || 'N/A' }}</span>
            </div>
          </div>

          <!-- Price & CTA -->
          <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100">
            <div>
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">From</p>
              <p class="font-black text-xl text-slate-900">NRs {{ Number(pkg.price_per_person).toLocaleString() }}</p>
            </div>
            <span class="bg-slate-100 text-slate-700 group-hover:bg-emerald-600 group-hover:text-white px-4 py-2 rounded-xl text-sm font-semibold transition-colors shadow-sm">View details</span>
          </div>
        </div>
      </Link>
    </div>

    <div v-else class="text-center py-24 bg-slate-50 rounded-3xl border border-slate-200 shadow-inner">
      <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🌍</div>
      <h3 class="text-2xl font-bold text-slate-800 mb-2">Packages Coming Soon</h3>
      <p class="text-slate-500 text-lg max-w-md mx-auto">We are currently crafting new experiences for {{ country.name }}. Check back later!</p>
    </div>

    <!-- Pagination -->
    <div v-if="packages.last_page > 1" class="flex justify-center flex-wrap gap-2 mt-12">
      <Link v-for="link in packages.links" :key="link.label"
            :href="link.url ?? '#'"
            :class="['px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-sm',
                     link.active ? 'bg-emerald-600 text-white hover:bg-emerald-700' : link.url ? 'bg-white border border-slate-200 text-slate-600 hover:border-emerald-300 hover:text-emerald-600' : 'bg-slate-50 text-slate-300 cursor-not-allowed border border-slate-100']"
            v-html="link.label"/>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    country: Object,
    packages: Object,
})
</script>
