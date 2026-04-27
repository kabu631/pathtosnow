<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'

const form = useForm({
  name:    '',
  email:   '',
  phone:   '',
  subject: '',
  message: '',
})

const flash = computed(() => usePage().props.flash)

function submit() {
  form.post('/contact', { onSuccess: () => form.reset() })
}

const contactInfo = [
  {
    icon: '📍',
    title: 'Our Office',
    lines: ['PathToSnow Nepal', 'Thamel, Kathmandu 44600', 'Nepal'],
  },
  {
    icon: '📞',
    title: 'Phone & WhatsApp',
    lines: ['+977-1-4700000', 'WhatsApp: +977-9800000000', 'Sun–Fri  9am–6pm NPT'],
  },
  {
    icon: '📧',
    title: 'Email',
    lines: ['hello@pathtosnow.com', 'bookings@pathtosnow.com', 'support@pathtosnow.com'],
  },
  {
    icon: '🕐',
    title: 'Response Time',
    lines: ['Within 24 hours', 'on business days'],
  },
]
</script>

<template>
  <Head>
    <title>Contact Us — PathToSnow Nepal</title>
    <meta name="description" content="Get in touch with PathToSnow Nepal. Contact form, phone, email, office address and map. We're here to help plan your perfect Nepal adventure."/>
  </Head>
  <AppLayout>

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-emerald-800 via-teal-800 to-slate-900 overflow-hidden">
      <div class="absolute inset-0 opacity-10"
           style="background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"/>
      <div class="relative container-main py-20 text-white">
        <nav class="flex items-center gap-2 text-xs text-emerald-300 mb-6" aria-label="Breadcrumb">
          <Link href="/" class="hover:text-white">Home</Link>
          <span>/</span>
          <span class="text-white font-medium">Contact Us</span>
        </nav>
        <h1 class="text-5xl font-black mb-4 leading-tight">Get in Touch</h1>
        <p class="text-emerald-100 text-lg max-w-xl">Have questions about a trek, booking, or gear? Our local experts are ready to help plan your perfect Nepal adventure.</p>
      </div>
    </section>

    <!-- Contact cards row -->
    <div class="bg-white border-b border-slate-100">
      <div class="container-main py-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div v-for="item in contactInfo" :key="item.title"
               class="flex flex-col items-center text-center p-5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50 transition-all group">
            <span class="text-3xl mb-3 group-hover:scale-110 transition-transform">{{ item.icon }}</span>
            <h3 class="font-bold text-slate-900 mb-2 text-sm">{{ item.title }}</h3>
            <p v-for="line in item.lines" :key="line" class="text-xs text-slate-500 leading-relaxed">{{ line }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content: Form + Map -->
    <div class="container-main py-14">
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        <!-- Contact Form -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-10">

            <h2 class="text-2xl font-black text-slate-900 mb-2">Send Us a Message</h2>
            <p class="text-slate-500 mb-8 text-sm">Fill out the form and we'll get back to you within 24 hours.</p>

            <!-- Success banner -->
            <div v-if="flash?.success"
                 class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4">
              <span class="text-2xl">✅</span>
              <div>
                <p class="font-semibold text-emerald-800">Message sent!</p>
                <p class="text-emerald-700 text-sm mt-0.5">{{ flash.success }}</p>
              </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2" for="cf-name">Full Name *</label>
                  <input id="cf-name" v-model="form.name" type="text" required placeholder="Your full name"
                         class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                         :class="{'border-red-400': form.errors.name}"/>
                  <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2" for="cf-email">Email Address *</label>
                  <input id="cf-email" v-model="form.email" type="email" required placeholder="you@example.com"
                         class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                         :class="{'border-red-400': form.errors.email}"/>
                  <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2" for="cf-phone">Phone / WhatsApp</label>
                  <input id="cf-phone" v-model="form.phone" type="tel" placeholder="+977 98XXXXXXXX"
                         class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"/>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2" for="cf-subject">Subject *</label>
                  <select id="cf-subject" v-model="form.subject" required
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all bg-white"
                          :class="{'border-red-400': form.errors.subject}">
                    <option value="">Select a subject…</option>
                    <option>Trekking Enquiry</option>
                    <option>Adventure Package</option>
                    <option>Booking & Payment</option>
                    <option>Gear Shop Order</option>
                    <option>Custom Group Tour</option>
                    <option>General Enquiry</option>
                    <option>Other</option>
                  </select>
                  <p v-if="form.errors.subject" class="text-red-500 text-xs mt-1">{{ form.errors.subject }}</p>
                </div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2" for="cf-message">Your Message *</label>
                <textarea id="cf-message" v-model="form.message" required rows="6"
                          placeholder="Tell us about your plans, travel dates, group size, budget, or any questions…"
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all resize-none"
                          :class="{'border-red-400': form.errors.message}"/>
                <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
              </div>

              <button type="submit" :disabled="form.processing"
                      class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 disabled:opacity-60 text-white font-bold py-4 rounded-xl text-sm transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5"
                      id="btn-contact-submit">
                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                  <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                  Sending…
                </span>
                <span v-else>✉️ Send Message</span>
              </button>

              <p class="text-center text-xs text-slate-400">We respect your privacy and will never share your information.</p>
            </form>
          </div>
        </div>

        <!-- Right: Map + extra info -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Google Map embed — Thamel, Kathmandu -->
          <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
            <iframe
              title="PathToSnow Nepal — Thamel, Kathmandu"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.8282697195087!2d85.30629931506174!3d27.71519988279534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb18fcb77fd4bd%3A0x58099b1b97680fa4!2sThamel%2C%20Kathmandu%2044600%2C%20Nepal!5e0!3m2!1sen!2snp!4v1614000000000!5m2!1sen!2snp"
              width="100%" height="300"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              class="w-full"
            />
            <div class="bg-white px-5 py-4 border-t border-slate-100">
              <p class="text-sm font-bold text-slate-900">📍 PathToSnow Nepal</p>
              <p class="text-xs text-slate-500 mt-0.5">Thamel, Kathmandu 44600, Nepal</p>
              <a href="https://maps.google.com/?q=Thamel,Kathmandu" target="_blank"
                 class="text-xs text-emerald-600 hover:text-emerald-800 font-medium mt-1 inline-block">
                Open in Google Maps →
              </a>
            </div>
          </div>

          <!-- Quick links -->
          <div class="bg-slate-900 rounded-3xl p-7">
            <h3 class="font-black text-lg mb-4 text-white">Quick Links</h3>
            <div class="space-y-3">
              <Link href="/packages" class="flex items-center gap-3 group">
                <span class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center text-lg group-hover:bg-emerald-600 transition-colors flex-shrink-0">🗺</span>
                <div>
                  <p class="text-sm font-semibold text-white">Browse Packages</p>
                  <p class="text-xs text-slate-400">Find your perfect trek</p>
                </div>
              </Link>
              <Link href="/shop" class="flex items-center gap-3 group">
                <span class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center text-lg group-hover:bg-emerald-600 transition-colors flex-shrink-0">🎒</span>
                <div>
                  <p class="text-sm font-semibold text-white">Gear Shop</p>
                  <p class="text-xs text-slate-400">Quality trekking equipment</p>
                </div>
              </Link>
              <Link href="/about" class="flex items-center gap-3 group">
                <span class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center text-lg group-hover:bg-emerald-600 transition-colors flex-shrink-0">🏔</span>
                <div>
                  <p class="text-sm font-semibold text-white">About PathToSnow</p>
                  <p class="text-xs text-slate-400">Our story & mission</p>
                </div>
              </Link>
            </div>
          </div>


          <!-- Social -->
          <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <h3 class="font-bold text-slate-900 mb-4 text-sm">Follow Us</h3>
            <div class="flex gap-3">
              <a href="#" class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white text-sm hover:opacity-80 transition-opacity font-bold">f</a>
              <a href="#" class="w-10 h-10 bg-gradient-to-br from-rose-500 to-orange-400 rounded-xl flex items-center justify-center text-white text-sm hover:opacity-80 transition-opacity font-bold">IG</a>
              <a href="#" class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-sm hover:opacity-80 transition-opacity font-bold">▶</a>
              <a href="#" class="w-10 h-10 bg-sky-500 rounded-xl flex items-center justify-center text-white text-sm hover:opacity-80 transition-opacity font-bold">in</a>
            </div>
          </div>
        </div>

      </div>
    </div>

  </AppLayout>
</template>
