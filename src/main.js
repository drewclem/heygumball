import { createApp } from "vue";
import router from "./router";
import { createPinia } from "pinia";
import "./assets/css/main.css";
import App from "./App.vue";

// supabase auth
import { supabase } from "./supabase";
import useAuthUser from "@/vuetils/useAuth";

createApp(App).use(router).use(createPinia()).mount("#app");

supabase.auth.onAuthStateChange((event, session) => {
  const { user } = useAuthUser();
  user.value = session?.user || null;
});
