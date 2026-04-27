<template>
<!-- resources/js/Pages/Admin/Products/Form.vue -->
<AdminLayout :title="product ? `Edit: ${product.name}` : 'New Product'">
    <template #actions>
        <a href="/admin/products" class="btn-secondary text-xs py-2 px-3">← Products</a>
    </template>
    <form @submit.prevent="submit" class="max-w-3xl space-y-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Product info</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Category</label>
                    <select v-model="form.category_id" class="input">
                        <option :value="null">Uncategorised</option>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">Product name *</label>
                    <input v-model="form.name" class="input" required placeholder="Osprey Atmos AG 65L"/>
                </div>
                <div>
                    <label class="label">Price (USD) *</label>
                    <input v-model.number="form.price" type="number" step="0.01" class="input" required/>
                </div>
                <div>
                    <label class="label">Compare-at price</label>
                    <input v-model.number="form.compare_price" type="number" step="0.01" class="input" placeholder="Original / crossed-out price"/>
                </div>
                <div>
                    <label class="label">Stock quantity</label>
                    <input v-model.number="form.stock" type="number" min="0" class="input"/>
                </div>
                <div>
                    <label class="label">SKU</label>
                    <input v-model="form.sku" class="input" placeholder="OSP-ATM-65L"/>
                </div>
                <div>
                    <label class="label">Weight (grams)</label>
                    <input v-model.number="form.weight_grams" type="number" class="input"/>
                </div>
            </div>
            <div>
                <label class="label">Description</label>
                <textarea v-model="form.description" rows="4" class="input resize-none"/>
            </div>
            <div>
                <label class="label">Upload Images</label>
                <input type="file" multiple accept="image/*" @change="handleFiles" class="input p-2 text-sm" />
                <div v-if="form.errors.new_images" class="text-red-500 text-xs mt-1">{{ form.errors.new_images }}</div>
            </div>
            
            <div v-if="form.new_images?.length" class="space-y-1">
                <label class="text-xs font-semibold text-emerald-600">New Images to Upload (click to remove)</label>
                <div class="flex gap-2 flex-wrap">
                    <div v-for="(file, idx) in form.new_images" :key="'new'+idx" class="relative group cursor-pointer" @click="form.new_images.splice(idx, 1)">
                        <img :src="getObjectUrl(file)" class="h-20 w-20 object-cover rounded-lg border-2 border-emerald-400 opacity-90 group-hover:opacity-30 transition-opacity"/>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                           <span class="text-xs bg-red-600 text-white px-2 py-1 rounded shadow-sm">Remove</span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="form.images?.length" class="space-y-1">
                <label class="text-xs font-semibold text-slate-500">Current Images (click to remove)</label>
                <div class="flex gap-2 flex-wrap">
                    <div v-for="(img, idx) in form.images" :key="img" class="relative group cursor-pointer" @click="form.images.splice(idx, 1)">
                        <img :src="img" class="h-20 w-20 object-cover rounded-lg border opacity-90 group-hover:opacity-30 transition-opacity"/>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                           <span class="text-xs bg-red-600 text-white px-2 py-1 rounded shadow-sm">Remove</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Specs & status</h2>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox" v-model="form.active" class="rounded"/> Active
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox" v-model="form.featured" class="rounded"/> Featured
                </label>
            </div>
            <div>
                <label class="label">Specs (one per line: Key: Value)</label>
                <textarea :value="specsText" @input="parseSpecs" rows="4" class="input resize-none font-mono text-xs"
                          placeholder="Volume: 65L&#10;Frame: Anti-Gravity&#10;Material: 100D Nylon"/>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" :disabled="submitting" class="btn-orange px-8">
                {{ submitting ? 'Saving...' : (product ? 'Update product' : 'Create product') }}
            </button>
            <a href="/admin/products" class="btn-secondary px-6">Cancel</a>
        </div>
    </form>
</AdminLayout>
</template>
<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ product: Object, categories: Array })
const p = props.product
const submitting = ref(false)

const form = useForm({
    category_id: p?.category_id||null, name: p?.name||'', description: p?.description||'',
    price: p?.price||'', compare_price: p?.compare_price||'', images: p?.images||[],
    new_images: [],
    stock: p?.stock||0, sku: p?.sku||'', weight_grams: p?.weight_grams||'',
    specs: p?.specs||{}, tags: p?.tags||[], featured: p?.featured||false, active: p?.active!==false,
})

const specsText = computed(() => Object.entries(form.specs||{}).map(([k,v]) => `${k}: ${v}`).join('\n'))
function parseSpecs(e) {
    const specs = {}
    e.target.value.split('\n').forEach(line => { const [k,...v] = line.split(':'); if(k?.trim()) specs[k.trim()] = v.join(':').trim() })
    form.specs = specs
}

function handleFiles(e) {
    const files = Array.from(e.target.files)
    if (!form.new_images) form.new_images = []
    form.new_images.push(...files)
    e.target.value = ''
}

function getObjectUrl(file) {
    return URL.createObjectURL(file)
}
function submit() {
    submitting.value = true
    if (p) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/products/${p.id}`, { onFinish: () => submitting.value = false })
    } else {
        form.post('/admin/products', { onFinish: () => submitting.value = false })
    }
}
</script>
