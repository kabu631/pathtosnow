<template>
<!-- resources/js/Components/PostCard.vue -->
<Link :href="`/blog/${post.slug}`" class="card group block focus:outline-none focus:ring-2 focus:ring-emerald-500">
  <div class="aspect-video bg-slate-100 overflow-hidden">
    <img v-if="post.cover_image" :src="post.cover_image" :alt="post.title"
         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
    <img v-else :src="'https://images.unsplash.com/photo-1501555088652-021faa106b9b?w=600&q=80&random=' + post.id" :alt="post.title"
         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
  </div>
  <div class="p-4">
    <span :class="`post-${post.post_type}`" class="badge mb-2">{{ typeLabel(post.post_type) }}</span>
    <h3 class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-emerald-700 transition-colors mb-2 leading-snug">
      {{ post.title }}
    </h3>
    <p v-if="post.excerpt" class="text-xs text-slate-500 line-clamp-2 mb-3 leading-relaxed">{{ post.excerpt }}</p>
    <div class="flex items-center justify-between text-xs text-slate-400 pt-3 border-t border-slate-100">
      <span>📖 {{ post.read_time }} min read</span>
      <span v-if="post.author">{{ post.author.name }}</span>
    </div>
  </div>
</Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ post: Object })
const typeIcon   = { blog:'📝', food:'🍜', culture:'🎭', festival:'🎉', city_tour:'🏙', travel_guide:'📖' }
const typeLabels = { blog:'Blog', food:'Food & Drink', culture:'Culture', festival:'Festivals', city_tour:'City Tour', travel_guide:'Travel Guide' }
function typeLabel(t) { return typeLabels[t] || t }
</script>
