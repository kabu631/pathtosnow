<template>
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-sky-50 flex items-center justify-center p-6">
  <Head title="Forgot Password — PathToSnow Nepal" />

  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <Link href="/" class="inline-flex items-center gap-2">
        <img src="/images/logo.png" alt="PathToSnow" class="h-10 object-contain" />
      </Link>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8">
      <!-- Success state -->
      <div v-if="success" class="text-center py-4">
        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <h1 class="text-xl font-black text-slate-900 mb-2">Check your inbox</h1>
        <p class="text-slate-500 text-sm mb-6">We've sent a password reset link to <strong>{{ form.email }}</strong>. Check your spam folder if you don't see it.</p>
        <Link href="/login" class="btn-primary w-full py-3 text-base">Back to sign in</Link>
      </div>

      <!-- Form state -->
      <template v-else>
        <h1 class="text-2xl font-black text-slate-900 mb-1">Forgot your password?</h1>
        <p class="text-slate-500 text-sm mb-6">Enter your email and we'll send a reset link.</p>

        <div v-if="errors.email" class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
          {{ errors.email }}
        </div>

        <form @submit.prevent="submit" class="space-y-4" novalidate>
          <div>
            <label for="email" class="label">Email address</label>
            <input id="email" v-model="form.email" type="email" class="input" placeholder="you@example.com" required autocomplete="email" autofocus />
          </div>
          <button type="submit" :disabled="submitting" class="btn-primary w-full py-3 text-base">
            <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ submitting ? 'Sending link...' : 'Send reset link' }}
          </button>
        </form>
      </template>
    </div>

    <p class="text-center text-sm text-slate-500 mt-6">
      Remember your password?
      <Link href="/login" class="text-emerald-600 hover:text-emerald-700 font-semibold">Sign in →</Link>
    </p>
  </div>
</div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const errors = computed(() => page.props.errors || {})
const success = computed(() => page.props.flash?.success)
const submitting = ref(false)
const form = ref({ email: '' })

function submit() {
  submitting.value = true
  router.post('/forgot-password', form.value, { onFinish: () => submitting.value = false })
}
</script>
