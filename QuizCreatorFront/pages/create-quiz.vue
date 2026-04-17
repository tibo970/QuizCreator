<template>
  <div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Créer un nouveau Quiz</h1>
      <p class="text-slate-400">Partagez vos connaissances avec la communauté en créant un quiz unique.</p>
    </div>

    <div class="bg-slate-800/80 backdrop-blur-sm border border-slate-700 rounded-2xl p-6 md:p-8">
      <form @submit.prevent="submitQuiz" class="space-y-8">
        
        <!-- Informations générales -->
        <div class="space-y-6">
          <h2 class="text-xl font-semibold text-white border-b border-slate-700 pb-2">Informations Générales</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label for="title" class="block text-sm font-medium text-slate-300">Titre du Quiz</label>
              <input 
                type="text" 
                id="title" 
                v-model="quiz.title" 
                class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Ex: Les capitales du monde"
                required
              >
            </div>
            
            <div class="space-y-2">
              <label for="category" class="block text-sm font-medium text-slate-300">Catégorie</label>
              <select 
                id="category" 
                v-model="quiz.category" 
                class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none"
                required
              >
                <option value="" disabled>Sélectionnez une catégorie</option>
                <option value="mario">Mario</option>
                <option value="pokemon">Pokémon</option>
                <option value="harry-potter">Harry Potter</option>
                <option value="autre">Autre</option>
              </select>
            </div>
          </div>

          <div class="space-y-2">
            <label for="description" class="block text-sm font-medium text-slate-300">Description</label>
            <textarea 
              id="description" 
              v-model="quiz.description" 
              rows="3"
              class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
              placeholder="Décrivez brièvement le sujet de votre quiz..."
              required
            ></textarea>
          </div>
        </div>

        <!-- Questions -->
        <div class="space-y-6">
          <div class="flex justify-between items-center border-b border-slate-700 pb-2">
            <h2 class="text-xl font-semibold text-white">Questions ({{ quiz.questions.length }})</h2>
          </div>

          <div v-for="(question, qIndex) in quiz.questions" :key="qIndex" class="bg-slate-900/50 rounded-xl p-6 border border-slate-700 relative group transition-all duration-300 hover:border-indigo-500/50">
            <button type="button" @click="removeQuestion(qIndex)" class="absolute top-4 right-4 text-slate-500 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </button>

            <div class="space-y-4">
              <div class="space-y-2">
                <label :for="'q-' + qIndex" class="block text-sm font-medium text-slate-300">Question {{ qIndex + 1 }}</label>
                <input 
                  type="text" 
                  :id="'q-' + qIndex" 
                  v-model="question.libelleQ" 
                  class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500"
                  placeholder="Posez votre question..."
                  required
                >
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div v-for="(answer, aIndex) in question.answers" :key="aIndex" class="flex items-center gap-3">
                  <input 
                    type="radio" 
                    :name="'correct-' + qIndex" 
                    :id="'correct-' + qIndex + '-' + aIndex"
                    :value="aIndex"
                    v-model="question.correctAnswerIndex"
                    class="text-indigo-500 bg-slate-800 border-slate-600 focus:ring-indigo-500 focus:ring-offset-slate-900 cursor-pointer"
                    required
                  >
                  <input 
                    type="text" 
                    v-model="answer.libelleR" 
                    class="flex-1 bg-slate-800 border border-slate-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500"
                    :placeholder="'Réponse ' + (aIndex + 1)"
                    required
                  >
                </div>
              </div>
            </div>
          </div>

          <button type="button" @click="addQuestion" class="w-full py-4 border-2 border-dashed border-slate-700 hover:border-indigo-500 rounded-xl text-slate-400 hover:text-indigo-400 font-medium transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            Ajouter une question
          </button>
        </div>

        <div class="pt-6 border-t border-slate-700 flex justify-end gap-4">
          <NuxtLink to="/" class="px-6 py-3 text-slate-300 hover:text-white font-medium transition-colors">
            Annuler
          </NuxtLink>
          <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5">
            Publier le Quiz
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

definePageMeta({ middleware: 'auth' })
useHead({ title: 'Créer un Quiz - QuizCreator' })

const getEmptyQuestion = () => ({
  libelleQ: '',
  correctAnswerIndex: null,
  answers: [
    { libelleR: '' },
    { libelleR: '' },
    { libelleR: '' },
    { libelleR: '' }
  ]
})

const quiz = ref({
  title: '',
  category: '',
  description: '',
  questions: [getEmptyQuestion()]
})

const addQuestion = () => {
  quiz.value.questions.push(getEmptyQuestion())
}

const removeQuestion = (index) => {
  if (quiz.value.questions.length > 1) {
    quiz.value.questions.splice(index, 1)
  }
}

const submitQuiz = async () => {
  const payload = {
    ...quiz.value,
    questions: quiz.value.questions.map(q => ({
      libelleQ: q.libelleQ,
      answers: q.answers.map((a, i) => ({
        libelleR: a.libelleR,
        verite: i === q.correctAnswerIndex ? 1 : 0
      }))
    }))
  }
  
  try {
    const token = localStorage.getItem('auth_token')
    await $fetch('http://localhost:8000/api/quizzes', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      body: payload
    })
    
    alert('Quiz créé avec succès !')
    navigateTo('/explore')
  } catch (error) {
    console.error(error)
    alert(error.data?.message || 'Erreur lors de la création du quiz.')
  }
}
</script>
