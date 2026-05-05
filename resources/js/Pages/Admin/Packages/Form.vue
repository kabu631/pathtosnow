<template>
<AdminLayout :title="pkg ? `Edit: ${pkg.name}` : (is_abroad ? 'New Abroad Package' : 'New Package')">
    <template #actions>
        <a :href="is_abroad ? '/admin/packages?abroad=1' : '/admin/packages'" class="btn-secondary text-xs py-2 px-3">← Back</a>
    </template>

    <form @submit.prevent="submit" class="max-w-4xl space-y-6">

        <!-- Basic info -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Basic information</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="label">Package type{{ is_abroad ? '' : ' *' }}</label>
                    <select v-model="form.type" :class="ic('type')">
                        <option value="">{{ is_abroad ? 'Select type (optional)' : 'Select type' }}</option>
                        <option v-for="(label, type) in types" :key="type" :value="type">{{ label }}</option>
                    </select>
                    <p v-if="form.errors.type" class="text-xs text-red-500 mt-1">{{ form.errors.type }}</p>
                </div>
                <div>
                    <label class="label">Country *</label>
                    <select v-model="form.country_id" :class="ic('country_id')">
                        <option v-if="!is_abroad" :value="null">None (Nepal only)</option>
                        <option v-if="is_abroad" :value="null" disabled>Select a country</option>
                        <option v-for="(name, id) in countries" :key="id" :value="id">{{ name }}</option>
                    </select>
                    <p v-if="form.errors.country_id" class="text-xs text-red-500 mt-1">{{ form.errors.country_id }}</p>
                </div>
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="label">Package name *</label>
                    <input v-model="form.name" :class="ic('name')" placeholder="Annapurna Base Camp Trek"/>
                    <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Location *</label>
                    <input v-model="form.location" :class="ic('location')" placeholder="Pokhara, Gandaki Province"/>
                    <p v-if="form.errors.location" class="text-xs text-red-500 mt-1">{{ form.errors.location }}</p>
                </div>
                <div>
                    <label class="label">Region</label>
                    <input v-model="form.region" :class="ic('region')" placeholder="Gandaki Province"/>
                    <p v-if="form.errors.region" class="text-xs text-red-500 mt-1">{{ form.errors.region }}</p>
                </div>
            </div>
            <div>
                <label class="label">Short description * (max 500 chars)</label>
                <textarea v-model="form.short_description" rows="2" :class="ic('short_description') + ' resize-none'"
                          placeholder="One-paragraph hook for listings and cards" maxlength="500"/>
                <p class="text-xs text-gray-400 mt-1">{{ form.short_description.length }}/500</p>
                <p v-if="form.errors.short_description" class="text-xs text-red-500 mt-1">{{ form.errors.short_description }}</p>
            </div>
            <div>
                <label class="label">Full description * (HTML supported)</label>
                <textarea v-model="form.description" rows="8" :class="ic('description') + ' resize-none font-mono text-xs'"
                          placeholder="<p>Full description with HTML...</p>"/>
                <p v-if="form.errors.description" class="text-xs text-red-500 mt-1">{{ form.errors.description }}</p>
            </div>
        </div>

        <!-- Pricing & Logistics -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Pricing & logistics</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="label">Price per person (NRs) *</label>
                    <input v-model.number="form.price_per_person" type="number" min="0" step="1" :class="ic('price_per_person')" placeholder="e.g. 45000"/>
                    <p v-if="form.errors.price_per_person" class="text-xs text-red-500 mt-1">{{ form.errors.price_per_person }}</p>
                </div>
                <div>
                    <label class="label">Duration (days) *</label>
                    <input v-model.number="form.duration_days" type="number" min="1" :class="ic('duration_days')"/>
                    <p v-if="form.errors.duration_days" class="text-xs text-red-500 mt-1">{{ form.errors.duration_days }}</p>
                </div>
                <div>
                    <label class="label">Duration (nights)</label>
                    <input v-model.number="form.duration_nights" type="number" min="0" :class="ic('duration_nights')"/>
                    <p v-if="form.errors.duration_nights" class="text-xs text-red-500 mt-1">{{ form.errors.duration_nights }}</p>
                </div>
                <div>
                    <label class="label">Difficulty</label>
                    <select v-model="form.difficulty" :class="ic('difficulty')">
                        <option value="">Not specified</option>
                        <option v-for="d in difficulties" :key="d" :value="d" class="capitalize">{{ d }}</option>
                    </select>
                    <p v-if="form.errors.difficulty" class="text-xs text-red-500 mt-1">{{ form.errors.difficulty }}</p>
                </div>
                <div>
                    <label class="label">Min group size</label>
                    <input v-model.number="form.min_group_size" type="number" min="1" :class="ic('min_group_size')"/>
                    <p v-if="form.errors.min_group_size" class="text-xs text-red-500 mt-1">{{ form.errors.min_group_size }}</p>
                </div>
                <div>
                    <label class="label">Max group size</label>
                    <input v-model.number="form.max_group_size" type="number" min="1" :class="ic('max_group_size')"/>
                    <p v-if="form.errors.max_group_size" class="text-xs text-red-500 mt-1">{{ form.errors.max_group_size }}</p>
                </div>
                <div>
                    <label class="label">Max altitude (m)</label>
                    <input v-model.number="form.max_altitude_m" type="number" :class="ic('max_altitude_m')" placeholder="4130"/>
                    <p v-if="form.errors.max_altitude_m" class="text-xs text-red-500 mt-1">{{ form.errors.max_altitude_m }}</p>
                </div>
                <div>
                    <label class="label">Best season</label>
                    <input v-model="form.best_season" :class="ic('best_season')" placeholder="Mar-May, Sep-Nov"/>
                    <p v-if="form.errors.best_season" class="text-xs text-red-500 mt-1">{{ form.errors.best_season }}</p>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Start point</label>
                    <input v-model="form.start_point" :class="ic('start_point')" placeholder="Nayapul"/>
                    <p v-if="form.errors.start_point" class="text-xs text-red-500 mt-1">{{ form.errors.start_point }}</p>
                </div>
                <div>
                    <label class="label">End point</label>
                    <input v-model="form.end_point" :class="ic('end_point')" placeholder="Nayapul"/>
                    <p v-if="form.errors.end_point" class="text-xs text-red-500 mt-1">{{ form.errors.end_point }}</p>
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
                    <input v-model="form.meta_title" :class="ic('meta_title')" maxlength="60"/>
                    <p v-if="form.errors.meta_title" class="text-xs text-red-500 mt-1">{{ form.errors.meta_title }}</p>
                </div>
                <div>
                    <label class="label">URL slug (auto-generated if empty)</label>
                    <input v-model="form.slug" :class="ic('slug')" placeholder="annapurna-base-camp-trek"/>
                    <p v-if="form.errors.slug" class="text-xs text-red-500 mt-1">{{ form.errors.slug }}</p>
                </div>
            </div>
            <div>
                <label class="label">SEO description (max 160 chars)</label>
                <textarea v-model="form.meta_description" rows="2" :class="ic('meta_description') + ' resize-none'" maxlength="160"/>
                <p v-if="form.errors.meta_description" class="text-xs text-red-500 mt-1">{{ form.errors.meta_description }}</p>
            </div>
        </div>

        <!-- General error summary -->
        <div v-if="Object.keys(form.errors).length" class="bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="text-sm font-medium text-red-700">Please fix the errors above before saving.</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" :disabled="form.processing" class="btn-orange px-8">
                {{ form.processing ? 'Saving...' : (pkg ? 'Update package' : 'Create package') }}
            </button>
            <a :href="is_abroad ? '/admin/packages?abroad=1' : '/admin/packages'" class="btn-secondary px-6">Cancel</a>
        </div>
    </form>
</AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ package: Object, types: Object, countries: Object, is_abroad: Boolean })
const pkg = props.package

const difficulties = ['easy', 'moderate', 'challenging', 'strenuous']

const form = useForm({
    type:              pkg?.type              || '',
    country_id:        pkg?.country_id        || null,
    name:              pkg?.name              || '',
    slug:              pkg?.slug              || '',
    location:          pkg?.location          || '',
    region:            pkg?.region            || '',
    short_description: pkg?.short_description || '',
    description:       pkg?.description       || '',
    cover_image:       pkg?.cover_image       || '',
    price_per_person:  pkg?.price_per_person  || '',
    price_nrs:         pkg?.price_nrs         || '',
    price_group:       pkg?.price_group       || '',
    duration_days:     pkg?.duration_days     || 1,
    duration_nights:   pkg?.duration_nights   || 0,
    min_group_size:    pkg?.min_group_size    || 1,
    max_group_size:    pkg?.max_group_size    || 15,
    difficulty:        pkg?.difficulty        || '',
    max_altitude_m:    pkg?.max_altitude_m    || '',
    best_season:       pkg?.best_season       || '',
    start_point:       pkg?.start_point       || '',
    end_point:         pkg?.end_point         || '',
    highlights:        pkg?.highlights        || [''],
    included:          pkg?.included          || [''],
    excluded:          pkg?.excluded          || [''],
    featured:          pkg?.featured          || false,
    active:            pkg?.active !== undefined ? pkg.active : true,
    meta_title:        pkg?.meta_title        || '',
    meta_description:  pkg?.meta_description  || '',
})

const listFields = [
    { key: 'highlights', label: 'Highlights',              placeholder: 'e.g. Annapurna Base Camp panorama' },
    { key: 'included',   label: "What's included",         placeholder: 'e.g. Licensed guide & porter' },
    { key: 'excluded',   label: 'Not included (excluded)', placeholder: 'e.g. International flights' },
]

function ic(field) {
    return form.errors[field] ? 'input border-red-500 focus:ring-red-500' : 'input'
}

function addItem(field)       { form[field].push('') }
function removeItem(field, i) { form[field].splice(i, 1) }

function submit() {
    const transform = (data) => ({
        ...data,
        highlights: data.highlights.filter(Boolean),
        included:   data.included.filter(Boolean),
        excluded:   data.excluded.filter(Boolean),
    })

    if (pkg) {
        form.transform(transform).put(`/admin/packages/${pkg.id}`)
    } else {
        form.transform(transform).post('/admin/packages')
    }
}
</script>
