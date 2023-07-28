import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: () => import('../components/Login.vue')
    },
    {
      path: '/login',
      component: () => import('../components/Login.vue')
    },
    {
      path: '/register',
      component: () => import('../components/Register.vue')
    },
    {
      path: '/',
      children: [
        {
          path: '/dashboard',
          component: () => import('../components/Dashboard.vue')
        },
        {
          path: '/roles',
          component: () => import('../components/Roles.vue')
        },
        {
          path: '/users',
          component: () => import('../components/Users.vue')
        },
        {
          path: '/expense-categories',
          component: () => import('../components/ExpenseCategories.vue')
        },
        {
          path: '/expenses',
          component: () => import('../components/Expenses.vue')
        },
        {
          path: '/change-password',
          component: () => import('../components/ChangePassword.vue')
        },
      ],
    },
  ]
})

export default router
