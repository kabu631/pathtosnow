<template>
<AdminLayout title="Users">
  <div class="mb-5 flex flex-wrap gap-3">
    <input v-model="search" @input="filter" placeholder="Search name or email..." class="input text-sm max-w-xs" />
    <select v-model="roleF" @change="filter" class="input text-sm w-auto">
      <option value="">All roles</option>
      <option value="user">Users</option>
      <option value="admin">Admins</option>
    </select>
    <span class="ml-auto text-sm text-slate-500 self-center">
      {{ users.total }} {{ users.total === 1 ? 'user' : 'users' }} total
    </span>
  </div>

  <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">#</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Name</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Email</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 hidden md:table-cell">Phone</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 hidden lg:table-cell">Nationality</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Role</th>
          <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500 hidden lg:table-cell">Joined</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <tr v-for="u in users.data" :key="u.id" class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3 text-xs text-slate-400 font-mono">{{ u.id }}</td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-sky-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ u.name?.[0]?.toUpperCase() }}
              </div>
              <span class="font-medium text-slate-900">{{ u.name }}</span>
            </div>
          </td>
          <td class="px-4 py-3 text-slate-600">{{ u.email }}</td>
          <td class="px-4 py-3 text-slate-500 hidden md:table-cell">{{ u.phone || '—' }}</td>
          <td class="px-4 py-3 text-slate-500 hidden lg:table-cell">{{ u.nationality || '—' }}</td>
          <td class="px-4 py-3">
            <span :class="u.role === 'admin' ? 'badge-blue' : 'badge-slate'" class="badge capitalize">
              {{ u.role || 'user' }}
            </span>
          </td>
          <td class="px-4 py-3 text-xs text-slate-400 hidden lg:table-cell">{{ formatDate(u.created_at) }}</td>
          <td class="px-4 py-3 whitespace-nowrap">
            <div class="flex items-center gap-2">
              <a :href="`/admin/users/${u.id}/edit`" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium hover:underline">Edit</a>
              <button v-if="u.id !== authId" @click="deleteUser(u)" class="text-xs text-red-500 hover:text-red-700 font-medium hover:underline">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!users.data.length" class="text-center py-16 text-slate-400">
      <p class="text-4xl mb-3">👤</p>
      <p class="text-sm">No users found.</p>
    </div>
  </div>

  <div v-if="users.last_page > 1" class="flex flex-wrap gap-2 mt-5">
    <a v-for="link in users.links" :key="link.label"
       :href="link.url || '#'"
       v-html="link.label"
       :class="['px-3 py-1.5 rounded-lg text-xs transition-colors',
         link.active ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50',
         !link.url && 'opacity-40 pointer-events-none']"
    />
  </div>
</AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ users: Object, filters: Object, authId: Number })
const search = ref(props.filters?.q || '')
const roleF  = ref(props.filters?.role || '')

let t = null
function filter() {
  clearTimeout(t)
  t = setTimeout(() => {
    router.get('/admin/users', {
      q:    search.value || undefined,
      role: roleF.value  || undefined,
    }, { preserveState: true, replace: true })
  }, 350)
}

function deleteUser(u) {
  if (!confirm(`Delete user "${u.name}"? This cannot be undone.`)) return
  router.delete(`/admin/users/${u.id}`)
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}
</script>
