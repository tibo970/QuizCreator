<template>
  <div class="min-h-screen bg-slate-900 text-white font-sans selection:bg-indigo-500 selection:text-white">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-slate-900/80 border-b border-slate-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-lg shadow-lg shadow-indigo-500/30">
              Q
            </div>
            <span class="font-bold text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">
              QuizCreator
            </span>
          </div>
          
          <div class="hidden md:flex space-x-8">
            <NuxtLink to="/" class="text-slate-300 hover:text-white transition-colors">Accueil</NuxtLink>
            <NuxtLink to="/explore" class="text-slate-300 hover:text-white transition-colors">Explorer</NuxtLink>
          </div>

          <div class="flex items-center space-x-4">
            <template v-if="user">
              <span class="text-slate-300 text-sm">Bonjour, <strong class="text-white">{{ user.name }}</strong></span>
              <NuxtLink to="/dashboard" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">
                Mon Profil
              </NuxtLink>
              <button @click="logout" class="text-sm font-medium bg-slate-800 hover:bg-rose-500/20 text-slate-300 hover:text-rose-400 px-4 py-2 rounded-full transition-all duration-300 border border-slate-700 hover:border-rose-500/50">
                Déconnexion
              </button>
            </template>
            <template v-else>
              <NuxtLink to="/login" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">
                Connexion
              </NuxtLink>
              <NuxtLink to="/register" class="text-sm font-medium bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-full transition-all duration-300 shadow-[0_0_15px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5">
                S'inscrire
              </NuxtLink>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
      <!-- Background Ambient Glow -->
      <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-indigo-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>
      
      <!-- Slot for pages -->
      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const user = ref(null)

onMounted(() => {
  const userData = localStorage.getItem('user')
  if (userData) {
    user.value = JSON.parse(userData)
  }
})

const logout = async () => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    try {
      await $fetch('http://localhost:8000/api/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      })
    } catch(e) {
      console.error(e)
    }
  }
  
  localStorage.removeItem('auth_token')
  localStorage.removeItem('user')
  user.value = null
  navigateTo('/login')
}
</script>

<style>
body {
  font-family: 'Inter', sans-serif;
}
.page-enter-active,
.page-leave-active {
  transition: all 0.4s;
}
.page-enter-from,
.page-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
