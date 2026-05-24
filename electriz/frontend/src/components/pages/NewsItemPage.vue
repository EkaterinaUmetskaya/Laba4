<template>
  <div class="page">
    <button class="back-btn" @click="$router.back()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>Назад</button>
    <div v-if="!item" class="loader-wrap"><div class="loader"></div></div>
    <div v-else>
      <img v-if="item.image" :src="item.image" class="news-hero-img" />
      <h1 class="news-title">{{ item.title }}</h1>
      <p class="news-date">{{ formatDate(item.published_at) }}</p>
      <div class="news-body">{{ item.body }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
const store = useStore()
const route = useRoute()
const item  = computed(() => store.getters['news/current'])
onMounted(() => store.dispatch('news/fetchNewsItem', route.params.id))
function formatDate(d) { return new Date(d).toLocaleDateString('ru-RU', { day: '2-digit', month: 'long', year: 'numeric' }) }
</script>

<style scoped>
.back-btn { display: flex; align-items: center; gap: 4px; background: none; border: none; color: var(--clr-text-muted); font-size: .9rem; font-family: var(--font-main); cursor: pointer; margin-bottom: 16px; padding: 0; }
.back-btn svg { width: 18px; height: 18px; }
.news-hero-img { width: 100%; height: 200px; object-fit: cover; border-radius: var(--radius); margin-bottom: 20px; }
.news-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 8px; font-family: var(--font-display); }
.news-date  { font-size: .85rem; color: var(--clr-text-muted); margin-bottom: 20px; }
.news-body  { font-size: .95rem; line-height: 1.7; color: var(--clr-text); white-space: pre-wrap; }
</style>
