import { createApp } from "vue";
import router from "./router";
import { createPinia } from "pinia";
import piniaPersist from "pinia-plugin-persist";
import "./assets/css/main.css";
import App from "./App.vue";

const app = createApp(App);

// supabase auth
import { supabase } from "./supabase";
import useAuthUser from "@/utils/useAuth";

const pinia = createPinia();
pinia.use(piniaPersist);

app.use(router);
app.use(pinia);
app.mount("#app");

supabase.auth.onAuthStateChange((event, session) => {
  const { user } = useAuthUser();
  user.value = session?.user || null;
});
