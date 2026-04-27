<template>
<!-- resources/js/Pages/Admin/Bookings/Index.vue -->
<AdminLayout title="Bookings">
    <div class="mb-5 flex flex-wrap gap-3">
        <input v-model="search" @input="filter" placeholder="Search name / email / ref..." class="input text-sm max-w-xs"/>
        <select v-model="statusF" @change="filter" class="input text-sm w-auto">
            <option value="">All statuses</option>
            <option v-for="s in statuses" :key="s" :value="s" class="capitalize">{{ s }}</option>
        </select>
        <select v-model="typeF" @change="filter" class="input text-sm w-auto">
            <option value="">All types</option>
            <option v-for="(label,type) in types" :key="type" :value="type">{{ label }}</option>
        </select>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Reference</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Customer</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden md:table-cell">Package</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Travel date</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Total</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="b in bookings.data" :key="b.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a :href="`/admin/bookings/${b.id}`" class="font-mono text-xs text-[#E85D26] hover:underline">
                            {{ b.booking_reference }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm text-gray-900">{{ b.customer_name }}</p>
                        <p class="text-xs text-gray-400">{{ b.customer_email }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span :class="`type-${b.package?.type}`" class="badge text-xs">
                            {{ b.package?.name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell">{{ b.travel_date }}</td>
                    <td class="px-4 py-3 font-semibold text-[#0D1B2A]">${{ Number(b.total_price).toFixed(0) }}</td>
                    <td class="px-4 py-3">
                        <span :class="statusBadge(b.status)" class="badge text-xs capitalize">{{ b.status }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <a :href="`/admin/bookings/${b.id}`" class="text-xs text-[#E85D26] hover:underline">View</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!bookings.data.length" class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">📅</p>
            <p class="text-sm">No bookings yet.</p>
        </div>
    </div>

    <div v-if="bookings.last_page > 1" class="flex gap-2 mt-5">
        <a v-for="link in bookings.links" :key="link.label" :href="link.url || '#'"
           v-html="link.label"
           :class="['px-3 py-1.5 rounded-lg text-xs', link.active ? 'bg-[#0D1B2A] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50', !link.url && 'opacity-40 pointer-events-none']"/>
    </div>
</AdminLayout>
</template>
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ bookings: Object, filters: Object, types: Object })
const search = ref(props.filters?.q || '')
const statusF = ref(props.filters?.status || '')
const typeF   = ref(props.filters?.type || '')
const statuses = ['pending','confirmed','in_progress','completed','cancelled']

let t = null
function filter() {
    clearTimeout(t); t = setTimeout(() => {
        router.get('/admin/bookings', { q: search.value || undefined, status: statusF.value || undefined, type: typeF.value || undefined }, { preserveState: true, replace: true })
    }, 400)
}

const STATUS_COLORS = {
    pending:'bg-yellow-100 text-yellow-800', confirmed:'bg-blue-100 text-blue-800',
    in_progress:'bg-purple-100 text-purple-800', completed:'bg-green-100 text-green-800', cancelled:'bg-red-100 text-red-800',
}
function statusBadge(s) { return STATUS_COLORS[s] || 'bg-gray-100 text-gray-600' }
</script>
