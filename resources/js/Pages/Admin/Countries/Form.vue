<template>
<!-- resources/js/Pages/Admin/Countries/Form.vue -->
<AdminLayout :title="country ? `Edit: ${country.name}` : 'New Country'">
    <template #actions>
        <a href="/admin/countries" class="btn-secondary text-xs py-2 px-3">← Countries</a>
    </template>
    <form @submit.prevent="submit" class="max-w-2xl space-y-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <div>
                <label class="label">Country Name *</label>
                <input v-model="form.name" class="input" required placeholder="e.g. Bhutan"/>
            </div>
            <div>
                <label class="label">Description</label>
                <textarea v-model="form.description" rows="4" class="input resize-none" placeholder="Brief intro about this destination..."/>
            </div>
            <div>
                <label class="label">Cover Image</label>
                <input type="file" accept="image/*" @change="handleFile" class="input p-2 text-sm" />
                <div v-if="form.errors.cover_image" class="text-red-500 text-xs mt-1">{{ form.errors.cover_image }}</div>
            </div>
            
            <div v-if="form.cover_image && typeof form.cover_image === 'object'" class="mt-2">
                <p class="text-xs font-semibold text-emerald-600 mb-1">New Image Preview:</p>
                <img :src="getObjectUrl(form.cover_image)" class="h-32 object-cover rounded-lg border"/>
            </div>
            <div v-else-if="country?.cover_image" class="mt-2">
                <p class="text-xs font-semibold text-slate-500 mb-1">Current Image:</p>
                <img :src="country.cover_image" class="h-32 object-cover rounded-lg border"/>
            </div>
            
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-medium">
                    <input type="checkbox" v-model="form.is_active" class="rounded text-emerald-600"/> 
                    Visible to public
                </label>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" :disabled="submitting" class="btn-orange px-8">
                {{ submitting ? 'Saving...' : (country ? 'Update country' : 'Add country') }}
            </button>
            <a href="/admin/countries" class="btn-secondary px-6">Cancel</a>
        </div>
    </form>
</AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ country: Object })
const submitting = ref(false)

const form = useForm({
    name: props.country?.name || '',
    description: props.country?.description || '',
    cover_image: null,
    is_active: props.country ? props.country.is_active : true,
})

function handleFile(e) {
    if (e.target.files.length) {
        form.cover_image = e.target.files[0]
    }
}

function getObjectUrl(file) {
    return URL.createObjectURL(file)
}

function submit() {
    submitting.value = true
    if (props.country) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/countries/${props.country.id}`, { onFinish: () => submitting.value = false })
    } else {
        form.post('/admin/countries', { onFinish: () => submitting.value = false })
    }
}
</script>
