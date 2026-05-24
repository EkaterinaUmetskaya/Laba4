<template>
  <div v-if="isDesktop" class="desktop-page">
    <aside class="desktop-side">
      <div class="desktop-logo">⚡</div>
      <h1>Electriz<span>Shop</span></h1>
      <p>PWA-магазин электроники.<br>На телефоне работает как нативное приложение.</p>
      <div class="desktop-features">
        <div class="feature">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Быстрая доставка
        </div>
        <div class="feature">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Работает офлайн
        </div>
        <div class="feature">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Push-уведомления
        </div>
      </div>
    </aside>
    <div class="desktop-phone-wrap">
      <div class="phone-shell">
        <div class="phone-btn-vol"></div>
        <div class="phone-btn-pwr"></div>
        <div class="phone-screen">
          <div class="phone-status-bar"><span>9:41</span><span>●●●</span></div>
          <div class="phone-app">
            <AppNav class="nav-in-phone" />
            <main class="phone-main"><router-view /></main>
            <BottomNav class="nav-in-phone" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="app-shell">
    <AppNav v-if="!isSplash" />
    <main class="app-main">
      <SplashScreen v-if="isSplash" @done="isSplash = false" />
      <router-view v-else />
    </main>
    <BottomNav v-if="!isSplash" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppNav       from './components/layout/AppNav.vue'
import BottomNav    from './components/layout/BottomNav.vue'
import SplashScreen from './components/pages/SplashScreen.vue'

const isSplash = ref(true)

const isDesktop = computed(() => {
  if (typeof window === 'undefined') return false
  const ua = navigator.userAgent.toLowerCase()
  const isMobile = /android|iphone|ipad|ipod|mobile|phone/i.test(ua)
  if (isMobile) return false
  return window.innerWidth >= 1024
})
</script>

<style>
@import './assets/css/global.css';
</style>
