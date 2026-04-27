<template>
<!-- resources/js/Pages/Public/Shop/Show.vue -->
<AppLayout>
    <Head><title>{{ product.name }}</title></Head>
    <div class="container-main py-10">
        <Link href="/shop" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-6">← Back to shop</Link>
        <div class="grid md:grid-cols-2 gap-10">
            <div>
                <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden mb-3">
                    <img v-if="mainImage" :src="mainImage" :alt="product.name" class="w-full h-full object-cover"/>
                    <div v-else class="w-full h-full flex items-center justify-center text-6xl">🎒</div>
                </div>
                <div v-if="product.images?.length > 1" class="flex gap-2">
                    <button v-for="(img,i) in product.images" :key="i" @click="mainImage = img"
                            :class="['w-16 h-16 rounded-lg overflow-hidden border-2 transition-colors', mainImage===img ? 'border-emerald-500' : 'border-transparent']">
                        <img :src="img" :alt="`${product.name} ${i+1}`" class="w-full h-full object-cover"/>
                    </button>
                </div>
            </div>
            <div>
                <span class="badge bg-gray-100 text-gray-600 text-xs mb-2">{{ product.category?.name }}</span>
                <h1 class="text-2xl font-black text-[#0f172a] mb-3">{{ product.name }}</h1>
                <div class="flex items-center gap-3 mb-5">
                    <span class="text-3xl font-black text-[#0f172a]">${{ Number(product.price).toFixed(2) }}</span>
                    <span v-if="product.compare_price" class="text-lg text-gray-400 line-through">${{ Number(product.compare_price).toFixed(2) }}</span>
                </div>
                <p v-if="product.description" class="text-sm text-gray-600 leading-relaxed mb-6">{{ product.description }}</p>
                <!-- Specs -->
                <div v-if="product.specs && Object.keys(product.specs).length" class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Specifications</h3>
                    <div class="bg-gray-50 rounded-xl divide-y divide-gray-100">
                        <div v-for="(val,key) in product.specs" :key="key" class="flex justify-between px-4 py-2 text-sm">
                            <span class="text-gray-500">{{ key }}</span>
                            <span class="font-medium text-gray-900">{{ val }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    <div :class="product.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="badge text-xs">
                        {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                    </div>
                </div>
                <form @submit.prevent="addToCart">
                    <div class="flex gap-3">
                        <input v-model.number="qty" type="number" min="1" :max="product.stock" class="input w-20 text-center"/>
                        <button type="submit" :disabled="product.stock === 0" class="btn-primary flex-1">
                            Add to cart
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div v-if="related?.length" class="mt-14">
            <h2 class="text-xl font-bold text-[#0f172a] mb-5">You might also like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <Link v-for="r in related" :key="r.id" :href="`/shop/${r.slug}`" class="card group block">
                    <div class="h-40 bg-gray-50 overflow-hidden">
                        <img v-if="r.images?.[0]" :src="r.images[0]" :alt="r.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform"/>
                    </div>
                    <div class="p-3">
                        <p class="text-xs font-medium text-gray-900 line-clamp-2 group-hover:text-emerald-600">{{ r.name }}</p>
                        <p class="font-bold text-[#0f172a] mt-1">${{ Number(r.price).toFixed(2) }}</p>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</AppLayout>
</template>
<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
const props = defineProps({ product: Object, related: Array })
const mainImage = ref(props.product.images?.[0] || null)
const qty = ref(1)
function addToCart() {
    router.post('/cart/add', { product_id: props.product.id, quantity: qty.value })
}
</script>
