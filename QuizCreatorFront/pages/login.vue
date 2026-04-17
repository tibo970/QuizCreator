<template>
  <div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-md bg-slate-800/80 backdrop-blur-xl border border-slate-700 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
      <!-- Glow effect -->
      <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-500/20 blur-3xl rounded-full pointer-events-none"></div>
      <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-purple-500/20 blur-3xl rounded-full pointer-events-none"></div>
      
      <div class="relative z-10 text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white mb-2">Bon retour !</h2>
        <p class="text-slate-400 text-sm">Connectez-vous pour accéder à vos quiz et vos scores.</p>
      </div>

      <form @submit.prevent="handleLogin" class="relative z-10 space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="email" 
            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
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
            class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            placeholder="••••••••"
            required
          >
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" class="rounded border-slate-700 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
            <span class="text-slate-400">Se souvenir de moi</span>
          </label>
          <a href="#" class="text-indigo-400 hover:text-indigo-300">Mot de passe oublié ?</a>
        </div>

        <button 
          type="submit" 
          class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5"
          :disabled="isLoading"
        >
          <span v-if="!isLoading">Se connecter</span>
          <span v-else class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Connexion...
          </span>
        </button>
      </form>

      <div class="mt-8 text-center text-sm text-slate-400">
        Pas encore de compte ? 
        <NuxtLink to="/register" class="text-indigo-400 hover:text-indigo-300 font-medium">Créer un compte</NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

useHead({ title: 'Connexion - QuizCreator' })

const email = ref('')
const password = ref('')
const isLoading = ref(false)

const handleLogin = async () => {
  isLoading.value = true
  try {
    const config = useRuntimeConfig()
    const response = await $fetch('http://localhost:8000/api/login', {
      method: 'POST',
      body: {
        email: email.value,
        password: password.value,
      }
    })
    
    if (process.client) {
      localStorage.setItem('auth_token', response.access_token)
    }
    
    alert('Connexion réussie !')
    navigateTo('/')
  } catch (error) {
    console.error(error)
    alert(error.data?.message || 'Identifiants incorrects.')
  } finally {
    isLoading.value = false
  }
}
</script>
