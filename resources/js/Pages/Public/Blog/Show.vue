<template>
<AppLayout>
  <Head>
    <title>{{ post.meta_title || post.title + ' | PathToSnow Nepal' }}</title>
    <meta name="description" :content="post.meta_description || post.excerpt"/>
    <meta property="og:title" :content="post.title"/>
    <meta property="og:image" :content="post.cover_image"/>
    <meta property="og:type" content="article"/>
  </Head>

  <!-- Hero -->
  <div class="relative h-64 md:h-96 bg-slate-800 overflow-hidden">
    <img v-if="post.cover_image" :src="post.cover_image" :alt="post.title"
         class="w-full h-full object-cover opacity-70"/>
    <div v-else class="w-full h-full bg-gradient-to-br from-sky-900 to-emerald-900"/>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"/>
    <div class="absolute inset-0 flex items-end">
      <div class="container-main pb-8 w-full">
        <span :class="`post-${post.post_type}`" class="badge mb-3 shadow-sm">{{ typeLabels[post.post_type] || post.post_type }}</span>
        <h1 class="text-2xl md:text-4xl font-black text-white leading-tight">{{ post.title }}</h1>
        <div class="flex items-center gap-4 mt-3 text-slate-300 text-sm">
          <span v-if="post.author?.name">✍️ {{ post.author.name }}</span>
          <span v-if="post.read_time">📖 {{ post.read_time }} min read</span>
          <time v-if="post.published_at" :datetime="post.published_at">📅 {{ fmt(post.published_at) }}</time>
        </div>
      </div>
    </div>
  </div>

  <!-- Breadcrumb -->
  <nav class="border-b border-slate-100 bg-slate-50" aria-label="Breadcrumb">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600">Home</Link>
      <span>/</span>
      <Link href="/blog" class="hover:text-emerald-600">Blog</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium truncate">{{ post.title }}</span>
    </div>
  </nav>

  <div class="container-main py-10">
    <div class="grid lg:grid-cols-3 gap-10">

      <!-- Article Content -->
      <article class="lg:col-span-2">
        <div v-if="post.excerpt" class="text-lg text-slate-600 font-medium leading-relaxed mb-8 pb-8 border-b border-slate-100">
          {{ post.excerpt }}
        </div>
        <div class="prose prose-slate prose-sm sm:prose max-w-none" v-html="post.content"/>

        <!-- Tags -->
        <div v-if="post.tags?.length" class="mt-8 pt-8 border-t border-slate-100">
          <p class="text-xs text-slate-500 mb-2 font-medium">Tags:</p>
          <div class="flex flex-wrap gap-2">
            <span v-for="tag in post.tags" :key="tag" class="badge bg-slate-100 text-slate-700 hover:bg-emerald-100 hover:text-emerald-700 cursor-default transition-colors">
              {{ tag }}
            </span>
          </div>
        </div>

        <!-- Related Package CTA -->
        <div v-if="post.relatedPackage" class="mt-8 bg-gradient-to-br from-emerald-50 to-sky-50 border border-emerald-200 rounded-2xl p-6">
          <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wider mb-2">Related Experience</p>
          <h3 class="text-lg font-bold text-slate-900 mb-3">{{ post.relatedPackage.name }}</h3>
          <Link :href="`/packages/${post.relatedPackage.slug}`" class="btn-primary text-sm">
            View package →
          </Link>
        </div>
      </article>

      <!-- Sidebar -->
      <aside>
        <!-- Related posts -->
        <div v-if="related?.length" class="mb-8">
          <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-200">Related articles</h2>
          <div class="space-y-4">
            <Link v-for="r in related" :key="r.id" :href="`/blog/${r.slug}`" class="flex gap-3 group">
              <div class="w-16 h-16 flex-shrink-0 bg-slate-100 rounded-xl overflow-hidden">
                <img v-if="r.cover_image" :src="r.cover_image" :alt="r.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                <div v-else class="w-full h-full flex items-center justify-center text-xl">📖</div>
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-800 group-hover:text-emerald-700 line-clamp-2 transition-colors">{{ r.title }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ r.read_time }} min read</p>
              </div>
            </Link>
          </div>
        </div>

        <!-- Browse categories -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
          <h2 class="text-sm font-bold text-slate-900 mb-3">Browse guides</h2>
          <div class="space-y-2">
            <Link v-for="g in guideLinks" :key="g.href" :href="g.href"
                  class="flex items-center gap-2 text-sm text-slate-700 hover:text-emerald-700 py-1.5 transition-colors">
              <span>{{ g.icon }}</span> {{ g.label }}
            </Link>
          </div>
        </div>
      </aside>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ post: Object, related: Array })

const typeLabels = {
  blog: 'Blog', food: 'Food & Drink', culture: 'Culture',
  festival: 'Festival', city_tour: 'City Tour', travel_guide: 'Travel Guide',
}

const guideLinks = [
  { href: '/travel-guide/food',       label: 'Food & Drink', icon: '🍜' },
  { href: '/travel-guide/culture',    label: 'Culture',      icon: '🎭' },
  { href: '/travel-guide/festivals',  label: 'Festivals',    icon: '🎉' },
  { href: '/travel-guide/city-tours', label: 'City Tours',   icon: '🏙' },
]

function fmt(d) {
  return d ? new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) : ''
}
</script>
