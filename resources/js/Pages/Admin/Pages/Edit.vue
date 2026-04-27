<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ page: Object })
const form = useForm({
  title:            props.page.title,
  content:          props.page.content,
  meta_description: props.page.meta_description ?? '',
  show_in_footer:   props.page.show_in_footer ?? true,
  sort_order:       props.page.sort_order ?? 0,
})

function save() {
  form.patch(`/admin/pages/${props.page.id}`)
}
</script>

<template>
  <Head :title="`Edit: ${page.title} — Admin | PathToSnow Nepal`" />
  <AdminLayout>

    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
      <Link href="/admin/pages" class="text-gray-500 hover:text-gray-800 text-sm">← Pages</Link>
      <h1 class="text-xl font-bold text-gray-900">Edit: {{ page.title }}</h1>
      <a :href="`/pages/${page.slug}`" target="_blank"
         class="text-xs text-emerald-600 hover:text-emerald-800 font-medium ml-auto">
        👁 Preview page →
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Content -->
      <div class="lg:col-span-2 space-y-5">

        <!-- Title -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <label class="block text-sm font-semibold text-gray-700 mb-2" for="page-title">Page Title</label>
          <input id="page-title" v-model="form.title" type="text"
                 class="w-full border border-gray-300 bg-white text-gray-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300"
                 placeholder="e.g. About Us"/>
          <p v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</p>
        </div>

        <!-- Content (textarea — admin can paste HTML or write plain text) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
          <label class="block text-sm font-semibold text-gray-700 mb-2" for="page-content">
            Page Content
            <span class="font-normal text-gray-400 ml-2">— HTML is supported</span>
          </label>
          <textarea id="page-content" v-model="form.content"
                    rows="22"
                    class="w-full border border-gray-300 bg-white text-gray-800 rounded-lg px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-300 resize-y"
                    placeholder="Enter page content. HTML tags like &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt; are supported."/>
          <p v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</p>
        </div>

      </div>

      <!-- Settings sidebar -->
      <div class="space-y-5">

        <!-- Save -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4">Publish</h2>
          <button @click="save" :disabled="form.processing"
                  class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors"
                  id="btn-save-page">
            {{ form.processing ? 'Saving…' : '💾 Save Changes' }}
          </button>
          <p v-if="form.recentlySuccessful" class="text-xs text-emerald-600 text-center mt-2 font-medium">✅ Saved successfully!</p>
        </div>

        <!-- SEO -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4">SEO</h2>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Meta Description</label>
          <textarea v-model="form.meta_description" rows="3"
                    class="w-full border border-gray-300 bg-white text-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 resize-none"
                    placeholder="Short summary for search engines (max 300 chars)"/>
          <p class="text-xs text-gray-400 mt-1">{{ (form.meta_description ?? '').length }}/300</p>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4">Settings</h2>

          <label class="flex items-center gap-3 cursor-pointer mb-4">
            <input type="checkbox" v-model="form.show_in_footer"
                   class="w-4 h-4 rounded text-emerald-600 border-gray-300 focus:ring-emerald-400"/>
            <div>
              <p class="text-sm font-medium text-gray-800">Show in Footer</p>
              <p class="text-xs text-gray-400">Display this page link in the website footer</p>
            </div>
          </label>

          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sort Order</label>
          <input type="number" v-model="form.sort_order" min="0"
                 class="w-full border border-gray-300 bg-white text-gray-800 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300"/>
          <p class="text-xs text-gray-400 mt-1">Lower number appears first in footer</p>
        </div>

        <!-- Info -->
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 text-xs text-gray-500 space-y-1">
          <p><span class="font-medium">URL:</span> <code class="text-emerald-700">/pages/{{ page.slug }}</code></p>
          <p><span class="font-medium">Created:</span> {{ new Date(page.created_at).toLocaleDateString('en-GB') }}</p>
        </div>

      </div>
    </div>

  </AdminLayout>
</template>
