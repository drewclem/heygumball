import { createRouter, createWebHistory } from "vue-router";
import useAuthUser from "@/utils/useAuth";

const routes = [
  {
    path: "/*",
    component: () => import("@/views/NotFound.vue"),
  },
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
    path: "/:id",
    name: "User Request Form",
    component: () => import("@/views/account/AccountForm.vue"),
    meta: {
      layout: "AppLayoutDefault",
    },
  },
  {
    path: "/:id/account",
    name: "User Account",
    component: () => import("@/views/account/AccountHome.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/collections",
    name: "User Collections",
    component: () => import("@/views/account/AccountCollections.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/inbox",
    name: "User inbox",
    component: () => import("@/views/account/AccountInbox.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/account/edit",
    name: "Edit user account",
    component: () => import("@/views/account/AccountSettingsEdit.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/collections/:collection_id",
    name: "Collection",
    component: () => import("@/views/account/AccountSubmissions.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/collections/:collection_id/:submission_id",
    name: "Submission",
    component: () => import("@/views/account/AccountSubmission.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/saved",
    name: "Saved submissions",
    component: () => import("@/views/account/AccountSaved.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/:id/tags",
    name: "User Tags",
    component: () => import("@/views/account/AccountTags.vue"),
    meta: {
      layout: "AppLayoutAccountDashboard",
      requiresAuth: true,
    },
  },
  {
    path: "/terms-and-conditions",
    name: "Terms and Conditions",
    component: () => import("@/views/TermsAndConditions.vue"),
    meta: {
      layout: "AppLayoutDefault",
    },
  },
  {
    path: "/privacy-policy",
    name: "Privacy Policy",
    component: () => import("@/views/PrivacyPolicy.vue"),
    meta: {
      layout: "AppLayoutDefault",
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
