<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
defineProps({ pages: Array })
</script>

<template>
  <Head title="Static Pages — Admin | PathToSnow Nepal" />
  <AdminLayout>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Static Pages</h1>
        <p class="text-gray-500 text-sm mt-0.5">Manage footer pages — About Us, Privacy Policy, Terms &amp; Contact</p>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-6 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Page</th>
            <th class="text-left px-6 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">URL</th>
            <th class="text-left px-6 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Footer</th>
            <th class="text-left px-6 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Updated</th>
            <th class="px-6 py-3.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="page in pages" :key="page.id" class="hover:bg-gray-50 group transition-colors">
            <td class="px-6 py-4">
              <p class="font-semibold text-gray-900">{{ page.title }}</p>
              <p v-if="page.meta_description" class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ page.meta_description }}</p>
            </td>
            <td class="px-6 py-4">
              <a :href="`/pages/${page.slug}`" target="_blank"
                 class="text-emerald-600 hover:text-emerald-800 text-xs font-mono">/pages/{{ page.slug }}</a>
            </td>
            <td class="px-6 py-4">
              <span :class="page.show_in_footer ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500'"
                    class="px-2 py-1 rounded-full text-xs font-medium">
                {{ page.show_in_footer ? '✓ Visible' : '– Hidden' }}
              </span>
            </td>
            <td class="px-6 py-4 text-gray-500 text-xs">
              {{ new Date(page.updated_at).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }) }}
            </td>
            <td class="px-6 py-4">
              <Link :href="`/admin/pages/${page.id}/edit`"
                    class="text-orange-600 hover:text-orange-800 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-opacity">
                Edit →
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>
