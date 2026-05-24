<template>
  <div class="page login-page">
    <div class="login-logo">⚡</div>
    <h2 class="login-title">ElectrizShop</h2>
    <div class="tabs">
      <button :class="{ active: tab === 'login' }"    @click="tab = 'login'">Вход</button>
      <button :class="{ active: tab === 'register' }" @click="tab = 'register'">Регистрация</button>
    </div>
    <div v-if="tab === 'login'" class="form-card card">
      <div class="form-group"><label>Email</label><input v-model="lForm.email" type="email" class="form-input" placeholder="ivan@example.com" /></div>
      <div class="form-group"><label>Пароль</label><input v-model="lForm.password" type="password" class="form-input" placeholder="••••••" /></div>
      <p v-if="error" class="error-msg">{{ error }}</p>
      <button class="btn btn-primary btn-full" :disabled="loading" @click="login">{{ loading ? 'Входим...' : 'Войти' }}</button>
    </div>
    <div v-else class="form-card card">
      <div class="form-group"><label>Имя</label><input v-model="rForm.name" type="text" class="form-input" placeholder="Иван Иванов" /></div>
      <div class="form-group"><label>Email</label><input v-model="rForm.email" type="email" class="form-input" placeholder="ivan@example.com" /></div>
      <div class="form-group"><label>Пароль</label><input v-model="rForm.password" type="password" class="form-input" placeholder="••••••" /></div>
      <p v-if="error" class="error-msg">{{ error }}</p>
      <button class="btn btn-primary btn-full" :disabled="loading" @click="register">{{ loading ? 'Создаём...' : 'Зарегистрироваться' }}</button>
    </div>
    <div class="oauth-section">
      <p class="oauth-label">или войти через</p>
      <div class="oauth-btns">
        <button @click="oauthLogin('google')"    class="btn btn-oauth">Google</button>
        <button @click="oauthLogin('github')"    class="btn btn-oauth">GitHub</button>
        <button @click="oauthLogin('vkontakte')" class="btn btn-oauth">VK</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
const store   = useStore()
const router  = useRouter()
const route   = useRoute()
const tab     = ref('login')
const loading = ref(false)
const error   = ref('')
const lForm   = ref({ email: '', password: '' })
const rForm   = ref({ name: '', email: '', password: '' })

function oauthLogin(provider) { window.top.location.href = `http://localhost:8000/api/auth/${provider}` }

async function login() {
  loading.value = true; error.value = ''
  try { await store.dispatch('auth/login', lForm.value); redirect() }
  catch (e) { error.value = e.response?.data?.message || 'Ошибка входа' }
  finally { loading.value = false }
}
async function register() {
  loading.value = true; error.value = ''
  try { await store.dispatch('auth/register', rForm.value); redirect() }
  catch (e) { const errs = e.response?.data?.errors; error.value = errs ? Object.values(errs).flat().join('. ') : 'Ошибка регистрации' }
  finally { loading.value = false }
}
function redirect() { router.push(route.query.redirect || '/') }
</script>

<style scoped>
.login-page  { display: flex; flex-direction: column; align-items: center; padding-top: 32px; }
.login-logo  { font-size: 4rem; margin-bottom: 8px; }
.login-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 24px; font-family: var(--font-display); color: var(--clr-primary); }
.tabs { display: flex; background: var(--clr-bg-card); border-radius: var(--radius); padding: 4px; margin-bottom: 20px; width: 100%; }
.tabs button { flex: 1; padding: 10px; border: none; border-radius: calc(var(--radius) - 4px); background: transparent; color: var(--clr-text-muted); font-size: .9rem; cursor: pointer; transition: all .2s; font-family: var(--font-main); }
.tabs button.active { background: var(--clr-primary); color: #fff; font-weight: 700; }
.form-card  { width: 100%; margin-bottom: 20px; }
.error-msg  { color: #dc2626; font-size: .85rem; margin-bottom: 10px; }
.oauth-section { width: 100%; text-align: center; }
.oauth-label { color: var(--clr-text-muted); font-size: .85rem; margin-bottom: 12px; }
.oauth-btns  { display: flex; gap: 10px; }
.btn-oauth   { flex: 1; background: var(--clr-bg-card); border: 1px solid var(--clr-border); color: var(--clr-text); padding: 12px 8px; font-size: .85rem; border-radius: var(--radius); font-family: var(--font-main); cursor: pointer; transition: border-color .2s; }
.btn-oauth:hover { border-color: var(--clr-primary); color: var(--clr-primary); }
</style>
