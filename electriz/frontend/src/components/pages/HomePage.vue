<template>
  <div class="page home-page">
    <div class="hero">
      <div class="hero-bg"></div>
      <div class="hero-content">
        <p class="hero-tag">Новинки · Гарантия · Доставка</p>
        <h1 class="hero-title">Лучшая<br><span>электроника</span></h1>
        <p class="hero-desc">Смартфоны, ноутбуки, аксессуары — всё в одном месте</p>
        <router-link to="/catalog" class="btn btn-primary hero-btn">Перейти в каталог</router-link>
      </div>
    </div>

    <section class="section">
      <h3 class="section-title">Категории</h3>
      <div class="categories-row">
        <router-link v-for="cat in categories" :key="cat.id" :to="`/catalog?category_id=${cat.id}`" class="cat-chip">
          {{ cat.name }}
        </router-link>
      </div>
    </section>

    <section class="section">
      <div class="section-header">
        <h3 class="section-title">Популярное</h3>
        <router-link to="/catalog" class="see-all">Все →</router-link>
      </div>
      <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
      <div v-else class="products-grid">
        <ProductCard v-for="p in products.slice(0,6)" :key="p.id" :product="p" />
      </div>
    </section>

    <div v-if="isAuth && !subscribed" class="push-banner">
      <div class="push-icon">🔔</div>
      <div class="push-text">
        <strong>Включить уведомления</strong>
        <p>Статус заказа и новые акции</p>
      </div>
      <button class="btn btn-primary push-btn" @click="subscribePush">Вкл</button>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useStore } from 'vuex'
import axios from 'axios'
import ProductCard from '../ui/ProductCard.vue'

const store      = useStore()
const loading    = computed(() => store.getters['products/loading'])
const products   = computed(() => store.getters['products/products'])
const categories = computed(() => store.getters['products/categories'])
const isAuth     = computed(() => store.getters['auth/isAuthenticated'])
const subscribed = ref(false)

onMounted(async () => {
  await store.dispatch('products/fetchProducts')
  await store.dispatch('products/fetchCategories')
  if (isAuth.value) await store.dispatch('cart/fetchCart')
})

async function subscribePush() {
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) return
  try {
    const reg = await navigator.serviceWorker.ready
    const sub = await reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: urlBase64ToUint8Array(import.meta.env.VITE_VAPID_PUBLIC_KEY || '') })
    const json = sub.toJSON()
    await axios.post('/push/subscribe', { endpoint: json.endpoint, keys: json.keys })
    subscribed.value = true
  } catch(e) {}
}

function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4)
  const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const raw     = window.atob(base64)
  return Uint8Array.from([...raw].map(c => c.charCodeAt(0)))
}
</script>

<style scoped>
.hero { position: relative; margin: -16px -16px 24px; padding: 44px 20px 40px; overflow: hidden; }
.hero-bg { position: absolute; inset: 0; background: linear-gradient(135deg, #080820 0%, #0a0a0f 60%); }
.hero-bg::before { content: ''; position: absolute; top: -60px; right: -60px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(37,99,235,.18) 0%, transparent 70%); border-radius: 50%; }
.hero-bg::after  { content: ''; position: absolute; bottom: -40px; left: 20%; width: 180px; height: 180px; background: radial-gradient(circle, rgba(6,182,212,.08) 0%, transparent 70%); border-radius: 50%; }
.hero-content { position: relative; z-index: 1; }
.hero-tag   { font-size: .72rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--clr-accent); margin-bottom: 12px; }
.hero-title { font-size: 2.4rem; font-weight: 800; line-height: 1.05; letter-spacing: -.04em; margin-bottom: 10px; font-family: var(--font-display); }
.hero-title span { color: var(--clr-primary); }
.hero-desc  { color: var(--clr-text-muted); font-size: .88rem; margin-bottom: 24px; line-height: 1.5; }
.hero-btn   { padding: 14px 28px; }

.section { margin-bottom: 28px; }
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.section-title  { font-size: 1.1rem; font-weight: 800; letter-spacing: -.02em; margin-bottom: 0; font-family: var(--font-display); }
.see-all { font-size: .82rem; color: var(--clr-primary); font-weight: 700; text-decoration: none; }

.categories-row { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
.categories-row::-webkit-scrollbar { display: none; }
.cat-chip { white-space: nowrap; padding: 9px 18px; background: var(--clr-bg-card); border: 1px solid var(--clr-border); border-radius: 100px; color: var(--clr-text-soft); text-decoration: none; font-size: .82rem; font-weight: 600; transition: all .2s; }
.cat-chip:hover { border-color: var(--clr-primary); color: var(--clr-primary); }

.products-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.push-banner { display: flex; align-items: center; gap: 12px; background: var(--clr-bg-card); border: 1px solid var(--clr-border-soft); border-left: 3px solid var(--clr-primary); border-radius: var(--radius); padding: 14px; }
.push-icon { font-size: 1.5rem; }
.push-text { flex: 1; }
.push-text strong { font-size: .9rem; font-weight: 700; }
.push-text p { font-size: .78rem; color: var(--clr-text-muted); margin-top: 2px; }
.push-btn { padding: 9px 16px; font-size: .82rem; white-space: nowrap; }
</style>
