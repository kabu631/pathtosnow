<template>
<AppLayout>
  <Head>
    <title>Top Regions in Nepal — PathToSnow</title>
    <meta name="description" content="Explore Nepal's legendary destinations including Everest, Annapurna, Langtang, Kathmandu, Pokhara and Chitwan. Filter by trekking, adventure, wildlife and more."/>
  </Head>

  <!-- Hero -->
  <section class="relative overflow-hidden bg-slate-900 py-20 text-white text-center">
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="relative container-main z-10">
      <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-3">Explore Destinations</span>
      <h1 class="text-4xl md:text-5xl font-black leading-tight mb-4 text-white">Top Regions in Nepal</h1>
      <p class="text-slate-300 text-sm md:text-base max-w-xl mx-auto">
        Discover Nepal's legendary landscapes, culture &amp; trails — filter by the experience you're after
      </p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600">Home</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium">Locations</span>
    </div>
  </nav>

  <!-- Content: sidebar + grid -->
  <div class="container-main py-10 flex gap-8 items-start">

    <!-- ── LEFT SIDEBAR ─────────────────────────────────────── -->
    <aside class="hidden lg:block w-72 flex-shrink-0 sticky top-20">
      <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        <!-- Search -->
        <div class="p-5 border-b border-slate-100">
          <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">🔍 Search Locations</h2>
          <div class="relative">
            <input
              v-model="searchQ"
              @input="debouncedApply"
              type="search"
              placeholder="e.g. Everest, Pokhara…"
              class="w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
              id="location-search-input"
              aria-label="Search locations"
            />
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
          </div>
        </div>

        <!-- Package type filter -->
        <div class="p-5">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">🗺️ Tour Type</h2>
            <button
              v-if="selectedTypes.length"
              @click="clearTypes"
              class="text-xs text-emerald-600 hover:text-emerald-800 font-semibold transition-colors"
              aria-label="Clear type filters"
            >Clear</button>
          </div>

          <div class="space-y-1.5">
            <label
              v-for="pt in packageTypes"
              :key="pt.type_key"
              :for="`type-${pt.type_key}`"
              class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all duration-150 select-none"
              :class="selectedTypes.includes(pt.type_key)
                ? 'bg-emerald-50 ring-1 ring-emerald-200'
                : 'hover:bg-slate-50'"
            >
              <input
                type="checkbox"
                :id="`type-${pt.type_key}`"
                :value="pt.type_key"
                v-model="selectedTypes"
                @change="applyFilters"
                class="w-4 h-4 rounded text-emerald-600 border-slate-300 focus:ring-emerald-500 focus:ring-offset-0 transition"
              />
              <span class="text-lg leading-none">{{ pt.icon_emoji }}</span>
              <span class="text-sm font-medium text-slate-700">{{ pt.name }}</span>
            </label>
          </div>
        </div>

        <!-- Active filter summary -->
        <div v-if="hasActiveFilters" class="px-5 pb-5">
          <div class="border-t border-slate-100 pt-4">
            <button
              @click="clearAll"
              class="w-full py-2.5 text-sm font-semibold text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition-colors"
              id="clear-all-filters-btn"
            >
              ✕ Clear All Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Result count hint -->
      <p class="text-xs text-slate-400 mt-3 px-1">
        Showing <span class="font-semibold text-slate-600">{{ locations.length }}</span>
        {{ locations.length === 1 ? 'location' : 'locations' }}
        <template v-if="hasActiveFilters"> matching your filters</template>
      </p>
    </aside>

    <!-- ── MAIN CONTENT ──────────────────────────────────────── -->
    <main class="flex-1 min-w-0">

      <!-- Mobile filter bar -->
      <div class="lg:hidden mb-5">
        <div class="flex gap-2">
          <div class="relative flex-1">
            <input
              v-model="searchQ"
              @input="debouncedApply"
              type="search"
              placeholder="Search locations…"
              class="w-full pl-9 pr-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition"
              aria-label="Search locations"
            />
            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
          </div>
          <button
            @click="mobileFilterOpen = !mobileFilterOpen"
            class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 flex items-center gap-2 hover:bg-slate-50 transition-colors"
            :class="hasActiveFilters ? 'border-emerald-400 text-emerald-700 bg-emerald-50' : ''"
            aria-label="Toggle filters"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 8h10M11 12h2"/></svg>
            Filters
            <span v-if="selectedTypes.length" class="bg-emerald-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ selectedTypes.length }}</span>
          </button>
        </div>

        <!-- Mobile type filter panel -->
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-2"
          leave-active-class="transition duration-150 ease-in"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div v-if="mobileFilterOpen" class="mt-3 bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
              <span class="text-sm font-semibold text-slate-700">Filter by Tour Type</span>
              <button v-if="selectedTypes.length" @click="clearTypes" class="text-xs text-emerald-600 font-semibold">Clear</button>
            </div>
            <div class="grid grid-cols-2 gap-2">
              <label
                v-for="pt in packageTypes"
                :key="pt.type_key"
                :for="`mob-type-${pt.type_key}`"
                class="flex items-center gap-2 px-3 py-2 rounded-xl cursor-pointer transition-all text-sm"
                :class="selectedTypes.includes(pt.type_key) ? 'bg-emerald-50 ring-1 ring-emerald-200 font-semibold text-emerald-800' : 'hover:bg-slate-50 text-slate-700'"
              >
                <input type="checkbox" :id="`mob-type-${pt.type_key}`" :value="pt.type_key" v-model="selectedTypes" @change="applyFilters" class="w-4 h-4 rounded text-emerald-600 border-slate-300 focus:ring-emerald-500" />
                <span>{{ pt.icon_emoji }}</span>
                <span>{{ pt.name }}</span>
              </label>
            </div>
          </div>
        </Transition>
      </div>

      <!-- Active filter chips -->
      <div v-if="hasActiveFilters" class="flex flex-wrap gap-2 mb-6">
        <span v-if="searchQ" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-full text-xs font-semibold">
          🔍 "{{ searchQ }}"
          <button @click="searchQ=''; applyFilters()" aria-label="Remove search filter" class="hover:text-red-500 transition-colors">✕</button>
        </span>
        <span v-for="tk in selectedTypes" :key="tk"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 border border-sky-200 text-sky-800 rounded-full text-xs font-semibold">
          {{ typeLabel(tk) }}
          <button @click="removeType(tk)" :aria-label="`Remove ${typeLabel(tk)} filter`" class="hover:text-red-500 transition-colors">✕</button>
        </span>
        <button @click="clearAll" class="inline-flex items-center gap-1 px-3 py-1.5 border border-red-200 text-red-600 rounded-full text-xs font-semibold hover:bg-red-50 transition-colors">
          ✕ Clear all
        </button>
      </div>

      <!-- Grid -->
      <div v-if="locations.length" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        <Link
          v-for="dest in locations"
          :key="dest.id"
          :href="`/locations/${dest.slug}`"
          class="group relative h-72 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block"
        >
          <img
            :src="dest.cover_image || 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=800&q=80&auto=format&fit=crop'"
            :alt="dest.name"
            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
          />
          <!-- Gradient overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent"></div>

          <!-- Content -->
          <div class="absolute bottom-0 left-0 right-0 p-6">
            <div class="flex justify-between items-end">
              <div>
                <h3 class="text-xl font-bold tracking-tight mb-1 drop-shadow text-white">{{ dest.name }}</h3>
                <p class="text-emerald-300 text-xs font-semibold">
                  {{ dest.packages_count }} {{ dest.packages_count === 1 ? 'package' : 'packages' }}
                </p>
              </div>
              <span class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-black group-hover:bg-emerald-500 transition-all duration-200 text-white shrink-0">→</span>
            </div>
          </div>
        </Link>
      </div>

      <!-- Empty state -->
      <div v-else class="text-center py-24 bg-slate-50 rounded-3xl border border-slate-100">
        <p class="text-5xl mb-4">🧭</p>
        <p class="text-slate-600 text-lg font-semibold mb-2">No locations found</p>
        <p class="text-slate-400 text-sm mb-6">
          Try adjusting your search or removing some filters
        </p>
        <button @click="clearAll" class="btn-primary" id="reset-location-filters-btn">
          Reset Filters
        </button>
      </div>
    </main>
  </div>
</AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  locations:    { type: Array, default: () => [] },
  packageTypes: { type: Array, default: () => [] },
  filters:      { type: Object, default: () => ({}) },
})

// ── Reactive filter state (seeded from server-side filters) ──
const searchQ       = ref(props.filters?.q || '')
const selectedTypes = ref(Array.isArray(props.filters?.types) ? props.filters.types : [])
const mobileFilterOpen = ref(false)

const hasActiveFilters = computed(() => searchQ.value || selectedTypes.value.length > 0)

// Debounce helper for the search input
let debounceTimer = null
function debouncedApply() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(applyFilters, 350)
}

function applyFilters() {
  const params = {}
  if (searchQ.value)            params.q     = searchQ.value
  if (selectedTypes.value.length) params.types = selectedTypes.value

  router.get('/locations', params, {
    preserveState: true,
    replace: true,
  })
}

function clearTypes() {
  selectedTypes.value = []
  applyFilters()
}

function removeType(key) {
  selectedTypes.value = selectedTypes.value.filter(t => t !== key)
  applyFilters()
}

function clearAll() {
  searchQ.value       = ''
  selectedTypes.value = []
  mobileFilterOpen.value = false
  router.get('/locations', {}, { replace: true })
}

// Helper: get display label for a type key
function typeLabel(key) {
  const found = props.packageTypes.find(pt => pt.type_key === key)
  return found ? `${found.icon_emoji} ${found.name}` : key
}
</script>
