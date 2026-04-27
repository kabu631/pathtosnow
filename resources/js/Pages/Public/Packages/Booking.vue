<template>
<!-- resources/js/Pages/Public/Packages/Booking.vue -->
<AppLayout>
    <Head><title>Book {{ pkg.name }}</title></Head>
    <div class="min-h-screen bg-gray-50">
        <div class="container-main py-10">

            <!-- Back -->
            <Link :href="`/packages/${pkg.slug}`" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-6">
                ← Back to package
            </Link>

            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Form -->
                <div class="lg:col-span-2">
                    <h1 class="text-2xl font-black text-[#0f172a] mb-6">Book your experience</h1>

                    <div v-if="Object.keys(errors).length"
                         class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                        <ul class="space-y-1">
                            <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
                        </ul>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                            <h2 class="text-sm font-semibold text-gray-900">Your details</h2>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Full name *</label>
                                    <input v-model="form.customer_name" class="input" placeholder="Ram Shrestha" required/>
                                </div>
                                <div>
                                    <label class="label">Email *</label>
                                    <input v-model="form.customer_email" type="email" class="input" placeholder="ram@example.com" required/>
                                </div>
                                <div>
                                    <label class="label">Phone</label>
                                    <input v-model="form.customer_phone" class="input" placeholder="+977 9800000000"/>
                                </div>
                                <div>
                                    <label class="label">Nationality</label>
                                    <input v-model="form.customer_nationality" class="input" placeholder="Nepali / British..."/>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                            <h2 class="text-sm font-semibold text-gray-900">Trip details</h2>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Travel date *</label>
                                    <input v-model="form.travel_date" type="date" class="input" :min="minDate" required/>
                                </div>
                                <div>
                                    <label class="label">Group size ({{ pkg.min_group_size }}–{{ pkg.max_group_size }} people) *</label>
                                    <input v-model.number="form.group_size" type="number"
                                           :min="pkg.min_group_size" :max="pkg.max_group_size"
                                           class="input" required/>
                                </div>
                            </div>
                            <div>
                                <label class="label">Special requests / dietary requirements</label>
                                <textarea v-model="form.special_requests" rows="3" class="input resize-none"
                                          placeholder="Vegetarian meals, medical conditions, equipment needs..."/>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                            <h2 class="text-sm font-semibold text-gray-900">Emergency contact</h2>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Contact name</label>
                                    <input v-model="form.emergency_contact_name" class="input" placeholder="Emergency contact name"/>
                                </div>
                                <div>
                                    <label class="label">Contact phone</label>
                                    <input v-model="form.emergency_contact_phone" class="input" placeholder="+977..."/>
                                </div>
                            </div>
                        </div>

                        <button type="submit" :disabled="submitting"
                                :class="['btn-primary w-full py-4 text-base justify-center', submitting && 'opacity-60 cursor-not-allowed']">
                            {{ submitting ? 'Submitting...' : `Confirm booking — $${totalPrice.toFixed(2)}` }}
                        </button>

                        <p class="text-xs text-center text-gray-400">
                            No payment required now. Our team will contact you within 24 hours to confirm.
                        </p>
                    </form>
                </div>

                <!-- Summary sidebar -->
                <div>
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 sticky top-20">
                        <div class="h-32 bg-gray-100 rounded-xl overflow-hidden mb-4">
                            <img v-if="pkg.cover_image" :src="pkg.cover_image" :alt="pkg.name" class="w-full h-full object-cover"/>
                        </div>
                        <span :class="`type-${pkg.type}`" class="badge text-xs mb-2">{{ typeLabel }}</span>
                        <h3 class="text-sm font-bold text-gray-900 mb-3">{{ pkg.name }}</h3>
                        <div class="space-y-2 text-sm border-t border-gray-100 pt-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Location</span>
                                <span class="text-gray-900 text-xs">{{ pkg.location }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Duration</span>
                                <span>{{ pkg.duration_days }} days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Per person</span>
                                <span class="font-semibold">${{ Number(pkg.price_per_person).toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Group size</span>
                                <span>{{ form.group_size }} {{ form.group_size === 1 ? 'person' : 'people' }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-base pt-2 border-t border-gray-100">
                                <span>Total</span>
                                <span class="text-emerald-600">${{ totalPrice.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</AppLayout>
</template>
<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ package: Object, user: Object })
const pkg   = computed(() => props.package)
const page  = usePage()
const errors = computed(() => page.props.errors || {})

const submitting = ref(false)
const form = ref({
    customer_name: props.user?.name || '', customer_email: props.user?.email || '',
    customer_phone: props.user?.phone || '', customer_nationality: props.user?.nationality || '',
    travel_date: '', group_size: pkg.value.min_group_size,
    special_requests: '', emergency_contact_name: '', emergency_contact_phone: '',
})

const minDate = new Date(Date.now() + 86400000).toISOString().split('T')[0]
const totalPrice = computed(() => Number(pkg.value.price_per_person) * (form.value.group_size || 1))
const typeLabels = { adventure:'Adventure', valley_visit:'Valley Visit', trekking:'Trekking', national_park:'National Park', wildlife_reserve:'Wildlife Reserve', lake:'Lake' }
const typeLabel  = computed(() => typeLabels[pkg.value.type] || pkg.value.type)

function submit() {
    submitting.value = true
    router.post(`/book/${pkg.value.slug}`, form.value, { onFinish: () => submitting.value = false })
}
</script>
