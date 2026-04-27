<template>
<!-- resources/js/Pages/Admin/Packages/Index.vue -->
<AdminLayout title="Packages">
    <template #actions>
        <a href="/admin/packages/create" class="btn-orange text-xs py-2 px-3">+ New package</a>
    </template>

    <div class="mb-4 flex flex-wrap gap-3">
        <input v-model="search" @input="filter" placeholder="Search packages..." class="input text-sm max-w-xs"/>
        <select v-model="typeF" @change="filter" class="input text-sm w-auto">
            <option value="">All types</option>
            <option v-for="(label,type) in types" :key="type" :value="type">{{ label }}</option>
        </select>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Package</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden md:table-cell">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Price</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Bookings</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="pkg in packages.data" :key="pkg.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-900 text-sm">{{ pkg.name }}</p>
                        <p class="text-xs text-gray-400">{{ pkg.location }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span :class="`type-${pkg.type}`" class="badge text-xs">{{ types[pkg.type] || pkg.type }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium hidden lg:table-cell">${{ Number(pkg.price_per_person).toFixed(0) }}</td>
                    <td class="px-4 py-3 text-sm hidden lg:table-cell">{{ pkg.bookings_count }}</td>
                    <td class="px-4 py-3">
                        <button @click="toggle(pkg)"
                                :class="pkg.active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500'"
                                class="badge text-xs cursor-pointer hover:opacity-80">
                            {{ pkg.active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a :href="`/admin/packages/${pkg.id}/itinerary`" class="text-xs text-purple-600 hover:underline">Itinerary</a>
                        <a :href="`/admin/packages/${pkg.id}/edit`" class="text-xs text-[#E85D26] hover:underline">Edit</a>
                        <button @click="del(pkg)" class="text-xs text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!packages.data.length" class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🗺</p>
            <p class="text-sm">No packages yet. <a href="/admin/packages/create" class="text-[#E85D26] hover:underline">Add the first one.</a></p>
        </div>
    </div>
</AdminLayout>
</template>
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
const props = defineProps({ packages: Object, types: Object })
const search = ref(''), typeF = ref('')
let t = null
function filter() { clearTimeout(t); t = setTimeout(() => router.get('/admin/packages', { q: search.value||undefined, type: typeF.value||undefined }, { preserveState:true, replace:true }), 400) }
function toggle(pkg) { router.patch(`/admin/packages/${pkg.id}/toggle`) }
function del(pkg) { if(confirm(`Delete "${pkg.name}"?`)) router.delete(`/admin/packages/${pkg.id}`) }
</script>
