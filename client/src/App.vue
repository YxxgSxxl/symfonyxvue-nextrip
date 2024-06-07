<script setup lang="ts">
import HeaderComp from './components/HeaderComp.vue'
</script>

<template>
  <div class="app-bg">
  <HeaderComp />

    <RouterView />
  </div>
</template>

<style>
*, *:before, *:after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

::selection {
  background: rgba(130, 192, 220, 0.3);
  color: rgb(0, 170, 249) 
}

body {
  font-family: Roboto, Arial, Helvetica, sans-serif;
  
  background: #ffffff;
  background: -webkit-linear-gradient(42deg, #f7f7f7 0%, #ffffff 100%);
  background: linear-gradient(42deg, #f9f9ff 0%, #ffffff 100%);
  min-height: 100vh;
  z-index: -2;
  padding-bottom: 4rem;
}

.black-text {
  color: #000000;
  font-weight: 500;
}

.blue-text {
  color: #3e96bf;
  font-weight: 600;
}
</style>
