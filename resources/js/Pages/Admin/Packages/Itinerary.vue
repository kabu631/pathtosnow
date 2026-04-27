<template>
<!-- resources/js/Pages/Admin/Packages/Itinerary.vue -->
<AdminLayout :title="`Itinerary — ${pkg.name}`">
    <template #actions>
        <a :href="`/admin/packages/${pkg.id}/edit`" class="btn-secondary text-xs py-2 px-3">← Package</a>
    </template>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- Existing days -->
        <div>
            <h2 class="text-sm font-semibold text-gray-900 mb-3">
                Current itinerary ({{ days.length }} / {{ pkg.duration_days }} days)
            </h2>
            <div v-if="!days.length" class="bg-gray-50 rounded-xl p-8 text-center text-gray-400 text-sm">
                No days added yet. Use the form to build your itinerary.
            </div>
            <div class="space-y-3">
                <div v-for="day in sortedDays" :key="day.id"
                     class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#0D1B2A] rounded-lg flex items-center justify-center text-[#E85D26] text-xs font-black">
                                {{ day.day_number }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ day.title }}</p>
                                <div class="flex gap-3 mt-0.5">
                                    <span v-if="day.altitude_m" class="text-xs text-gray-400">{{ day.altitude_m.toLocaleString() }}m</span>
                                    <span v-if="day.distance_km" class="text-xs text-gray-400">{{ day.distance_km }}km</span>
                                    <span v-if="day.accommodation" class="text-xs text-gray-400">{{ day.accommodation }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="editDay = day; showAdd = true" class="text-xs text-[#E85D26] hover:underline">Edit</button>
                            <button @click="deleteDay(day)" class="text-xs text-red-500 hover:underline">×</button>
                        </div>
                    </div>
                    <div class="px-4 pb-3">
                        <p class="text-xs text-gray-500 line-clamp-2">{{ day.description }}</p>
                        <div v-if="day.meals?.length" class="flex flex-wrap gap-1 mt-2">
                            <span v-for="m in day.meals" :key="m" class="badge bg-amber-100 text-amber-800 text-xs">{{ m }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add / Edit form -->
        <div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 sticky top-20">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-semibold text-gray-900">{{ editDay ? 'Edit day' : 'Add day' }}</h2>
                    <button v-if="editDay" @click="resetForm" class="text-xs text-gray-400 hover:text-gray-600">+ New instead</button>
                </div>
                <form @submit.prevent="saveDay" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label">Day number *</label>
                            <input v-model.number="dayForm.day_number" type="number" min="1" :max="pkg.duration_days" class="input" required/>
                        </div>
                        <div>
                            <label class="label">Altitude (m)</label>
                            <input v-model.number="dayForm.altitude_m" type="number" class="input" placeholder="3200"/>
                        </div>
                    </div>
                    <div>
                        <label class="label">Day title *</label>
                        <input v-model="dayForm.title" class="input" placeholder="Day 1: Arrival in Pokhara" required/>
                    </div>
                    <div>
                        <label class="label">Description *</label>
                        <textarea v-model="dayForm.description" rows="4" class="input resize-none" required
                                  placeholder="What happens this day — activities, sights, challenges..."/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label">Accommodation</label>
                            <input v-model="dayForm.accommodation" class="input" placeholder="Teahouse / Hotel"/>
                        </div>
                        <div>
                            <label class="label">Distance (km)</label>
                            <input v-model.number="dayForm.distance_km" type="number" class="input" placeholder="12"/>
                        </div>
                        <div>
                            <label class="label">Elevation gain (m)</label>
                            <input v-model.number="dayForm.elevation_gain_m" type="number" class="input" placeholder="700"/>
                        </div>
                        <div>
                            <label class="label">Place name</label>
                            <input v-model="dayForm.place_name" class="input" placeholder="Ghorepani"/>
                        </div>
                    </div>
                    <div>
                        <label class="label">Meals included</label>
                        <div class="flex flex-wrap gap-2">
                            <label v-for="m in allMeals" :key="m" class="flex items-center gap-1.5 text-sm cursor-pointer">
                                <input type="checkbox" :value="m" v-model="dayForm.meals" class="rounded"/>
                                {{ m }}
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="label">Notes / tips</label>
                        <input v-model="dayForm.notes" class="input" placeholder="Tip for this day..."/>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" :disabled="saving" class="btn-orange flex-1">
                            {{ saving ? 'Saving...' : (editDay ? 'Update day' : 'Add day') }}
                        </button>
                        <button v-if="editDay" type="button" @click="resetForm" class="btn-secondary px-4">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</AdminLayout>
</template>
<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({ package: Object, days: Array })
const pkg   = props.package
const saving = ref(false)
const editDay = ref(null)
const allMeals = ['Breakfast','Lunch','Dinner','Tea & snacks']

const blankForm = () => ({ day_number: '', title: '', description: '', accommodation: '', meals: [], distance_km: '', altitude_m: '', elevation_gain_m: '', place_name: '', notes: '' })
const dayForm = ref(blankForm())

const sortedDays = computed(() => [...(props.days || [])].sort((a,b) => a.day_number - b.day_number))

function editDayFn(day) { editDay.value = day; Object.assign(dayForm.value, { ...day, meals: day.meals || [] }) }
function resetForm()    { editDay.value = null; dayForm.value = blankForm() }

function saveDay() {
    saving.value = true
    if (editDay.value) {
        router.put(`/admin/packages/${pkg.id}/itinerary/${editDay.value.id}`, dayForm.value, {
            onSuccess: () => resetForm(), onFinish: () => saving.value = false
        })
    } else {
        router.post(`/admin/packages/${pkg.id}/itinerary`, dayForm.value, {
            onSuccess: () => dayForm.value = blankForm(), onFinish: () => saving.value = false
        })
    }
}

function deleteDay(day) {
    if (confirm(`Delete Day ${day.day_number}: "${day.title}"?`)) {
        router.delete(`/admin/packages/${pkg.id}/itinerary/${day.id}`)
    }
}
</script>
