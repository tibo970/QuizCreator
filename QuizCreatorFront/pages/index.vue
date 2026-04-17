<template>
  <div class="space-y-16">
    <!-- Hero Section -->
    <section class="text-center py-20 lg:py-32 relative">
      <div class="relative z-10">
        <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
          <span class="block text-slate-100">Créez et jouez à des</span>
          <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">
            Quiz Extraordinaires
          </span>
        </h1>
        <p class="mt-4 max-w-2xl text-lg md:text-xl text-slate-400 mx-auto mb-10">
          Rejoignez la communauté QuizCreator. Testez vos connaissances ou défiez le monde entier avec vos propres créations en quelques clics.
        </p>
        <div class="flex justify-center gap-4">
          <NuxtLink to="/explore" class="px-8 py-4 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full font-semibold transition-all duration-300 shadow-[0_0_20px_rgba(99,102,241,0.4)] hover:shadow-[0_0_30px_rgba(99,102,241,0.6)] transform hover:-translate-y-1">
            Jouer maintenant
          </NuxtLink>
          <NuxtLink to="/create-quiz" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-full font-semibold border border-slate-700 transition-all duration-300 transform hover:-translate-y-1">
            Créer un Quiz
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Trending Quizzes Section -->
    <section>
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-white flex items-center gap-3">
          <span class="w-8 h-8 rounded-full bg-gradient-to-r from-pink-500 to-purple-500 flex items-center justify-center">🔥</span>
          Quiz Tendances
        </h2>
        <NuxtLink to="/explore" class="text-indigo-400 hover:text-indigo-300 font-medium group flex items-center gap-2">
          Voir tout 
          <span class="transform group-hover:translate-x-1 transition-transform">→</span>
        </NuxtLink>
      </div>

      <div v-if="loading" class="flex justify-center py-20 w-full">
        <svg class="animate-spin h-10 w-10 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
      </div>
      <div v-else-if="quizzes.length === 0" class="text-center py-10 text-slate-400 w-full">
        Aucun quiz tendance pour le moment. Soyez le premier à en créer un !
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <NuxtLink :to="`/quiz/${quiz.id}`" v-for="quiz in quizzes" :key="quiz.id" class="group bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] hover:-translate-y-1 cursor-pointer flex flex-col">
          <div class="h-48 relative overflow-hidden">
            <img v-if="quiz.cover && quiz.cover !== 'default.png'" :src="quiz.cover" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" alt="">
            <div v-else :class="`absolute inset-0 bg-gradient-to-br ${getRandomColor(quiz.id)} transform group-hover:scale-105 transition-transform duration-700`"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent z-10"></div>
            <div class="absolute bottom-3 left-3 z-20">
              <span class="px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs font-semibold text-white border border-white/10 capitalize">{{ quiz.category }}</span>
            </div>
          </div>
          <div class="p-5 flex flex-col flex-grow">
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors line-clamp-1">{{ quiz.title }}</h3>
            <p class="text-slate-400 text-sm line-clamp-2 mb-4 flex-grow">{{ quiz.description }}</p>
            <div class="flex justify-between items-center text-sm text-slate-500">
              <span class="flex items-center gap-1">⏱️ {{ quiz.questions_count }} questions</span>
              <span class="flex items-center gap-1">Par {{ quiz.user ? quiz.user.name : 'Anonyme' }}</span>
            </div>
          </div>
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

useHead({
  title: 'QuizCreator - Accueil',
  meta: [
    { name: 'description', content: 'Créez et jouez à des Quiz extraordinaires.' }
  ]
})

const quizzes = ref([])
const loading = ref(true)

const colors = [
  'from-red-500 to-yellow-500',
  'from-blue-500 to-purple-600',
  'from-yellow-700 to-yellow-500',
  'from-emerald-500 to-teal-400',
  'from-pink-500 to-rose-400'
]

const getRandomColor = (id) => {
  return colors[(id || 0) % colors.length]
}

onMounted(async () => {
  try {
    const { apiBase } = useRuntimeConfig().public
    const response = await $fetch(`${apiBase}/quizzes`)
    if (response.status && response.quiz) {
      // On ne garde que les 3 derniers pour la page d'accueil
      quizzes.value = response.quiz.slice(0, 3)
    }
  } catch (error) {
    console.error('Erreur de chargement', error)
  } finally {
    loading.value = false
  }
})
</script>
