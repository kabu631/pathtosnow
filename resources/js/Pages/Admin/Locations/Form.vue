<template>
<AdminLayout>
  <Head :title="isEdit ? 'Edit Location' : 'Create Location'" />
  
  <div class="mb-6 flex items-center gap-3 text-sm text-slate-500">
    <Link href="/admin/locations" class="hover:text-emerald-600">Locations</Link>
    <span>/</span>
    <span class="font-medium text-slate-900">{{ isEdit ? 'Edit Location' : 'Create Location' }}</span>
  </div>

  <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form @submit.prevent="submit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Location Name (Region)</label>
        <input type="text" v-model="form.name" class="input" placeholder="e.g. Everest Region, Pokhara Lakes" required />
        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea v-model="form.description" class="input" rows="3" placeholder="Brief description of this region (shown in public detail page)..."></textarea>
        <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Cover Image</label>
        <div v-if="location && location.cover_image && !form.cover_image_file" class="mb-2">
            <img :src="location.cover_image" class="w-32 h-24 object-cover rounded-lg border" />
        </div>
        <input type="file" @change="e => form.cover_image_file = e.target.files[0]" class="input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/*" />
        <div v-if="form.errors.cover_image_file" class="text-red-500 text-xs mt-1">{{ form.errors.cover_image_file }}</div>
      </div>
      
      <div class="flex items-center gap-2 pt-2">
        <input type="checkbox" id="active" v-model="form.is_active" class="rounded text-emerald-600 focus:ring-emerald-500" />
        <label for="active" class="text-sm font-medium text-slate-700">Active (Visible to public)</label>
      </div>

      <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
        <Link href="/admin/locations" class="btn-white">Cancel</Link>
        <button type="submit" class="btn-primary" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : 'Save Location' }}
        </button>
      </div>
    </form>
  </div>
</AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ location: Object })
const isEdit = computed(() => !!props.location?.id)

const form = useForm({
    name: props.location?.name || '',
    description: props.location?.description || '',
    is_active: props.location?.is_active ?? true,
    cover_image_file: null,
    _method: isEdit.value ? 'put' : 'post'
})

function submit() {
    if (isEdit.value) {
        form.post(`/admin/locations/${props.location.id}`)
    } else {
        form.post('/admin/locations')
    }
}
</script>
