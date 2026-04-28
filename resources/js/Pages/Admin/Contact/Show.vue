<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ message: Object })

const showReplyBox = ref(false)
const replyForm  = useForm({ reply_text: '' })
const deleteForm = useForm({})

function sendReply() {
  replyForm.post(`/admin/contact/${props.message.id}/reply`, {
    onSuccess: () => { showReplyBox.value = false; replyForm.reset() }
  })
}
function remove() {
  if (confirm('Delete this message permanently?')) {
    deleteForm.delete(`/admin/contact/${props.message.id}`)
  }
}

function fmt(d) {
  return d ? new Date(d).toLocaleDateString('en-GB', { day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '—'
}

const statusStyles = {
  unread:  'bg-amber-100 text-amber-800 border border-amber-200',
  read:    'bg-slate-100 text-slate-600 border border-slate-200',
  replied: 'bg-green-100 text-green-800 border border-green-200',
}
</script>

<template>
  <Head :title="`Message from ${message.name} — Admin | PathToSnow Nepal`" />
  <AdminLayout>

    <!-- Header -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
      <Link href="/admin/contact" class="text-gray-500 hover:text-gray-800 text-sm">← Messages</Link>
      <h1 class="text-xl font-bold text-gray-900 flex-1">{{ message.subject }}</h1>
      <span :class="['px-3 py-1 rounded-full text-xs font-bold border', statusStyles[message.status]]">
        {{ message.status }}
      </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Message body -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
          <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-lg font-black flex-shrink-0">
              {{ message.name[0]?.toUpperCase() }}
            </div>
            <div>
              <p class="font-bold text-gray-900">{{ message.name }}</p>
              <p class="text-gray-500 text-sm">{{ message.email }}</p>
              <p v-if="message.phone" class="text-gray-500 text-sm">{{ message.phone }}</p>
            </div>
            <p class="ml-auto text-xs text-gray-400 whitespace-nowrap">{{ fmt(message.created_at) }}</p>
          </div>

          <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
            <p class="text-gray-800 leading-relaxed whitespace-pre-line text-sm">{{ message.message }}</p>
          </div>

          <!-- Reply box -->
          <div class="mt-6">
            <div v-if="!showReplyBox">
              <button @click="showReplyBox = true"
                      class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl text-sm transition-colors">
                ✉️ Write a Reply
              </button>
            </div>
            <div v-else class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
              <p class="text-sm font-semibold text-emerald-900 mb-3">Reply to {{ message.name }}</p>
              <textarea v-model="replyForm.reply_text" rows="5" class="input resize-none mb-3"
                        placeholder="Write your reply here…"/>
              <p v-if="replyForm.errors.reply_text" class="text-red-500 text-xs mb-2">{{ replyForm.errors.reply_text }}</p>
              <div class="flex gap-2">
                <button @click="sendReply" :disabled="replyForm.processing"
                        class="btn-primary text-sm px-6">
                  {{ replyForm.processing ? 'Sending…' : 'Send via Email' }}
                </button>
                <button @click="showReplyBox = false; replyForm.reset()" class="btn-ghost text-sm">
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions sidebar -->
      <div class="space-y-5">

        <!-- Actions -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4">Actions</h2>
          <div class="space-y-3">
            <button @click="showReplyBox = !showReplyBox"
                    class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl text-sm transition-colors">
              ✉️ {{ showReplyBox ? 'Hide Reply' : 'Reply by Email' }}
            </button>
            <button @click="remove" :disabled="deleteForm.processing"
                    class="w-full bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 font-semibold py-2.5 rounded-xl text-sm transition-colors">
              🗑 Delete Message
            </button>
          </div>
        </div>

        <!-- Details -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
          <h2 class="font-bold text-gray-900 mb-4">Details</h2>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-500">Status</span>
              <span :class="['px-2 py-0.5 rounded-full text-xs font-semibold border', statusStyles[message.status]]">{{ message.status }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Received</span>
              <span class="text-gray-800 text-xs">{{ fmt(message.created_at) }}</span>
            </div>
            <div v-if="message.ip_address" class="flex justify-between">
              <span class="text-gray-500">IP Address</span>
              <span class="text-gray-600 text-xs font-mono">{{ message.ip_address }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AdminLayout>
</template>
