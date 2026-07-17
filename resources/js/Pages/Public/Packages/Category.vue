<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PackageCard from '@/Components/PackageCard.vue'
import LeftSidebar from '@/Components/LeftSidebar.vue'
import Pagination from '@/Components/Pagination.vue'
import { ref, watch } from 'vue'

const props = defineProps({
  packages:   Object,
  filters:    Object,
  packageType: Object,   // DB record from package_types table
})

const q          = ref(props.filters?.q ?? '')
const difficulty = ref(props.filters?.difficulty ?? '')
const sort       = ref(props.filters?.sort ?? 'featured')
const baseUrl    = `/packages/type/${props.packageType.slug}`

function search() {
  const params = { q: q.value, difficulty: difficulty.value, sort: sort.value }
  Object.keys(params).forEach(k => !params[k] && delete params[k])
  router.get(baseUrl, params, { preserveState: true, replace: true })
}
watch([difficulty, sort], search)
</script>

<template>
  <Head :title="`${packageType.name} Packages | PathToSnow Nepal`" />
  <AppLayout>

    <!-- Hero — identical pattern to Adventure.vue but driven by DB -->
    <section
      class="relative overflow-hidden min-h-[280px] flex items-center"
      :style="packageType.hero_image_url
        ? `background-image:url('${packageType.hero_image_url}');background-size:cover;background-position:center;`
        : ''"
    >
      <div :class="`absolute inset-0 bg-gradient-to-r ${packageType.gradient ?? 'from-slate-700 to-slate-800'} opacity-75`"/>
      <div class="absolute inset-0 bg-black/40"/>
      <div class="relative container-main py-16 text-white">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-5xl drop-shadow-lg">{{ packageType.icon_emoji }}</span>
          <span :class="packageType.badge_class" class="badge text-sm px-3 py-1 shadow-sm">Nepal Experiences</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3 drop-shadow-md text-white">{{ packageType.name }} Packages</h1>
        <p class="text-white/90 text-lg max-w-2xl drop-shadow">{{ packageType.description }}</p>
      </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
      <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
        <Link href="/" class="hover:text-emerald-600">Home</Link>
        <span>/</span>
        <Link href="/packages" class="hover:text-emerald-600">Packages</Link>
        <span>/</span>
        <span class="text-slate-800 font-medium">{{ packageType.name }}</span>
      </div>
    </nav>

    <!-- Three-column layout -->
    <div class="container-main py-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">
        <aside class="lg:col-span-3"><LeftSidebar :active-type="packageType.slug" /></aside>

        <main class="lg:col-span-9">
          <!-- Filters -->
          <div class="flex flex-wrap gap-3 mb-6 items-center justify-between">
            <p class="text-sm text-slate-500">
              <span class="font-semibold text-slate-800">{{ packages.total }}</span> packages found
            </p>
            <div class="flex gap-2 flex-wrap">
              <select v-model="difficulty" class="min-w-[160px] text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">All difficulties</option>
                <option value="easy">Easy</option>
                <option value="moderate">Moderate</option>
                <option value="challenging">Challenging</option>
                <option value="strenuous">Strenuous</option>
              </select>
              <select v-model="sort" class="min-w-[160px] text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="featured">Featured first</option>
                <option value="price_asc">Price ↑</option>
                <option value="price_desc">Price ↓</option>
                <option value="duration_asc">Duration ↑</option>
              </select>
              <form @submit.prevent="search" class="flex gap-1">
                <input v-model="q" type="text" placeholder="Search…" class="text-sm border border-slate-200 rounded-lg px-3 py-2 w-36 focus:outline-none focus:ring-2 focus:ring-emerald-400"/>
                <button class="px-3 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700">Go</button>
              </form>
            </div>
          </div>

          <div v-if="packages.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <PackageCard v-for="pkg in packages.data" :key="pkg.id" :package="pkg"/>
          </div>
          <div v-else class="text-center py-16 text-slate-400">
            <p class="text-4xl mb-3">🔍</p>
            <p class="font-medium">No packages found for this category.</p>
            <Link href="/packages" class="text-emerald-600 text-sm mt-2 inline-block">← All Packages</Link>
          </div>

          <Pagination v-if="packages.last_page > 1" :links="packages.links" class="mt-8"/>
        </main>

      </div>
    </div>
  </AppLayout>
</template>
