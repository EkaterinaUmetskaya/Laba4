<template>
  <div class="page">
    <button class="back-btn" @click="$router.back()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Назад
    </button>
    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
    <div v-else-if="product" class="product-detail">
      <div class="product-hero">
        <img v-if="product.image" :src="product.image" :alt="product.name" />
        <div v-else class="product-hero-placeholder">
          <div class="big-device">
            <div class="big-screen"></div>
            <div class="big-btn"></div>
          </div>
        </div>
        <div class="hero-brand" v-if="product.brand">{{ product.brand }}</div>
      </div>
      <div class="product-info card">
        <div class="product-cat">{{ product.category?.name }}</div>
        <h1 class="product-name">{{ product.name }}</h1>
        <div class="product-meta-row">
          <div class="meta-pill" v-if="product.sku">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Арт. {{ product.sku }}
          </div>
          <div class="meta-pill stock" v-if="product.stock > 0">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            В наличии {{ product.stock }} шт.
          </div>
          <div class="meta-pill no-stock" v-else>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Нет в наличии
          </div>
        </div>
        <p class="product-desc">{{ product.description }}</p>
        <div class="buy-row">
          <span class="big-price">{{ product.price?.toLocaleString('ru-RU') }} ₽</span>
          <div class="qty-control">
            <button class="qty-btn" @click="dec"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
            <span class="qty-val">{{ qty }}</span>
            <button class="qty-btn" @click="inc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></button>
          </div>
        </div>
        <button class="btn btn-primary btn-full add-cart-btn" @click="addToCart" :disabled="product.stock === 0">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
          В корзину · {{ (product.price * qty)?.toLocaleString('ru-RU') }} ₽
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'
const store   = useStore()
const route   = useRoute()
const router  = useRouter()
const product = computed(() => store.getters['products/current'])
const loading = computed(() => store.getters['products/loading'])
const qty     = ref(1)
onMounted(() => store.dispatch('products/fetchProduct', route.params.id))
function inc() { qty.value++ }
function dec() { if (qty.value > 1) qty.value-- }
async function addToCart() {
  if (!store.getters['auth/isAuthenticated']) { router.push('/login'); return }
  await store.dispatch('cart/addItem', { product_id: product.value.id, quantity: qty.value })
  router.push('/cart')
}
</script>

<style scoped>
.back-btn { display: flex; align-items: center; gap: 4px; background: none; border: none; color: var(--clr-text-muted); font-size: .9rem; font-family: var(--font-main); cursor: pointer; margin-bottom: 16px; padding: 0; }
.back-btn svg { width: 18px; height: 18px; }
.product-hero { width: calc(100% + 32px); margin: -16px -16px 0; aspect-ratio: 4/3; overflow: hidden; position: relative; max-height: 260px; }
.product-hero img { width: 100%; height: 100%; object-fit: cover; }
.product-hero-placeholder { width: 100%; height: 100%; background: linear-gradient(145deg, #10101e, #1a1a2e); display: flex; align-items: center; justify-content: center; }
.big-device { position: relative; width: 100px; height: 100px; }
.big-screen  { position: absolute; inset: 0; background: #1a1a30; border-radius: 16px; border: 3px solid #2a2a45; }
.big-btn     { position: absolute; bottom: -14px; left: 50%; transform: translateX(-50%); width: 22px; height: 22px; background: #1a1a30; border: 3px solid #2a2a45; border-radius: 50%; }
.hero-brand  { position: absolute; top: 12px; right: 12px; background: rgba(37,99,235,.25); color: var(--clr-primary); font-size: .75rem; font-weight: 700; padding: 4px 10px; border-radius: 100px; border: 1px solid rgba(37,99,235,.4); }
.product-info { margin-top: -16px; position: relative; z-index: 1; border-radius: var(--radius) var(--radius) 0 0; }
.product-cat  { font-size: .72rem; font-weight: 700; color: var(--clr-primary); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
.product-name { font-size: 1.5rem; font-weight: 800; letter-spacing: -.03em; margin-bottom: 12px; font-family: var(--font-display); }
.product-meta-row { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
.meta-pill { display: flex; align-items: center; gap: 6px; background: var(--clr-bg-elevated); border: 1px solid var(--clr-border); padding: 6px 12px; border-radius: 100px; font-size: .8rem; color: var(--clr-text-soft); font-weight: 600; }
.meta-pill svg { width: 13px; height: 13px; }
.meta-pill.stock { color: #059669; border-color: rgba(5,150,105,.3); background: rgba(5,150,105,.08); }
.meta-pill.no-stock { color: #dc2626; border-color: rgba(220,38,38,.3); background: rgba(220,38,38,.08); }
.product-desc { color: var(--clr-text-muted); font-size: .9rem; line-height: 1.6; margin-bottom: 20px; }
.buy-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.big-price { font-size: 2rem; font-weight: 800; letter-spacing: -.04em; font-family: var(--font-display); }
.qty-control { display: flex; align-items: center; gap: 12px; }
.qty-btn { width: 38px; height: 38px; border-radius: 50%; background: var(--clr-bg-elevated); border: 1px solid var(--clr-border-soft); color: var(--clr-text); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s; }
.qty-btn svg { width: 14px; height: 14px; }
.qty-btn:hover { border-color: var(--clr-primary); color: var(--clr-primary); }
.qty-val { font-size: 1.1rem; font-weight: 800; min-width: 24px; text-align: center; }
.add-cart-btn { gap: 10px; padding: 16px; font-size: .95rem; }
.add-cart-btn svg { width: 18px; height: 18px; }
.add-cart-btn:disabled { opacity: .5; cursor: not-allowed; }
</style>
