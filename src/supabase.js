import { createClient } from "@supabase/supabase-js";
import useAuthUser from "./vuetils/useAuth";

const supabaseUrl = process.env.VUE_APP_SUPABASE_URL;
const supbaseAnonKey = process.env.VUE_APP_SUPABASE_ANON_KEY;

export const supabase = createClient(supabaseUrl, supbaseAnonKey);

supabase.auth.onAuthStateChange((event, session) => {
  const { user } = useAuthUser();

  user.value = session?.user || null;
});
