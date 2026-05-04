<template>
<div class="min-h-screen flex flex-col bg-white">

  <!-- Top Bar -->
  <div class="bg-emerald-700 text-white text-xs py-1.5 hidden md:block">
    <div class="container-main flex justify-between items-center">
      <span>🇳🇵 Nepal's #1 curated travel platform — Book directly with local guides</span>
      <div class="flex items-center gap-4">
        <span>📞 +977-1-234-5678</span>
        <span>✉️ hello@pathtosnow.com</span>
      </div>
    </div>
  </div>

  <!-- Main Navigation -->
  <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
    <div class="container-main">
      <div class="flex items-center justify-between h-16">

        <!-- Logo -->
        <Link href="/" class="flex items-center gap-2.5 flex-shrink-0">
          <img src="/images/logo.png" alt="PathToSnow" class="h-9 sm:h-12 object-contain" />
        </Link>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-1" aria-label="Main navigation">

          <!-- Experiences dropdown -->
          <div class="relative group">
            <button class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors"
                    aria-haspopup="true" aria-expanded="false">
              🗺️ Experiences
              <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-600 transition-transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div class="absolute top-full left-0 mt-2 w-72 bg-white border border-slate-200 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 p-2">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 py-2">Choose your experience</p>
              <Link v-for="(item, type) in packageTypeMap" :key="type"
                    :href="item.href"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 transition-colors group/item">
                <span class="w-9 h-9 flex items-center justify-center rounded-lg text-lg flex-shrink-0" :class="item.bg">{{ item.icon }}</span>
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-slate-800 group-hover/item:text-emerald-700">{{ item.label }}</p>
                  <p class="text-xs text-slate-400">{{ packageTypeCounts[type] || 0 }} packages</p>
                </div>
              </Link>
            </div>
          </div>

          <!-- Travel Guide dropdown -->
          <div class="relative group">
            <button class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 hover:text-sky-700 hover:bg-sky-50 rounded-lg transition-colors">
              📖 Travel Guide
              <svg class="w-3.5 h-3.5 text-slate-400 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div class="absolute top-full left-0 mt-2 w-52 bg-white border border-slate-200 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 p-2">
              <Link v-for="g in guideLinks" :key="g.href" :href="g.href"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-sky-50 text-sm text-slate-700 hover:text-sky-700 font-medium transition-colors">
                <span>{{ g.icon }}</span> {{ g.label }}
              </Link>
            </div>
          </div>


          <!-- Abroad dropdown -->
          <div class="relative group">
            <button class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">
              🌍 Abroad
              <svg class="w-3.5 h-3.5 text-slate-400 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div class="absolute top-full left-0 mt-2 w-52 bg-white border border-slate-200 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 p-2">
              <Link v-for="c in abroadLinks" :key="c.href" :href="c.href"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-sm text-slate-700 hover:text-emerald-700 font-medium transition-colors">
                {{ c.label }}
              </Link>
            </div>
          </div>

          <Link href="/blog" class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">Blog</Link>
          <Link href="/gallery" class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">Gallery</Link>
          <Link href="/shop" class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">Gear Shop</Link>
        </nav>

        <!-- Right actions -->
        <div class="flex items-center gap-2">

          <!-- Search toggle -->
          <button @click="searchOpen = !searchOpen" class="p-2 rounded-lg hover:bg-slate-100 transition-colors" aria-label="Toggle search" id="header-search-toggle">
            <svg v-if="!searchOpen" class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <svg v-else class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <!-- Cart -->
          <Link href="/cart" class="relative p-2 rounded-lg hover:bg-slate-100 transition-colors" aria-label="Shopping cart">
            <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <span v-if="$page.props.cartCount > 0"
                  class="absolute -top-0.5 -right-0.5 bg-emerald-600 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">
              {{ $page.props.cartCount }}
            </span>
          </Link>

          <!-- Auth -->
          <template v-if="$page.props.auth?.user">
            <Link v-if="$page.props.auth.user.role === 'admin'" href="/admin"
                  class="hidden md:block btn-primary text-xs py-2 px-4">Dashboard</Link>
            <Link href="/logout" method="post" as="button"
                  class="hidden md:block btn-ghost text-xs">Logout</Link>
          </template>
          <template v-else>
            <Link href="/login"  class="hidden md:block btn-ghost text-sm">Login</Link>
            <Link href="/register" class="hidden md:block btn-primary text-sm">Get Started</Link>
          </template>

          <!-- Mobile toggle -->
          <button @click="mob = !mob" class="lg:hidden p-2 rounded-lg hover:bg-slate-100" aria-label="Toggle menu">
            <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="mob" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Search bar (slide-down) -->
      <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 -translate-y-2" leave-active-class="transition duration-150 ease-in" leave-to-class="opacity-0 -translate-y-2">
        <div v-if="searchOpen" class="border-t border-slate-100 py-3 px-2">
          <form @submit.prevent="doSearch" class="flex gap-2 max-w-xl mx-auto">
            <input
              v-model="searchQ"
              ref="searchInput"
              type="search"
              placeholder="Search packages, guides, gear…"
              class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"
              id="header-search-input"
              aria-label="Site search"
            />
            <button type="submit" class="btn-primary px-5 py-2.5 text-sm">Search</button>
          </form>
        </div>
      </Transition>

      <!-- Mobile Menu -->
      <div v-if="mob" class="lg:hidden border-t border-slate-100 py-4">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 mb-2">Experiences</p>
        <Link v-for="(item, type) in packageTypeMap" :key="type"
              :href="item.href" @click="mob=false"
              class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-sm font-medium text-slate-700 hover:text-emerald-700 mx-1 transition-colors">
          <span>{{ item.icon }}</span> {{ item.label }}
        </Link>
        <div class="h-px bg-slate-100 my-3 mx-3"/>
        <Link v-for="g in guideLinks" :key="g.href" :href="g.href" @click="mob=false"
              class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-sky-50 text-sm font-medium text-slate-700 hover:text-sky-700 mx-1 transition-colors">
          <span>{{ g.icon }}</span> {{ g.label }}
        </Link>
        <div class="h-px bg-slate-100 my-3 mx-3"/>
        <Link href="/blog" @click="mob=false"  class="block px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-sm font-medium text-slate-700 mx-1">Blog</Link>
        <Link href="/gallery" @click="mob=false"  class="block px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-sm font-medium text-slate-700 mx-1">Gallery</Link>
        <Link href="/shop" @click="mob=false"  class="block px-3 py-2.5 rounded-xl hover:bg-emerald-50 text-sm font-medium text-slate-700 mx-1">Gear Shop</Link>
        <div class="h-px bg-slate-100 my-3 mx-3"/>
        <div class="flex gap-2 px-3">
          <Link href="/login"    class="btn-outline flex-1 text-sm py-2">Login</Link>
          <Link href="/register" class="btn-primary flex-1 text-sm py-2">Register</Link>
        </div>
      </div>
    </div>
  </header>

  <!-- Flash message -->
  <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-2 scale-95" leave-active-class="transition duration-200 ease-in" leave-to-class="opacity-0 scale-95">
    <div v-if="flash" class="fixed top-20 right-4 z-50 bg-emerald-600 text-white text-sm px-5 py-3 rounded-2xl shadow-lg shadow-emerald-200 max-w-sm flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ flash }}
    </div>
  </Transition>

  <!-- Page Content -->
  <main class="flex-1">
    <Transition name="page" mode="out-in" appear>
      <div :key="$page.url">
        <slot/>
      </div>
    </Transition>
  </main>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-400" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>

    <!-- Footer top -->
    <div class="container-main py-14">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

        <!-- Brand -->
        <div class="lg:col-span-1">
          <div class="flex items-center gap-2.5 mb-4">
            <img src="/images/logo_footer.png" alt="PathToSnow" class="h-12 object-contain" />
          </div>
          <p class="text-sm leading-relaxed mb-5">Nepal's curated travel & gear platform — built by a local for the world. From Himalayan treks to cultural tours.</p>
          <div class="flex gap-3">
            <a href="#" class="w-8 h-8 bg-slate-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors" aria-label="Facebook">
              <span class="text-xs font-bold text-slate-400 hover:text-white">f</span>
            </a>
            <a href="#" class="w-8 h-8 bg-slate-800 hover:bg-sky-600 rounded-lg flex items-center justify-center transition-colors" aria-label="Twitter">
              <span class="text-xs font-bold text-slate-400">𝕏</span>
            </a>
          </div>
        </div>

        <!-- Experiences -->
        <div>
          <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Experiences</h3>
          <ul class="space-y-2.5" role="list">
            <li v-for="(item, type) in packageTypeMap" :key="type">
              <Link :href="item.href" class="text-sm hover:text-emerald-400 transition-colors">{{ item.label }}</Link>
            </li>
          </ul>
        </div>

        <!-- Travel Guide -->
        <div>
          <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Travel Guide</h3>
          <ul class="space-y-2.5" role="list">
            <li v-for="g in guideLinks" :key="g.href">
              <Link :href="g.href" class="text-sm hover:text-sky-400 transition-colors">{{ g.label }}</Link>
            </li>
            <li><Link href="/blog" class="text-sm hover:text-sky-400 transition-colors">Blog</Link></li>
            <li><Link href="/gallery" class="text-sm hover:text-sky-400 transition-colors">Gallery</Link></li>
          </ul>
        </div>

        <!-- Gear Shop -->
        <div>
          <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wider">Gear Shop</h3>
          <ul class="space-y-2.5" role="list">
            <li><Link href="/shop" class="text-sm hover:text-emerald-400 transition-colors">All trekking gear</Link></li>
            <li><Link href="/shop" class="text-sm hover:text-emerald-400 transition-colors">Backpacks & bags</Link></li>
            <li><Link href="/shop" class="text-sm hover:text-emerald-400 transition-colors">Trekking boots</Link></li>
            <li><Link href="/shop" class="text-sm hover:text-emerald-400 transition-colors">Sleeping gear</Link></li>
            <li><Link href="/cart" class="text-sm hover:text-emerald-400 transition-colors">My cart</Link></li>
          </ul>
        </div>
      </div>

      <!-- Footer bottom -->
      <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
        <p class="text-xs">© {{ year }} PathToSnow Nepal. All rights reserved. Built with ❤️ in Kathmandu.</p>
        <div class="flex items-center gap-6 text-xs">
          <Link href="/about" class="hover:text-white transition-colors">About Us</Link>
          <Link href="/pages/privacy-policy" class="hover:text-white transition-colors">Privacy Policy</Link>
          <Link href="/pages/terms-of-service" class="hover:text-white transition-colors">Terms of Service</Link>
          <Link href="/contact" class="hover:text-white transition-colors">Contact Us</Link>
        </div>
      </div>
    </div>
  </footer>
</div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const mob        = ref(false)
const searchOpen = ref(false)
const searchQ    = ref('')
const searchInput = ref(null)
const page = usePage()
const year = new Date().getFullYear()

async function toggleSearch() {
  searchOpen.value = !searchOpen.value
  if (searchOpen.value) {
    await nextTick()
    searchInput.value?.focus()
  }
}

function doSearch() {
  const q = searchQ.value.trim()
  if (!q) return
  searchOpen.value = false
  searchQ.value = ''
  router.visit(`/packages?q=${encodeURIComponent(q)}`)
}

const flash = computed(() => page.props.flash?.success)

const packageTypeMap = computed(() => {
  const types = page.props.navPackageTypes || []
  const map = {}
  types.forEach(t => {
    map[t.type_key] = {
      href: `/packages/type/${t.slug}`,
      label: t.name,
      icon: t.icon_emoji,
    }
  })
  return map
})

const packageTypeCounts = computed(() => page.props.packageTypeCounts || {})

const guideLinks = computed(() => {
  const types = page.props.navPostTypes || []
  return types.map(t => ({
    href: `/travel-guide/type/${t.slug}`,
    label: t.name,
    icon: t.icon_emoji,
  }))
})

const abroadLinks = computed(() => {
  const countries = page.props.navCountries || []
  return countries.map(c => ({
    href: `/abroad/${c.slug}`,
    label: c.name,
  }))
})
</script>
