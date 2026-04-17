<template>
  <div class="max-w-3xl mx-auto py-10 px-4">
    <div v-if="loading" class="flex flex-col items-center justify-center py-20">
      <svg class="animate-spin h-10 w-10 text-indigo-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="text-slate-400">Chargement du quiz...</p>
    </div>

    <div v-else-if="quiz" class="bg-slate-800/80 backdrop-blur-xl border border-slate-700 rounded-3xl overflow-hidden shadow-2xl">
      <!-- Header avec image de couverture -->
      <div class="h-48 relative flex items-end p-8 overflow-hidden">
        <!-- Image Unsplash si disponible, sinon gradient -->
        <img 
          v-if="quiz.cover && quiz.cover !== 'default.png'" 
          :src="quiz.cover" 
          class="absolute inset-0 w-full h-full object-cover"
          alt="Couverture du quiz"
        >
        <div v-else class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 w-full">
          <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold text-white mb-3 capitalize">
            {{ quiz.category }}
          </span>
          <h1 class="text-3xl md:text-4xl font-extrabold text-white">{{ quiz.title }}</h1>
        </div>
      </div>

      <!-- Game Area -->
      <div class="p-8" v-if="!isFinished">
        <div class="flex justify-between items-center mb-6 text-slate-400 font-medium">
          <span>Question {{ currentQuestionIndex + 1 }} sur {{ quiz.questions.length }}</span>
          <span>Score : {{ score }}</span>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-semibold text-white leading-relaxed">
            {{ currentQuestion.libelleQ }}
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <button 
            v-for="(answer, index) in currentQuestion.answers" 
            :key="index"
            @click="selectAnswer(answer)"
            class="p-4 rounded-xl border text-left transition-all duration-300"
            :class="[
              selectedAnswer === answer
                ? (answer.verite ? 'bg-emerald-500/20 border-emerald-500 text-emerald-300' : 'bg-rose-500/20 border-rose-500 text-rose-300')
                : 'bg-slate-900/50 border-slate-700 hover:border-indigo-500 hover:bg-indigo-500/10 text-white'
            ]"
            :disabled="selectedAnswer !== null"
          >
            <div class="flex items-center justify-between">
              <span>{{ answer.libelleR }}</span>
              <span v-if="selectedAnswer === answer">
                <svg v-if="answer.verite" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              </span>
            </div>
          </button>
        </div>

        <div class="mt-8 flex justify-end" v-if="selectedAnswer">
          <button @click="nextQuestion" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-8 rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)]">
            {{ currentQuestionIndex === quiz.questions.length - 1 ? 'Terminer' : 'Suivant' }}
          </button>
        </div>
      </div>

      <!-- Result Area -->
      <div class="p-8 text-center py-16" v-else>
        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-5xl mb-6 shadow-[0_0_30px_rgba(245,158,11,0.4)]">
          🏆
        </div>
        <h2 class="text-3xl font-bold text-white mb-2">Quiz Terminé !</h2>
        <p class="text-slate-400 text-lg mb-8">Vous avez terminé le quiz "{{ quiz.title }}"</p>
        
        <div class="bg-slate-900/50 rounded-2xl p-6 max-w-sm mx-auto mb-8 border border-slate-700">
          <p class="text-slate-400 font-medium mb-1">Votre score final</p>
          <p class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-400">
            {{ score }} / {{ quiz.questions.length }}
          </p>
        </div>

        <div class="flex justify-center gap-4">
          <NuxtLink to="/explore" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium transition-colors">
            Autres quiz
          </NuxtLink>
          <button @click="restartQuiz" class="px-6 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-medium transition-all shadow-[0_0_15px_rgba(99,102,241,0.3)]">
            Rejouer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const loading = ref(true)
const quiz = ref(null)
const currentQuestionIndex = ref(0)
const score = ref(0)
const selectedAnswer = ref(null)
const selectedAnswersIds = ref([])
const isFinished = ref(false)
const error = ref(null)

const currentQuestion = computed(() => quiz.value?.questions[currentQuestionIndex.value])

onMounted(async () => {
  try {
    const config = useRuntimeConfig()
    const response = await $fetch(`http://localhost:8000/api/quizzes/${route.params.id}`)
    
    if (response.status && response.quiz) {
      quiz.value = response.quiz
    } else {
      error.value = "Quiz introuvable."
    }
  } catch (e) {
    console.error(e)
    error.value = "Erreur lors du chargement du quiz."
  } finally {
    loading.value = false
  }
})

const selectAnswer = (answer) => {
  if (selectedAnswer.value) return
  selectedAnswer.value = answer
  
  if (answer.id) {
    selectedAnswersIds.value.push(answer.id)
  }
  
  // Affichage client-side temporaire
  if (answer.verite) {
    score.value++
  }
}

const nextQuestion = async () => {
  if (currentQuestionIndex.value < quiz.value.questions.length - 1) {
    currentQuestionIndex.value++
    selectedAnswer.value = null
  } else {
    isFinished.value = true
    
    // Save secure score to API
    if (process.client) {
      const token = localStorage.getItem('auth_token')
      if (token) {
        try {
          const response = await $fetch('http://localhost:8000/api/scores', {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`
            },
            body: {
              quiz_id: quiz.value.id,
              answers_ids: selectedAnswersIds.value
            }
          })
          
          if (response.data && response.data.score !== undefined) {
            score.value = response.data.score
          }
        } catch (e) {
          console.error('Erreur lors de la sauvegarde du score', e)
        }
      }
    }
  }
}

const restartQuiz = () => {
  currentQuestionIndex.value = 0
  score.value = 0
  selectedAnswersIds.value = []
  selectedAnswer.value = null
  isFinished.value = false
}

useHead({
  title: computed(() => quiz.value ? `${quiz.value.title} - QuizCreator` : 'Quiz - QuizCreator')
})
</script>
