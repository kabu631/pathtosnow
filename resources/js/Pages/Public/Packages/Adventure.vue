<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PackageCard from '@/Components/PackageCard.vue'
import LeftSidebar from '@/Components/LeftSidebar.vue'
import RightSidebar from '@/Components/RightSidebar.vue'
import Pagination from '@/Components/Pagination.vue'
import { ref, watch } from 'vue'

const props = defineProps({ packages: Object, filters: Object, type: String })

const q          = ref(props.filters?.q ?? '')
const difficulty = ref(props.filters?.difficulty ?? '')
const sort       = ref(props.filters?.sort ?? 'featured')

const routeMap = {
  adventure:        '/packages/adventure',
  trekking:         '/packages/trekking',
  valley_visit:     '/packages/valley-visits',
  national_park:    '/packages/national-parks',
  wildlife_reserve: '/packages/wildlife',
  lake:             '/packages/lakes',
}

const config = {
  adventure: {
    title: 'Adventure Packages — Nepal',
    h1: 'Adventure Packages',
    desc: 'Bungee jumping, white-water rafting, paragliding & extreme Nepal experiences',
    icon: '⚡', gradient: 'from-red-700 to-orange-700', badge: 'bg-red-100 text-red-700',
    bgImage: 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=1600&q=80&auto=format&fit=crop',
  },
  trekking: {
    title: 'Trekking Packages — Nepal Himalaya',
    h1: 'Trekking Packages',
    desc: "Annapurna, Everest Base Camp, Langtang & Nepal's greatest trails",
    icon: '🏔', gradient: 'from-emerald-800 to-green-700', badge: 'bg-emerald-100 text-emerald-800',
    bgImage: 'https://images.unsplash.com/photo-1467887913518-98ca7ce13ede?w=1600&q=80&auto=format&fit=crop',
  },
  valley_visit: {
    title: 'Valley Visit Packages — Kathmandu & Pokhara',
    h1: 'Valley Visit Packages',
    desc: "Kathmandu, Pokhara, Bhaktapur & Nepal's UNESCO World Heritage Sites",
    icon: '🏛', gradient: 'from-violet-800 to-purple-700', badge: 'bg-violet-100 text-violet-700',
    bgImage: 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=1600&q=80&auto=format&fit=crop',
  },
  national_park: {
    title: 'National Park Packages — Chitwan & Sagarmatha',
    h1: 'National Park Packages',
    desc: "Chitwan, Sagarmatha, Langtang & Nepal's protected natural wonders",
    icon: '🌿', gradient: 'from-green-800 to-teal-700', badge: 'bg-green-100 text-green-800',
    bgImage: 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1600&q=80&auto=format&fit=crop',
  },
  wildlife_reserve: {
    title: 'Wildlife Reserve Packages — Nepal Safaris',
    h1: 'Wildlife Reserve Packages',
    desc: "Bardia, Parsa & Nepal's remote wildlife sanctuaries",
    icon: '🐅', gradient: 'from-amber-700 to-yellow-700', badge: 'bg-amber-100 text-amber-800',
    bgImage: 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=1600&q=80&auto=format&fit=crop',
  },
  lake: {
    title: 'Lakes Packages — Rara, Phewa, Nepal',
    h1: 'Lakes Packages',
    desc: "Rara, Phewa, Begnas & Nepal's stunning high-altitude mountain lakes",
    icon: '🏞', gradient: 'from-sky-800 to-cyan-700', badge: 'bg-sky-100 text-sky-700',
    bgImage: 'https://images.unsplash.com/photo-1606210695818-fbe9f66e7df0?w=1600&q=80&auto=format&fit=crop',
  },
}

const cfg     = config[props.type] || config.adventure
const baseUrl = routeMap[props.type] || '/packages'

function search() {
  const params = { q: q.value, difficulty: difficulty.value, sort: sort.value }
  Object.keys(params).forEach(k => !params[k] && delete params[k])
  router.get(baseUrl, params, { preserveState: true, replace: true })
}
watch([difficulty, sort], search)
</script>

<template>
  <Head :title="cfg.title + ' | PathToSnow Nepal'" />
  <AppLayout>
    <!-- Hero -->
    <section
      class="relative overflow-hidden min-h-[280px] flex items-center"
      :style="cfg.bgImage
        ? `background-image:url('${cfg.bgImage}');background-size:cover;background-position:center;`
        : ''"
    >
      <!-- Gradient overlay: keeps text readable over the photo -->
      <div :class="`absolute inset-0 bg-gradient-to-r ${cfg.gradient} opacity-75`"/>
      <div class="absolute inset-0 bg-black/40"/>
      <div class="relative container-main py-16 text-white">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-5xl drop-shadow-lg">{{ cfg.icon }}</span>
          <span :class="cfg.badge" class="badge text-sm px-3 py-1 shadow-sm">Nepal Experiences</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3 drop-shadow-md">{{ cfg.h1 }}</h1>
        <p class="text-white/90 text-lg max-w-2xl drop-shadow">{{ cfg.desc }}</p>
      </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
      <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
        <Link href="/" class="hover:text-emerald-600">Home</Link>
        <span>/</span>
        <Link href="/packages" class="hover:text-emerald-600">Packages</Link>
        <span>/</span>
        <span class="text-slate-800 font-medium">{{ cfg.h1 }}</span>
      </div>
    </nav>

    <!-- Three-column layout -->
    <div class="container-main py-10 flex gap-8 items-start">

      <LeftSidebar :active-type="type" />

      <main class="flex-1 min-w-0">
        <!-- Filters -->
        <div class="flex flex-wrap gap-3 mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-200">
          <input v-model="q" @keydown.enter="search" placeholder="Search packages…"
                 class="input max-w-xs flex-1 min-w-48" aria-label="Search packages"/>
          <select v-model="difficulty" class="input w-48" aria-label="Filter by difficulty">
            <option value="">All Difficulties</option>
            <option value="easy">Easy</option>
            <option value="moderate">Moderate</option>
            <option value="challenging">Challenging</option>
            <option value="strenuous">Strenuous</option>
          </select>
          <select v-model="sort" class="input w-44" aria-label="Sort packages">
            <option value="featured">Featured first</option>
            <option value="price_asc">Price: Low → High</option>
            <option value="price_desc">Price: High → Low</option>
            <option value="duration_asc">Duration: Short → Long</option>
          </select>
          <button @click="search" class="btn-primary">Search</button>
        </div>

        <p v-if="packages.total !== undefined" class="text-sm text-slate-500 mb-6">
          Showing {{ packages.data.length }} of {{ packages.total }} packages
        </p>

        <div v-if="packages.data.length" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <PackageCard v-for="pkg in packages.data" :key="pkg.id" :package="pkg" />
        </div>
        <div v-else class="text-center py-24 bg-slate-50 rounded-2xl border border-slate-200">
          <p class="text-4xl mb-4">🔍</p>
          <p class="text-slate-500 text-lg mb-4">No packages found</p>
          <a :href="baseUrl" class="btn-outline">Clear all filters</a>
        </div>

        <Pagination :meta="packages" />
      </main>

      <RightSidebar />

    </div>
  </AppLayout>
</template>
