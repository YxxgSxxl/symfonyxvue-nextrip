import { createApp } from 'vue' // CreateApp init
import App from './App.vue' // App init
import './normalize.css' // Normalize.css

import { createMemoryHistory, createRouter } from 'vue-router' // Vue Router
import HomeView from './view/HomeView.vue' // HomePage View
import TripView from './view/TripView.vue' // chooseNextTrip View

const routes = [
    { path: '/', component: HomeView },
    { path: '/chooseNextTrip', component: TripView }
]

const router = createRouter({
    history: createMemoryHistory(),
    routes,
})

createApp(App)
  .use(router)
  .mount('#app')