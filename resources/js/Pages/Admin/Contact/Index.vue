<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ messages: Object, unreadCount: Number })

const statusStyles = {
  unread:  'bg-amber-100 text-amber-800 border border-amber-200',
  read:    'bg-slate-100 text-slate-600 border border-slate-200',
  replied: 'bg-green-100 text-green-800 border border-green-200',
}
const statusIcons = { unread: '📬', read: '📖', replied: '✅' }

function fmt(d) {
  return d ? new Date(d).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '—'
}
</script>

<template>
  <Head title="Contact Messages — Admin | PathToSnow Nepal" />
  <AdminLayout>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
          Contact Messages
          <span v-if="unreadCount > 0"
                class="text-sm bg-amber-100 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full font-semibold">
            {{ unreadCount }} unread
          </span>
        </h1>
        <p class="text-gray-500 text-sm mt-0.5">Messages submitted via the Contact Us form</p>
      </div>
    </div>

    <!-- Summary cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="bg-amber-50 rounded-xl border border-amber-200 p-4">
        <p class="text-xs text-amber-700 mb-1">📬 Unread</p>
        <p class="text-2xl font-black text-amber-700">{{ messages.data.filter(m => m.status==='unread').length }}</p>
      </div>
      <div class="bg-slate-50 rounded-xl border border-slate-200 p-4">
        <p class="text-xs text-slate-600 mb-1">📖 Read</p>
        <p class="text-2xl font-black text-slate-700">{{ messages.data.filter(m => m.status==='read').length }}</p>
      </div>
      <div class="bg-green-50 rounded-xl border border-green-200 p-4">
        <p class="text-xs text-green-700 mb-1">✅ Replied</p>
        <p class="text-2xl font-black text-green-700">{{ messages.data.filter(m => m.status==='replied').length }}</p>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Sender</th>
            <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Subject</th>
            <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Status</th>
            <th class="text-left px-5 py-3.5 text-gray-500 font-semibold text-xs uppercase tracking-wider">Date</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="msg in messages.data" :key="msg.id"
              :class="['hover:bg-gray-50 transition-colors group', msg.status === 'unread' ? 'bg-amber-50/40' : '']">
            <td class="px-5 py-4">
              <p class="font-semibold text-gray-900 text-sm flex items-center gap-2">
                <span v-if="msg.status==='unread'" class="w-2 h-2 bg-amber-500 rounded-full flex-shrink-0"/>
                {{ msg.name }}
              </p>
              <p class="text-gray-400 text-xs mt-0.5">{{ msg.email }}</p>
              <p v-if="msg.phone" class="text-gray-400 text-xs">{{ msg.phone }}</p>
            </td>
            <td class="px-5 py-4">
              <p class="text-gray-800 text-sm font-medium line-clamp-1">{{ msg.subject }}</p>
              <p class="text-gray-400 text-xs mt-0.5 line-clamp-1">{{ msg.message }}</p>
            </td>
            <td class="px-5 py-4">
              <span :class="['inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold', statusStyles[msg.status]]">
                {{ statusIcons[msg.status] }} {{ msg.status }}
              </span>
            </td>
            <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">{{ fmt(msg.created_at) }}</td>
            <td class="px-5 py-4">
              <Link :href="`/admin/contact/${msg.id}`"
                    class="text-orange-600 hover:text-orange-800 text-xs font-semibold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                View →
              </Link>
            </td>
          </tr>
          <tr v-if="!messages.data.length">
            <td colspan="5" class="text-center py-16 text-gray-400">
              <p class="text-3xl mb-3">📭</p>
              <p>No messages yet.</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="messages.last_page > 1" class="flex justify-center gap-2 mt-6">
      <Link v-for="link in messages.links" :key="link.label" :href="link.url ?? '#'"
            :class="['px-4 py-2 rounded-lg text-sm font-medium border', link.active ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-gray-600 border-gray-200 hover:border-orange-300', !link.url && 'opacity-40 pointer-events-none']"
            v-html="link.label" />
    </div>

  </AdminLayout>
</template>
