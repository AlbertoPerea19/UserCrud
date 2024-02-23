import { createRouter, createWebHistory } from 'vue-router'
import SignUpComponent from '@/components/SignUpComponent.vue'
import LoginComponent from "@/components/LoginComponent.vue";
import HomeComponent from "@/components/HomeComponent.vue";

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes: [
    {
      path: '/signup',
      component: SignUpComponent
    }
    ,
    {
      path: '/login',
      component: LoginComponent
    }
    ,
    {
      path: '/',
      component: HomeComponent
    }

  ]
})

export default router
