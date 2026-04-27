<template>
<AdminLayout :title="slide ? 'Edit Slide' : 'New Slide'">
    <template #actions>
        <Link href="/admin/slides" class="btn-secondary text-xs py-2 px-3">← Slides</Link>
    </template>
    <form @submit.prevent="submit" class="max-w-3xl space-y-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 border-b border-gray-100 pb-3">Slide info</h2>
            
            <div>
                <label class="label">Background Image</label>
                <div v-if="slide && slide.image && !form.new_image" class="mb-2">
                    <img :src="slide.image" class="w-full h-48 object-cover rounded-lg border"/>
                </div>
                <input type="file" accept="image/*" @change="form.new_image = $event.target.files[0]" class="input p-2 text-sm" />
                <div v-if="form.errors.new_image" class="text-red-500 text-xs mt-1">{{ form.errors.new_image }}</div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Small Tag/Eyebrow</label>
                    <input v-model="form.tag" class="input" placeholder="🇳🇵 Nepal's #1 curated travel platform"/>
                </div>
                <div>
                    <label class="label">Main Title</label>
                    <textarea v-model="form.title" class="input resize-none" rows="2" placeholder="Adventure, culture &&#10;Himalayan wilderness"></textarea>
                </div>
            </div>
            
            <div>
                <label class="label">Description text</label>
                <textarea v-model="form.desc" rows="3" class="input resize-none" placeholder="Book treks, adventures, wildlife safaris..."></textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Button Text</label>
                    <input v-model="form.btn_text" class="input" placeholder="Browse all experiences"/>
                </div>
                <div>
                    <label class="label">Button Link</label>
                    <input v-model="form.btn_link" class="input" placeholder="/packages"/>
                </div>
            </div>
            
            <div>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox" v-model="form.is_active" class="rounded"/> Active / Visible
                </label>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" :disabled="form.processing" class="btn-primary px-8">
                {{ form.processing ? 'Saving...' : 'Save Slide' }}
            </button>
            <Link href="/admin/slides" class="btn-secondary px-6">Cancel</Link>
        </div>
    </form>
</AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ slide: Object })
const s = props.slide

const form = useForm({
    tag: s?.tag || '',
    title: s?.title || '',
    desc: s?.desc || '',
    btn_text: s?.btn_text || '',
    btn_link: s?.btn_link || '',
    is_active: s ? s.is_active : true,
    new_image: null,
})

function submit() {
    if (s) {
        form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/slides/${s.id}`)
    } else {
        form.post('/admin/slides')
    }
}
</script>
