<template>
<!-- resources/js/Components/PackageCard.vue -->
<Link :href="`/packages/${package_.slug}`" class="card group block focus:outline-none focus:ring-2 focus:ring-emerald-500">
    <!-- Image -->
    <div class="relative h-52 bg-slate-100 overflow-hidden">
        <img v-if="package_.cover_image" :src="package_.cover_image" :alt="package_.name"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"/>
        <img v-else :src="'https://images.unsplash.com/photo-1522163182402-834f871fd851?w=800&q=80&random=' + package_.id" :alt="package_.name"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"/>
        <!-- Type badge -->
        <span :class="`type-${package_.type}`" class="badge absolute top-3 left-3 shadow-sm">
            {{ typeLabel }}
        </span>
        <!-- Duration pill -->
        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-slate-800 text-xs font-semibold px-2.5 py-1 rounded-lg shadow-sm">
            {{ package_.duration_days }}D
        </span>
        <!-- Featured badge -->
        <div v-if="package_.featured" class="absolute bottom-3 left-3 bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg shadow-sm">
            ⭐ Featured
        </div>
    </div>

    <!-- Body -->
    <div class="p-4">
        <p class="text-xs text-slate-400 mb-1 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ package_.location }}
        </p>
        <h3 class="font-bold text-slate-900 text-sm leading-snug mb-2 group-hover:text-emerald-700 transition-colors line-clamp-2">
            {{ package_.name }}
        </h3>
        <p v-if="package_.short_description" class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-3">
            {{ package_.short_description }}
        </p>
        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
            <div>
                <span class="text-xs text-slate-400">From </span>
                <span class="font-bold text-emerald-700 text-base">NRs {{ Number(package_.price_per_person).toLocaleString() }}</span>
                <span class="text-xs text-slate-400">/person</span>
            </div>
            <span v-if="package_.difficulty" :class="`diff-${package_.difficulty}`" class="capitalize">
                {{ package_.difficulty }}
            </span>
        </div>
    </div>
</Link>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

// Accept both `pkg` and `package` props for backwards compatibility
const props = defineProps({
  pkg:     { type: Object, default: null },
  package: { type: Object, default: null },
})

// Use whichever prop is provided
const package_ = computed(() => props.package || props.pkg || {})

const typeEmoji    = { adventure:'⚡', valley_visit:'🏛', trekking:'🏔', national_park:'🌿', wildlife_reserve:'🐅', lake:'🏞' }
const typeLabelMap = { adventure:'Adventure', valley_visit:'Valley Visit', trekking:'Trekking', national_park:'National Park', wildlife_reserve:'Wildlife', lake:'Lake' }
const typeLabel    = computed(() => typeLabelMap[package_.value.type] || package_.value.type || '')
</script>
