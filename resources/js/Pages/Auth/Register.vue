<template>
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-sky-50 flex">
  <Head title="Create Account — PathToSnow Nepal" />

  <!-- Left brand panel (desktop) -->
  <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-sky-700 to-emerald-700 relative overflow-hidden items-center justify-center p-12">
    <div class="absolute inset-0 bg-black/10"/>
    <div class="relative z-10 text-white text-center">
      <Link href="/" class="inline-flex items-center gap-3 mb-10">
        <img src="/images/logo_footer.png" alt="PathToSnow" class="h-14 object-contain" />
      </Link>
      <h2 class="text-3xl font-black mb-4 leading-tight">Start your<br/>Nepal journey</h2>
      <p class="text-sky-100 leading-relaxed max-w-sm mx-auto">Join thousands of travellers who book Himalayan adventures directly with local guides — no middleman, best price.</p>
      <div class="mt-10 space-y-3 text-left max-w-sm mx-auto">
        <div v-for="b in benefits" :key="b" class="flex items-center gap-3 bg-white/10 backdrop-blur rounded-xl px-4 py-3 text-sm">
          <span class="text-emerald-300 font-bold flex-shrink-0">✓</span>
          {{ b }}
        </div>
      </div>
    </div>
  </div>

  <!-- Right register form -->
  <div class="flex-1 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      <!-- Mobile logo -->
      <div class="lg:hidden text-center mb-8">
        <Link href="/" class="inline-flex items-center gap-2">
          <img src="/images/logo.png" alt="PathToSnow" class="h-10 object-contain" />
        </Link>
      </div>

      <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8">
        <h1 class="text-2xl font-black text-slate-900 mb-1">Create your account</h1>
        <p class="text-slate-500 text-sm mb-6">Join PathToSnow to book and track your trips</p>

        <form @submit.prevent="submit" class="space-y-4" novalidate>
          <div>
            <label for="name" class="label">Full name</label>
            <input id="name" v-model="form.name" type="text" class="input" placeholder="Ram Shrestha" required autocomplete="name"/>
            <p v-if="errors.name" class="text-xs text-red-600 mt-1">{{ errors.name }}</p>
          </div>
          <div>
            <label for="reg-email" class="label">Email address</label>
            <input id="reg-email" v-model="form.email" type="email" class="input" placeholder="you@example.com" required autocomplete="email"/>
            <p v-if="errors.email" class="text-xs text-red-600 mt-1">{{ errors.email }}</p>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label for="reg-password" class="label">Password</label>
              <input id="reg-password" v-model="form.password" type="password" class="input" placeholder="Min. 8 chars" required autocomplete="new-password"/>
              <p v-if="errors.password" class="text-xs text-red-600 mt-1">{{ errors.password }}</p>
            </div>
            <div>
              <label for="reg-confirm" class="label">Confirm</label>
              <input id="reg-confirm" v-model="form.password_confirmation" type="password" class="input" placeholder="Repeat password" required autocomplete="new-password"/>
            </div>
          </div>
          <button type="submit" :disabled="submitting" class="btn-primary w-full py-3 text-base" id="register-submit">
            <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            {{ submitting ? 'Creating account...' : 'Create free account' }}
          </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
          Already have an account?
          <Link href="/login" class="text-emerald-600 hover:text-emerald-700 font-semibold">Sign in →</Link>
        </p>
      </div>
    </div>
  </div>
</div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const errors    = computed(() => page.props.errors || {})
const submitting = ref(false)
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })

function submit() {
  submitting.value = true
  router.post('/register', form.value, { onFinish: () => submitting.value = false })
}

const benefits = [
  'Book directly with certified local guides',
  'Track all your bookings in one place',
  'Exclusive members-only gear discounts',
  'Free cancellation on most packages',
]
</script>
