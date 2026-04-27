<template>
<!-- resources/js/Pages/Admin/Posts/Index.vue -->
<AdminLayout title="Blog & Travel Guide">
    <template #actions>
        <a href="/admin/posts/create" class="btn-orange text-xs py-2 px-3">+ New post</a>
    </template>
    <div class="mb-4 flex flex-wrap gap-3">
        <input v-model="search" @input="filter" placeholder="Search posts..." class="input text-sm max-w-xs"/>
        <select v-model="typeF" @change="filter" class="input text-sm w-auto">
            <option value="">All types</option>
            <option v-for="(label,type) in types" :key="type" :value="type">{{ label }}</option>
        </select>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Post</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden md:table-cell">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Author</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 hidden lg:table-cell">Views</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50">
                    <td class="px-4 py-3 max-w-xs">
                        <p class="font-medium text-gray-900 text-sm truncate">{{ post.title }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span :class="`post-${post.post_type}`" class="badge text-xs">{{ types[post.post_type] }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell">{{ post.author?.name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell">{{ post.views.toLocaleString() }}</td>
                    <td class="px-4 py-3">
                        <button @click="togglePublish(post)"
                                :class="post.published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500'"
                                class="badge text-xs cursor-pointer hover:opacity-80">
                            {{ post.published ? 'Published' : 'Draft' }}
                        </button>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a :href="`/admin/posts/${post.id}/edit`" class="text-xs text-[#E85D26] hover:underline">Edit</a>
                        <button @click="del(post)" class="text-xs text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="!posts.data.length" class="text-center py-16 text-gray-400 text-sm">No posts yet.</div>
    </div>
</AdminLayout>
</template>
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
const props = defineProps({ posts: Object, types: Object })
const search = ref(''), typeF = ref('')
let t = null
function filter() { clearTimeout(t); t = setTimeout(() => router.get('/admin/posts', { q:search.value||undefined, type:typeF.value||undefined }, { preserveState:true, replace:true }), 400) }
function togglePublish(post) { router.patch(`/admin/posts/${post.id}/publish`) }
function del(post) { if(confirm(`Delete "${post.title}"?`)) router.delete(`/admin/posts/${post.id}`) }
</script>
