<template>
<AdminLayout>
  <Head title="Manage Gallery Albums" />
  
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Gallery Albums</h1>
    <Link href="/admin/gallery/create" class="btn-primary flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Create Album
    </Link>
  </div>

  <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="bg-slate-50 text-slate-500 uppercase font-medium">
          <tr>
            <th class="px-6 py-4">Album</th>
            <th class="px-6 py-4">Images</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="album in albums.data" :key="album.id" class="hover:bg-slate-50/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <img v-if="album.cover_image" :src="album.cover_image" class="w-10 h-10 rounded object-cover bg-slate-100" />
                <div v-else class="w-10 h-10 rounded bg-emerald-50 flex items-center justify-center text-emerald-600">📁</div>
                <div>
                  <p class="font-bold text-slate-900">{{ album.title }}</p>
                  <p class="text-xs text-slate-500 truncate max-w-[200px]">{{ album.description || 'No description' }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-medium">{{ album.images_count }} photos</span>
            </td>
            <td class="px-6 py-4">
              <span v-if="album.is_active" class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-medium">Active</span>
              <span v-else class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full text-xs font-medium">Draft</span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-3">
                <Link :href="`/admin/gallery/${album.id}`" class="text-sky-600 hover:text-sky-900 text-xs font-semibold">Manage Photos</Link>
                <Link :href="`/admin/gallery/${album.id}/edit`" class="text-emerald-600 hover:text-emerald-900 text-xs font-semibold">Edit</Link>
                <button @click="deleteAlbum(album.id)" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!albums.data.length">
            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
              No gallery albums found. Click "Create Album" to add one.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
  <div v-if="albums.last_page > 1" class="mt-6 flex justify-center gap-2">
    <Link v-for="link in albums.links" :key="link.label" :href="link.url ?? '#'"
          v-html="link.label"
          :class="['px-3 py-1.5 rounded-lg text-sm font-medium transition-colors', link.active ? 'bg-emerald-600 text-white' : link.url ? 'bg-white border text-slate-600 hover:border-emerald-300' : 'opacity-50 cursor-not-allowed bg-slate-100 border']" />
  </div>
</AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({ albums: Object })

function deleteAlbum(id) {
    if(confirm('Are you sure you want to delete this album? All uploaded photos inside will also be deleted.')) {
        router.delete(`/admin/gallery/${id}`)
    }
}
</script>
