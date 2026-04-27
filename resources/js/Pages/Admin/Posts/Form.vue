<template>
<!-- resources/js/Pages/Admin/Posts/Form.vue -->
<AdminLayout :title="post ? `Edit: ${post.title}` : 'New Post'">
    <template #actions>
        <a href="/admin/posts" class="btn-secondary text-xs py-2 px-3">← Posts</a>
    </template>
    <form @submit.prevent="submit" class="max-w-4xl space-y-5">

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Post content</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="label">Title *</label>
                    <input v-model="form.title" class="input" placeholder="10 things to know before trekking Annapurna" required/>
                </div>
                <div>
                    <label class="label">Post type *</label>
                    <select v-model="form.post_type" class="input" required>
                        <option v-for="(label,type) in types" :key="type" :value="type">{{ label }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">Category / tag</label>
                    <input v-model="form.category" class="input" placeholder="e.g. Dashain, Kathmandu, Newari..."/>
                </div>
                <div>
                    <label class="label">Read time (minutes)</label>
                    <input v-model.number="form.read_time" type="number" min="1" class="input"/>
                </div>
                <div>
                    <label class="label">Related package (optional)</label>
                    <select v-model="form.related_package_id" class="input">
                        <option :value="null">None</option>
                        <option v-for="p in packages" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="label">Excerpt (max 500)</label>
                <textarea v-model="form.excerpt" rows="2" class="input resize-none" placeholder="Short summary for listing cards..." maxlength="500"/>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Upload Cover Photo</label>
                    <div class="flex items-center gap-4">
                        <input type="file" @change="e => form.cover_image = e.target.files[0]" class="input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/*"/>
                    </div>
                    <div v-if="p?.cover_image && !form.cover_image" class="mt-2">
                        <img :src="p.cover_image" class="h-20 rounded-lg object-cover border border-slate-200"/>
                    </div>
                </div>
                <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl">
                    <label class="label text-emerald-800">Assign Photo to Gallery Location?</label>
                    <select v-model="form.photo_album_id" class="input">
                        <option :value="null">Don't add to gallery</option>
                        <option v-for="album in albums" :key="album.id" :value="album.id">{{ album.title }}</option>
                        <option value="new">+ Add new location</option>
                    </select>
                    <div v-if="form.photo_album_id === 'new'" class="mt-2">
                        <input v-model="form.new_album_title" class="input" placeholder="Enter new location name (e.g. Pokhara)" />
                    </div>
                </div>
            </div>
            <div>
                <label class="label">Content * (HTML supported)</label>
                <textarea v-model="form.content" rows="16" class="input resize-none font-mono text-xs"
                          placeholder="<p>Start writing...</p>" required/>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">SEO & publishing</h2>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" v-model="form.published" class="rounded"/>
                    <span class="text-sm text-gray-700">Published (visible to public)</span>
                </label>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">SEO title (max 60)</label>
                    <input v-model="form.meta_title" class="input" maxlength="60"/>
                </div>
                <div>
                    <label class="label">Tags (comma separated)</label>
                    <input :value="(form.tags||[]).join(', ')"
                           @input="form.tags = $event.target.value.split(',').map(t=>t.trim()).filter(Boolean)"
                           class="input" placeholder="nepal, food, kathmandu"/>
                </div>
            </div>
            <div>
                <label class="label">SEO description (max 160)</label>
                <textarea v-model="form.meta_description" rows="2" class="input resize-none" maxlength="160"/>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" :disabled="submitting" class="btn-orange px-8">
                {{ submitting ? 'Saving...' : (post ? 'Update post' : 'Create post') }}
            </button>
            <a href="/admin/posts" class="btn-secondary px-6">Cancel</a>
        </div>
    </form>
</AdminLayout>
</template>
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ post: Object, types: Object, packages: Array, albums: Array })
const p = props.post
const submitting = ref(false)

const form = ref({
    title: p?.title||'', post_type: p?.post_type||'blog', category: p?.category||'',
    excerpt: p?.excerpt||'', content: p?.content||'', cover_image: null,
    photo_album_id: null, new_album_title: '',
    tags: p?.tags||[], published: p?.published||false, read_time: p?.read_time||5,
    meta_title: p?.meta_title||'', meta_description: p?.meta_description||'',
    related_package_id: p?.related_package_id||null, slug: '',
})

function submit() {
    submitting.value = true
    if (p) {
        // use POST with _method=PUT to support file uploads
        router.post(`/admin/posts/${p.id}`, {
            _method: 'put',
            ...form.value
        }, { onFinish: () => submitting.value = false })
    } else {
        router.post('/admin/posts', form.value, { onFinish: () => submitting.value = false })
    }
}
</script>
