<template>
<AppLayout>
  <Head>
    <title>Trekking Gear Shop — Nepal Outdoor Equipment | PathToSnow</title>
    <meta name="description" content="Shop authentic trekking gear for the Himalayas — backpacks, boots, sleeping bags and more. Shipped from Kathmandu."/>
  </Head>

  <!-- Hero -->
  <section class="relative overflow-hidden min-h-[350px] flex items-center bg-slate-900" style="background-image:url('/images/gear-hero.jpg');background-size:cover;background-position:center;">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
    <div class="relative container-main py-20 text-white">
      <div class="flex items-center gap-3 mb-6">
        <span class="text-3xl drop-shadow-lg">🏔️</span>
        <span class="bg-emerald-500 text-white text-sm px-4 py-1.5 rounded-full font-bold shadow-lg uppercase tracking-wider">Gear for the Himalayas</span>
      </div>
      <h1 class="text-5xl md:text-6xl font-black leading-tight mb-4 drop-shadow-[0_4px_4px_rgba(0,0,0,0.5)] text-white">Trekking Gear Shop</h1>
      <p class="text-white text-xl max-w-2xl drop-shadow-md leading-relaxed">Everything you need for the Himalayas — curated by experienced local guides. Shipped from Kathmandu.</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600">Home</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium">Gear Shop</span>
    </div>
  </nav>

  <div class="container-main py-10">
    <div class="grid lg:grid-cols-4 gap-8">

      <!-- Sidebar: Categories -->
      <aside class="hidden lg:block">
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sticky top-20">
          <h2 class="text-sm font-bold text-slate-900 mb-3">Categories</h2>
          <div class="space-y-1">
            <Link href="/shop"
                  :class="['flex items-center px-3 py-2 rounded-xl text-sm font-medium transition-colors',
                           !filters.category ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700']">
              🎒 All gear
            </Link>
            <Link v-for="cat in categories" :key="cat.id"
                  :href="`/shop?category=${cat.slug}`"
                  :class="['flex items-center px-3 py-2 rounded-xl text-sm font-medium transition-colors',
                           filters.category===cat.slug ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700']">
              {{ cat.name }}
            </Link>
          </div>
        </div>
      </aside>

      <!-- Products grid -->
      <div class="lg:col-span-3">
        <div class="flex items-center justify-between mb-5">
          <p class="text-sm text-slate-500">
            <span class="font-semibold text-slate-800">{{ products.total }}</span> products
          </p>
          <select v-model="sort" @change="applySort" class="input text-sm w-auto py-2" aria-label="Sort products">
            <option value="newest">Newest first</option>
            <option value="price_asc">Price: Low → High</option>
            <option value="price_desc">Price: High → Low</option>
            <option value="name_asc">Name A–Z</option>
          </select>
        </div>

        <div v-if="products.data.length" class="grid grid-cols-2 md:grid-cols-3 gap-5">
          <Link v-for="p in products.data" :key="p.id" :href="`/shop/${p.slug}`" class="card group block">
            <div class="relative aspect-square bg-slate-50 overflow-hidden">
              <img v-if="p.images?.[0]" :src="p.images[0]" :alt="p.name"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
              <div v-else class="w-full h-full flex items-center justify-center text-5xl">🎒</div>
              <!-- Sale badge -->
              <div v-if="p.compare_price && Number(p.compare_price) > Number(p.price)"
                   class="absolute top-2 left-2 bg-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm">
                SALE
              </div>
            </div>
            <div class="p-4">
              <p class="text-xs text-slate-400 mb-1">{{ p.category?.name }}</p>
              <p class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors leading-snug">{{ p.name }}</p>
              <div class="flex items-center gap-2 mt-2.5">
                <span class="font-black text-emerald-700 text-lg">${{ Number(p.price).toFixed(2) }}</span>
                <span v-if="p.compare_price && Number(p.compare_price) > Number(p.price)"
                      class="text-xs text-slate-400 line-through">${{ Number(p.compare_price).toFixed(2) }}</span>
              </div>
            </div>
          </Link>
        </div>

        <div v-else class="text-center py-24 bg-slate-50 rounded-2xl border border-slate-200">
          <p class="text-5xl mb-4">🔍</p>
          <p class="text-slate-500 text-lg">No products found in this category.</p>
          <Link href="/shop" class="btn-outline mt-4">View all gear</Link>
        </div>

        <!-- Pagination -->
        <div v-if="products.last_page > 1" class="flex justify-center flex-wrap gap-2 mt-10">
          <Link v-for="link in products.links" :key="link.label"
                :href="link.url ?? '#'"
                :class="['px-4 py-2 rounded-xl text-sm font-medium transition-colors',
                         link.active ? 'bg-emerald-600 text-white' : link.url ? 'bg-white border border-slate-200 text-slate-700 hover:border-emerald-300' : 'bg-slate-100 text-slate-400 cursor-default']"
                v-html="link.label"/>
        </div>
      </div>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
const props = defineProps({ products: Object, categories: Array, filters: Object })
const sort = ref(props.filters?.sort || 'newest')
function applySort() { router.get('/shop', { ...props.filters, sort: sort.value }, { preserveState: true }) }
</script>
