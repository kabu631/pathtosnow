<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ type: Object })
const isEdit = !!props.type?.id

const form = useForm({
  name:           props.type?.name ?? '',
  icon_emoji:     props.type?.icon_emoji ?? '📝',
  color:          props.type?.color ?? 'blue',
  hero_image_url: props.type?.hero_image_url ?? '',
  sort_order:     props.type?.sort_order ?? 99,
  is_active:      props.type?.is_active ?? true,
})

function submit() {
  if (isEdit) form.patch(`/admin/post-types/${props.type.id}`)
  else         form.post('/admin/post-types')
}

const colorOptions = ['blue','amber','purple','rose','teal','green','orange','slate','cyan','red','indigo','emerald']
</script>

<template>
  <Head :title="`${isEdit ? 'Edit' : 'New'} Post Type — Admin | PathToSnow Nepal`" />
  <AdminLayout>
    <div class="flex items-center gap-3 mb-6">
      <Link href="/admin/post-types" class="text-gray-400 hover:text-gray-700 text-sm">← Post Types</Link>
      <h1 class="text-2xl font-bold text-gray-900">{{ isEdit ? 'Edit Post Type' : 'New Post Type' }}</h1>
    </div>

    <div class="max-w-lg">
      <form @submit.prevent="submit" class="space-y-5">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
          <h2 class="font-bold text-gray-800 border-b border-gray-100 pb-3">Post Type Details</h2>

          <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Name *</label>
            <input v-model="form.name" type="text" required placeholder="e.g. Pilgrimage Stories"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
            <p class="text-xs text-gray-400 mt-1">Slug and key auto-generated from name</p>
            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Icon Emoji</label>
              <input v-model="form.icon_emoji" type="text" placeholder="⛩"
                     class="w-full border border-gray-200 rounded-xl px-4 py-3 text-2xl text-center focus:outline-none focus:ring-2 focus:ring-orange-400"/>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Sort Order</label>
              <input v-model="form.sort_order" type="number" min="0" max="255"
                     class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Badge Color</label>
            <div class="flex flex-wrap gap-2">
              <button v-for="c in colorOptions" :key="c" type="button"
                      @click="form.color = c"
                      :class="[`bg-${c}-100 text-${c}-700 border-2`, form.color === c ? `border-${c}-500` : 'border-transparent']"
                      class="px-3 py-1 rounded-full text-xs font-semibold transition-all">
                {{ c }}
              </button>
            </div>
          </div>
        </div>

        <!-- Hero image -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-800 border-b border-gray-100 pb-3">Hero Banner</h2>

          <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hero Image URL</label>
            <input v-model="form.hero_image_url" type="url" placeholder="https://images.unsplash.com/..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
            <p class="text-xs text-gray-400 mt-1">Paste any image URL (Unsplash, your server, etc.)</p>
          </div>

          <!-- Live preview -->
          <div v-if="form.hero_image_url"
               class="relative h-32 rounded-xl overflow-hidden border border-gray-200">
            <img :src="form.hero_image_url" class="absolute inset-0 w-full h-full object-cover"/>
            <div :class="`absolute inset-0 bg-${form.color}-900 opacity-60`"/>
            <div class="absolute inset-0 bg-black/20 flex items-center px-6 gap-3">
              <span class="text-3xl">{{ form.icon_emoji }}</span>
              <span class="text-white font-bold text-lg drop-shadow">{{ form.name || 'Type Name' }}</span>
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
          <div>
            <p class="font-semibold text-gray-800 text-sm">Active</p>
            <p class="text-xs text-gray-400">Hidden from forms and navigation when inactive</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="form.is_active" class="sr-only peer"/>
            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-orange-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-orange-300"></div>
            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
          </label>
        </div>

        <div class="flex gap-3">
          <button type="submit" :disabled="form.processing"
                  class="bg-orange-500 hover:bg-orange-600 disabled:opacity-60 text-white font-bold px-8 py-3 rounded-xl text-sm">
            {{ isEdit ? 'Save Changes' : 'Create Type' }}
          </button>
          <Link href="/admin/post-types" class="px-8 py-3 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50">Cancel</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
