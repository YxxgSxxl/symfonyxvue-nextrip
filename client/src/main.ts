import { createApp } from 'vue' // CreateApp init
import App from './App.vue' // App init
import './normalize.css' // Normalize.css

import { createWebHashHistory, createRouter } from 'vue-router' // Vue Router
import HomeView from './view/HomeView.vue' // Home page View
import AboutView from './view/AboutView.vue' // About page view
import TripView from './view/TripView.vue' // chooseNextTrip page view

// Routes
const routes = [
    { path: '/', component: HomeView },
    { path: '/about', component: AboutView },
    { path: '/chooseNextTrip', component: TripView },
]

// Router
const router = createRouter({
    history: createWebHashHistory(),
    routes,
    
    linkActiveClass: "active",
    linkExactActiveClass: "exact-active",
})

export default router // Export router to use it in external cases if needed (outsite of this file)

createApp(App)
  .use(router)
  .mount('#app')