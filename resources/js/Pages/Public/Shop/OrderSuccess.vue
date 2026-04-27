<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
defineProps({ orderNumber: String })
</script>

<template>
  <Head title="Order Successful — PathToSnow Shop" />
  <AppLayout>
    <div class="min-h-[75vh] flex items-center justify-center px-6 py-16 bg-slate-50">
      <div class="text-center max-w-lg w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-10">

        <!-- Animated checkmark -->
        <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
          <svg class="w-12 h-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          <span class="absolute -top-1 -right-1 text-2xl">🎉</span>
        </div>

        <h1 class="text-3xl font-black text-slate-900 mb-2">Your order has been successful!</h1>
        <p class="text-slate-500 mb-4">Thank you for shopping with PathToSnow. Your gear is being prepared for dispatch.</p>

        <div v-if="orderNumber" class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-2.5 rounded-xl mb-6">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          <span class="font-mono font-bold text-sm">Order {{ orderNumber }}</span>
        </div>

        <!-- What's next -->
        <div class="bg-slate-50 rounded-2xl p-5 mb-7 text-left space-y-3">
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">What happens next?</p>
          <div class="flex items-start gap-3">
            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
            <div>
              <p class="text-sm font-semibold text-slate-800">Confirmation email sent</p>
              <p class="text-xs text-slate-500">Check your inbox for order details</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
            <div>
              <p class="text-sm font-semibold text-slate-800">Order processing (1–2 days)</p>
              <p class="text-xs text-slate-500">We pack and prepare your gear from Kathmandu</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
            <div>
              <p class="text-sm font-semibold text-slate-800">Delivery (3–5 business days)</p>
              <p class="text-xs text-slate-500">Within Nepal. International orders vary.</p>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <Link href="/shop" class="btn-primary px-8 py-3">Continue Shopping →</Link>
          <Link href="/" class="btn-ghost px-8 py-3">Back to Home</Link>
        </div>

      </div>
    </div>
  </AppLayout>
</template>
