<template>
<!-- resources/js/Pages/Admin/Bookings/Show.vue -->
<AdminLayout :title="`Booking — ${booking.booking_reference}`">
    <template #actions>
        <a href="/admin/bookings" class="btn-secondary text-xs py-2 px-3">← Bookings</a>
    </template>
    <div class="max-w-4xl grid md:grid-cols-3 gap-6">

        <!-- Details -->
        <div class="md:col-span-2 space-y-5">
            <!-- Customer -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Customer details</h2>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div><dt class="text-gray-400 text-xs">Name</dt><dd class="font-medium">{{ booking.customer_name }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Email</dt><dd>{{ booking.customer_email }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Phone</dt><dd>{{ booking.customer_phone || '—' }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Nationality</dt><dd>{{ booking.customer_nationality || '—' }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Emergency contact</dt><dd>{{ booking.emergency_contact_name || '—' }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Emergency phone</dt><dd>{{ booking.emergency_contact_phone || '—' }}</dd></div>
                </dl>
            </div>

            <!-- Trip -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Trip details</h2>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div><dt class="text-gray-400 text-xs">Package</dt><dd class="font-medium">{{ booking.package?.name }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Type</dt>
                        <dd><span :class="`type-${booking.package?.type}`" class="badge text-xs">{{ booking.package?.type }}</span></dd></div>
                    <div><dt class="text-gray-400 text-xs">Travel date</dt><dd class="font-medium">{{ booking.travel_date }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Group size</dt><dd>{{ booking.group_size }} people</dd></div>
                    <div><dt class="text-gray-400 text-xs">Price/person</dt><dd>${{ Number(booking.price_per_person).toFixed(2) }}</dd></div>
                    <div><dt class="text-gray-400 text-xs">Total</dt><dd class="font-bold text-lg text-[#E85D26]">${{ Number(booking.total_price).toFixed(2) }}</dd></div>
                </dl>
                <div v-if="booking.special_requests" class="mt-4 p-3 bg-amber-50 rounded-lg">
                    <p class="text-xs font-medium text-amber-800 mb-1">Special requests</p>
                    <p class="text-sm text-amber-700">{{ booking.special_requests }}</p>
                </div>
            </div>

            <!-- Admin notes -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">Admin notes (internal)</h2>
                <textarea v-model="notes" rows="4" class="input resize-none" placeholder="Internal notes — not visible to customer..."/>
                <button @click="saveNotes" :disabled="savingNotes" class="btn-primary text-xs mt-3">
                    {{ savingNotes ? 'Saving...' : 'Save notes' }}
                </button>
            </div>
        </div>

        <!-- Sidebar: status -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">Booking status</h2>
                <p class="font-mono text-xs text-gray-400 mb-3">{{ booking.booking_reference }}</p>
                <span :class="statusBadge(booking.status)" class="badge text-sm capitalize mb-4 block text-center">
                    {{ booking.status }}
                </span>
                <div class="space-y-2">
                    <button v-for="s in statuses" :key="s"
                            :disabled="s === booking.status"
                            @click="updateStatus(s)"
                            :class="['w-full text-left px-3 py-2 rounded-lg text-xs transition-colors capitalize',
                                     s === booking.status ? 'bg-gray-100 text-gray-400 cursor-default' : 'hover:bg-gray-50 text-gray-700 border border-gray-200']">
                        → {{ s.replace('_',' ') }}
                    </button>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-400">Created</p>
                <p class="text-sm text-gray-700">{{ booking.created_at }}</p>
                <p v-if="booking.confirmed_at" class="text-xs text-gray-400 mt-2">Confirmed</p>
                <p v-if="booking.confirmed_at" class="text-sm text-gray-700">{{ booking.confirmed_at }}</p>
            </div>
            <div class="bg-white rounded-xl border border-red-100 p-5">
                <h2 class="text-sm font-semibold text-slate-900 mb-3">Danger Zone</h2>
                <button @click="deleteBooking"
                        class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold py-2.5 rounded-xl text-sm transition-colors">
                    🗑 Delete Booking
                </button>
            </div>
        </div>
    </div>
</AdminLayout>
</template>
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props  = defineProps({ booking: Object })
const notes  = ref(props.booking.admin_notes || '')
const savingNotes = ref(false)
const statuses = ['pending','confirmed','in_progress','completed','cancelled']

const STATUS_COLORS = { pending:'bg-yellow-100 text-yellow-800', confirmed:'bg-blue-100 text-blue-800', in_progress:'bg-purple-100 text-purple-800', completed:'bg-green-100 text-green-800', cancelled:'bg-red-100 text-red-800' }
function statusBadge(s) { return STATUS_COLORS[s] || 'bg-gray-100 text-gray-700' }

function updateStatus(s) { router.patch(`/admin/bookings/${props.booking.id}/status`, { status:s }) }
function saveNotes() {
    savingNotes.value = true
    router.patch(`/admin/bookings/${props.booking.id}/notes`, { admin_notes: notes.value }, { onFinish: () => savingNotes.value = false })
}
function deleteBooking() {
    if (!confirm(`Permanently delete booking ${props.booking.booking_reference}? This cannot be undone.`)) return
    router.delete(`/admin/bookings/${props.booking.id}`)
}
</script>
