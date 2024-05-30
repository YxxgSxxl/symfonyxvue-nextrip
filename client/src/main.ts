import { createApp } from 'vue'
import App from './App.vue'
import './normalize.css' // Normalize.css

import { createMemoryHistory, createRouter } from 'vue-router' // Vue Router
import HomeView from './view/HomeView.vue' //

const routes = [
    { path: '/', component: HomeView },
]

const router = createRouter({
    history: createMemoryHistory(),
    routes,
})

createApp(App)
  .use(router)
  .mount('#app')