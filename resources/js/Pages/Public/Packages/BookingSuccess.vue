<template>
<!-- resources/js/Pages/Public/Packages/BookingSuccess.vue -->
<AppLayout>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 max-w-lg w-full text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 text-3xl">
                {{ booking.is_quotation ? '📋' : '✅' }}
            </div>
            <h1 class="text-2xl font-black text-[#0f172a] mb-2">
                {{ booking.is_quotation ? 'Request Submitted!' : 'Booking submitted!' }}
            </h1>
            <p class="text-gray-500 text-sm mb-5">
                <template v-if="booking.is_quotation">
                    Thank you, <strong>{{ booking.customer_name }}</strong>. We have received your request for a custom quotation and will contact you shortly with an offer.
                </template>
                <template v-else>
                    Thank you, <strong>{{ booking.customer_name }}</strong>. We've received your booking and will confirm within 24 hours.
                </template>
            </p>
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">
                        {{ booking.is_quotation ? 'Quotation reference' : 'Booking reference' }}
                    </span>
                    <span class="font-mono font-bold text-emerald-600">{{ booking.booking_reference }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Package</span>
                    <span class="font-medium">{{ booking.package?.name ?? booking.custom_package_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Travel date</span>
                    <span>{{ booking.travel_date }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Group size</span>
                    <span>{{ booking.group_size }} people</span>
                </div>
                <div class="flex justify-between font-bold text-base">
                    <span>{{ booking.is_quotation ? 'Est. Total' : 'Total' }}</span>
                    <span class="text-emerald-600">
                        {{ booking.package_id ? `$${Number(booking.total_price).toFixed(2)}` : 'TBD' }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <Link href="/packages" class="btn-secondary flex-1 text-center">Browse packages</Link>
                <Link href="/" class="btn-primary flex-1 text-center">Back to home</Link>
            </div>
        </div>
    </div>
</AppLayout>
</template>
<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ booking: Object })
</script>
