<template>
<AppLayout>
  <Head :title="`${album.title} Gallery — PathToSnow`">
    <meta name="description" :content="album.description || `View beautiful photos from ${album.title} in our gallery.`"/>
  </Head>

  <!-- Hero -->
  <section class="relative overflow-hidden h-64 md:h-80 bg-slate-900">
    <img v-if="album.cover_image" :src="album.cover_image" :alt="album.title" class="absolute inset-0 w-full h-full object-cover opacity-50" />
    <img v-else-if="album.images && album.images.length" :src="album.images[0].image_path" class="absolute inset-0 w-full h-full object-cover opacity-50" />
    <div v-else class="absolute inset-0 w-full h-full bg-emerald-900 opacity-50"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
    <div class="relative container-main h-full flex flex-col justify-end pb-12">
      <Link href="/gallery" class="text-emerald-300 hover:text-emerald-200 text-sm font-medium mb-3 flex items-center gap-1 w-max">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to all galleries
      </Link>
      <h1 class="text-4xl md:text-5xl font-black mb-2 text-white drop-shadow-md">{{ album.title }}</h1>
      <p v-if="album.description" class="text-slate-300 text-lg max-w-2xl drop-shadow-sm">{{ album.description }}</p>
    </div>
  </section>

  <div class="container-main py-12">
    <div v-if="album.images.length" class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
      <!-- Masonry layout via CSS columns -->
      <div v-for="img in album.images" :key="img.id" class="break-inside-avoid group cursor-pointer relative rounded-xl overflow-hidden bg-slate-100 shadow-sm border border-slate-200/50" @click="openLightbox(img.image_path)">
        <img :src="img.image_path" class="w-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" />
        <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
          <svg class="w-10 h-10 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-24 bg-slate-50 rounded-2xl border border-slate-200">
      <p class="text-5xl mb-4">📷</p>
      <p class="text-slate-500 text-lg">No photos have been uploaded to this album yet.</p>
    </div>
  </div>

  <!-- Lightbox Modal -->
  <div v-if="lightboxOpen" class="fixed inset-0 z-50 bg-slate-900/95 backdrop-blur-sm flex items-center justify-center p-4 md:p-10" @click="lightboxOpen = false">
    <button @click.stop="lightboxOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" @click.stop />
  </div>
</AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({ album: Object })

const lightboxOpen = ref(false)
const activeImage = ref('')

function openLightbox(src) {
    activeImage.value = src
    lightboxOpen.value = true
}
</script>
