<template>
<!-- resources/js/Pages/Admin/Countries/Index.vue -->
<AdminLayout title="Abroad Countries">
    <template #actions>
        <a href="/admin/countries/create" class="btn-orange text-xs py-2 px-3">+ Add Country</a>
    </template>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Country</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Packages</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="country in countries.data" :key="country.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img v-if="country.cover_image"
                                 :src="country.cover_image"
                                 class="h-10 w-10 rounded-lg object-cover border border-gray-200 flex-shrink-0"/>
                            <div v-else class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-gray-400 text-xs">🌍</span>
                            </div>
                            <p class="font-medium text-gray-900 text-sm">{{ country.name }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ country.packages_count }} packages
                    </td>
                    <td class="px-4 py-3">
                        <span :class="country.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500'"
                              class="badge text-xs w-fit">
                            {{ country.is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a :href="`/admin/countries/${country.id}/edit`" class="text-xs text-[#E85D26] hover:underline">Edit</a>
                        <button v-if="country.packages_count === 0" @click="del(country)" class="text-xs text-red-500 hover:underline">Delete</button>
                        <span v-else class="text-xs text-gray-300 cursor-not-allowed" title="Remove packages first">Delete</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!countries.data.length" class="text-center py-16 text-gray-400 text-sm">No countries added yet.</div>
    </div>

    <!-- Pagination -->
    <div v-if="countries.last_page > 1" class="mt-4 flex justify-center gap-2">
        <a v-for="page in countries.last_page" :key="page"
           :href="`/admin/countries?page=${page}`"
           :class="page === countries.current_page
               ? 'bg-[#E85D26] text-white'
               : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
            {{ page }}
        </a>
    </div>
</AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({ countries: Object })

function del(country) {
    if (confirm(`Delete country "${country.name}"?`)) {
        router.delete(`/admin/countries/${country.id}`)
    }
}
</script>
