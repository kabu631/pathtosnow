<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  meta: {
    type: Object,
    required: true,
    // expects Laravel paginator: { current_page, last_page, links[], from, to, total }
  },
})

const prevLink = computed(() => props.meta.links?.[0] ?? null)
const nextLink = computed(() => props.meta.links?.[props.meta.links.length - 1] ?? null)

// Windowed page numbers: always show first, last, current ±2, with ellipsis gaps
const visiblePages = computed(() => {
  const total   = props.meta.last_page ?? 1
  const current = props.meta.current_page ?? 1
  const keep    = new Set()

  for (let i = 1; i <= total; i++) {
    if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
      keep.add(i)
    }
  }

  const sorted = [...keep].sort((a, b) => a - b)
  const result = []
  let prev = null

  for (const p of sorted) {
    if (prev !== null && p - prev > 1) {
      result.push({ type: 'ellipsis', key: `e-${p}` })
    }
    result.push({ type: 'page', value: p, key: p })
    prev = p
  }
  return result
})

// Get URL for a specific page number from links array
function urlForPage(n) {
  const link = props.meta.links?.find(l => l.label == String(n))
  return link?.url ?? null
}
</script>

<template>
  <div class="mt-10 flex flex-col items-center gap-4">

    <!-- Page counter label -->
    <p class="text-sm text-slate-500 font-medium">
      Page
      <span class="text-emerald-700 font-bold text-base">{{ meta.current_page }}</span>
      /
      <span class="font-bold text-slate-700">{{ meta.last_page }}</span>
      <span class="mx-2 text-slate-300">·</span>
      <span class="text-slate-400">{{ meta.from }}–{{ meta.to }} of {{ meta.total }} packages</span>
    </p>

    <!-- Navigation row -->
    <div class="flex items-center gap-1.5">

      <!-- ← Prev -->
      <component
        :is="prevLink?.url ? Link : 'span'"
        :href="prevLink?.url ?? undefined"
        :class="[
          'inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all select-none border',
          prevLink?.url
            ? 'bg-white border-slate-200 text-slate-700 hover:bg-emerald-50 hover:border-emerald-400 hover:text-emerald-700 shadow-sm'
            : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'
        ]"
        aria-label="Previous page"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Prev
      </component>

      <!-- Page numbers -->
      <template v-for="item in visiblePages" :key="item.key">

        <!-- … ellipsis -->
        <span v-if="item.type === 'ellipsis'"
              class="w-9 h-10 flex items-center justify-center text-slate-400 text-sm">…</span>

        <!-- Number -->
        <component
          v-else
          :is="urlForPage(item.value) && item.value !== meta.current_page ? Link : 'span'"
          :href="urlForPage(item.value) ?? undefined"
          :class="[
            'w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold transition-all select-none border',
            item.value === meta.current_page
              ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-200 scale-110'
              : urlForPage(item.value)
                ? 'bg-white border-slate-200 text-slate-700 hover:bg-emerald-50 hover:border-emerald-400 hover:text-emerald-700 shadow-sm cursor-pointer'
                : 'bg-slate-50 border-slate-100 text-slate-300 cursor-default'
          ]"
          :aria-label="`Page ${item.value}`"
          :aria-current="item.value === meta.current_page ? 'page' : undefined"
        >
          {{ item.value }}
        </component>

      </template>

      <!-- Next → -->
      <component
        :is="nextLink?.url ? Link : 'span'"
        :href="nextLink?.url ?? undefined"
        :class="[
          'inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all select-none border',
          nextLink?.url
            ? 'bg-emerald-600 border-emerald-600 text-white hover:bg-emerald-700 shadow-sm'
            : 'bg-slate-50 border-slate-100 text-slate-300 cursor-not-allowed'
        ]"
        aria-label="Next page"
      >
        Next
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </component>

    </div>

  </div>
</template>
