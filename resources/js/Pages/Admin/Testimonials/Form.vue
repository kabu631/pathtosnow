<template>
<AdminLayout>
  <Head :title="isEdit ? 'Edit Testimonial' : 'Add Testimonial'" />
  
  <div class="mb-6 flex items-center gap-3 text-sm text-slate-500">
    <Link href="/admin/testimonials" class="hover:text-emerald-600">Testimonials</Link>
    <span>/</span>
    <span class="font-medium text-slate-900">{{ isEdit ? 'Edit Testimonial' : 'Add Testimonial' }}</span>
  </div>

  <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form @submit.prevent="submit" class="space-y-5">
      
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Author Name</label>
        <input type="text" v-model="form.author" class="input" placeholder="e.g. Brittany Clark" required />
        <div v-if="form.errors.author" class="text-red-500 text-xs mt-1">{{ form.errors.author }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Author Location</label>
        <input type="text" v-model="form.location" class="input" placeholder="e.g. Sydney, Australia" />
        <div v-if="form.errors.location" class="text-red-500 text-xs mt-1">{{ form.errors.location }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Testimonial Quote</label>
        <textarea v-model="form.quote" class="input" rows="4" placeholder="Enter customer quote here..." required></textarea>
        <div v-if="form.errors.quote" class="text-red-500 text-xs mt-1">{{ form.errors.quote }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Rating (1 to 5 Stars)</label>
        <select v-model="form.rating" class="input" required>
            <option v-for="star in 5" :key="star" :value="star">{{ star }} Star{{ star === 1 ? '' : 's' }}</option>
        </select>
        <div v-if="form.errors.rating" class="text-red-500 text-xs mt-1">{{ form.errors.rating }}</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Author Avatar / Image</label>
        <div v-if="testimonial && testimonial.avatar && !form.avatar_file" class="mb-2">
            <img :src="testimonial.avatar" class="w-16 h-16 rounded-full object-cover border" />
        </div>
        <input type="file" @change="e => form.avatar_file = e.target.files[0]" class="input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/*" />
        <div v-if="form.errors.avatar_file" class="text-red-500 text-xs mt-1">{{ form.errors.avatar_file }}</div>
      </div>

      <div class="flex items-center gap-2 pt-2">
        <input type="checkbox" id="active" v-model="form.is_active" class="rounded text-emerald-600 focus:ring-emerald-500" />
        <label for="active" class="text-sm font-medium text-slate-700">Active (Visible on homepage)</label>
      </div>

      <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
        <Link href="/admin/testimonials" class="btn-white">Cancel</Link>
        <button type="submit" class="btn-primary" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : 'Save Testimonial' }}
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

const props = defineProps({ testimonial: Object })
const isEdit = computed(() => !!props.testimonial?.id)

const form = useForm({
    author: props.testimonial?.author || '',
    location: props.testimonial?.location || '',
    quote: props.testimonial?.quote || '',
    rating: props.testimonial?.rating || 5,
    is_active: props.testimonial?.is_active ?? true,
    avatar_file: null,
    _method: isEdit.value ? 'put' : 'post'
})

function submit() {
    if (isEdit.value) {
        form.post(`/admin/testimonials/${props.testimonial.id}`)
    } else {
        form.post('/admin/testimonials')
    }
}
</script>
