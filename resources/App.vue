<script setup>
import { RouterView } from 'vue-router'
import Sidebar from './js/components/Sidebar.vue';
import Header from './js/components/Header.vue';
</script>

<template>
  <div class="flex">
    <Sidebar />
    <div class="flex-1">
      <Header />
      <RouterView />
    </div>
  </div>
</template>

<style scoped></style>
