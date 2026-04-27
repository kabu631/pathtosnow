<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ bookings: Object })

const statusColors = {
  pending: 'bg-amber-500/20 text-amber-400',
  confirmed: 'bg-blue-500/20 text-blue-400',
  in_progress: 'bg-purple-500/20 text-purple-400',
  completed: 'bg-green-500/20 text-green-400',
  cancelled: 'bg-red-500/20 text-red-400',
}

function fmt(d) {
  return d ? new Date(d).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '—'
}
</script>

<template>
  <Head title="My Bookings" />
  <AppLayout>
    <div class="max-w-4xl mx-auto px-6 py-14">
      <h1 class="text-3xl font-bold text-white mb-8">My Bookings</h1>

      <div v-if="bookings.data.length" class="space-y-4">
        <div v-for="b in bookings.data" :key="b.id" class="card p-6 flex flex-col sm:flex-row gap-4 items-start">
          <img v-if="b.package?.cover_image" :src="b.package.cover_image" class="w-24 h-20 object-cover rounded-xl flex-shrink-0" />
          <div v-else class="w-24 h-20 rounded-xl bg-slate-700 flex-shrink-0 flex items-center justify-center text-2xl">🏔️</div>

          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2 flex-wrap">
              <div>
                <p class="font-semibold text-white text-lg">{{ b.package?.name }}</p>
                <p class="text-slate-400 text-sm mt-0.5">Ref: {{ b.booking_reference }}</p>
              </div>
              <span :class="['badge text-xs px-3 py-1', statusColors[b.status] ?? 'bg-slate-700 text-slate-300']">
                {{ b.status.replace('_', ' ') }}
              </span>
            </div>
            <div class="flex flex-wrap gap-4 mt-3 text-sm text-slate-400">
              <span>📅 Travel: <strong class="text-slate-200">{{ fmt(b.travel_date) }}</strong></span>
              <span>👥 Group: <strong class="text-slate-200">{{ b.group_size }}</strong></span>
              <span>💵 Total: <strong class="text-orange-400">${{ Number(b.total_price).toFixed(2) }}</strong></span>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-20">
        <p class="text-5xl mb-4">🏔️</p>
        <p class="text-xl text-slate-400 mb-6">No bookings yet</p>
        <Link :href="route('packages.index')" class="btn-primary">Explore Packages</Link>
      </div>

      <div v-if="bookings.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link v-for="link in bookings.links" :key="link.label" :href="link.url ?? '#'"
              :class="['px-4 py-2 rounded-lg text-sm', link.active ? 'bg-orange-500 text-white' : 'bg-slate-800 text-slate-300']"
              v-html="link.label" />
      </div>
    </div>
  </AppLayout>
</template>
