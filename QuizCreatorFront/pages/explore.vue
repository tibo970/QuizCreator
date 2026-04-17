<template>
  <div>
    <div class="mb-10 text-center">
      <h1 class="text-4xl font-bold text-white mb-4">Explorer les Quiz</h1>
      <p class="text-slate-400 max-w-2xl mx-auto">Parcourez les centaines de quiz créés par la communauté sur tous les thèmes imaginables.</p>
      
      <!-- Search and Filter -->
      <div class="mt-8 flex flex-col md:flex-row gap-4 justify-center items-center max-w-3xl mx-auto">
        <div class="relative w-full">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
            🔍
          </span>
          <input 
            type="text" 
            v-model="searchQuery"
            placeholder="Rechercher un quiz..." 
            class="w-full bg-slate-800/80 border border-slate-700 rounded-full pl-12 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          >
        </div>
        <select v-model="selectedCategory" class="w-full md:w-auto bg-slate-800/80 border border-slate-700 rounded-full px-6 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none">
          <option value="">Toutes les catégories</option>
          <option value="mario">Mario</option>
          <option value="pokemon">Pokémon</option>
          <option value="harry-potter">Harry Potter</option>
        </select>
      </div>
    </div>

    <!-- Grid -->
    <div v-if="loading" class="flex justify-center py-20">
      <svg class="animate-spin h-10 w-10 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </div>
    <div v-else-if="filteredQuizzes.length === 0" class="text-center py-20 bg-slate-800/50 rounded-2xl border border-slate-700 max-w-3xl mx-auto">
      <div class="text-6xl mb-4">📭</div>
      <h3 class="text-xl font-medium text-white mb-2">Aucun quiz trouvé</h3>
      <p class="text-slate-400">Essayez une autre recherche ou créez votre propre quiz !</p>
    </div>
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <NuxtLink :to="`/quiz/${quiz.id}`" v-for="quiz in filteredQuizzes" :key="quiz.id" class="group bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] hover:-translate-y-1 cursor-pointer flex flex-col">
        <div class="h-40 relative overflow-hidden">
          <img v-if="quiz.cover && quiz.cover !== 'default.png'" :src="quiz.cover" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" alt="">
          <div v-else :class="`absolute inset-0 bg-gradient-to-br ${getRandomColor(quiz.id)} transform group-hover:scale-105 transition-transform duration-700`"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent z-10"></div>
          <div class="absolute bottom-3 left-3 z-20">
            <span class="px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs font-semibold text-white border border-white/10 capitalize">{{ quiz.category }}</span>
          </div>
        </div>
        <div class="p-4 flex flex-col flex-grow">
          <h3 class="text-lg font-bold text-white mb-1 group-hover:text-indigo-300 transition-colors line-clamp-1">{{ quiz.title }}</h3>
          <p class="text-slate-400 text-xs line-clamp-2 mb-3 flex-grow">{{ quiz.description }}</p>
          <div class="flex justify-between items-center text-xs text-slate-500 border-t border-slate-700/50 pt-3 mt-auto">
            <span>⏱️ {{ quiz.questions_count }} Qs</span>
            <span>Par {{ quiz.user ? quiz.user.name : 'Anonyme' }}</span>
          </div>
        </div>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

useHead({ title: 'Explorer - QuizCreator' })

const quizzes = ref([])
const loading = ref(true)
const searchQuery = ref('')
const selectedCategory = ref('')

const colors = [
  'from-red-500 to-orange-500',
  'from-emerald-400 to-cyan-500',
  'from-indigo-500 to-purple-500',
  'from-pink-500 to-rose-500',
  'from-amber-400 to-orange-500',
  'from-blue-500 to-indigo-600'
]

const getRandomColor = (id) => {
  // Use id to make the color deterministic for each quiz
  return colors[(id || 0) % colors.length]
}

onMounted(async () => {
  try {
    const response = await $fetch('http://localhost:8000/api/quizzes')
    if (response.status && response.quiz) {
      quizzes.value = response.quiz
    }
  } catch (error) {
    console.error('Erreur lors du chargement des quiz', error)
  } finally {
    loading.value = false
  }
})

const filteredQuizzes = computed(() => {
  return quizzes.value.filter(quiz => {
    const matchSearch = quiz.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                        quiz.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchCategory = selectedCategory.value === '' || quiz.category === selectedCategory.value
    return matchSearch && matchCategory
  })
})
</script>
