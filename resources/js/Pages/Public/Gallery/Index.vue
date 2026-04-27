<template>
<AppLayout>
  <Head>
    <title>Nepal Photo Gallery — PathToSnow</title>
    <meta name="description" content="Explore beautiful photo galleries of Nepal's most stunning destinations, including Kathmandu, Pokhara, Everest, and more."/>
  </Head>

  <!-- Hero -->
  <section class="bg-gradient-to-br from-emerald-700 to-sky-800 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/15"/>
    <div class="relative container-main py-16 text-white text-center">
      <p class="text-emerald-200 text-sm font-medium mb-3">📸 Himalayan Visuals</p>
      <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3">Photo Gallery</h1>
      <p class="text-emerald-100 text-lg max-w-xl mx-auto">Discover the beauty of Nepal through the lenses of our local guides and travelers.</p>
    </div>
  </section>

  <!-- Breadcrumb -->
  <nav class="border-b border-slate-100 bg-white">
    <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
      <Link href="/" class="hover:text-emerald-600">Home</Link>
      <span>/</span>
      <span class="text-slate-800 font-medium">Gallery</span>
    </div>
  </nav>

  <div class="container-main py-12">
    <!-- Album Folders Grid -->
    <div v-if="albums.length" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <Link v-for="album in albums" :key="album.id" :href="`/gallery/${album.slug}`" class="group block">
        <div class="relative aspect-[4/3] bg-slate-100 rounded-2xl overflow-hidden mb-3 shadow-sm border border-slate-200/60">
          <!-- Folder Tab Effect -->
          <div class="absolute -top-1 left-4 w-1/3 h-4 bg-emerald-500 rounded-t-lg transform skew-x-12 opacity-0 group-hover:opacity-100 transition-opacity"></div>
          
          <img v-if="album.cover_image" :src="album.cover_image" :alt="album.title"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <img v-else-if="album.images && album.images.length" :src="album.images[0].image_path" :alt="album.title"
               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
          <div v-else class="w-full h-full bg-slate-200 flex items-center justify-center">
             <span class="text-4xl opacity-50">📷</span>
          </div>
          
          <!-- Image Count Badge -->
          <div class="absolute top-3 right-3 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ album.images_count }}
          </div>
          
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        </div>
        <div>
          <h2 class="font-bold text-lg text-slate-900 group-hover:text-emerald-700 transition-colors">{{ album.title }}</h2>
          <p v-if="album.description" class="text-sm text-slate-500 line-clamp-1 mt-0.5">{{ album.description }}</p>
        </div>
      </Link>
    </div>

    <div v-else class="text-center py-24 bg-slate-50 rounded-2xl border border-slate-200">
      <p class="text-5xl mb-4">📂</p>
      <p class="text-slate-500 text-lg">No gallery albums available yet.</p>
    </div>
  </div>
</AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({ albums: Array })
</script>
