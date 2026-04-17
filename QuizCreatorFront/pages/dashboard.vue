<template>
  <div class="max-w-7xl mx-auto py-8">
    <div class="flex justify-between items-center mb-10 border-b border-slate-700 pb-6">
      <div>
        <h1 class="text-3xl font-bold text-white mb-2">Mon Tableau de Bord</h1>
        <p class="text-slate-400">Gérez vos quiz et consultez vos statistiques.</p>
      </div>
      <NuxtLink to="/create-quiz" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-3 px-6 rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.3)] flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
        Créer un Quiz
      </NuxtLink>
    </div>

    <div v-if="loading" class="flex justify-center py-20">
      <svg class="animate-spin h-10 w-10 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <div v-else>
      <div v-if="quizzes.length === 0" class="text-center py-20 bg-slate-800/50 rounded-2xl border border-slate-700">
        <div class="text-6xl mb-4">📝</div>
        <h3 class="text-xl font-medium text-white mb-2">Vous n'avez pas encore créé de quiz</h3>
        <p class="text-slate-400 mb-6">Commencez dès maintenant à partager vos connaissances.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="quiz in quizzes" :key="quiz.id" class="bg-slate-800/80 border border-slate-700 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-300 group">
          <div class="h-32 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 relative p-6 flex flex-col justify-end">
            <span class="absolute top-4 right-4 px-3 py-1 bg-slate-900/50 backdrop-blur-md rounded-full text-xs font-medium text-slate-300">
              {{ quiz.questions_count }} questions
            </span>
            <h3 class="text-xl font-bold text-white truncate">{{ quiz.title }}</h3>
            <p class="text-sm text-slate-400 capitalize">{{ quiz.category }}</p>
          </div>
          
          <div class="p-6 border-t border-slate-700 flex justify-between items-center bg-slate-900/50">
            <NuxtLink :to="`/quiz/${quiz.id}`" class="text-indigo-400 hover:text-indigo-300 font-medium text-sm flex items-center gap-1">
              Voir le quiz
            </NuxtLink>
            
            <button @click="deleteQuiz(quiz.id)" class="text-slate-500 hover:text-rose-500 transition-colors p-2 rounded-lg hover:bg-rose-500/10" title="Supprimer le quiz">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })
useHead({ title: 'Mon Dashboard - QuizCreator' })

const quizzes = ref([])
const loading = ref(true)

const fetchMyQuizzes = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const { apiBase } = useRuntimeConfig().public
    const response = await $fetch(`${apiBase}/my-quizzes`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    
    if (response.status && response.quiz) {
      quizzes.value = response.quiz
    }
  } catch (error) {
    console.error('Erreur de chargement', error)
  } finally {
    loading.value = false
  }
}

const deleteQuiz = async (id) => {
  if (!confirm('Êtes-vous sûr de vouloir supprimer ce quiz définitivement ?')) return
  
  try {
    const token = localStorage.getItem('auth_token')
    const { apiBase } = useRuntimeConfig().public
    await $fetch(`${apiBase}/quizzes/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    
    // Retirer le quiz de la liste locale
    quizzes.value = quizzes.value.filter(q => q.id !== id)
    alert('Quiz supprimé avec succès.')
  } catch (error) {
    console.error('Erreur lors de la suppression', error)
    alert('Impossible de supprimer ce quiz.')
  }
}

onMounted(() => {
  fetchMyQuizzes()
})
</script>
