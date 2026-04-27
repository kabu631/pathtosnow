<template>
<!-- resources/js/Pages/Admin/Packages/Form.vue -->
<AdminLayout :title="pkg ? `Edit: ${pkg.name}` : 'New Package'">
    <template #actions>
        <a href="/admin/packages" class="btn-secondary text-xs py-2 px-3">← Back</a>
    </template>

    <form @submit.prevent="submit" class="max-w-4xl space-y-6">

        <!-- Basic info -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Basic information</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Package type *</label>
                    <select v-model="form.type" class="input" required>
                        <option value="">Select type</option>
                        <option v-for="(label,type) in types" :key="type" :value="type">{{ label }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">Package name *</label>
                    <input v-model="form.name" class="input" placeholder="Annapurna Base Camp Trek" required/>
                </div>
                <div>
                    <label class="label">Location *</label>
                    <input v-model="form.location" class="input" placeholder="Pokhara, Gandaki Province" required/>
                </div>
                <div>
                    <label class="label">Region</label>
                    <input v-model="form.region" class="input" placeholder="Gandaki Province"/>
                </div>
            </div>
            <div>
                <label class="label">Short description * (max 500 chars)</label>
                <textarea v-model="form.short_description" rows="2" class="input resize-none"
                          placeholder="One-paragraph hook for listings and cards" maxlength="500" required/>
                <p class="text-xs text-gray-400 mt-1">{{ form.short_description.length }}/500</p>
            </div>
            <div>
                <label class="label">Full description * (HTML supported)</label>
                <textarea v-model="form.description" rows="8" class="input resize-none font-mono text-xs"
                          placeholder="<p>Full description with HTML...</p>" required/>
            </div>
        </div>

        <!-- Pricing & Logistics -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Pricing & logistics</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="label">Price per person (USD) *</label>
                    <input v-model.number="form.price_per_person" type="number" min="0" step="0.01" class="input" required/>
                </div>
                <div>
                    <label class="label">Duration (days) *</label>
                    <input v-model.number="form.duration_days" type="number" min="1" class="input" required/>
                </div>
                <div>
                    <label class="label">Duration (nights)</label>
                    <input v-model.number="form.duration_nights" type="number" min="0" class="input"/>
                </div>
                <div>
                    <label class="label">Difficulty</label>
                    <select v-model="form.difficulty" class="input">
                        <option value="">Not specified</option>
                        <option v-for="d in difficulties" :key="d" :value="d" class="capitalize">{{ d }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">Min group size</label>
                    <input v-model.number="form.min_group_size" type="number" min="1" class="input"/>
                </div>
                <div>
                    <label class="label">Max group size</label>
                    <input v-model.number="form.max_group_size" type="number" min="1" class="input"/>
                </div>
                <div>
                    <label class="label">Max altitude (m)</label>
                    <input v-model.number="form.max_altitude_m" type="number" class="input" placeholder="4130"/>
                </div>
                <div>
                    <label class="label">Best season</label>
                    <input v-model="form.best_season" class="input" placeholder="Mar-May, Sep-Nov"/>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Start point</label>
                    <input v-model="form.start_point" class="input" placeholder="Nayapul"/>
                </div>
                <div>
                    <label class="label">End point</label>
                    <input v-model="form.end_point" class="input" placeholder="Nayapul"/>
                </div>
            </div>
        </div>

        <!-- Lists: Highlights, Included, Excluded -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-5">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Highlights & inclusions</h2>

            <div v-for="field in listFields" :key="field.key">
                <label class="label">{{ field.label }}</label>
                <div class="space-y-2">
                    <div v-for="(item, i) in form[field.key]" :key="i" class="flex gap-2">
                        <input v-model="form[field.key][i]" class="input flex-1" :placeholder="field.placeholder"/>
                        <button type="button" @click="removeItem(field.key, i)"
                                class="p-2 text-red-400 hover:text-red-600">×</button>
                    </div>
                    <button type="button" @click="addItem(field.key)"
                            class="text-xs text-[#E85D26] hover:underline">+ Add item</button>
                </div>
            </div>
        </div>

        <!-- Status & SEO -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Status & SEO</h2>
            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" v-model="form.active" class="rounded"/>
                    <span class="text-sm text-gray-700">Active (visible to public)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" v-model="form.featured" class="rounded"/>
                    <span class="text-sm text-gray-700">Featured on homepage</span>
                </label>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">SEO title (max 60 chars)</label>
                    <input v-model="form.meta_title" class="input" maxlength="60"/>
                </div>
                <div>
                    <label class="label">URL slug (auto-generated if empty)</label>
                    <input v-model="form.slug" class="input" placeholder="annapurna-base-camp-trek"/>
                </div>
            </div>
            <div>
                <label class="label">SEO description (max 160 chars)</label>
                <textarea v-model="form.meta_description" rows="2" class="input resize-none" maxlength="160"/>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" :disabled="submitting" class="btn-orange px-8">
                {{ submitting ? 'Saving...' : (pkg ? 'Update package' : 'Create package') }}
            </button>
            <a href="/admin/packages" class="btn-secondary px-6">Cancel</a>
        </div>
    </form>
</AdminLayout>
</template>
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ package: Object, types: Object })
const pkg = props.package

const submitting = ref(false)
const difficulties = ['easy','moderate','challenging','strenuous']

const form = ref({
    type: pkg?.type || '', name: pkg?.name || '', slug: pkg?.slug || '',
    location: pkg?.location || '', region: pkg?.region || '',
    short_description: pkg?.short_description || '', description: pkg?.description || '',
    cover_image: pkg?.cover_image || '',
    price_per_person: pkg?.price_per_person || '', price_group: pkg?.price_group || '',
    duration_days: pkg?.duration_days || 1, duration_nights: pkg?.duration_nights || 0,
    min_group_size: pkg?.min_group_size || 1, max_group_size: pkg?.max_group_size || 15,
    difficulty: pkg?.difficulty || '', max_altitude_m: pkg?.max_altitude_m || '',
    best_season: pkg?.best_season || '', start_point: pkg?.start_point || '', end_point: pkg?.end_point || '',
    highlights: pkg?.highlights || [''], included: pkg?.included || [''], excluded: pkg?.excluded || [''],
    featured: pkg?.featured || false, active: pkg?.active !== undefined ? pkg.active : true,
    meta_title: pkg?.meta_title || '', meta_description: pkg?.meta_description || '',
})

const listFields = [
    { key:'highlights', label:'Highlights', placeholder:'e.g. Annapurna Base Camp panorama' },
    { key:'included',   label:'What\'s included', placeholder:'e.g. Licensed guide & porter' },
    { key:'excluded',   label:'Not included (excluded)', placeholder:'e.g. International flights' },
]

function addItem(field)       { form.value[field].push('') }
function removeItem(field, i) { form.value[field].splice(i, 1) }

function submit() {
    submitting.value = true
    const clean = { ...form.value,
        highlights: form.value.highlights.filter(Boolean),
        included:   form.value.included.filter(Boolean),
        excluded:   form.value.excluded.filter(Boolean),
    }
    if (pkg) {
        router.put(`/admin/packages/${pkg.id}`, clean, { onFinish: () => submitting.value = false })
    } else {
        router.post('/admin/packages', clean, { onFinish: () => submitting.value = false })
    }
}
</script>
