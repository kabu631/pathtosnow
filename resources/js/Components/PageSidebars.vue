<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  activeType: { type: String, default: '' }, // e.g. 'adventure'
})

const experiences = [
  { type: 'adventure',        icon: '⚡', label: 'Adventure',        href: '/packages/adventure',     color: 'text-red-600 bg-red-50' },
  { type: 'trekking',         icon: '🏔', label: 'Trekking',         href: '/packages/trekking',      color: 'text-emerald-700 bg-emerald-50' },
  { type: 'valley_visit',     icon: '🏛', label: 'Valley Visit',     href: '/packages/valley-visits', color: 'text-violet-700 bg-violet-50' },
  { type: 'national_park',    icon: '🌿', label: 'National Parks',   href: '/packages/national-parks',color: 'text-green-700 bg-green-50' },
  { type: 'wildlife_reserve', icon: '🐅', label: 'Wildlife Reserves',href: '/packages/wildlife',      color: 'text-amber-700 bg-amber-50' },
  { type: 'lake',             icon: '🏞', label: 'Lakes',            href: '/packages/lakes',         color: 'text-sky-700 bg-sky-50' },
]

const guideLinks = [
  { icon: '🍜', label: 'Food & Drink',  href: '/travel-guide/food' },
  { icon: '🎭', label: 'Culture',       href: '/travel-guide/culture' },
  { icon: '🎉', label: 'Festivals',     href: '/travel-guide/festivals' },
  { icon: '🏙', label: 'City Tours',    href: '/travel-guide/city-tours' },
  { icon: '📖', label: 'All Guides',    href: '/travel-guide' },
]

const blogLinks = [
  { icon: '✍️', label: 'All Posts',     href: '/blog' },
  { icon: '🍜', label: 'Food & Drink',  href: '/blog?type=food' },
  { icon: '🎭', label: 'Culture',       href: '/blog?type=culture' },
  { icon: '🎉', label: 'Festivals',     href: '/blog?type=festival' },
  { icon: '🏙', label: 'City Tours',    href: '/blog?type=city_tour' },
]
</script>

<template>
  <!-- ── LEFT SIDEBAR — Experiences ── -->
  <aside class="hidden lg:block w-52 flex-shrink-0" aria-label="Experience categories">
    <div class="sticky top-24 space-y-2">
      <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-3">Experiences</h3>

      <Link
        href="/packages"
        :class="['flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                 !activeType
                   ? 'bg-emerald-600 text-white shadow-sm'
                   : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900']"
        id="sidebar-all-packages"
      >
        <span class="text-base">🗺️</span> All Packages
      </Link>

      <Link
        v-for="exp in experiences"
        :key="exp.type"
        :href="exp.href"
        :id="`sidebar-${exp.type}`"
        :class="['flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                 activeType === exp.type
                   ? 'bg-emerald-600 text-white shadow-sm'
                   : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900']"
      >
        <span :class="['w-7 h-7 rounded-lg flex items-center justify-center text-sm flex-shrink-0',
                       activeType === exp.type ? 'bg-white/20' : exp.color]">
          {{ exp.icon }}
        </span>
        {{ exp.label }}
      </Link>

      <div class="pt-4 mt-2 border-t border-slate-100">
        <Link href="/shop" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
          <span class="w-7 h-7 rounded-lg flex items-center justify-center text-sm bg-orange-50 text-orange-700 flex-shrink-0">🎒</span>
          Gear Shop
        </Link>
      </div>
    </div>
  </aside>

  <!-- ── RIGHT SIDEBAR — Travel Guide + Blog ── -->
  <aside class="hidden xl:block w-52 flex-shrink-0" aria-label="Travel guide and blog quick links">
    <div class="sticky top-24 space-y-6">

      <!-- Travel Guide -->
      <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-3">Travel Guide</h3>
        <div class="space-y-1">
          <Link
            v-for="g in guideLinks"
            :key="g.href"
            :href="g.href"
            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-sky-50 hover:text-sky-700 transition-all"
          >
            <span>{{ g.icon }}</span> {{ g.label }}
          </Link>
        </div>
      </div>

      <!-- Blog -->
      <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-3">Blog</h3>
        <div class="space-y-1">
          <Link
            v-for="b in blogLinks"
            :key="b.href"
            :href="b.href"
            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all"
          >
            <span>{{ b.icon }}</span> {{ b.label }}
          </Link>
        </div>
      </div>

      <!-- Quick CTA -->
      <div class="bg-gradient-to-br from-emerald-600 to-sky-600 rounded-2xl p-4 text-white text-center">
        <div class="text-2xl mb-2">🏔</div>
        <p class="text-xs font-bold mb-1">Plan your trek</p>
        <p class="text-xs text-emerald-100 mb-3">Talk to a local guide</p>
        <a href="mailto:hello@trekbazar.com" class="block bg-white text-emerald-700 text-xs font-bold py-2 rounded-xl hover:bg-emerald-50 transition-colors">
          Contact Us
        </a>
      </div>

    </div>
  </aside>
</template>
