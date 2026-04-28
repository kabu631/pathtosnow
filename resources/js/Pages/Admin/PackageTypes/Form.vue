<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ type: Object })
const isEdit = !!props.type?.id

const form = useForm({
  name:           props.type?.name ?? '',
  description:    props.type?.description ?? '',
  icon_emoji:     props.type?.icon_emoji ?? '📦',
  hero_image_url: props.type?.hero_image_url ?? '',
  gradient:       props.type?.gradient ?? 'from-slate-700 to-slate-800',
  badge_class:    props.type?.badge_class ?? 'bg-slate-100 text-slate-700',
  sort_order:     props.type?.sort_order ?? 99,
  is_active:      props.type?.is_active ?? true,
})

function submit() {
  if (isEdit) form.patch(`/admin/package-types/${props.type.id}`)
  else         form.post('/admin/package-types')
}

const gradientOptions = [
  { label: 'Red → Orange (Adventure)',     value: 'from-red-700 to-orange-700' },
  { label: 'Emerald → Green (Trekking)',   value: 'from-emerald-800 to-green-700' },
  { label: 'Violet → Purple (Valley)',     value: 'from-violet-800 to-purple-700' },
  { label: 'Green → Teal (National Park)', value: 'from-green-800 to-teal-700' },
  { label: 'Amber → Yellow (Wildlife)',    value: 'from-amber-700 to-yellow-700' },
  { label: 'Sky → Cyan (Lakes)',           value: 'from-sky-800 to-cyan-700' },
  { label: 'Slate (Default)',              value: 'from-slate-700 to-slate-800' },
  { label: 'Custom…',                      value: '' },
]

// Image upload
const fileInput = ref(null)
const uploading = ref(false)
const uploadError = ref('')

function onFileChange(e) {
  const file = e.target.files[0]
  e.target.value = ''
  if (file) uploadHeroImage(file)
}

function onDrop(e) {
  const file = e.dataTransfer.files[0]
  if (file?.type.startsWith('image/')) uploadHeroImage(file)
}

async function uploadHeroImage(file) {
  uploading.value = true
  uploadError.value = ''
  try {
    const fd = new FormData()
    fd.append('image', file)
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    const res = await fetch('/admin/upload', {
      method: 'POST',
      headers: token ? { 'X-CSRF-TOKEN': token } : {},
      body: fd,
    })
    const data = await res.json()
    if (data.url) {
      form.hero_image_url = data.url
    } else {
      uploadError.value = 'Upload failed. Please try again.'
    }
  } catch {
    uploadError.value = 'Upload failed. Please check the file and try again.'
  } finally {
    uploading.value = false
  }
}
</script>

<template>
  <Head :title="`${isEdit ? 'Edit' : 'New'} Package Type — Admin | PathToSnow Nepal`" />
  <AdminLayout>
    <div class="flex items-center gap-3 mb-6">
      <Link href="/admin/package-types" class="text-gray-400 hover:text-gray-700 text-sm">← Package Types</Link>
      <h1 class="text-2xl font-bold text-gray-900">{{ isEdit ? 'Edit Package Type' : 'New Package Type' }}</h1>
    </div>

    <div class="max-w-2xl">
      <form @submit.prevent="submit" class="space-y-6">

        <!-- Basic info -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
          <h2 class="font-bold text-gray-800 border-b border-gray-100 pb-3">Basic Information</h2>

          <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
              <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Type Name *</label>
              <input v-model="form.name" type="text" required placeholder="e.g. Pilgrimage Tour"
                     class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
              <p class="text-xs text-gray-400 mt-1">Slug and key are auto-generated from the name</p>
              <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
            </div>

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

            <div class="col-span-2">
              <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Description</label>
              <input v-model="form.description" type="text" placeholder="Short subtitle shown under the heading"
                     class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
            </div>
          </div>
        </div>

        <!-- Hero image -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
          <h2 class="font-bold text-gray-800 border-b border-gray-100 pb-3">Hero Banner</h2>

          <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Hero Image</label>

            <!-- Preview with controls (when image set) -->
            <div v-if="form.hero_image_url" class="relative h-44 rounded-xl overflow-hidden border border-gray-200 mb-3">
              <img :src="form.hero_image_url" class="absolute inset-0 w-full h-full object-cover"/>
              <div :class="`absolute inset-0 bg-gradient-to-r ${form.gradient} opacity-70`"/>
              <div class="absolute inset-0 bg-black/30 flex items-center px-6 gap-3">
                <span class="text-3xl">{{ form.icon_emoji }}</span>
                <span class="text-white font-bold text-lg drop-shadow">{{ form.name || 'Type Name' }}</span>
              </div>
              <div class="absolute top-2 right-2 flex gap-2">
                <button type="button" @click="fileInput.click()"
                        class="bg-white/90 hover:bg-white text-xs font-semibold text-gray-700 px-3 py-1.5 rounded-lg shadow transition-colors">
                  Change
                </button>
                <button type="button" @click="form.hero_image_url = ''"
                        class="bg-red-500/90 hover:bg-red-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow transition-colors">
                  Remove
                </button>
              </div>
            </div>

            <!-- Upload zone (when no image) -->
            <div v-else
                 @click="fileInput.click()"
                 @dragover.prevent
                 @drop.prevent="onDrop"
                 class="border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition-colors group mb-3">
              <template v-if="uploading">
                <svg class="w-8 h-8 text-orange-400 animate-spin mb-2" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                <p class="text-sm text-gray-500">Uploading…</p>
              </template>
              <template v-else>
                <svg class="w-10 h-10 text-gray-300 group-hover:text-orange-400 mb-2 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <p class="text-sm font-semibold text-gray-600 group-hover:text-orange-600">Click to upload or drag & drop</p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG, WebP — max 5 MB</p>
              </template>
            </div>

            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange"/>
            <p v-if="uploadError" class="text-red-500 text-xs mb-2">{{ uploadError }}</p>

            <!-- URL fallback -->
            <div>
              <p class="text-xs text-gray-400 mb-1">Or paste an image URL:</p>
              <input v-model="form.hero_image_url" type="url" placeholder="https://images.unsplash.com/..."
                     class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"/>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Color Gradient</label>
            <select v-model="form.gradient"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 bg-white">
              <option v-for="opt in gradientOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Badge Class</label>
            <input v-model="form.badge_class" type="text" placeholder="bg-red-100 text-red-700"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-orange-400"/>
          </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
          <div>
            <p class="font-semibold text-gray-800 text-sm">Active</p>
            <p class="text-xs text-gray-400">Inactive types are hidden from nav and package forms</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" v-model="form.is_active" class="sr-only peer"/>
            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-orange-500 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-orange-300"></div>
            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
          </label>
        </div>

        <div class="flex gap-3">
          <button type="submit" :disabled="form.processing"
                  class="bg-orange-500 hover:bg-orange-600 disabled:opacity-60 text-white font-bold px-8 py-3 rounded-xl text-sm transition-colors">
            {{ isEdit ? 'Save Changes' : 'Create Type' }}
          </button>
          <Link href="/admin/package-types" class="px-8 py-3 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50">Cancel</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>
