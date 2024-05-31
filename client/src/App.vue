<script setup lang="ts">
import HeaderComp from './components/HeaderComp.vue'
</script>

<template>
  <HeaderComp />

  <RouterView />
</template>

<style>
*, *:before, *:after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Roboto, Arial, Helvetica, sans-serif;
}

.blue-text {
  color: #3e96bf;
  font-weight: 600;
}
</style>
