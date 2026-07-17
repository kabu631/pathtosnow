<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const page = usePage()

const searchQ = ref('')
function doSearch() {
  const q = searchQ.value.trim()
  if (!q) return
  // Search within the current path
  const base = window.location.pathname || '/travel-guide'
  router.visit(`${base}?q=${encodeURIComponent(q)}`, { preserveState: false })
}

const dynamicLinks = computed(() => {
  return (page.props.navPostTypes || [])
    .filter(t => t.slug !== 'blog' && t.type_key !== 'blog')
    .map(t => ({
      icon: t.icon_emoji,
      label: t.name,
      href: `/travel-guide/type/${t.slug}`
    }))
})
</script>

<template>
  <aside class="hidden lg:block w-52 flex-shrink-0 self-start sticky top-24 max-h-[calc(100vh-7rem)] overflow-y-auto sidebar-scroll space-y-6 pr-1" aria-label="Travel guide and blog quick links">

      <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-3">Blog & Guides</h3>

        <!-- Search bar -->
        <form @submit.prevent="doSearch" class="flex gap-1.5 mb-4">
          <input
            v-model="searchQ"
            type="text"
            placeholder="Search guides…"
            class="flex-1 min-w-0 text-xs px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400 bg-white"
            aria-label="Search guides"
          />
          <button type="submit"
            class="px-3 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-lg transition-colors flex-shrink-0">
            Go
          </button>
        </form>

        <div class="space-y-1">
          <Link href="/travel-guide"
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
        <a href="mailto:hello@pathtosnow.com"
           class="block bg-white text-emerald-700 text-xs font-bold py-2 rounded-xl hover:bg-emerald-50 transition-colors">
          Contact Us
        </a>
      </div>

  </aside>
</template>

<style scoped>
.sidebar-scroll { scrollbar-width: none; }
.sidebar-scroll::-webkit-scrollbar { display: none; }
</style>
