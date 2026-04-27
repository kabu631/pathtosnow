<template>
<AppLayout>
  <Head><title>All Nepal Experiences — Packages | PathToSnow</title></Head>

  <!-- Hero -->
  <section class="relative overflow-hidden min-h-[260px] flex items-center bg-slate-900" style="background-image:url('https://images.unsplash.com/photo-1544634076-a90160ddf44a?q=80&w=2000&auto=format&fit=crop');background-size:cover;background-position:center;">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-sky-900/80"></div>
    <div class="absolute inset-0 bg-black/40"/>
    <div class="relative container-main py-16 text-white">
      <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3 drop-shadow-md">All Nepal experiences</h1>
      <p class="text-emerald-50 text-lg max-w-2xl drop-shadow">Adventures, treks, valley visits, parks, wildlife and lake expeditions</p>
    </div>
  </section>

  <!-- Three-column layout -->
  <div class="container-main py-10 flex gap-8 items-start">

    <LeftSidebar :active-type="filters.type || ''" />

    <main class="flex-1 min-w-0">
      <!-- Type tabs (mobile) -->
      <div class="flex flex-wrap gap-2 mb-6 lg:hidden">
        <Link href="/packages" :class="tabClass(!filters.type)">All</Link>
        <Link v-for="(label, type) in typeLabels" :key="type"
              :href="`/packages?type=${type}`" :class="tabClass(filters.type === type)">
          {{ typeEmoji[type] }} {{ label }}
        </Link>
      </div>

      <p class="text-sm text-slate-500 mb-4">Showing {{ packages.data.length }} of {{ packages.total }} packages</p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <PackageCard v-for="pkg in packages.data" :key="pkg.id" :package="pkg"/>
      </div>

      <div v-if="!packages.data.length" class="text-center py-16 text-gray-400 bg-slate-50 rounded-2xl border border-slate-200 mt-4">
        <p class="text-4xl mb-3">🏔</p>
        <p class="text-sm">No packages found for this filter.</p>
      </div>

      <Pagination :meta="packages" />
    </main>

    <RightSidebar />

  </div>
</AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PackageCard from '@/Components/PackageCard.vue'
import LeftSidebar from '@/Components/LeftSidebar.vue'
import RightSidebar from '@/Components/RightSidebar.vue'
import Pagination from '@/Components/Pagination.vue'

defineProps({ packages: Object, counts: Object, filters: Object })

const typeLabels = { adventure:'Adventure', valley_visit:'Valley Visit', trekking:'Trekking', national_park:'National Parks', wildlife_reserve:'Wildlife Reserves', lake:'Lakes' }
const typeEmoji  = { adventure:'⚡', valley_visit:'🏛', trekking:'🏔', national_park:'🌲', wildlife_reserve:'🐅', lake:'🏞' }

function tabClass(active) {
  return `px-4 py-2 rounded-full text-xs font-medium border transition-colors ${active ? 'bg-emerald-600 text-white border-emerald-600' : 'border-gray-300 text-gray-600 hover:border-gray-400 bg-white'}`
}
</script>
