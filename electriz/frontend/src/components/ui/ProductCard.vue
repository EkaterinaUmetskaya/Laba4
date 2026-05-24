<template>
  <div class="product-card" @click="$router.push(`/product/${product.id}`)">
    <div class="product-img">
      <img v-if="product.image" :src="product.image" :alt="product.name" />
      <div v-else class="product-img-placeholder">
        <div class="device-art">
          <div class="device-screen"></div>
          <div class="device-btn"></div>
        </div>
      </div>
      <div class="product-cat-badge" v-if="product.category">{{ product.category.name }}</div>
      <div class="product-brand-badge" v-if="product.brand">{{ product.brand }}</div>
    </div>
    <div class="product-info">
      <h4 class="product-name">{{ product.name }}</h4>
      <p class="product-sku" v-if="product.sku">Арт. {{ product.sku }}</p>
      <div class="product-footer">
        <span class="product-price">{{ product.price.toLocaleString('ru-RU') }} ₽</span>
        <button class="add-btn" :class="{ added: justAdded }" @click.stop="addToCart">
          <span class="add-btn-icon">
            <svg v-if="!justAdded" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
const props     = defineProps({ product: Object })
const store     = useStore()
const router    = useRouter()
const justAdded = ref(false)
let adding      = false
async function addToCart() {
  if (adding) return
  if (!store.getters['auth/isAuthenticated']) { router.push('/login'); return }
  adding = true
  await store.dispatch('cart/addItem', { product_id: props.product.id, quantity: 1 })
  justAdded.value = true
  setTimeout(() => { justAdded.value = false; adding = false }, 1500)
}
</script>

<style scoped>
.product-card { background: var(--clr-bg-card); border-radius: var(--radius); overflow: hidden; cursor: pointer; border: 1px solid var(--clr-border); transition: box-shadow .25s, border-color .25s; }
.product-card:hover { box-shadow: 0 8px 24px rgba(37,99,235,.12); border-color: var(--clr-border-soft); }
.product-card:active { opacity: .92; }

.product-img { width: 100%; aspect-ratio: 1/1; background: var(--clr-bg-elevated); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
.product-img img { width: 100%; height: 100%; object-fit: cover; }

.product-img-placeholder { width: 100%; height: 100%; background: linear-gradient(145deg, #12121e, #1a1a2a); display: flex; align-items: center; justify-content: center; }
.device-art { position: relative; width: 52px; height: 52px; }
.device-screen { position: absolute; inset: 0; background: #1e1e35; border-radius: 10px; border: 2px solid #2a2a45; }
.device-btn { position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 14px; height: 14px; background: #1e1e35; border: 2px solid #2a2a45; border-radius: 50%; }

.product-cat-badge { position: absolute; top: 8px; left: 8px; background: rgba(10,10,20,.8); backdrop-filter: blur(6px); color: var(--clr-text-soft); font-size: .58rem; font-weight: 700; padding: 3px 8px; border-radius: 100px; letter-spacing: .05em; text-transform: uppercase; }
.product-brand-badge { position: absolute; top: 8px; right: 8px; background: rgba(37,99,235,.2); color: var(--clr-primary); font-size: .58rem; font-weight: 700; padding: 3px 8px; border-radius: 100px; border: 1px solid rgba(37,99,235,.3); }

.product-info  { padding: 10px 12px 12px; }
.product-name  { font-size: .88rem; font-weight: 700; margin-bottom: 3px; line-height: 1.3; }
.product-sku   { font-size: .7rem; color: var(--clr-text-muted); margin-bottom: 10px; }
.product-footer{ display: flex; align-items: center; justify-content: space-between; }
.product-price { font-weight: 800; font-size: .95rem; }

.add-btn { width: 32px; height: 32px; flex-shrink: 0; background: var(--clr-primary); border: none; border-radius: 9px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .2s; }
.add-btn.added { background: #059669; }
.add-btn-icon { display: flex; align-items: center; justify-content: center; width: 14px; height: 14px; color: #fff; pointer-events: none; }
.add-btn-icon svg { width: 14px; height: 14px; stroke: #fff; }
</style>
