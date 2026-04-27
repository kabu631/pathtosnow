<template>
<!-- resources/js/Pages/Public/Shop/Cart.vue -->
<AppLayout>
    <Head><title>My Cart — PathToSnow</title></Head>
    <div class="container-main py-10 max-w-4xl">
        <h1 class="text-2xl font-black text-[#0f172a] mb-8">
            Shopping cart <span class="text-gray-400 font-normal text-lg">({{ cartItems.length }} items)</span>
        </h1>
        <div v-if="!cartItems.length" class="text-center py-20">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-gray-500 mb-6">Your cart is empty.</p>
            <Link href="/shop" class="btn-primary">Browse gear</Link>
        </div>
        <div v-else class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-3">
                <div v-for="item in cartItems" :key="item.id"
                     class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img v-if="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover"/>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ item.name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">${{ Number(item.price).toFixed(2) }} each</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" :value="item.quantity" min="0" max="99"
                               @change="updateQty(item, $event.target.value)"
                               class="input w-16 text-center text-sm"/>
                        <button @click="remove(item)" class="p-1.5 text-red-400 hover:text-red-600">×</button>
                    </div>
                    <p class="font-bold text-[#0f172a] w-20 text-right">
                        ${{ (Number(item.price) * item.quantity).toFixed(2) }}
                    </p>
                </div>
            </div>
            <!-- Checkout -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 h-fit sticky top-20">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Order summary</h3>
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span>${{ subtotal.toFixed(2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Shipping</span>
                        <span>{{ subtotal >= 100 ? 'Free' : '$12.99' }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-base pt-3 border-t border-gray-100">
                        <span>Total</span>
                        <span class="text-emerald-600">${{ (subtotal >= 100 ? subtotal : subtotal + 12.99).toFixed(2) }}</span>
                    </div>
                </div>
                <p v-if="subtotal < 100" class="text-xs text-amber-600 mb-3">
                    Add ${{ (100 - subtotal).toFixed(2) }} more for free shipping
                </p>
                <button @click="checkoutOpen = true" class="btn-primary w-full mb-2">Checkout</button>
                <Link href="/shop" class="btn-secondary w-full text-center text-xs py-2">Continue shopping</Link>
            </div>
        </div>

        <!-- Checkout modal -->
        <div v-if="checkoutOpen" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between mb-5">
                    <h2 class="text-lg font-bold text-gray-900">Shipping details</h2>
                    <button @click="checkoutOpen = false" class="text-gray-400 hover:text-gray-600">×</button>
                </div>
                <form @submit.prevent="submitCheckout" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="label">Full name *</label><input v-model="checkout.name" class="input" required/></div>
                        <div><label class="label">Email *</label><input v-model="checkout.email" type="email" class="input" required/></div>
                        <div><label class="label">Phone</label><input v-model="checkout.phone" class="input"/></div>
                        <div><label class="label">Country *</label><input v-model="checkout.country" class="input" required/></div>
                    </div>
                    <div><label class="label">Address *</label><input v-model="checkout.address" class="input" required/></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="label">City *</label><input v-model="checkout.city" class="input" required/></div>
                        <div><label class="label">Postal code</label><input v-model="checkout.postal_code" class="input"/></div>
                    </div>
                    <button type="submit" :disabled="submitting" class="btn-primary w-full mt-4">
                        {{ submitting ? 'Placing order...' : `Place order — $${(subtotal >= 100 ? subtotal : subtotal+12.99).toFixed(2)}` }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</AppLayout>
</template>
<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
const props = defineProps({ cartItems: Array })
const checkoutOpen = ref(false)
const submitting   = ref(false)
const checkout = ref({ name:'', email:'', phone:'', address:'', city:'', country:'', postal_code:'' })
const subtotal = computed(() => (props.cartItems||[]).reduce((s,i) => s + Number(i.price) * i.quantity, 0))
function remove(item) { router.delete('/cart/remove', { data: { product_id: item.id } }) }
function updateQty(item, qty) { router.patch('/cart/update', { product_id: item.id, quantity: parseInt(qty) }) }
function submitCheckout() {
    submitting.value = true
    router.post('/cart/checkout', checkout.value, { onFinish: () => submitting.value = false })
}
</script>
