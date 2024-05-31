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
    // { path: '/chooseNextTrip/:city1/:city2', component: TripResView }
]

// Router
const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

createApp(App)
  .use(router)
  .mount('#app')