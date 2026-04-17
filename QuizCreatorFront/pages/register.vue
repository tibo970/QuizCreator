<template>
  <div class="min-h-[80vh] flex items-center justify-center py-10">
    <div class="w-full max-w-md bg-slate-800/80 backdrop-blur-xl border border-slate-700 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
      <!-- Glow effect -->
      <div class="absolute -top-20 -left-20 w-40 h-40 bg-pink-500/20 blur-3xl rounded-full pointer-events-none"></div>
      <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-indigo-500/20 blur-3xl rounded-full pointer-events-none"></div>
      
      <div class="relative z-10 text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white mb-2">Rejoignez-nous</h2>
        <p class="text-slate-400 text-sm">Créez votre compte et commencez à créer des quiz !</p>
      </div>

      <form @submit.prevent="handleRegister" class="relative z-10 space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nom d'utilisateur</label>
          <input 
            type="text" 
            id="name" 
            v-model="name" 
            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="Pseudo"
            required
          >
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="email" 
            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="vous@email.com"
            required
          >
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Mot de passe</label>
          <input 
            type="password" 
            id="password" 
            v-model="password" 
            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="••••••••"
            required
            minlength="8"
          >
        </div>
        
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Confirmez le mot de passe</label>
          <input 
            type="password" 
            id="password_confirmation" 
            v-model="password_confirmation" 
            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
            placeholder="••••••••"
            required
            minlength="8"
          >
        </div>

        <button 
          type="submit" 
          class="w-full bg-gradient-to-r from-pink-500 to-indigo-500 hover:from-pink-600 hover:to-indigo-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(236,72,153,0.3)] hover:shadow-[0_0_25px_rgba(236,72,153,0.5)] transform hover:-translate-y-0.5"
          :disabled="isLoading"
        >
          <span v-if="!isLoading">Créer mon compte</span>
          <span v-else class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Inscription...
          </span>
        </button>
      </form>

      <div class="mt-8 text-center text-sm text-slate-400">
        Déjà un compte ? 
        <NuxtLink to="/login" class="text-pink-400 hover:text-pink-300 font-medium">Se connecter</NuxtLink>
      </div>
      
      <!-- Notification Toast -->
      <div v-if="notification.show" :class="`absolute top-4 left-1/2 -translate-x-1/2 w-[90%] p-4 rounded-xl shadow-lg transition-all duration-300 z-50 flex items-center justify-between ${notification.type === 'error' ? 'bg-red-500/90 text-white' : 'bg-emerald-500/90 text-white'}`">
        <span class="text-sm font-medium">{{ notification.message }}</span>
        <button @click="notification.show = false" class="text-white/80 hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

useHead({ title: 'Inscription - QuizCreator' })

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const isLoading = ref(false)

const notification = ref({ show: false, message: '', type: 'error' })

const showNotification = (message, type = 'error') => {
  notification.value = { show: true, message, type }
  setTimeout(() => { notification.value.show = false }, 5000)
}

const handleRegister = async () => {
  if (password.value !== password_confirmation.value) {
    showNotification('Les mots de passe ne correspondent pas !', 'error')
    return
  }
  
  isLoading.value = true
  notification.value.show = false
  
  try {
    const config = useRuntimeConfig()
    const response = await $fetch('http://localhost:8000/api/register', {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value
      }
    })
    
    // Auto-login : on stocke le token de sécurité
    if (process.client) {
      localStorage.setItem('auth_token', response.access_token)
      localStorage.setItem('user', JSON.stringify(response.user))
    }
    
    showNotification('Inscription réussie ! Connexion en cours...', 'success')
    
    // Redirection automatique après 1.5s
    setTimeout(() => {
      navigateTo('/')
    }, 1500)
    
  } catch (error) {
    console.error(error)
    
    // Traitement spécifique des erreurs Laravel (email déjà pris, etc.)
    if (error.data?.errors?.email) {
      showNotification('Cette adresse email est déjà utilisée par un autre compte.', 'error')
    } else {
      showNotification(error.data?.message || 'Erreur lors de l\'inscription.', 'error')
    }
  } finally {
    isLoading.value = false
  }
}
</script>
