<template>
  <div class="page">
    <button class="back-btn" @click="$router.back()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>Назад</button>
    <h2 class="page-title">Оформление</h2>
    <div class="form-card card">
      <div class="form-group">
        <label>Адрес доставки</label>
        <input v-model="form.address" type="text" class="form-input" placeholder="г. Москва, ул. Ленина, д. 1, кв. 5" />
      </div>
      <div class="form-group">
        <label>Способ оплаты</label>
        <select v-model="form.payment_method" class="form-input">
          <option value="card">Картой онлайн</option>
          <option value="cash">Наличными курьеру</option>
        </select>
      </div>
      <div class="form-group">
        <label>Комментарий</label>
        <textarea v-model="form.comment" class="form-input" rows="3" placeholder="Оставить у двери, позвонить заранее..."></textarea>
      </div>
    </div>
    <div class="summary card">
      <div class="summary-row"><span>Товаров:</span><strong>{{ cartCount }}</strong></div>
      <div class="summary-row total"><span>К оплате:</span><strong>{{ cartTotal?.toLocaleString('ru-RU') }} ₽</strong></div>
    </div>
    <p v-if="error" class="error-msg">{{ error }}</p>
    <button class="btn btn-primary btn-full" :disabled="loading || !form.address" @click="placeOrder">
      {{ loading ? 'Оформляем...' : 'Подтвердить заказ' }}
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
const store   = useStore()
const router  = useRouter()
const loading = ref(false)
const error   = ref('')
const form    = ref({ address: '', payment_method: 'card', comment: '' })
const cartCount = computed(() => store.getters['cart/count'])
const cartTotal = computed(() => store.getters['cart/total'])
onMounted(() => { store.dispatch('cart/fetchCart'); const user = store.getters['auth/user']; if (user?.address) form.value.address = user.address })
async function placeOrder() {
  if (!form.value.address) { error.value = 'Укажите адрес доставки'; return }
  loading.value = true; error.value = ''
  try { await store.dispatch('orders/createOrder', form.value); router.push('/orders') }
  catch (e) { error.value = e.response?.data?.message || 'Ошибка при оформлении заказа' }
  finally { loading.value = false }
}
</script>

<style scoped>
.back-btn { display: flex; align-items: center; gap: 4px; background: none; border: none; color: var(--clr-text-muted); font-size: .9rem; font-family: var(--font-main); cursor: pointer; margin-bottom: 16px; padding: 0; }
.back-btn svg { width: 18px; height: 18px; }
.form-card { margin-bottom: 16px; }
.form-input[rows] { resize: vertical; }
.summary { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.summary-row { display: flex; justify-content: space-between; }
.summary-row.total strong { color: var(--clr-primary); font-size: 1.2rem; }
.error-msg { color: #dc2626; font-size: .85rem; margin-bottom: 12px; }
</style>
