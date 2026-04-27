<template>
<AdminLayout>
  <Head :title="isEdit ? 'Edit Album' : 'Create Album'" />
  
  <div class="mb-6 flex items-center gap-3 text-sm text-slate-500">
    <Link href="/admin/gallery" class="hover:text-emerald-600">Gallery</Link>
    <span>/</span>
    <span class="font-medium text-slate-900">{{ isEdit ? 'Edit Album' : 'Create Album' }}</span>
  </div>

  <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form @submit.prevent="submit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Album Title (Location)</label>
        <input type="text" v-model="form.title" class="input" placeholder="e.g. Kathmandu, Everest Base Camp" required />
        <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
        <textarea v-model="form.description" class="input" rows="3" placeholder="Brief description of this location..."></textarea>
        <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Cover Image</label>
        <div v-if="album.cover_image && !form.cover_image" class="mb-2">
            <img :src="album.cover_image" class="w-32 h-24 object-cover rounded-lg border" />
        </div>
        <input type="file" @change="e => form.cover_image = e.target.files[0]" class="input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/*" />
        <div v-if="form.errors.cover_image" class="text-red-500 text-xs mt-1">{{ form.errors.cover_image }}</div>
      </div>
      
      <div class="flex items-center gap-2 pt-2">
        <input type="checkbox" id="active" v-model="form.is_active" class="rounded text-emerald-600 focus:ring-emerald-500" />
        <label for="active" class="text-sm font-medium text-slate-700">Active (Visible to public)</label>
      </div>

      <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
        <Link href="/admin/gallery" class="btn-white">Cancel</Link>
        <button type="submit" class="btn-primary" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : 'Save Album' }}
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

const props = defineProps({ album: Object })
const isEdit = computed(() => !!props.album.id)

const form = useForm({
    title: props.album.title || '',
    description: props.album.description || '',
    is_active: props.album.is_active ?? true,
    cover_image: null,
    _method: isEdit.value ? 'put' : 'post'
})

function submit() {
    if (isEdit.value) {
        form.post(`/admin/gallery/${props.album.id}`)
    } else {
        form.post('/admin/gallery')
    }
}
</script>
