<template>
<AppLayout>
  <Head>
    <title>{{ pkg.meta_title || pkg.name + ' | PathToSnow Nepal' }}</title>
    <meta name="description" :content="pkg.meta_description || pkg.short_description"/>
    <meta property="og:title" :content="pkg.name"/>
    <meta property="og:image" :content="pkg.cover_image"/>
    <meta property="og:type" content="product"/>
  </Head>

  <!-- Hero -->
  <div class="relative h-72 md:h-[420px] bg-slate-800 overflow-hidden">
    <img v-if="pkg.cover_image" :src="pkg.cover_image" :alt="pkg.name"
         class="w-full h-full object-cover opacity-70"/>
    <div v-else class="w-full h-full bg-gradient-to-br from-emerald-900 to-sky-900 flex items-center justify-center">
      <span class="text-8xl opacity-30">🏔</span>
    </div>
    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"/>
    <div class="absolute inset-0 flex items-end">
      <div class="container-main pb-8 w-full">
        <div class="flex flex-wrap gap-2 mb-3">
          <span :class="`type-${pkg.type}`" class="badge shadow-sm">{{ typeLabel }}</span>
          <span v-if="pkg.difficulty" :class="`diff-${pkg.difficulty}`" class="badge capitalize shadow-sm">{{ pkg.difficulty }}</span>
          <span v-if="pkg.max_altitude_m" class="badge bg-white/20 text-white backdrop-blur border border-white/30">⛰ Max {{ pkg.max_altitude_m.toLocaleString() }}m</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-2">{{ pkg.name }}</h1>
        <p class="text-slate-300 flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
          {{ pkg.location }}
        </p>
      </div>
    </div>
  </div>

  <!-- Breadcrumb (SEO) -->
  <nav class="border-b border-slate-100 bg-slate-50" aria-label="Breadcrumb">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600">Home</Link>
      <span>/</span>
      <Link href="/packages" class="hover:text-emerald-600">Packages</Link>
      <span>/</span>
      <Link :href="`/packages/type/${pkg.type.replace(/_/g,'-')}`" class="hover:text-emerald-600 capitalize">{{ typeLabel }}</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium truncate">{{ pkg.name }}</span>
    </div>
  </nav>

  <div class="container-main py-10">
    <div class="grid lg:grid-cols-3 gap-10">

      <!-- Left: Details -->
      <div class="lg:col-span-2 space-y-10">

        <!-- Quick facts -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
          <div v-for="f in quickFacts" :key="f.label"
               class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-center">
            <p class="text-xl mb-1">{{ f.icon }}</p>
            <p class="text-lg font-black text-emerald-800">{{ f.value }}</p>
            <p class="text-xs text-emerald-600 mt-0.5">{{ f.label }}</p>
          </div>
        </div>

        <!-- Description -->
        <div>
          <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-emerald-500 rounded-full inline-block"/>
            About this experience
          </h2>
          <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed" v-html="pkg.description"/>
        </div>

        <!-- Highlights -->
        <div v-if="pkg.highlights?.length">
          <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-emerald-500 rounded-full inline-block"/>
            Highlights
          </h2>
          <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <li v-for="h in pkg.highlights" :key="h"
                class="flex items-start gap-3 text-sm text-slate-700 bg-slate-50 rounded-xl px-4 py-3">
              <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
              </svg>
              {{ h }}
            </li>
          </ul>
        </div>

        <!-- Included / Excluded -->
        <div class="grid sm:grid-cols-2 gap-4" v-if="pkg.included?.length || pkg.excluded?.length">
          <div v-if="pkg.included?.length" class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-emerald-800 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              What's included
            </h3>
            <ul class="space-y-2">
              <li v-for="item in pkg.included" :key="item" class="flex items-start gap-2 text-sm text-emerald-900">
                <span class="text-emerald-500 flex-shrink-0 mt-0.5">✓</span> {{ item }}
              </li>
            </ul>
          </div>
          <div v-if="pkg.excluded?.length" class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-red-800 mb-3 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              Not included
            </h3>
            <ul class="space-y-2">
              <li v-for="item in pkg.excluded" :key="item" class="flex items-start gap-2 text-sm text-red-900">
                <span class="text-red-400 flex-shrink-0">×</span> {{ item }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Itinerary -->
        <div v-if="pkg.itinerary_days?.length">
          <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-sky-500 rounded-full inline-block"/>
            Day-by-day itinerary
          </h2>
          <div class="space-y-2">
            <div v-for="day in pkg.itinerary_days" :key="day.id"
                 class="border border-slate-200 rounded-2xl overflow-hidden bg-white">
              <button @click="openDay = openDay === day.id ? null : day.id"
                      class="w-full flex items-center justify-between p-4 text-left hover:bg-emerald-50 transition-colors"
                      :aria-expanded="openDay === day.id">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center text-white text-xs font-black flex-shrink-0">
                    {{ day.day_number }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-900">{{ day.title }}</p>
                    <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                      <span v-if="day.altitude_m" class="text-xs text-slate-400">⛰ {{ day.altitude_m.toLocaleString() }}m</span>
                      <span v-if="day.distance_km" class="text-xs text-slate-400">📍 {{ day.distance_km }}km</span>
                      <span v-if="day.accommodation" class="text-xs text-slate-400">🏨 {{ day.accommodation }}</span>
                    </div>
                  </div>
                </div>
                <svg :class="openDay === day.id ? 'rotate-180 text-emerald-600' : 'text-slate-400'"
                     class="w-4 h-4 transition-transform flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <div v-if="openDay === day.id" class="px-5 pb-5 border-t border-slate-100 bg-slate-50">
                <p class="text-sm text-slate-600 leading-relaxed mt-4">{{ day.description }}</p>
                <div v-if="day.meals?.length" class="mt-3 flex flex-wrap gap-1.5">
                  <span v-for="meal in day.meals" :key="meal" class="badge bg-amber-100 text-amber-800">🍽 {{ meal }}</span>
                </div>
                <p v-if="day.notes" class="text-xs text-slate-400 mt-3 italic bg-white rounded-lg px-3 py-2 border border-slate-100">💡 {{ day.notes }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Related posts -->
        <div v-if="relatedPosts?.length">
          <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-sky-500 rounded-full inline-block"/>
            Related travel guides
          </h2>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <Link v-for="post in relatedPosts" :key="post.id" :href="`/blog/${post.slug}`" class="card group block">
              <div class="h-28 bg-slate-100 overflow-hidden">
                <img v-if="post.cover_image" :src="post.cover_image" :alt="post.title"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                <div v-else class="w-full h-full flex items-center justify-center text-3xl bg-gradient-to-br from-sky-50 to-emerald-50">📖</div>
              </div>
              <div class="p-3">
                <p class="text-xs font-semibold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors">{{ post.title }}</p>
              </div>
            </Link>
          </div>
        </div>
      </div>

      <!-- Right: Booking sidebar -->
      <div>
        <div class="border border-slate-200 rounded-2xl shadow-sm bg-white sticky top-20 overflow-hidden">
          <!-- Price header -->
          <div class="bg-gradient-to-br from-emerald-600 to-sky-700 p-5 text-white text-center">
            <p class="text-4xl font-black">NRs {{ Number(pkg.price_per_person).toLocaleString() }}</p>
            <p class="text-emerald-100 text-sm mt-1">per person</p>
            <div class="flex justify-center gap-4 mt-3 text-xs text-emerald-100">
              <span>📅 {{ pkg.duration_days }} days</span>
              <span v-if="pkg.best_season">🌤 {{ pkg.best_season }}</span>
            </div>
          </div>

            <div class="p-5">
            <!-- Admin notice instead of booking button -->
            <div v-if="isAdmin"
                 class="mb-4 flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
              <span class="text-xl flex-shrink-0">🔒</span>
              <div>
                <p class="text-sm font-bold text-amber-800">Admin View</p>
                <p class="text-xs text-amber-700 mt-0.5">Admins cannot book packages. Manage this package from the
                  <a href="/admin/packages" class="underline font-semibold">Admin Panel</a>.
                </p>
              </div>
            </div>
            <Link v-else :href="`/book/${pkg.slug}`" class="btn-primary w-full mb-4">
              Book this package →
            </Link>

            <div class="space-y-2.5 text-sm text-slate-600 mb-5">
              <div class="flex justify-between items-center py-2 border-b border-slate-100">
                <span class="text-slate-500">Group size</span>
                <span class="font-semibold">{{ pkg.min_group_size }}–{{ pkg.max_group_size }} people</span>
              </div>
              <div v-if="pkg.start_point" class="flex justify-between items-center py-2 border-b border-slate-100">
                <span class="text-slate-500">Starts at</span>
                <span class="font-semibold">{{ pkg.start_point }}</span>
              </div>
              <div v-if="pkg.end_point" class="flex justify-between items-center py-2 border-b border-slate-100">
                <span class="text-slate-500">Ends at</span>
                <span class="font-semibold">{{ pkg.end_point }}</span>
              </div>
            </div>

            <!-- Trust badges -->
            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100">
              <div v-for="b in trustBadges" :key="b.label" class="text-center">
                <p class="text-xl mb-1">{{ b.icon }}</p>
                <p class="text-xs text-slate-500 leading-tight">{{ b.label }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Related packages -->
    <div v-if="related?.length" class="mt-16">
      <h2 class="text-2xl font-bold text-slate-900 mb-6">Similar packages</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <PackageCard v-for="r in related" :key="r.id" :package="r"/>
      </div>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PackageCard from '@/Components/PackageCard.vue'

const props = defineProps({ package: Object, related: Array, relatedPosts: Array })
const pkg = computed(() => props.package)
const openDay = ref(null)

const isAdmin = computed(() => usePage().props.auth?.user?.role === 'admin')

const typeLabelMap = { adventure:'Adventure', valley_visit:'Valley Visit', trekking:'Trekking', national_park:'National Park', wildlife_reserve:'Wildlife Reserve', lake:'Lake' }
const typeLabel    = computed(() => typeLabelMap[pkg.value.type] || pkg.value.type)

const quickFacts = computed(() => [
  { icon: '📅', label: 'Duration',   value: `${pkg.value.duration_days} days` },
  { icon: '💵', label: 'From price', value: `NRs ${Number(pkg.value.price_per_person).toLocaleString()}` },
  { icon: '👥', label: 'Group size', value: `${pkg.value.min_group_size}–${pkg.value.max_group_size}` },
  { icon: '📍', label: 'Location',   value: pkg.value.location?.split(',')[0] || '' },
])

const trustBadges = [
  { icon: '🔒', label: 'Secure booking' },
  { icon: '🧭', label: 'Local guide' },
  { icon: '↩',  label: 'Free cancel' },
]
</script>
