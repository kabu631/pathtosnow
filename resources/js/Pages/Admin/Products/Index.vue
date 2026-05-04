<template>
<!-- resources/js/Pages/Admin/Products/Index.vue -->
<AdminLayout title="Products">
    <template #actions>
        <a href="/admin/products/create" class="btn-orange text-xs py-2 px-3">+ New product</a>
    </template>

    <!-- Filters -->
    <div class="mb-4 flex flex-wrap gap-3">
        <input v-model="search" @input="filter" placeholder="Search products..." class="input text-sm max-w-xs"/>
        <select v-model="catF" @change="filter" class="input text-sm w-auto">
            <option value="">All categories</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Product</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden md:table-cell">Category</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Price</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Stock</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
                    <!-- Name + thumbnail -->
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img v-if="product.images && product.images[0]"
                                 :src="product.images[0]"
                                 class="h-10 w-10 rounded-lg object-cover border border-gray-200 flex-shrink-0"/>
                            <div v-else class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-gray-400 text-xs">—</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm truncate max-w-[180px]">{{ product.name }}</p>
                                <p v-if="product.sku" class="text-xs text-gray-400">{{ product.sku }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-xs text-gray-500">{{ product.category?.name ?? '—' }}</span>
                    </td>

                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="font-medium text-gray-800">${{ Number(product.price).toFixed(2) }}</span>
                        <span v-if="product.compare_price" class="ml-1 text-xs text-gray-400 line-through">
                            ${{ Number(product.compare_price).toFixed(2) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span :class="product.stock > 0 ? 'text-gray-700' : 'text-red-500 font-medium'">
                            {{ product.stock }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-1">
                            <span :class="product.active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500'"
                                  class="badge text-xs w-fit">
                                {{ product.active ? 'Active' : 'Inactive' }}
                            </span>
                            <span v-if="product.featured" class="badge text-xs bg-amber-100 text-amber-700 w-fit">Featured</span>
                        </div>
                    </td>

                    <td class="px-4 py-3 flex items-center gap-2">
                        <a :href="`/admin/products/${product.id}/edit`" class="text-xs text-[#E85D26] hover:underline">Edit</a>
                        <button @click="del(product)" class="text-xs text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!products.data.length" class="text-center py-16 text-gray-400 text-sm">No products yet.</div>
    </div>

    <!-- Pagination -->
    <div v-if="products.last_page > 1" class="mt-4 flex justify-center gap-2">
        <a v-for="page in products.last_page" :key="page"
           :href="`/admin/products?page=${page}`"
           :class="page === products.current_page
               ? 'bg-[#E85D26] text-white'
               : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
            {{ page }}
        </a>
    </div>
</AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ products: Object, categories: Array })

const search = ref('')
const catF   = ref('')

let t = null
function filter() {
    clearTimeout(t)
    t = setTimeout(() => {
        router.get('/admin/products', {
            q:    search.value || undefined,
            cat:  catF.value   || undefined,
        }, { preserveState: true, replace: true })
    }, 400)
}

function del(product) {
    if (confirm(`Delete "${product.name}"?`)) {
        router.delete(`/admin/products/${product.id}`)
    }
}
</script>
