<template>
<!-- resources/js/Pages/Admin/Dashboard.vue -->
<AdminLayout title="Dashboard">
    <template #actions>
        <a href="/admin/packages/create" class="btn-orange text-xs py-2 px-3">+ New package</a>
        <a href="/admin/posts/create" class="btn-secondary text-xs py-2 px-3 ml-2">+ New post</a>
    </template>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div v-for="s in statCards" :key="s.label" class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 mb-1">{{ s.label }}</p>
            <p :class="`text-2xl font-black ${s.color}`">{{ s.value }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Recent bookings & quotes -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-900 text-sm">Recent bookings</h2>
                    <a href="/admin/bookings?quote=0" class="text-xs text-[#E85D26] hover:underline">View all</a>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100">
                            <th class="text-left pb-2 font-medium">Ref</th>
                            <th class="text-left pb-2 font-medium">Customer</th>
                            <th class="text-left pb-2 font-medium hidden md:table-cell">Package</th>
                            <th class="text-left pb-2 font-medium">Total</th>
                            <th class="text-left pb-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="b in recentBookings" :key="b.id">
                            <td class="py-2.5">
                                <a :href="`/admin/bookings/${b.id}`" class="font-mono text-xs text-[#E85D26] hover:underline">
                                    {{ b.booking_reference }}
                                </a>
                            </td>
                            <td class="py-2.5 text-gray-700 text-xs">{{ b.customer_name }}</td>
                            <td class="py-2.5 text-gray-500 text-xs hidden md:table-cell">{{ b.package?.name ?? b.custom_package_name }}</td>
                            <td class="py-2.5 font-medium text-xs">{{ b.package_id ? `$${Number(b.total_price).toFixed(0)}` : 'TBD' }}</td>
                            <td class="py-2.5">
                                <span :class="statusBadge(b.status)" class="badge text-xs capitalize">{{ b.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p v-if="!recentBookings?.length" class="text-center text-gray-400 text-sm py-8">No bookings yet</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-900 text-sm">Recent quotation requests</h2>
                    <a href="/admin/bookings?quote=1" class="text-xs text-[#E85D26] hover:underline">View all</a>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 border-b border-gray-100">
                            <th class="text-left pb-2 font-medium">Ref</th>
                            <th class="text-left pb-2 font-medium">Customer</th>
                            <th class="text-left pb-2 font-medium hidden md:table-cell">Package</th>
                            <th class="text-left pb-2 font-medium">Est. Price</th>
                            <th class="text-left pb-2 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="b in recentQuotes" :key="b.id">
                            <td class="py-2.5">
                                <a :href="`/admin/bookings/${b.id}`" class="font-mono text-xs text-[#E85D26] hover:underline">
                                    {{ b.booking_reference }}
                                </a>
                            </td>
                            <td class="py-2.5 text-gray-700 text-xs">{{ b.customer_name }}</td>
                            <td class="py-2.5 text-gray-500 text-xs hidden md:table-cell">{{ b.package?.name ?? b.custom_package_name }}</td>
                            <td class="py-2.5 font-medium text-xs">{{ b.package_id ? `$${Number(b.total_price).toFixed(0)}` : 'TBD' }}</td>
                            <td class="py-2.5">
                                <span :class="statusBadge(b.status)" class="badge text-xs capitalize">{{ b.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p v-if="!recentQuotes?.length" class="text-center text-gray-400 text-sm py-8">No quotation requests yet</p>
            </div>
        </div>

        <!-- Quick links -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 align-self-start">
            <h2 class="font-semibold text-gray-900 text-sm mb-4">Quick actions</h2>
            <div class="space-y-1">
                <a v-for="l in quickLinks" :key="l.href" :href="l.href"
                   class="flex items-center gap-2.5 p-2.5 rounded-lg hover:bg-gray-50 text-sm text-gray-700 transition-colors">
                    <span style="font-size:14px">{{ l.icon }}</span> {{ l.label }}
                </a>
            </div>
        </div>
    </div>
</AdminLayout>
</template>
<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ stats: Object, recentBookings: Array, recentQuotes: Array, bookingsByType: Object })

const statCards = [
    { label: 'Pending bookings',   value: props.stats?.pending_bookings || 0, color: 'text-[#E85D26]' },
    { label: 'Total bookings',     value: props.stats?.bookings || 0,         color: 'text-green-600' },
    { label: 'Pending quotes',     value: props.stats?.pending_quotes || 0,   color: 'text-indigo-650' },
    { label: 'Total quotes',       value: props.stats?.quotes || 0,           color: 'text-[#0D1B2A]' },
]

const STATUS_COLORS = {
    pending: 'bg-yellow-100 text-yellow-800', confirmed: 'bg-blue-100 text-blue-800',
    in_progress: 'bg-purple-100 text-purple-800', completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
}
function statusBadge(s) { return STATUS_COLORS[s] || 'bg-gray-100 text-gray-600' }

const quickLinks = [
    { href:'/admin/packages', label:'Manage packages', icon:'🗺' },
    { href:'/admin/packages/create', label:'Add new package', icon:'➕' },
    { href:'/admin/bookings?quote=0', label:'View standard bookings', icon:'📅' },
    { href:'/admin/bookings?quote=1', label:'View quotation requests', icon:'📋' },
    { href:'/admin/posts/create', label:'Write new post', icon:'✏' },
    { href:'/admin/products/create', label:'Add gear product', icon:'🎒' },
    { href:'/', label:'View live site', icon:'🌐' },
]
</script>
