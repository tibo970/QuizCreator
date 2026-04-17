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
            placeholder="Rechercher un quiz..." 
            class="w-full bg-slate-800/80 border border-slate-700 rounded-full pl-12 pr-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          >
        </div>
        <select class="w-full md:w-auto bg-slate-800/80 border border-slate-700 rounded-full px-6 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none">
          <option value="">Toutes les catégories</option>
          <option value="mario">Mario</option>
          <option value="pokemon">Pokémon</option>
          <option value="harry-potter">Harry Potter</option>
        </select>
      </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="i in 8" :key="i" class="group bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 hover:shadow-[0_0_30px_rgba(99,102,241,0.15)] hover:-translate-y-1 cursor-pointer">
        <div class="h-40 bg-slate-700 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
          <div :class="`w-full h-full bg-gradient-to-br ${getRandomColor()} transform group-hover:scale-105 transition-transform duration-700`"></div>
        </div>
        <div class="p-4">
          <h3 class="text-lg font-bold text-white mb-1 group-hover:text-indigo-300 transition-colors line-clamp-1">Quiz Générique {{ i }}</h3>
          <p class="text-slate-400 text-xs line-clamp-2 mb-3">Une description générique pour montrer l'apparence des cartes de quiz dans la grille.</p>
          <div class="flex justify-between items-center text-xs text-slate-500 border-t border-slate-700/50 pt-3">
            <span>⏱️ 10 Qs</span>
            <span>⭐ 4.5/5</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
useHead({ title: 'Explorer - QuizCreator' })

const colors = [
  'from-red-500 to-orange-500',
  'from-emerald-400 to-cyan-500',
  'from-indigo-500 to-purple-500',
  'from-pink-500 to-rose-500',
  'from-amber-400 to-orange-500',
  'from-blue-500 to-indigo-600'
]

const getRandomColor = () => {
  return colors[Math.floor(Math.random() * colors.length)]
}
</script>
