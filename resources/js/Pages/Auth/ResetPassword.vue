<template>
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-sky-50 flex items-center justify-center p-6">
  <Head title="Reset Password — PathToSnow Nepal" />

  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <Link href="/" class="inline-flex items-center gap-2">
        <img src="/images/logo.png" alt="PathToSnow" class="h-10 object-contain" />
      </Link>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8">
      <h1 class="text-2xl font-black text-slate-900 mb-1">Set new password</h1>
      <p class="text-slate-500 text-sm mb-6">Choose a strong password for your account.</p>

      <div v-if="errors.email" class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
        {{ errors.email }}
      </div>

      <form @submit.prevent="submit" class="space-y-4" novalidate>
        <div>
          <label for="email" class="label">Email address</label>
          <input id="email" v-model="form.email" type="email" class="input" placeholder="you@example.com" required autocomplete="email" />
          <p v-if="errors.email" class="text-xs text-red-600 mt-1">{{ errors.email }}</p>
        </div>
        <div>
          <label for="password" class="label">New password</label>
          <input id="password" v-model="form.password" type="password" class="input" placeholder="Min. 8 characters" required autocomplete="new-password" autofocus />
          <p v-if="errors.password" class="text-xs text-red-600 mt-1">{{ errors.password }}</p>
        </div>
        <div>
          <label for="password-confirm" class="label">Confirm new password</label>
          <input id="password-confirm" v-model="form.password_confirmation" type="password" class="input" placeholder="Repeat password" required autocomplete="new-password" />
        </div>
        <button type="submit" :disabled="submitting" class="btn-primary w-full py-3 text-base">
          <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
          </svg>
          {{ submitting ? 'Resetting...' : 'Reset password' }}
        </button>
      </form>
    </div>

    <p class="text-center text-sm text-slate-500 mt-6">
      Back to <Link href="/login" class="text-emerald-600 hover:text-emerald-700 font-semibold">sign in</Link>
    </p>
  </div>
</div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({ token: String, email: String })
const page = usePage()
const errors = computed(() => page.props.errors || {})
const submitting = ref(false)
const form = ref({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

function submit() {
  submitting.value = true
  router.post('/reset-password', form.value, { onFinish: () => submitting.value = false })
}
</script>
