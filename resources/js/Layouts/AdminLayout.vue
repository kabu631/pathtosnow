<template>
<!-- resources/js/Layouts/AdminLayout.vue -->
<div class="min-h-screen bg-slate-50 flex">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r border-slate-200 flex flex-col fixed h-full z-30 shadow-sm">

    <!-- Brand -->
    <div class="p-5 border-b border-slate-100">
      <Link href="/" class="flex items-center justify-center">
        <img src="/images/logo.png" alt="PathToSnow" class="h-10 object-contain" />
      </Link>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-3 overflow-y-auto space-y-0.5" aria-label="Admin navigation">

      <Link :href="r('admin.dashboard')" :class="['admin-link', active('/admin') && !active('/admin/')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
        Dashboard
      </Link>

      <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-5 pb-2">Experiences</p>

      <Link :href="r('admin.packages.index')" :class="['admin-link', active('/admin/packages')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        Packages
      </Link>
      <Link :href="r('admin.packages.create')" class="admin-link-sub">+ New package</Link>
      <Link :href="r('admin.package-types.index')" class="admin-link-sub">Package Types</Link>

      <Link :href="r('admin.bookings.index')" :class="['admin-link', active('/admin/bookings')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Bookings
        <span v-if="$page.props.pendingBookings > 0"
              class="ml-auto bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">
          {{ $page.props.pendingBookings }}
        </span>
      </Link>

      <div class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider mt-4 mb-2">Content</div>
      <Link :href="r('admin.slides.index')" :class="['admin-link', active('/admin/slides')]">
        <span class="text-lg">🖼</span> Hero Slider
      </Link>
      <Link :href="r('admin.posts.index')" :class="['admin-link', active('/admin/posts')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Blog & Guide
      </Link>
      <Link :href="r('admin.posts.create')" class="admin-link-sub">+ New post</Link>
      <Link :href="r('admin.post-types.index')" class="admin-link-sub">Post Types</Link>
      <Link :href="r('admin.gallery.index')" :class="['admin-link', active('/admin/gallery')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Gallery
      </Link>
      <Link :href="r('admin.pages.index')" :class="['admin-link', active('/admin/pages')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Pages
      </Link>
      <Link :href="r('admin.contact.index')" :class="['admin-link', active('/admin/contact')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Contact Messages
        <span v-if="$page.props.unreadContactCount > 0"
              class="ml-auto bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">
          {{ $page.props.unreadContactCount }}
        </span>
      </Link>

      <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-5 pb-2">People</p>

      <Link :href="r('admin.users.index')" :class="['admin-link', active('/admin/users')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        Users
      </Link>

      <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 pt-5 pb-2">Shop</p>

      <Link :href="r('admin.products.index')" :class="['admin-link', active('/admin/products')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        Products
      </Link>
      <Link :href="r('admin.products.create')" class="admin-link-sub">+ New product</Link>
      <Link :href="r('admin.orders.index')" :class="['admin-link', active('/admin/orders')]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        Orders
      </Link>
    </nav>

    <!-- User footer -->
    <div class="p-3 border-t border-slate-100">
      <div class="flex items-center gap-2.5 px-3 py-2.5 mb-1 rounded-xl bg-emerald-50">
        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-600 to-sky-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
          {{ $page.props.auth?.user?.name?.[0]?.toUpperCase() }}
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold text-slate-900 truncate">{{ $page.props.auth?.user?.name }}</p>
          <p class="text-xs text-emerald-600">Administrator</p>
        </div>
      </div>
      <Link href="/" class="admin-link text-xs">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        View live site
      </Link>
      <Link href="/logout" method="post" as="button" class="admin-link text-xs w-full text-red-500 hover:text-red-700 hover:bg-red-50 mt-0.5">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Logout
      </Link>
    </div>
  </aside>

  <!-- Main content -->
  <div class="flex-1 ml-64 flex flex-col min-h-screen">

    <!-- Top bar -->
    <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between sticky top-0 z-20 shadow-sm">
      <h1 class="text-sm font-semibold text-slate-800">{{ title }}</h1>
      <div class="flex items-center gap-2"><slot name="actions"/></div>
    </header>

    <!-- Flash -->
    <div v-if="$page.props.flash?.success"
         class="mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
      <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ $page.props.flash.success }}
    </div>

    <main class="flex-1 p-6">
      <Transition name="page" mode="out-in" appear>
        <div :key="$page.url">
          <slot/>
        </div>
      </Transition>
    </main>
  </div>
</div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
defineProps({ title: String })
const page = usePage()

function active(path) {
  return page.url.startsWith(path) ? 'active' : ''
}
function r(name) {
  const map = {
    'admin.dashboard':      '/admin',
    'admin.packages.index': '/admin/packages',
    'admin.packages.create':'/admin/packages/create',
    'admin.package-types.index': '/admin/package-types',
    'admin.bookings.index': '/admin/bookings',
    'admin.slides.index':   '/admin/slides',
    'admin.posts.index':    '/admin/posts',
    'admin.posts.create':   '/admin/posts/create',
    'admin.post-types.index': '/admin/post-types',
    'admin.products.index': '/admin/products',
    'admin.products.create':'/admin/products/create',
    'admin.gallery.index':   '/admin/gallery',
    'admin.pages.index':     '/admin/pages',
    'admin.contact.index':   '/admin/contact',
    'admin.orders.index':    '/admin/orders',
    'admin.users.index':     '/admin/users',
  }
  return map[name] || '/admin'
}
</script>

<style scoped>
.admin-link {
  @apply flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-600
         hover:text-emerald-700 hover:bg-emerald-50 transition-colors w-full;
}
.admin-link.active {
  @apply bg-emerald-50 text-emerald-700 font-semibold;
}
.admin-link-sub {
  @apply flex items-center ml-7 px-3 py-1.5 rounded-lg text-xs text-slate-500
         hover:text-emerald-700 hover:bg-emerald-50 transition-colors;
}
</style>
