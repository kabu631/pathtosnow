<template>
<AppLayout>
  <Head>
    <title>Nepal Travel Guide — Food, Culture, Festivals & City Tours | PathToSnow</title>
    <meta name="description" content="Your complete guide to Nepal's culture, festivals and food. Curated by local experts at PathToSnow."/>
    <link rel="canonical" href="https://pathtosnow.com/travel-guide"/>
  </Head>

  <!-- Hero -->
  <section class="relative overflow-hidden bg-gradient-to-br from-sky-900 via-sky-800 to-emerald-900 py-20 md:py-28">
    <!-- Decorative blobs -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M0 40L40 0H20L0 20M40 40V20L20 40\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>

    <div class="relative container-main text-center">
      <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur border border-white/20 text-sky-100 text-sm font-medium px-4 py-2 rounded-full mb-6">
        <span class="w-2 h-2 bg-sky-300 rounded-full animate-pulse"></span>
        Nepal's #1 local travel knowledge
      </div>
      <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6">
        Nepal <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-emerald-300">Travel Guide</span>
      </h1>
      <p class="text-sky-100 text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
        Local knowledge on food, culture, festivals and city tours — written by a Nepali, for the world.
      </p>

      <!-- Quick search bar -->
      <div class="flex gap-3 max-w-md mx-auto">
        <div class="flex-1 relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
          <input
            v-model="q"
            @keydown.enter="search"
            placeholder="Search guides…"
            class="w-full pl-10 pr-4 py-3.5 rounded-xl bg-white/95 backdrop-blur text-slate-800 placeholder-slate-400 text-sm font-medium shadow-lg focus:outline-none focus:ring-2 focus:ring-sky-400"
            id="guide-search"
            aria-label="Search travel guides"
          />
        </div>
        <button
          @click="search"
          class="bg-emerald-500 hover:bg-emerald-400 text-white px-6 py-3.5 rounded-xl font-semibold text-sm shadow-lg transition-colors"
        >
          Search
        </button>
      </div>
    </div>
  </section>

  <!-- Category Cards -->
  <section class="py-16 bg-white" aria-labelledby="categories-heading">
    <div class="container-main">
      <div class="text-center mb-10">
        <h2 id="categories-heading" class="text-3xl font-black text-slate-900 mb-2">Explore by topic</h2>
        <p class="text-slate-500">Choose a category to dive deeper into Nepal's rich tapestry</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <Link
          v-for="cat in guideCategories"
          :key="cat.href"
          :href="cat.href"
          :id="`guide-cat-${cat.key}`"
          class="group relative rounded-2xl overflow-hidden border-2 border-transparent hover:border-sky-300 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl block"
          :class="cat.bgClass"
        >
          <!-- Background pattern overlay -->
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" :class="cat.hoverOverlay"></div>

          <div class="relative p-6 text-center">
            <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform duration-300">{{ cat.icon }}</div>
            <h3 class="font-bold text-slate-900 group-hover:text-sky-800 transition-colors mb-1">{{ cat.label }}</h3>
            <p class="text-xs text-slate-500 leading-relaxed hidden sm:block">{{ cat.tagline }}</p>
            <div class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-sky-600 opacity-0 group-hover:opacity-100 transition-opacity">
              Explore <span>→</span>
            </div>
            <!-- Article count badge -->
            <div
              v-if="(sections[cat.key]||[]).length > 0"
              class="absolute top-3 right-3 bg-sky-600 text-white text-xs font-bold px-2 py-0.5 rounded-full"
            >
              {{ (sections[cat.key]||[]).length }}
            </div>
          </div>
        </Link>
      </div>
    </div>
  </section>

  <!-- Content Sections -->
  <div class="bg-slate-50">
    <div class="container-main py-14 space-y-16">

      <!-- Each category section -->
      <template v-for="cat in guideCategories" :key="cat.key">
        <section v-if="(sections[cat.key]||[]).length > 0" :aria-labelledby="`section-${cat.key}`">
          <!-- Section header -->
          <div class="flex items-end justify-between mb-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 flex items-center justify-center rounded-2xl text-2xl shadow-sm" :class="cat.iconBg">
                {{ cat.icon }}
              </div>
              <div>
                <h2 :id="`section-${cat.key}`" class="text-2xl font-black text-slate-900">{{ cat.label }}</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ cat.description }}</p>
              </div>
            </div>
            <Link
              :href="cat.href"
              class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-sky-600 hover:text-sky-700 transition-colors group"
            >
              View all
              <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </Link>
          </div>

          <!-- Article grid -->
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <Link
              v-for="post in sections[cat.key]"
              :key="post.id"
              :href="`/blog/${post.slug}`"
              class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-lg hover:border-sky-200 transition-all duration-300 hover:-translate-y-1 block"
            >
              <div class="h-40 overflow-hidden relative">
                <img
                  v-if="post.cover_image"
                  :src="post.cover_image"
                  :alt="post.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div
                  v-else
                  class="w-full h-full flex items-center justify-center text-4xl"
                  :class="cat.bgClass"
                >
                  {{ cat.icon }}
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
              </div>
              <div class="p-4">
                <h3 class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-sky-700 transition-colors leading-snug">
                  {{ post.title }}
                </h3>
                <p v-if="post.read_time" class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                  <span>⏱</span> {{ post.read_time }} min read
                </p>
              </div>
            </Link>
          </div>

          <!-- Mobile "view all" -->
          <div class="mt-4 sm:hidden text-center">
            <Link :href="cat.href" class="text-sm font-semibold text-sky-600 hover:underline">
              All {{ cat.label }} articles →
            </Link>
          </div>
        </section>
      </template>

      <!-- Empty state — no posts at all -->
      <div v-if="totalPosts === 0" class="py-24 text-center">
        <div class="text-7xl mb-6">📖</div>
        <h2 class="text-2xl font-bold text-slate-800 mb-3">Travel guides coming soon</h2>
        <p class="text-slate-500 max-w-md mx-auto mb-8">
          We're busy crafting detailed guides about Nepal's food, culture, festivals and cities. Check back soon!
        </p>
        <div class="flex flex-wrap justify-center gap-3">
          <Link v-for="cat in guideCategories" :key="cat.key" :href="cat.href"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-sky-300 rounded-xl text-sm font-medium text-slate-700 hover:text-sky-700 transition-all shadow-sm">
            <span>{{ cat.icon }}</span> {{ cat.label }}
          </Link>
        </div>
      </div>

    </div>
  </div>

  <!-- CTA strip -->
  <section class="bg-gradient-to-br from-sky-600 to-emerald-600 py-16">
    <div class="container-main text-center text-white">
      <h2 class="text-3xl font-black mb-3">Ready to explore Nepal?</h2>
      <p class="text-sky-100 mb-8 max-w-lg mx-auto">Browse all travel packages and book your Himalayan experience directly with local guides.</p>
      <div class="flex flex-wrap justify-center gap-4">
        <Link href="/packages" class="bg-white text-sky-700 hover:bg-sky-50 font-bold px-8 py-3 rounded-xl shadow transition-all">
          Browse packages →
        </Link>
        <Link href="/blog" class="bg-white/10 hover:bg-white/20 border border-white/30 font-semibold px-8 py-3 rounded-xl transition-all">
          📝 Read the Blog
        </Link>
      </div>
    </div>
  </section>

</AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  sections: { type: Object, default: () => ({}) }
})

const q = ref('')

function search() {
  if (q.value.trim()) {
    router.visit(`/blog?q=${encodeURIComponent(q.value)}`, { preserveState: true })
  }
}

const guideCategories = [
  {
    key: 'food',
    icon: '🍜',
    label: 'Food & Drink',
    tagline: 'Dal bhat, momos & Newari feasts',
    description: 'Dal bhat, momos, Newari cuisine and street food adventures',
    href: '/travel-guide/food',
    bgClass: 'bg-amber-50',
    hoverOverlay: 'bg-amber-100/40',
    iconBg: 'bg-amber-100',
  },
  {
    key: 'culture',
    icon: '🎭',
    label: 'Culture',
    tagline: 'Traditions & ancient heritage',
    description: 'Traditions, customs and Nepal\'s rich living heritage',
    href: '/travel-guide/culture',
    bgClass: 'bg-violet-50',
    hoverOverlay: 'bg-violet-100/40',
    iconBg: 'bg-violet-100',
  },
  {
    key: 'festival',
    icon: '🎉',
    label: 'Festivals',
    tagline: 'Dashain, Tihar & Holi',
    description: 'Dashain, Tihar, Holi, Indra Jatra and more',
    href: '/travel-guide/festivals',
    bgClass: 'bg-rose-50',
    hoverOverlay: 'bg-rose-100/40',
    iconBg: 'bg-rose-100',
  },
  {
    key: 'city_tour',
    icon: '🏙',
    label: 'City Tours',
    tagline: 'Kathmandu, Pokhara & beyond',
    description: 'Kathmandu Valley, Pokhara, Chitwan and hidden gems',
    href: '/travel-guide/city-tours',
    bgClass: 'bg-teal-50',
    hoverOverlay: 'bg-teal-100/40',
    iconBg: 'bg-teal-100',
  },
  {
    key: 'travel_guide',
    icon: '📖',
    label: 'Travel Tips',
    tagline: 'Visa, safety & transport',
    description: 'Visa info, safety tips, money, transport and practical advice',
    href: '/blog?type=travel_guide',
    bgClass: 'bg-sky-50',
    hoverOverlay: 'bg-sky-100/40',
    iconBg: 'bg-sky-100',
  },
]

const totalPosts = computed(() => {
  return Object.values(props.sections || {}).reduce((sum, arr) => sum + (arr?.length || 0), 0)
})
</script>
