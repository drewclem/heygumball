import { createClient } from "@supabase/supabase-js";

const supabaseUrl = process.env.VUE_APP_SUPABASE_URL;
const supbaseAnonKey = process.env.VUE_APP_SUPABASE_ANON_KEY;

export const supabase = createClient(supabaseUrl, supbaseAnonKey);
