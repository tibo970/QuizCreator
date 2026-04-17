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

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Mock Quiz Card 1 -->
        <div class="group bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] hover:-translate-y-1 cursor-pointer">
          <div class="h-48 bg-slate-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
            <!-- Mock image placeholder -->
            <div class="w-full h-full bg-gradient-to-br from-red-500 to-yellow-500 transform group-hover:scale-105 transition-transform duration-700"></div>
            <div class="absolute bottom-3 left-3 z-20">
              <span class="px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs font-semibold text-white border border-white/10">Mario</span>
            </div>
          </div>
          <div class="p-5">
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors">Le Quiz Ultime de Mario</h3>
            <p class="text-slate-400 text-sm line-clamp-2 mb-4">Testez vos connaissances sur l'univers du plombier le plus célèbre du monde du jeu vidéo !</p>
            <div class="flex justify-between items-center text-sm text-slate-500">
              <span class="flex items-center gap-1">⏱️ 10 questions</span>
              <span class="flex items-center gap-1">🎮 1.2k joueurs</span>
            </div>
          </div>
        </div>

        <!-- Mock Quiz Card 2 -->
        <div class="group bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] hover:-translate-y-1 cursor-pointer">
          <div class="h-48 bg-slate-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 transform group-hover:scale-105 transition-transform duration-700"></div>
            <div class="absolute bottom-3 left-3 z-20">
              <span class="px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs font-semibold text-white border border-white/10">Pokémon</span>
            </div>
          </div>
          <div class="p-5">
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors">Connaissez-vous vos Pokémon ?</h3>
            <p class="text-slate-400 text-sm line-clamp-2 mb-4">Seuls les vrais maîtres Pokémon pourront obtenir 100% à ce quiz difficile.</p>
            <div class="flex justify-between items-center text-sm text-slate-500">
              <span class="flex items-center gap-1">⏱️ 15 questions</span>
              <span class="flex items-center gap-1">🎮 850 joueurs</span>
            </div>
          </div>
        </div>

        <!-- Mock Quiz Card 3 -->
        <div class="group bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] hover:-translate-y-1 cursor-pointer">
          <div class="h-48 bg-slate-700 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
            <div class="w-full h-full bg-gradient-to-br from-yellow-700 to-yellow-500 transform group-hover:scale-105 transition-transform duration-700"></div>
            <div class="absolute bottom-3 left-3 z-20">
              <span class="px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs font-semibold text-white border border-white/10">Harry Potter</span>
            </div>
          </div>
          <div class="p-5">
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-indigo-300 transition-colors">Harry Potter pour les experts</h3>
            <p class="text-slate-400 text-sm line-clamp-2 mb-4">Quiz réservé aux sorciers de niveau ASPIC ! Moldu s'abstenir.</p>
            <div class="flex justify-between items-center text-sm text-slate-500">
              <span class="flex items-center gap-1">⏱️ 20 questions</span>
              <span class="flex items-center gap-1">🎮 3.4k joueurs</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
// Config page meta if needed
useHead({
  title: 'QuizCreator - Accueil',
  meta: [
    { name: 'description', content: 'Créez et jouez à des Quiz extraordinaires.' }
  ]
})
</script>
