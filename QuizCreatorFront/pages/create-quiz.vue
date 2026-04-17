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

          <!-- Image picker Unsplash -->
          <div class="space-y-3">
            <label class="block text-sm font-medium text-slate-300">Image de couverture</label>
            
            <!-- Aperçu image sélectionnée -->
            <div v-if="quiz.cover_image_url" class="relative rounded-xl overflow-hidden h-48 group">
              <img :src="quiz.cover_image_url" class="w-full h-full object-cover" alt="Couverture du quiz">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <button type="button" @click="quiz.cover_image_url = ''" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors">
                  Changer l'image
                </button>
              </div>
            </div>

            <!-- Barre de recherche Unsplash -->
            <div v-else class="space-y-3">
              <div class="flex gap-2">
                <div class="relative flex-1">
                  <span class="absolute inset-y-0 left-3 flex items-center text-slate-500">🔍</span>
                  <input
                    type="text"
                    v-model="imageSearch"
                    @keyup.enter="searchImages"
                    class="w-full bg-slate-900/50 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Rechercher une image (ex: mario, space, nature)..."
                  >
                </div>
                <button
                  type="button"
                  @click="searchImages"
                  :disabled="imageLoading"
                  class="px-5 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-medium transition-all disabled:opacity-50 whitespace-nowrap"
                >
                  <span v-if="!imageLoading">Rechercher</span>
                  <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
              </div>

              <!-- Grille résultats Unsplash -->
              <div v-if="unsplashResults.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                <button
                  v-for="img in unsplashResults"
                  :key="img.id"
                  type="button"
                  @click="selectImage(img)"
                  class="relative h-24 rounded-lg overflow-hidden group border-2 border-transparent hover:border-indigo-500 transition-all"
                >
                  <img :src="img.urls.small" class="w-full h-full object-cover" :alt="img.alt_description">
                  <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <span class="text-white text-2xl">✓</span>
                  </div>
                </button>
              </div>

              <!-- Placeholder si aucune recherche -->
              <div v-else class="flex items-center justify-center h-28 border-2 border-dashed border-slate-700 rounded-xl text-slate-500 text-sm">
                🖼️ Cherchez une image pour illustrer votre quiz
              </div>
            </div>
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
          <button type="submit" :disabled="isSubmitting" class="bg-indigo-500 hover:bg-indigo-600 disabled:opacity-50 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5">
            <span v-if="!isSubmitting">Publier le Quiz</span>
            <span v-else>Publication...</span>
          </button>
        </div>
      </form>

      <!-- Toast d'erreur -->
      <div v-if="errorMsg" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 bg-red-600/95 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl border border-red-400/30 max-w-lg">
        <span class="text-xl">🚫</span>
        <p class="text-sm font-medium">{{ errorMsg }}</p>
        <button @click="errorMsg = ''" class="ml-2 text-white/70 hover:text-white text-lg leading-none">✕</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

definePageMeta({ middleware: 'auth' })
useHead({ title: 'Créer un Quiz - QuizCreator' })

const UNSPLASH_ACCESS_KEY = 'hkEftQuEMb-imx3s_06zIYKKXMdOKS1cEkdAtKAByJ8'

const imageSearch = ref('')
const unsplashResults = ref([])
const imageLoading = ref(false)
const isSubmitting = ref(false)
const errorMsg = ref('')

const showError = (msg) => {
  errorMsg.value = msg
  setTimeout(() => { errorMsg.value = '' }, 6000)
}

const searchImages = async () => {
  if (!imageSearch.value.trim()) return
  imageLoading.value = true
  unsplashResults.value = []
  try {
    const data = await $fetch(`https://api.unsplash.com/search/photos`, {
      params: {
        query: imageSearch.value,
        per_page: 8,
        orientation: 'landscape'
      },
      headers: {
        Authorization: `Client-ID ${UNSPLASH_ACCESS_KEY}`
      }
    })
    unsplashResults.value = data.results
  } catch (e) {
    console.error('Erreur Unsplash', e)
  } finally {
    imageLoading.value = false
  }
}

const selectImage = (img) => {
  quiz.value.cover_image_url = img.urls.regular
  unsplashResults.value = []
  imageSearch.value = ''
}

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
  cover_image_url: '',
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
    title: quiz.value.title,
    category: quiz.value.category,
    description: quiz.value.description,
    cover_image_url: quiz.value.cover_image_url || '',
    questions: quiz.value.questions.map(q => ({
      libelleQ: q.libelleQ,
      answers: q.answers.map((a, i) => ({
        libelleR: a.libelleR,
        verite: i === q.correctAnswerIndex ? 1 : 0
      }))
    }))
  }
  
  console.log('📤 Payload envoyé :', JSON.stringify({ cover_image_url: payload.cover_image_url }))
  
  isSubmitting.value = true
  try {
    const token = localStorage.getItem('auth_token')
    const { apiBase } = useRuntimeConfig().public
    await $fetch(`${apiBase}/quizzes`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: payload
    })
    
    navigateTo('/explore')
  } catch (error) {
    console.error(error)
    showError(error.data?.message || 'Erreur lors de la création du quiz.')
  } finally {
    isSubmitting.value = false
  }
}
</script>
