<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LeftSidebar from '@/Components/LeftSidebar.vue'
import RightSidebar from '@/Components/RightSidebar.vue'
import Pagination from '@/Components/Pagination.vue'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({ posts: Object, filters: Object, type: String, postType: Object })
const q = ref(props.filters?.q ?? '')

const colorMap = {
  blue: 'from-sky-700 to-blue-800', amber: 'from-amber-700 to-orange-600',
  purple: 'from-violet-700 to-purple-800', rose: 'from-rose-700 to-pink-800',
  teal: 'from-teal-700 to-cyan-800', green: 'from-emerald-700 to-green-800',
  orange: 'from-orange-600 to-red-700', slate: 'from-slate-700 to-slate-800',
  cyan: 'from-cyan-600 to-blue-700', red: 'from-red-700 to-orange-800',
  indigo: 'from-indigo-700 to-purple-800', emerald: 'from-emerald-600 to-teal-800'
}

// Fallbacks if not provided
const typeName = props.postType?.name ?? 'Articles'
const typeIcon = props.postType?.icon_emoji ?? '📰'
const heroClass = props.postType?.color ? colorMap[props.postType.color] : 'from-slate-700 to-slate-800'

const page = usePage()

// Left sidebar links - get dynamic links
const guideCategories = computed(() => {
  return (page.props.navPostTypes || []).map(t => ({
    type: t.slug,
    icon: t.icon_emoji,
    label: t.name,
    href: `/travel-guide/type/${t.slug}`
  }))
})

function getPostTypeLabel(slug) {
    const pt = page.props.navPostTypes?.find(t => t.slug === slug || t.type_key === slug);
    return pt ? pt.name : 'Article';
}
function getPostTypeIcon(slug) {
    const pt = page.props.navPostTypes?.find(t => t.slug === slug || t.type_key === slug);
    return pt ? pt.icon_emoji : '📖';
}

function search() {
  const base = props.postType ? `/travel-guide/type/${props.postType.slug}` : '/blog'
  router.visit(base + (q.value ? `?q=${encodeURIComponent(q.value)}` : ''), { preserveState: true, replace: true })
}

function fmt(d) {
  return d ? new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : ''
}
</script>

<template>
  <Head :title="`${typeName} — PathToSnow Nepal`" />
  <AppLayout>

    <!-- Hero -->
    <section class="relative overflow-hidden min-h-[260px] flex items-center"
             :class="props.postType?.hero_image_url ? 'bg-slate-900' : `bg-gradient-to-br ${heroClass}`"
             :style="props.postType?.hero_image_url ? `background-image:url('${props.postType.hero_image_url}');background-size:cover;background-position:center;` : ''">
      
      <div v-if="props.postType?.hero_image_url" :class="`absolute inset-0 bg-${props.postType.color}-900/80`"></div>
      <div class="absolute inset-0 bg-black/20"/>
      
      <div class="relative container-main py-16 text-white">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-5xl drop-shadow-lg">{{ typeIcon }}</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black leading-tight mb-2 drop-shadow-md">{{ typeName }}</h1>
        <p class="text-white/90 text-lg max-w-xl drop-shadow">Discover Nepal's {{ typeName?.toLowerCase() ?? 'stories' }} — curated by locals for travellers</p>
      </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
      <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
        <Link href="/" class="hover:text-sky-600">Home</Link>
        <span>/</span>
        <Link href="/travel-guide" class="hover:text-sky-600">Guide</Link>
        <span>/</span>
        <span class="text-slate-800 font-medium">{{ typeName }}</span>
      </div>
    </nav>

    <!-- Three-column layout -->
    <div class="container-main py-10 flex gap-8 items-start">

      <!-- LEFT sidebar — Experiences -->
      <LeftSidebar />

      <!-- MAIN content -->
      <main class="flex-1 min-w-0">

        <!-- Travel Guide category tabs -->
        <div class="flex flex-wrap gap-2 mb-6">
          <Link href="/travel-guide"
                :class="['px-4 py-1.5 rounded-full text-sm font-medium border-2 transition-colors',
                         type === 'travel_guide' ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-200 text-slate-600 hover:border-emerald-400 bg-white']">
            📖 All Guides
          </Link>
          <Link v-for="cat in guideCategories" :key="cat.type" :href="cat.href"
                :class="['px-4 py-1.5 rounded-full text-sm font-medium border-2 transition-colors',
                         type === cat.type ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-200 text-slate-600 hover:border-emerald-400 bg-white']">
            {{ cat.icon }} {{ cat.label }}
          </Link>
        </div>

        <!-- Search bar -->
        <div class="flex gap-3 mb-8 max-w-md">
          <input v-model="q" @keydown.enter="search"
                 :placeholder="`Search ${typeName?.toLowerCase() ?? 'articles'}…`"
                 class="input flex-1" id="guide-search"
                 :aria-label="`Search ${typeName} articles`"/>
          <button @click="search" class="btn-primary px-5">Search</button>
        </div>

        <!-- Articles grid -->
        <div v-if="posts.data.length" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <Link v-for="post in posts.data" :key="post.id"
                :href="`/blog/${post.slug}`"
                class="group bg-white rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-md transition-all overflow-hidden block">
            <div class="aspect-video bg-slate-100 overflow-hidden">
              <img v-if="post.cover_image" :src="post.cover_image" :alt="post.title"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
              <div v-else class="w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-slate-50 to-slate-100">
                {{ getPostTypeIcon(post.post_type) }}
              </div>
            </div>
            <div class="p-4">
              <span :class="`post-${post.post_type}`" class="badge mb-2 text-xs">{{ getPostTypeLabel(post.post_type) }}</span>
              <h2 class="font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 mb-1 text-sm">
                {{ post.title }}
              </h2>
              <p class="text-xs text-slate-400">{{ post.read_time }} min read · {{ fmt(post.published_at) }}</p>
            </div>
          </Link>
        </div>

        <div v-else class="text-center py-20 bg-slate-50 rounded-2xl border border-slate-200">
          <p class="text-4xl mb-4">🔍</p>
          <p class="text-slate-500 text-lg mb-4">No articles found</p>
          <button @click="q=''; search()" class="btn-outline">Clear search</button>
        </div>

        <!-- Pagination -->
        <Pagination :meta="posts" />

      </main>

      <!-- RIGHT sidebar -->
      <RightSidebar />

    </div>
  </AppLayout>
</template>
