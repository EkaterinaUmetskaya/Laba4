<template>
  <div class="page" style="text-align:center;padding-top:80px">
    <div class="loader-wrap"><div class="loader"></div></div>
    <p style="color:var(--clr-text-muted);margin-top:16px">Авторизация...</p>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'
const store  = useStore()
const router = useRouter()
const route  = useRoute()
onMounted(async () => {
  const token = route.query.token
  if (token) { await store.dispatch('auth/setTokenFromCallback', token); router.push('/') }
  else { router.push('/login') }
})
</script>
