<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps({
  activeType: { type: String, default: '' },
})

const page = usePage()

const experiences = computed(() => {
  const colors = [
    'text-red-600 bg-red-50', 'text-emerald-700 bg-emerald-50',
    'text-violet-700 bg-violet-50', 'text-green-700 bg-green-50',
    'text-amber-700 bg-amber-50', 'text-sky-700 bg-sky-50',
    'text-pink-700 bg-pink-50'
  ]
  return (page.props.navPackageTypes || []).map((t, index) => ({
    type: t.slug,
    icon: t.icon_emoji,
    label: t.name,
    href: `/packages/type/${t.slug}`,
    color: colors[index % colors.length]
  }))
})
</script>

<template>
  <aside class="hidden lg:block w-52 flex-shrink-0 self-start sticky top-24 max-h-[calc(100vh-7rem)] overflow-y-auto sidebar-scroll space-y-2 pr-1" aria-label="Experience categories">
      <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2 mb-3">Packages</h3>

      <Link href="/packages"
        :class="['flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                 !activeType ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900']"
        id="sidebar-all-packages">
        <span class="text-base">🗺️</span> All Packages
      </Link>

      <Link v-for="exp in experiences" :key="exp.type" :href="exp.href" :id="`sidebar-${exp.type}`"
        :class="['flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                 activeType === exp.type ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900']">
        <span :class="['w-7 h-7 rounded-lg flex items-center justify-center text-sm flex-shrink-0',
                       activeType === exp.type ? 'bg-white/20' : exp.color]">{{ exp.icon }}</span>
        {{ exp.label }}
      </Link>

      <div class="pt-4 mt-2 border-t border-slate-100">
        <Link href="/shop" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all">
          <span class="w-7 h-7 rounded-lg flex items-center justify-center text-sm bg-orange-50 text-orange-700 flex-shrink-0">🎒</span>
          Gear Shop
        </Link>
      </div>
  </aside>
</template>

<style scoped>
.sidebar-scroll { scrollbar-width: none; }
.sidebar-scroll::-webkit-scrollbar { display: none; }
</style>
