<template>
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-sky-50 flex">
  <Head title="Login — PathToSnow Nepal" />

  <!-- Left brand panel (desktop) -->
  <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-700 to-sky-800 relative overflow-hidden items-center justify-center p-12">
    <div class="absolute inset-0 bg-black/10"/>
    <div class="relative z-10 text-white text-center">
      <Link href="/" class="inline-flex items-center gap-3 mb-10">
        <img src="/images/logo_footer.png" alt="PathToSnow" class="h-14 object-contain" />
      </Link>
      <h2 class="text-3xl font-black mb-4 leading-tight">Nepal's #1<br/>travel platform</h2>
      <p class="text-emerald-100 leading-relaxed max-w-sm mx-auto">Book treks, adventures and wildlife safaris with local expert guides across the Himalayas.</p>
      <div class="mt-10 grid grid-cols-3 gap-4">
        <div v-for="s in stats" :key="s.label" class="bg-white/10 backdrop-blur rounded-2xl p-4">
          <p class="text-2xl font-black text-white">{{ s.value }}</p>
          <p class="text-xs text-emerald-200 mt-1">{{ s.label }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Right login form -->
  <div class="flex-1 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      <!-- Mobile logo -->
      <div class="lg:hidden text-center mb-8">
        <Link href="/" class="inline-flex items-center gap-2">
          <img src="/images/logo.png" alt="PathToSnow" class="h-10 object-contain" />
        </Link>
      </div>

      <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 p-8">
        <h1 class="text-2xl font-black text-slate-900 mb-1">Welcome back</h1>
        <p class="text-slate-500 text-sm mb-6">Sign in to your PathToSnow account</p>

        <!-- Error alert -->
        <div v-if="errors.email" class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
          {{ errors.email }}
        </div>

        <form @submit.prevent="submit" class="space-y-4" novalidate>
          <div>
            <label for="email" class="label">Email address</label>
            <input id="email" v-model="form.email" type="email" class="input" placeholder="you@example.com" required autocomplete="email" autofocus/>
          </div>
          <div>
            <div class="flex justify-between items-center mb-1.5">
              <label for="password" class="label mb-0">Password</label>
              <Link href="/forgot-password" class="text-xs text-emerald-600 hover:text-emerald-700">Forgot password?</Link>
            </div>
            <input id="password" v-model="form.password" type="password" class="input" placeholder="Your password" required autocomplete="current-password"/>
          </div>
          <label class="flex items-center gap-2.5 cursor-pointer text-sm text-slate-600">
            <input type="checkbox" v-model="form.remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"/>
            Remember me for 30 days
          </label>
          <button type="submit" :disabled="submitting"
                  class="btn-primary w-full py-3 text-base"
                  id="login-submit">
            <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            {{ submitting ? 'Signing in...' : 'Sign in to PathToSnow' }}
          </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
          Don't have an account?
          <Link href="/register" class="text-emerald-600 hover:text-emerald-700 font-semibold">Create one free →</Link>
        </p>
      </div>

      <p class="text-center text-xs text-slate-400 mt-6">
        By signing in, you agree to our <a href="#" class="hover:text-emerald-600">Terms of Service</a> and <a href="#" class="hover:text-emerald-600">Privacy Policy</a>.
      </p>
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
const form = ref({ email: '', password: '', remember: false })

function submit() {
  submitting.value = true
  router.post('/login', form.value, { onFinish: () => submitting.value = false })
}

const stats = [
  { value: '50+', label: 'Packages' },
  { value: '10+', label: 'Years local' },
  { value: '4.9★', label: 'Rating' },
]
</script>
