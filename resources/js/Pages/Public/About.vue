<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const stats = [
  { number: '10+', label: 'Years of Experience' },
  { number: '5,000+', label: 'Happy Trekkers' },
  { number: '120+', label: 'Curated Packages' },
  { number: '50+', label: 'Local Expert Guides' },
]

const services = [
  {
    icon: '🏔',
    title: 'Trekking & Hiking',
    desc: 'From the iconic Everest Base Camp to hidden Himalayan trails, we offer fully guided, safety-first trekking expeditions for all fitness levels.',
    color: 'bg-emerald-50 border-emerald-200',
    iconBg: 'bg-emerald-100',
  },
  {
    icon: '⚡',
    title: 'Adventure Activities',
    desc: "Paragliding over Pokhara, bungee jumping, white-water rafting, mountain biking — Nepal's best adrenaline experiences, all in one place.",
    color: 'bg-orange-50 border-orange-200',
    iconBg: 'bg-orange-100',
  },
  {
    icon: '🏛',
    title: 'Cultural Tours',
    desc: 'Explore UNESCO World Heritage temples, ancient Newari cities, colourful festivals, and authentic village homestays guided by local experts.',
    color: 'bg-violet-50 border-violet-200',
    iconBg: 'bg-violet-100',
  },
  {
    icon: '🌿',
    title: 'National Parks & Wildlife',
    desc: 'Jungle safaris in Chitwan and Bardia to spot rhinos, tigers, elephants and hundreds of bird species in their natural habitat.',
    color: 'bg-green-50 border-green-200',
    iconBg: 'bg-green-100',
  },
  {
    icon: '🎒',
    title: 'Gear & Equipment Shop',
    desc: 'Genuine Nepali trekking gear, clothing, souvenirs and equipment — carefully selected for quality and shipped worldwide.',
    color: 'bg-sky-50 border-sky-200',
    iconBg: 'bg-sky-100',
  },
  {
    icon: '📖',
    title: 'Travel Guide & Blog',
    desc: 'In-depth travel guides on Nepal food, culture, festivals and destinations — written by locals to help you travel smarter.',
    color: 'bg-amber-50 border-amber-200',
    iconBg: 'bg-amber-100',
  },
]

const values = [
  { icon: '🤝', title: 'Community First', desc: 'Every booking directly supports local guides, porters, and village communities across Nepal.' },
  { icon: '🌱', title: 'Sustainable Tourism', desc: 'We follow strict leave-no-trace principles and partner only with eco-conscious operators.' },
  { icon: '🛡', title: 'Safety Always', desc: 'Certified guides, proper gear, insurance, and 24/7 emergency support on all adventures.' },
  { icon: '✨', title: 'Authentic Experiences', desc: 'No cookie-cutter tours. Every itinerary is crafted to connect you deeply with Nepal.' },
]

const team = [
  { name: 'Bikash Tamang', role: 'Founder & Lead Trek Guide', emoji: '🧗', exp: '15+ years trekking experience' },
  { name: 'Priya Shrestha', role: 'Head of Operations', emoji: '📋', exp: 'Expert in luxury Nepal tours' },
  { name: 'Ram Bahadur', role: 'Senior Guide — Everest Region', emoji: '🏔', exp: '200+ Everest BC ascents' },
  { name: 'Sita Gurung', role: 'Cultural Tour Specialist', emoji: '🎭', exp: 'Kathmandu Valley expert' },
]
</script>

<template>
  <Head>
    <title>About Us — PathToSnow Nepal | Himalayan Travel & Trekking</title>
    <meta name="description" content="Learn about PathToSnow Nepal — your trusted local partner for authentic Himalayan trekking, adventure tours, wildlife safaris, and quality gear since 2014."/>
  </Head>
  <AppLayout>

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-slate-900 via-emerald-900 to-teal-900 overflow-hidden py-24">
      <!-- Mountain silhouette decoration -->
      <div class="absolute bottom-0 left-0 right-0 opacity-10">
        <svg viewBox="0 0 1440 200" fill="white" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
          <path d="M0,200 L0,120 L120,40 L240,100 L360,20 L520,120 L640,0 L800,100 L920,30 L1080,110 L1200,50 L1320,120 L1440,80 L1440,200 Z"/>
        </svg>
      </div>
      <div class="relative container-main text-white text-center">
        <p class="text-emerald-300 font-semibold mb-4 text-sm tracking-widest uppercase">Est. 2014 · Thamel, Kathmandu</p>
        <h1 class="text-5xl md:text-6xl font-black mb-6 leading-tight">
          Nepal's Most Trusted<br>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-200">Travel Marketplace</span>
        </h1>
        <p class="text-emerald-100 text-lg max-w-2xl mx-auto leading-relaxed">
          PathToSnow connects adventurers from around the world with authentic, locally-guided Himalayan experiences — from summit treks to cultural deep-dives and quality gear.
        </p>
      </div>
    </section>

    <!-- Stats bar -->
    <div class="bg-white border-b border-slate-100">
      <div class="container-main py-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
          <div v-for="stat in stats" :key="stat.label" class="py-4">
            <p class="text-4xl font-black text-emerald-600 mb-1">{{ stat.number }}</p>
            <p class="text-sm text-slate-500 font-medium">{{ stat.label }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Our Story -->
    <section class="container-main py-16">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
        <div>
          <p class="text-emerald-600 font-semibold text-sm uppercase tracking-widest mb-3">Our Story</p>
          <h2 class="text-4xl font-black text-slate-900 mb-6 leading-tight">Born from a Love for Nepal's Mountains</h2>
          <div class="space-y-4 text-slate-600 leading-relaxed">
            <p>PathToSnow was founded in 2014 by a group of passionate local guides and travel enthusiasts in Thamel, Kathmandu. We saw that most visitors to Nepal were getting generic, overpriced tours that barely scratched the surface of this incredible country.</p>
            <p>Our mission was simple: <strong class="text-slate-900">connect travellers with real Nepal</strong>. Not the tourist-brochure Nepal, but the Nepal where yak herders share their stories, where centuries-old temples hide in back alleys, where the Himalayas take your breath away — literally.</p>
            <p>Today, PathToSnow is Nepal's leading digital travel marketplace, helping over 5,000 trekkers and adventure seekers every year find their perfect Himalayan experience — all while ensuring the benefits flow directly to local communities.</p>
          </div>
          <div class="mt-8 flex flex-wrap gap-4">
            <Link href="/packages" class="btn-primary px-8 py-3">Explore Packages</Link>
            <Link href="/contact" class="btn-outline px-8 py-3">Contact Us</Link>
          </div>
        </div>
        <!-- Image placeholder with mountain illustration -->
        <div class="relative">
          <div class="bg-gradient-to-br from-emerald-100 to-teal-100 rounded-3xl aspect-[4/3] flex items-center justify-center border border-emerald-200">
            <div class="text-center">
              <div class="text-9xl mb-4">🏔</div>
              <p class="text-emerald-700 font-bold">Himalayan Adventures</p>
              <p class="text-emerald-600 text-sm">Since 2014</p>
            </div>
          </div>
          <!-- Floating badges -->
          <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-lg px-5 py-3 border border-slate-100">
            <p class="text-2xl font-black text-emerald-600">5,000+</p>
            <p class="text-xs text-slate-500">Happy Trekkers</p>
          </div>
          <div class="absolute -bottom-4 -left-4 bg-slate-900 rounded-2xl shadow-lg px-5 py-3 text-white">
            <p class="text-xl font-black">⭐ 4.9/5</p>
            <p class="text-xs text-slate-300">Average Rating</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Services -->
    <section class="bg-slate-50 py-16 border-y border-slate-100">
      <div class="container-main">
        <div class="text-center mb-12">
          <p class="text-emerald-600 font-semibold text-sm uppercase tracking-widest mb-3">What We Offer</p>
          <h2 class="text-4xl font-black text-slate-900">Our Services</h2>
          <p class="text-slate-500 mt-3 max-w-xl mx-auto">Everything you need for the perfect Nepal journey — all in one place</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div v-for="svc in services" :key="svc.title"
               :class="['rounded-2xl border p-6 hover:shadow-md transition-all group', svc.color]">
            <div :class="['w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform', svc.iconBg]">
              {{ svc.icon }}
            </div>
            <h3 class="font-bold text-slate-900 mb-2">{{ svc.title }}</h3>
            <p class="text-slate-600 text-sm leading-relaxed">{{ svc.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="container-main py-16">
      <div class="text-center mb-12">
        <p class="text-emerald-600 font-semibold text-sm uppercase tracking-widest mb-3">Why Choose Us</p>
        <h2 class="text-4xl font-black text-slate-900">Our Values</h2>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="val in values" :key="val.title"
             class="text-center p-6 rounded-2xl bg-white border border-slate-100 hover:border-emerald-200 hover:shadow-md transition-all group">
          <span class="text-4xl mb-4 block group-hover:scale-110 transition-transform">{{ val.icon }}</span>
          <h3 class="font-bold text-slate-900 mb-2">{{ val.title }}</h3>
          <p class="text-slate-500 text-sm leading-relaxed">{{ val.desc }}</p>
        </div>
      </div>
    </section>

    <!-- Team -->
    <section class="bg-gradient-to-br from-slate-900 to-slate-800 py-16">
      <div class="container-main">
        <div class="text-center mb-12">
          <p class="text-emerald-400 font-semibold text-sm uppercase tracking-widest mb-3">The People Behind PathToSnow</p>
          <h2 class="text-4xl font-black text-white">Meet Our Team</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="member in team" :key="member.name"
               class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center hover:bg-white/10 transition-all group">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-4xl mx-auto mb-4 group-hover:scale-105 transition-transform">
              {{ member.emoji }}
            </div>
            <h3 class="font-bold text-white mb-1">{{ member.name }}</h3>
            <p class="text-emerald-400 text-sm font-medium mb-2">{{ member.role }}</p>
            <p class="text-slate-400 text-xs">{{ member.exp }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="container-main py-16 text-center">
      <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-3xl py-16 px-8 text-white">
        <h2 class="text-4xl font-black mb-4">Ready for Your Nepal Adventure?</h2>
        <p class="text-emerald-100 mb-8 max-w-xl mx-auto">Browse 120+ hand-crafted packages or talk to our team to design a custom itinerary.</p>
        <div class="flex flex-wrap justify-center gap-4">
          <Link href="/packages" class="bg-white text-emerald-700 font-bold px-8 py-3.5 rounded-xl hover:bg-emerald-50 transition-colors shadow-md">
            Browse All Packages
          </Link>
          <Link href="/contact" class="bg-white/20 hover:bg-white/30 text-white font-bold px-8 py-3.5 rounded-xl transition-colors border border-white/30">
            Contact Our Team
          </Link>
        </div>
      </div>
    </section>

  </AppLayout>
</template>
