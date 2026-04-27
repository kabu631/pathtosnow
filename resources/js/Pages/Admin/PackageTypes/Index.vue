<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ types: Array })
const del = useForm({})
function destroy(id) {
  if (confirm('Delete this package type?')) del.delete(`/admin/package-types/${id}`)
}
</script>

<template>
  <Head title="Package Types — Admin | PathToSnow Nepal" />
  <AdminLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Package Types</h1>
        <p class="text-gray-500 text-sm mt-0.5">Manage experience categories (shown in nav & package forms)</p>
      </div>
      <Link href="/admin/package-types/create"
            class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
        + Add Type
      </Link>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3.5 text-gray-500 text-xs font-semibold uppercase tracking-wider">Type</th>
            <th class="text-left px-5 py-3.5 text-gray-500 text-xs font-semibold uppercase tracking-wider">Slug / Key</th>
            <th class="text-left px-5 py-3.5 text-gray-500 text-xs font-semibold uppercase tracking-wider">Hero Image</th>
            <th class="text-left px-5 py-3.5 text-gray-500 text-xs font-semibold uppercase tracking-wider">Sort</th>
            <th class="text-left px-5 py-3.5 text-gray-500 text-xs font-semibold uppercase tracking-wider">Status</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="t in types" :key="t.id" class="hover:bg-gray-50 group">
            <td class="px-5 py-4">
              <div class="flex items-center gap-3">
                <span class="text-2xl">{{ t.icon_emoji }}</span>
                <div>
                  <p class="font-semibold text-gray-900">{{ t.name }}</p>
                  <p class="text-xs text-gray-400 mt-0.5 max-w-xs truncate">{{ t.description }}</p>
                </div>
              </div>
            </td>
            <td class="px-5 py-4">
              <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono block mb-1">/packages/type/{{ t.slug }}</code>
              <code class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded font-mono block">key: {{ t.type_key }}</code>
            </td>
            <td class="px-5 py-4">
              <img v-if="t.hero_image_url" :src="t.hero_image_url" class="w-20 h-12 object-cover rounded-lg border border-gray-100"/>
              <span v-else class="text-gray-300 text-xs">No image</span>
            </td>
            <td class="px-5 py-4 text-gray-500">{{ t.sort_order }}</td>
            <td class="px-5 py-4">
              <span :class="t.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                    class="text-xs font-semibold px-2.5 py-1 rounded-full">
                {{ t.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-5 py-4">
              <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <Link :href="`/admin/package-types/${t.id}/edit`" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Edit</Link>
                <button @click="destroy(t.id)" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!types.length">
            <td colspan="6" class="text-center py-12 text-gray-400">No types found. Add your first package type.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>
