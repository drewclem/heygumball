import { createApp } from "vue";
import router from "./router";
import "./assets/css/main.css";
import App from "./App.vue";

// supabase auth
import { supabase } from "./supabase";
import { userSession } from "@/vuetils/useAuth";

createApp(App).use(router).mount("#app");

supabase.auth.onAuthStateChange((event, session) => {
  userSession.value = session;
});
