<template>
<AdminLayout :title="`Edit User — ${user.name}`">
  <template #actions>
    <a href="/admin/users" class="btn-secondary text-xs py-2 px-3">← Users</a>
  </template>

  <div class="max-w-xl">
    <form @submit.prevent="submit" class="space-y-5">

      <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-3">Profile</h2>

        <div>
          <label class="label">Full name</label>
          <input v-model="form.name" type="text" class="input" required />
          <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="label">Email address</label>
          <input v-model="form.email" type="email" class="input" required />
          <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="label">Phone</label>
            <input v-model="form.phone" type="text" class="input" placeholder="+977-..." />
          </div>
          <div>
            <label class="label">Nationality</label>
            <input v-model="form.nationality" type="text" class="input" placeholder="Nepali" />
          </div>
        </div>

        <div>
          <label class="label">Role</label>
          <select v-model="form.role" class="input">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
          <p class="text-xs text-slate-400 mt-1">Admins have full access to the admin panel.</p>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <h2 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-3">Change Password <span class="text-slate-400 font-normal">(leave blank to keep current)</span></h2>

        <div>
          <label class="label">New password</label>
          <input v-model="form.password" type="password" class="input" placeholder="Min. 8 characters" autocomplete="new-password" />
          <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
        </div>
        <div>
          <label class="label">Confirm new password</label>
          <input v-model="form.password_confirmation" type="password" class="input" placeholder="Repeat password" autocomplete="new-password" />
        </div>
      </div>

      <div class="flex gap-3">
        <button type="submit" :disabled="form.processing" class="btn-primary px-8">
          {{ form.processing ? 'Saving...' : 'Save Changes' }}
        </button>
        <a href="/admin/users" class="btn-secondary px-6">Cancel</a>
      </div>
    </form>
  </div>
</AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ user: Object })

const form = useForm({
  name:                  props.user.name,
  email:                 props.user.email,
  role:                  props.user.role || 'user',
  phone:                 props.user.phone || '',
  nationality:           props.user.nationality || '',
  password:              '',
  password_confirmation: '',
})

function submit() {
  form.put(`/admin/users/${props.user.id}`)
}
</script>
