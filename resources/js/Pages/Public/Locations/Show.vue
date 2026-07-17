<template>
<AppLayout>
  <Head>
    <title>{{ location.name }} Packages — PathToSnow</title>
    <meta name="description" content="Browse adventure trekking and tour packages in the {{ location.name }} region of Nepal."/>
  </Head>

  <!-- Hero Header -->
  <section class="relative h-96 flex items-center bg-slate-900 text-white overflow-hidden">
    <img v-if="location.cover_image" :src="location.cover_image" :alt="location.name" class="absolute inset-0 w-full h-full object-cover opacity-40"/>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
    
    <div class="relative container-main z-10 w-full text-center md:text-left">
      <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-2">Explore Regions</span>
      <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-4 text-white">{{ location.name }}</h1>
      <p v-if="location.description" class="text-slate-200 text-sm md:text-base max-w-2xl leading-relaxed">{{ location.description }}</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <nav class="border-b border-slate-100 bg-white">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600">Home</Link>
      <span>/</span>
      <Link href="/locations" class="hover:text-emerald-600">Locations</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium">{{ location.name }}</span>
    </div>
  </nav>

  <!-- Package grid listing -->
  <div class="container-main py-16">
    <div class="mb-10">
      <h2 class="text-2xl font-black text-slate-900 mb-2">Available Packages</h2>
      <p class="text-slate-500 text-sm">Showing {{ packages.data.length }} of {{ packages.total }} tours in this region</p>
    </div>

    <!-- Package Grid -->
    <div v-if="packages.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="pkg in packages.data" :key="pkg.id"
           class="group bg-white rounded-3xl border border-slate-150 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full">
        
        <Link :href="`/packages/${pkg.slug}`" class="relative h-56 overflow-hidden bg-slate-100 block">
          <img v-if="pkg.cover_image" :src="pkg.cover_image" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <img v-else :src="'https://images.unsplash.com/photo-1522163182402-834f871fd851?w=800&q=80&random=' + pkg.id" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
          
          <span v-if="pkg.is_special_offer && pkg.discount_label" class="absolute top-4 left-4 bg-orange-500 text-white text-xs font-black px-3 py-1.5 rounded-full shadow">
            🔥 {{ pkg.discount_label }}
          </span>
          
          <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1 rounded-lg">
            ⏱️ {{ pkg.duration_days }} {{ pkg.duration_days === 1 ? 'Day' : 'Days' }}
          </span>
        </Link>

        <div class="p-6 flex-1 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-3">
              <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ pkg.difficulty }}</span>
              <span class="text-xs text-slate-500">{{ pkg.location }}</span>
            </div>
            
            <Link :href="`/packages/${pkg.slug}`" class="block">
              <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 mb-4">
                {{ pkg.name }}
              </h3>
            </Link>
          </div>

          <div class="flex items-end justify-between pt-4 border-t border-slate-100">
            <div>
              <template v-if="pkg.is_special_offer && pkg.original_price">
                <p class="text-xs text-slate-450 line-through mb-0.5">Original NRs {{ pkg.original_price_nrs || (pkg.original_price * 135) }}</p>
                <p class="text-xl font-black text-orange-600">NRs {{ pkg.price_nrs || (pkg.price_per_person * 135) }} <span class="text-xs text-slate-400 font-normal">/person</span></p>
              </template>
              <template v-else>
                <p class="text-xs text-slate-400 mb-0.5">Per person price</p>
                <p class="text-xl font-black text-slate-800">NRs {{ pkg.price_nrs || (pkg.price_per_person * 135) }}</p>
              </template>
            </div>
            <Link :href="`/packages/${pkg.slug}`" class="btn-primary text-xs !py-2 !px-4 shadow">View Details</Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty state -->
    <div v-else class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-100">
      <p class="text-5xl mb-4">🥾</p>
      <p class="text-slate-500 text-lg font-medium mb-1">No packages available in this region yet.</p>
      <p class="text-slate-400 text-sm mb-6">Check back soon or explore other destinations in Nepal.</p>
      <Link href="/packages" class="btn-primary">Browse All Packages</Link>
    </div>

    <!-- Pagination -->
    <div v-if="packages.last_page > 1" class="flex justify-center gap-2 mt-12">
      <Link v-for="link in packages.links" :key="link.label"
            :href="link.url || '#'"
            v-html="link.label"
            class="px-4 py-2 text-sm border rounded-lg transition-colors"
            :class="link.active ? 'bg-emerald-600 border-emerald-600 text-white font-bold' : 'bg-white border-slate-200 text-slate-650 hover:bg-slate-50'"
      />
    </div>
  </div>

  <!-- Photo Gallery Section -->
  <section v-if="location.images && location.images.length" class="bg-slate-50 border-t border-slate-100 py-16">
    <div class="container-main">
      <div class="mb-10 text-center">
        <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-2">Visual Journeys</span>
        <h2 class="text-3xl font-black text-slate-900">Photos of {{ location.name }}</h2>
        <p class="text-slate-500 text-sm mt-2">Beautiful sights captured by our guides and travelers in this region.</p>
      </div>

      <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
        <div v-for="img in location.images" :key="img.id" 
             class="break-inside-avoid group cursor-pointer relative rounded-2xl overflow-hidden bg-white shadow-sm border border-slate-200/50 transition-all duration-300 hover:shadow-md" 
             @click="openLightbox(img.image_path)">
          <img :src="img.image_path" :alt="img.caption || location.name" class="w-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
          <div class="absolute inset-0 bg-slate-950/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
            <p v-if="img.caption" class="text-white text-xs font-semibold drop-shadow-md">{{ img.caption }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Lightbox Modal -->
  <div v-if="lightboxOpen" class="fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-sm flex items-center justify-center p-4 md:p-10 transition-opacity duration-300" @click="lightboxOpen = false">
    <button @click.stop="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2.5">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl transition-all duration-300 animate-fade-in" @click.stop />
  </div>
</AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  location: Object,
  packages: Object
})

const lightboxOpen = ref(false)
const activeImage = ref('')

function openLightbox(src) {
  activeImage.value = src
  lightboxOpen.value = true
}
</script>
