<template>
<AdminLayout>
  <Head title="Manage Testimonials" />
  
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Customer Testimonials</h1>
    <Link href="/admin/testimonials/create" class="btn-primary flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Testimonial
    </Link>
  </div>

  <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="bg-slate-50 text-slate-500 uppercase font-medium">
          <tr>
            <th class="px-6 py-4">Author</th>
            <th class="px-6 py-4">Quote</th>
            <th class="px-6 py-4">Rating</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="t in testimonials.data" :key="t.id" class="hover:bg-slate-50/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <img v-if="t.avatar" :src="t.avatar" class="w-10 h-10 rounded-full object-cover border border-slate-100 bg-slate-50" />
                <div v-else class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs uppercase">{{ t.author.slice(0,2) }}</div>
                <div>
                  <p class="font-bold text-slate-900">{{ t.author }}</p>
                  <p class="text-xs text-slate-550">{{ t.location || 'Unknown location' }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 max-w-xs md:max-w-md">
              <p class="text-slate-650 text-xs italic line-clamp-2">"{{ t.quote }}"</p>
            </td>
            <td class="px-6 py-4">
              <div class="flex text-amber-400 text-sm">
                <span v-for="star in 5" :key="star">{{ star <= t.rating ? '★' : '☆' }}</span>
              </div>
            </td>
            <td class="px-6 py-4">
              <button @click="toggleActive(t.id)"
                      :class="['px-2.5 py-1 rounded-full text-xs font-semibold transition-colors', t.is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-650 hover:bg-slate-200']">
                {{ t.is_active ? 'Active' : 'Disabled' }}
              </button>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-3">
                <Link :href="`/admin/testimonials/${t.id}/edit`" class="text-emerald-600 hover:text-emerald-900 text-xs font-semibold">Edit</Link>
                <button @click="deleteTestimonial(t.id)" class="text-red-500 hover:text-red-700 text-xs font-semibold">Delete</button>
              </div>
            </td>
          </tr>
          <tr v-if="!testimonials.data.length">
            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
              No testimonials found. Click "Add Testimonial" to add one.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <div v-if="testimonials.last_page > 1" class="mt-6 flex justify-center gap-2">
    <Link v-for="link in testimonials.links" :key="link.label" :href="link.url ?? '#'"
          v-html="link.label"
          :class="['px-3 py-1.5 rounded-lg text-sm font-medium transition-colors', link.active ? 'bg-emerald-600 text-white' : link.url ? 'bg-white border text-slate-600 hover:border-emerald-300' : 'opacity-50 cursor-not-allowed bg-slate-100 border']" />
  </div>
</AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps({
    testimonials: Object
})

function toggleActive(id) {
    router.patch(`/admin/testimonials/${id}/toggle`, {}, {
        preserveScroll: true
    })
}

function deleteTestimonial(id) {
    if(confirm('Are you sure you want to delete this testimonial?')) {
        router.delete(`/admin/testimonials/${id}`)
    }
}
</script>
