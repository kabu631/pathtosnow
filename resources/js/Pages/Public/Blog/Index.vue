<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PostCard from '@/Components/PostCard.vue'
import RightSidebar from '@/Components/RightSidebar.vue'
import Pagination from '@/Components/Pagination.vue'
defineProps({ posts: Object, filters: Object, types: Object })
</script>

<template>
  <Head>
    <title>Blog — Nepal Travel Tips & Stories | PathToSnow Nepal</title>
    <meta name="description" content="Nepal travel stories, insider tips, food guides, cultural insights and festival guides from PathToSnow's local experts."/>
  </Head>
  <AppLayout>

    <!-- Hero -->
    <section class="relative overflow-hidden min-h-[260px] flex items-center bg-slate-900" style="background-image:url('https://images.unsplash.com/photo-1544735716-392fe2489ffa?q=80&w=2000&auto=format&fit=crop');background-size:cover;background-position:center;">
      <div class="absolute inset-0 bg-gradient-to-r from-sky-900/90 to-emerald-900/80"></div>
      <div class="absolute inset-0 bg-black/40"/>
      <div class="relative container-main py-16 text-white">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl drop-shadow-lg">✍️</span>
          <span class="bg-sky-100 text-sky-800 text-sm px-3 py-1 rounded-full font-semibold shadow-sm">PathToSnow Journal</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3 drop-shadow-md text-white">Nepal Travel Blog</h1>
        <p class="text-sky-50 text-lg max-w-xl drop-shadow">Nepal travel stories, insider tips, food guides and cultural insights — written by locals for adventurers.</p>
      </div>
    </section>

    <!-- Breadcrumb -->
    <nav class="border-b border-slate-100 bg-white" aria-label="Breadcrumb">
      <div class="container-main py-3 flex items-center gap-2 text-xs text-slate-500">
        <Link href="/" class="hover:text-emerald-600">Home</Link>
        <span>/</span>
        <span class="text-slate-800 font-medium">Blog</span>
      </div>
    </nav>

    <!-- Three-column layout -->
    <div class="container-main py-10 flex gap-8 items-start">

      <RightSidebar />

      <main class="flex-1 min-w-0">
        <!-- Posts grid -->
        <div v-if="posts.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <PostCard v-for="post in posts.data" :key="post.id" :post="post"/>
        </div>

        <div v-else class="text-center py-24 bg-slate-50 rounded-2xl border border-slate-200">
          <p class="text-4xl mb-4">📭</p>
          <p class="text-slate-500 text-lg">No posts yet. Check back soon!</p>
        </div>

        <Pagination :meta="posts" />
      </main>

    </div>
  </AppLayout>
</template>
