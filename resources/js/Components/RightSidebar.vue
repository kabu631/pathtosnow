<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()

const dynamicLinks = computed(() => {
  return (page.props.navPostTypes || []).map(t => ({
    icon: t.icon_emoji,
    label: t.name,
    href: `/travel-guide/type/${t.slug}`
  }))
})
</script>

<template>
  <aside class="hidden xl:block w-52 flex-shrink-0" aria-label="Travel guide and blog quick links">
    <div class="sticky top-24 space-y-6">

      <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-3">Blog & Guides</h3>
        <div class="space-y-1">
          <Link href="/blog"
            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all">
            <span>✍️</span> All Posts
          </Link>
          <Link v-for="g in dynamicLinks" :key="g.href" :href="g.href"
            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm font-medium text-slate-600 hover:bg-sky-50 hover:text-sky-700 transition-all">
            <span>{{ g.icon }}</span> {{ g.label }}
          </Link>
        </div>
      </div>

      <div class="bg-gradient-to-br from-emerald-600 to-sky-600 rounded-2xl p-4 text-white text-center">
        <div class="text-2xl mb-2">🏔</div>
        <p class="text-xs font-bold mb-1">Plan your trek</p>
        <p class="text-xs text-emerald-100 mb-3">Talk to a local guide</p>
        <a href="mailto:hello@trekbazar.com"
           class="block bg-white text-emerald-700 text-xs font-bold py-2 rounded-xl hover:bg-emerald-50 transition-colors">
          Contact Us
        </a>
      </div>

    </div>
  </aside>
</template>
