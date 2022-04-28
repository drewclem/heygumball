import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    name: "Home",
    component: () => import("@/views/HomeView.vue"),
    meta: {
      layout: "AppLayoutDefault",
    },
  },
  {
    path: "/create-an-account",
    name: "Create an Account",
    component: () => import("@/views/CreateAnAccount.vue"),
    meta: {
      layout: "AppLayoutDefault",
    },
  },
  {
    path: "/sign-in",
    name: "Sign In",
    component: () => import("@/views/SignIn.vue"),
    meta: {
      layout: "AppLayoutDefault",
    },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
