import { createRouter, createWebHistory } from "vue-router";
import useAuthUser from "@/vuetils/useAuth";

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
  {
    path: "/account/:id",
    name: "User Account",
    component: () => import("@/views/account/_id.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

router.beforeEach((to) => {
  const { isLoggedIn } = useAuthUser();

  if (
    !isLoggedIn() &&
    to.meta.requiresAuth &&
    !Object.keys(to.query).includes("fromEmail")
  ) {
    return { path: "/sign-in" };
  }
});

export default router;
