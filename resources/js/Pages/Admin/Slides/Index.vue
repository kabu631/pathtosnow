<template>
<AdminLayout title="Hero Slider">
    <template #actions>
        <Link href="/admin/slides/create" class="btn-primary text-xs py-2 px-4">+ New Slide</Link>
        <button v-if="hasChanges" @click="saveOrder" class="btn-secondary text-xs py-2 px-4 ml-2">Save Order</button>
    </template>
    
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Order</th>
                    <th class="px-6 py-4 font-medium">Image</th>
                    <th class="px-6 py-4 font-medium">Title / Tag</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr v-for="(slide, index) in localSlides" :key="slide.id" class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1 items-center w-8">
                            <button @click="moveUp(index)" :disabled="index === 0" class="text-slate-400 hover:text-slate-700 disabled:opacity-30">▲</button>
                            <span class="text-center font-bold text-slate-600">{{ slide.sort_order }}</span>
                            <button @click="moveDown(index)" :disabled="index === localSlides.length - 1" class="text-slate-400 hover:text-slate-700 disabled:opacity-30">▼</button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <img v-if="slide.image" :src="slide.image" class="w-32 h-16 object-cover rounded-lg border"/>
                        <span v-else class="text-slate-400">No Image</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900 truncate max-w-[200px]">{{ slide.title }}</div>
                        <div class="text-xs text-slate-500 truncate max-w-[200px]">{{ slide.tag }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span :class="slide.is_active ? 'badge-green' : 'badge-slate'">
                            {{ slide.is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <Link :href="`/admin/slides/${slide.id}/edit`" class="text-emerald-600 hover:text-emerald-700 font-medium px-2">Edit</Link>
                        <Link :href="`/admin/slides/${slide.id}`" method="delete" as="button" class="text-red-500 hover:text-red-700 font-medium px-2">Delete</Link>
                    </td>
                </tr>
                <tr v-if="localSlides.length === 0">
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">No slides found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ slides: Array })

const localSlides = ref([...props.slides])
const hasChanges = ref(false)

watch(() => props.slides, (newSlides) => {
    localSlides.value = [...newSlides]
    hasChanges.value = false
}, { deep: true })

const moveUp = (idx) => {
    if (idx > 0) {
        const temp = localSlides.value[idx]
        localSlides.value[idx] = localSlides.value[idx - 1]
        localSlides.value[idx - 1] = temp
        updateSortOrders()
    }
}

const moveDown = (idx) => {
    if (idx < localSlides.value.length - 1) {
        const temp = localSlides.value[idx]
        localSlides.value[idx] = localSlides.value[idx + 1]
        localSlides.value[idx + 1] = temp
        updateSortOrders()
    }
}

const updateSortOrders = () => {
    localSlides.value.forEach((slide, idx) => {
        slide.sort_order = idx + 1
    })
    hasChanges.value = true
}

const saveOrder = () => {
    router.post('/admin/slides/reorder', {
        slides: localSlides.value.map(s => ({ id: s.id, sort_order: s.sort_order }))
    }, {
        onSuccess: () => hasChanges.value = false
    })
}
</script>
