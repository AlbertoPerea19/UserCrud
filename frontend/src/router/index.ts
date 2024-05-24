import { createRouter, createWebHistory } from 'vue-router'
import SignUpComponent from '@/components/SignUpComponent.vue'
import LoginComponent from "@/components/LoginComponent.vue";
import HomeComponent from "@/components/HomeComponent.vue";
import UserDetails from "@/components/UserDetails.vue";

const router = createRouter({
  history: createWebHistory(process.env.VUE_APP_BACKEND),
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
    },
    {
      path: '/user/:id',
      component: UserDetails
    },

  ]
})

export default router
