<template>
  <div class="page">
    <h2 class="page-title">Мои заказы</h2>
    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
    <div v-else-if="orders.length === 0" class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
      <p>У вас ещё нет заказов</p>
      <router-link to="/catalog" class="btn btn-primary">Перейти в каталог</router-link>
    </div>
    <div v-else class="orders-list">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <div class="order-header">
          <div><span class="order-id">Заказ #{{ order.id }}</span><span class="order-date">{{ formatDate(order.created_at) }}</span></div>
          <span class="order-status" :class="order.status">{{ statusLabel(order.status) }}</span>
        </div>
        <div class="order-items">
          <span v-for="item in order.items" :key="item.id" class="order-product">{{ item.product?.name }} × {{ item.quantity }}</span>
        </div>
        <div class="order-footer">
          <span class="order-addr">{{ order.address }}</span>
          <strong class="order-total">{{ order.total?.toLocaleString('ru-RU') }} ₽</strong>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
const store   = useStore()
const orders  = computed(() => store.getters['orders/items'])
const loading = computed(() => store.getters['orders/loading'])
onMounted(() => store.dispatch('orders/fetchOrders'))
const statusMap = { pending:'Принят', confirmed:'Подтверждён', processing:'Обрабатывается', shipped:'Отправлен', delivered:'Доставлен', cancelled:'Отменён' }
function statusLabel(s) { return statusMap[s] || s }
function formatDate(d) { return new Date(d).toLocaleString('ru-RU', { day:'2-digit', month:'2-digit', hour:'2-digit', minute:'2-digit' }) }
</script>

<style scoped>
.empty { text-align: center; padding: 60px 0; display: flex; flex-direction: column; align-items: center; gap: 16px; }
.empty svg { width: 56px; height: 56px; color: var(--clr-text-muted); }
.empty p { color: var(--clr-text-muted); }
.orders-list { display: flex; flex-direction: column; gap: 12px; }
.order-card  { background: var(--clr-bg-card); border: 1px solid var(--clr-border); border-radius: var(--radius); padding: 16px; display: flex; flex-direction: column; gap: 12px; }
.order-header { display: flex; justify-content: space-between; align-items: flex-start; }
.order-id   { display: block; font-weight: 800; font-size: .95rem; font-family: var(--font-display); }
.order-date { font-size: .75rem; color: var(--clr-text-muted); margin-top: 2px; display: block; }
.order-status { font-size: .7rem; font-weight: 700; padding: 4px 10px; border-radius: 100px; letter-spacing: .04em; text-transform: uppercase; background: var(--clr-bg-elevated); color: var(--clr-text-muted); }
.order-status.delivered { background: rgba(5,150,105,.12); color: #059669; }
.order-status.cancelled { background: rgba(220,38,38,.12); color: #dc2626; }
.order-status.shipped   { background: rgba(37,99,235,.12);  color: var(--clr-primary); }
.order-status.processing{ background: rgba(245,158,11,.12); color: #f59e0b; }
.order-items { display: flex; flex-wrap: wrap; gap: 6px; }
.order-product { font-size: .78rem; color: var(--clr-text-soft); background: var(--clr-bg-elevated); padding: 4px 10px; border-radius: var(--radius-xs); border: 1px solid var(--clr-border); }
.order-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 1px solid var(--clr-border); }
.order-addr  { font-size: .78rem; color: var(--clr-text-muted); }
.order-total { color: var(--clr-primary); font-size: 1rem; font-weight: 800; }
</style>
