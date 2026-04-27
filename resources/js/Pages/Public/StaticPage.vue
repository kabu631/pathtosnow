<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({ page: Object })
</script>

<template>
  <Head>
    <title>{{ page.title }} — PathToSnow Nepal</title>
    <meta v-if="page.meta_description" name="description" :content="page.meta_description"/>
  </Head>
  <AppLayout>

    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-800 to-slate-900 py-14">
      <div class="container-main">
        <nav class="flex items-center gap-2 text-xs mb-4" aria-label="Breadcrumb">
          <Link href="/" class="text-slate-300 hover:text-white transition-colors">Home</Link>
          <span class="text-slate-500">/</span>
          <span class="text-white font-medium">{{ page.title }}</span>
        </nav>
        <h1 class="text-4xl font-black text-white">{{ page.title }}</h1>
      </div>
    </section>


    <!-- Content -->
    <div class="container-main py-12">
      <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12">
        <div class="prose prose-slate prose-lg max-w-none
                    prose-headings:font-bold prose-headings:text-slate-900
                    prose-h2:text-2xl prose-h2:mt-8 prose-h2:mb-4
                    prose-h3:text-xl prose-h3:mt-6 prose-h3:mb-3
                    prose-p:text-slate-600 prose-p:leading-relaxed
                    prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                    prose-li:text-slate-600 prose-ul:space-y-1 prose-strong:text-slate-800"
             v-html="page.content"/>

        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
          <span>Last updated: {{ new Date(page.updated_at).toLocaleDateString('en-GB', { day:'numeric', month:'long', year:'numeric' }) }}</span>
          <Link href="/" class="text-emerald-600 hover:text-emerald-700 font-medium">← Back to Home</Link>
        </div>
      </div>
    </div>

  </AppLayout>
</template>
