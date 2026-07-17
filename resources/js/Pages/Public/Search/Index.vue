<template>
<AppLayout>
  <Head>
    <title>Search Results — PathToSnow</title>
    <meta name="description" content="Search packages, destinations, trekking gears, and guides on PathToSnow."/>
  </Head>

  <!-- Hero Section -->
  <section class="relative overflow-hidden bg-slate-900 py-16 text-white text-center">
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="relative container-main z-10">
      <span class="text-xs font-bold text-emerald-450 uppercase tracking-widest block mb-2">Search results for</span>
      <h1 class="text-3xl md:text-5xl font-black leading-tight mb-6 text-white">
        "{{ searchQuery || 'Search our site' }}"
      </h1>

      <!-- On-page Refined Search bar -->
      <form @submit.prevent="runNewSearch" class="max-w-2xl mx-auto flex gap-2">
        <div class="relative flex-1">
          <input
            v-model="onPageQuery"
            type="text"
            placeholder="Search packages, destinations, gear or guides..."
            class="w-full pl-12 pr-4 py-3.5 rounded-2xl border-0 bg-white/10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-450 focus:bg-white focus:text-slate-800 transition shadow-inner text-sm md:text-base"
          />
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
          </svg>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-3.5 rounded-2xl text-sm md:text-base shadow-lg transition active:scale-98">
          Search
        </button>
      </form>
    </div>
  </section>

  <!-- Navigation Breadcrumb -->
  <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
    <div class="container-main py-3.5 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600 transition-colors">Home</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium">Search</span>
    </div>
  </nav>

  <!-- Main Search Content -->
  <div class="container-main py-10">
    <!-- Category Tabs -->
    <div class="flex flex-wrap gap-2 mb-8 border-b border-slate-100 pb-5">
      <button 
        v-for="tab in tabs" 
        :key="tab.id"
        @click="activeTab = tab.id"
        :class="[
          'px-5 py-3 rounded-full text-xs md:text-sm font-bold border transition-all duration-200 select-none flex items-center gap-2',
          activeTab === tab.id 
            ? 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-100 scale-105' 
            : 'border-slate-200 text-slate-650 hover:border-slate-350 hover:bg-slate-50 bg-white'
        ]"
      >
        <span>{{ tab.emoji }}</span>
        <span>{{ tab.label }}</span>
        <span 
          :class="[
            'text-[10px] px-2 py-0.5 rounded-full font-bold',
            activeTab === tab.id ? 'bg-emerald-550 text-white' : 'bg-slate-150 text-slate-600'
          ]"
        >
          {{ tab.count }}
        </span>
      </button>
    </div>

    <!-- TAB: ALL -->
    <div v-if="activeTab === 'all'" class="space-y-16">
      
      <!-- Experiences Section Preview -->
      <section v-if="results.packages?.length">
        <div class="flex justify-between items-end mb-6">
          <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 flex items-center gap-2">
              <span>⚡</span> Experiences / Packages
            </h2>
            <p class="text-xs text-slate-500 mt-1">Treks, tours, and expeditions matching your query</p>
          </div>
          <button @click="activeTab = 'packages'" class="text-sm font-bold text-emerald-650 hover:text-emerald-800 transition-colors flex items-center gap-1">
            View all {{ results.packages.length }} →
          </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <PackageCard v-for="pkg in results.packages.slice(0, 3)" :key="pkg.id" :package="pkg" />
        </div>
      </section>

      <!-- Destinations Section Preview -->
      <section v-if="results.locations?.length">
        <div class="flex justify-between items-end mb-6">
          <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 flex items-center gap-2">
              <span>🏔️</span> Top Locations
            </h2>
            <p class="text-xs text-slate-500 mt-1">Beautiful destinations and regions to explore</p>
          </div>
          <button @click="activeTab = 'locations'" class="text-sm font-bold text-emerald-650 hover:text-emerald-800 transition-colors flex items-center gap-1">
            View all {{ results.locations.length }} →
          </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Reuse visual location card code -->
          <Link 
            v-for="dest in results.locations.slice(0, 3)" 
            :key="dest.id" 
            :href="`/locations/${dest.slug}`" 
            class="group relative aspect-[4/3] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 block"
          >
            <img 
              v-if="dest.cover_image" 
              :src="dest.cover_image" 
              :alt="dest.name" 
              class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
            />
            <div v-else class="absolute inset-0 w-full h-full bg-slate-200 flex items-center justify-center text-4xl">🧭</div>
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
      </section>

      <!-- Products Section Preview -->
      <section v-if="results.products?.length">
        <div class="flex justify-between items-end mb-6">
          <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 flex items-center gap-2">
              <span>🎒</span> Trekking Gear
            </h2>
            <p class="text-xs text-slate-500 mt-1">Authentic trekking equipment shipped from Kathmandu</p>
          </div>
          <button @click="activeTab = 'products'" class="text-sm font-bold text-emerald-650 hover:text-emerald-800 transition-colors flex items-center gap-1">
            View all {{ results.products.length }} →
          </button>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
          <!-- Reuse product card design -->
          <Link v-for="p in results.products.slice(0, 4)" :key="p.id" :href="`/shop/${p.slug}`" class="card group block">
            <div class="relative aspect-square bg-slate-50 overflow-hidden rounded-2xl border border-slate-100">
              <img v-if="p.images?.[0]" :src="p.images[0]" :alt="p.name"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
              <div v-else class="w-full h-full flex items-center justify-center text-4xl">🎒</div>
              <!-- Sale badge -->
              <div v-if="p.compare_price && Number(p.compare_price) > Number(p.price)"
                   class="absolute top-2 left-2 bg-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                SALE
              </div>
            </div>
            <div class="p-3">
              <p class="text-xs text-slate-400 mb-0.5">{{ p.category?.name || 'Trekking Gear' }}</p>
              <p class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors leading-snug">{{ p.name }}</p>
              <div class="flex items-center gap-2 mt-1.5">
                <span class="font-black text-emerald-700 text-base">${{ Number(p.price).toFixed(2) }}</span>
                <span v-if="p.compare_price && Number(p.compare_price) > Number(p.price)"
                      class="text-xs text-slate-400 line-through">${{ Number(p.compare_price).toFixed(2) }}</span>
              </div>
            </div>
          </Link>
        </div>
      </section>

      <!-- Guides/Posts Section Preview -->
      <section v-if="results.posts?.length">
        <div class="flex justify-between items-end mb-6">
          <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-900 flex items-center gap-2">
              <span>📖</span> Travel Guides & Blogs
            </h2>
            <p class="text-xs text-slate-500 mt-1">Useful travel guide write-ups, culture, food, and trail info</p>
          </div>
          <button @click="activeTab = 'posts'" class="text-sm font-bold text-emerald-650 hover:text-emerald-800 transition-colors flex items-center gap-1">
            View all {{ results.posts.length }} →
          </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <PostCard v-for="post in results.posts.slice(0, 3)" :key="post.id" :post="post" />
        </div>
      </section>

      <!-- Global Empty state inside all -->
      <div 
        v-if="!results.packages?.length && !results.locations?.length && !results.products?.length && !results.posts?.length" 
        class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100"
      >
        <p class="text-5xl mb-4">🏔️</p>
        <h3 class="text-lg font-bold text-slate-900 mb-2">No matching results found</h3>
        <p class="text-slate-550 text-sm max-w-md mx-auto leading-relaxed">
          We couldn't find any packages, locations, gear, or guides matching "{{ searchQuery }}". Please try searching with other keywords.
        </p>
      </div>

    </div>

    <!-- TAB: EXPERIENCES -->
    <div v-else-if="activeTab === 'packages'">
      <div v-if="results.packages?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <PackageCard v-for="pkg in results.packages" :key="pkg.id" :package="pkg" />
      </div>
      <div v-else class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
        <p class="text-5xl mb-4">⚡</p>
        <h3 class="text-lg font-bold text-slate-900 mb-1">No matching experiences</h3>
        <p class="text-slate-550 text-sm">We couldn't find any packages or tours matching your search query.</p>
      </div>
    </div>

    <!-- TAB: LOCATIONS -->
    <div v-else-if="activeTab === 'locations'">
      <div v-if="results.locations?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <Link 
          v-for="dest in results.locations" 
          :key="dest.id" 
          :href="`/locations/${dest.slug}`" 
          class="group relative aspect-[4/3] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 block"
        >
          <img 
            v-if="dest.cover_image" 
            :src="dest.cover_image" 
            :alt="dest.name" 
            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
          />
          <div v-else class="absolute inset-0 w-full h-full bg-slate-200 flex items-center justify-center text-4xl">🧭</div>
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
      <div v-else class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
        <p class="text-5xl mb-4">🏔️</p>
        <h3 class="text-lg font-bold text-slate-900 mb-1">No matching locations</h3>
        <p class="text-slate-550 text-sm">We couldn't find any locations or regions matching your search query.</p>
      </div>
    </div>

    <!-- TAB: GEAR -->
    <div v-else-if="activeTab === 'products'">
      <div v-if="results.products?.length" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <Link v-for="p in results.products" :key="p.id" :href="`/shop/${p.slug}`" class="card group block">
          <div class="relative aspect-square bg-slate-50 overflow-hidden rounded-2xl border border-slate-100">
            <img v-if="p.images?.[0]" :src="p.images[0]" :alt="p.name"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
            <div v-else class="w-full h-full flex items-center justify-center text-4xl">🎒</div>
            <!-- Sale badge -->
            <div v-if="p.compare_price && Number(p.compare_price) > Number(p.price)"
                 class="absolute top-2 left-2 bg-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
              SALE
            </div>
          </div>
          <div class="p-3">
            <p class="text-xs text-slate-400 mb-0.5">{{ p.category?.name || 'Trekking Gear' }}</p>
            <p class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors leading-snug">{{ p.name }}</p>
            <div class="flex items-center gap-2 mt-1.5">
              <span class="font-black text-emerald-700 text-base">${{ Number(p.price).toFixed(2) }}</span>
              <span v-if="p.compare_price && Number(p.compare_price) > Number(p.price)"
                    class="text-xs text-slate-400 line-through">${{ Number(p.compare_price).toFixed(2) }}</span>
            </div>
          </div>
        </Link>
      </div>
      <div v-else class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
        <p class="text-5xl mb-4">🎒</p>
        <h3 class="text-lg font-bold text-slate-900 mb-1">No matching gear found</h3>
        <p class="text-slate-550 text-sm">We couldn't find any products in our outdoor shop matching your query.</p>
      </div>
    </div>

    <!-- TAB: GUIDES -->
    <div v-else-if="activeTab === 'posts'">
      <div v-if="results.posts?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <PostCard v-for="post in results.posts" :key="post.id" :post="post" />
      </div>
      <div v-else class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
        <p class="text-5xl mb-4">📖</p>
        <h3 class="text-lg font-bold text-slate-900 mb-1">No matching travel guides</h3>
        <p class="text-slate-550 text-sm">We couldn't find any articles, guides, or blogs matching your query.</p>
      </div>
    </div>

  </div>
</AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PackageCard from '@/Components/PackageCard.vue'
import PostCard from '@/Components/PostCard.vue'

const props = defineProps({
  results: {
    type: Object,
    default: () => ({
      packages: [],
      locations: [],
      products: [],
      posts: []
    })
  },
  filters: {
    type: Object,
    default: () => ({ q: '' })
  }
})

const searchQuery = computed(() => props.filters?.q || '')
const onPageQuery = ref(searchQuery.value)
const activeTab = ref('all')

const tabs = computed(() => [
  { id: 'all', label: 'All Results', emoji: '🔍', count: totalCount.value },
  { id: 'packages', label: 'Experiences', emoji: '⚡', count: props.results.packages?.length || 0 },
  { id: 'locations', label: 'Destinations', emoji: '🏔️', count: props.results.locations?.length || 0 },
  { id: 'products', label: 'Trekking Gear', emoji: '🎒', count: props.results.products?.length || 0 },
  { id: 'posts', label: 'Guides & Blogs', emoji: '📖', count: props.results.posts?.length || 0 }
])

const totalCount = computed(() => {
  return (props.results.packages?.length || 0) +
         (props.results.locations?.length || 0) +
         (props.results.products?.length || 0) +
         (props.results.posts?.length || 0)
})

function runNewSearch() {
  const query = onPageQuery.value.trim()
  if (!query) return
  router.get('/search', { q: query })
}
</script>
