<template>
<AdminLayout>
  <Head :title="`Manage Photos - ${album.title}`" />
  
  <div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3 text-sm text-slate-500">
      <Link href="/admin/gallery" class="hover:text-emerald-600">Gallery</Link>
      <span>/</span>
      <span class="font-medium text-slate-900">{{ album.title }}</span>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-6">
    <!-- Upload Section -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Upload Photos</h2>
        <form @submit.prevent="uploadPhotos">
          <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-500 transition-colors bg-slate-50 relative">
            <input type="file" multiple @change="handleFiles" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
            <div class="text-4xl mb-2">📸</div>
            <p class="text-sm font-medium text-slate-700">Click or drag photos here</p>
            <p class="text-xs text-slate-500 mt-1">Up to 5MB per image</p>
          </div>
          
          <div v-if="uploadForm.images.length" class="mt-4">
            <p class="text-sm font-medium text-slate-700 mb-2">{{ uploadForm.images.length }} files selected</p>
            <button type="submit" class="btn-primary w-full justify-center" :disabled="uploadForm.processing">
              {{ uploadForm.processing ? 'Uploading...' : 'Upload All' }}
            </button>
          </div>
          <div v-if="uploadForm.errors" class="text-red-500 text-xs mt-2">
            <p v-for="err in uploadForm.errors" :key="err">{{ err }}</p>
          </div>
        </form>
      </div>
    </div>

    <!-- Photos Grid -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Uploaded Photos ({{ album.images.length }})</h2>
        
        <div v-if="album.images.length" class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div v-for="img in album.images" :key="img.id" class="group relative aspect-square rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
            <img :src="img.image_path" class="w-full h-full object-cover" />
            
            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
              <button @click="deleteImage(img.id)" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors shadow-lg">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
        </div>
        
        <div v-else class="text-center py-16 text-slate-400">
          <p class="text-5xl mb-3">📂</p>
          <p>No photos uploaded to this album yet.</p>
        </div>
      </div>
    </div>
  </div>
</AdminLayout>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ album: Object })

const uploadForm = useForm({
    images: []
})

function handleFiles(e) {
    uploadForm.images = Array.from(e.target.files)
}

function uploadPhotos() {
    uploadForm.post(`/admin/gallery/${props.album.id}/images`, {
        preserveScroll: true,
        onSuccess: () => uploadForm.reset('images')
    })
}

function deleteImage(id) {
    if(confirm('Delete this photo?')) {
        router.delete(`/admin/gallery/images/${id}`, {
            preserveScroll: true
        })
    }
}
</script>
