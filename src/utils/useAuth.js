import router from "@/router";
import { ref } from "vue";
import { supabase } from "../supabase";

const user = ref();

export default function useAuthUser() {
  const handleLogin = async (credentials) => {
    try {
      const { error, user } = await supabase.auth.signIn({
        email: credentials.email,
        password: credentials.password,
      });

      if (error) {
        alert("Error logging in: " + error.message);
      }

      if (user) {
        router.push({ path: `/${user.user_metadata.username}/collections` });
      }

      return { user };
    } catch (error) {
      alert(error.error_description || error);
    }
  };

  const handleSignup = async (credentials) => {
    try {
      const { email, username, password } = credentials;

      const { error } = await supabase.auth.signUp(
        {
          email,
          password,
        },
        {
          data: {
            username: username,
          },
        }
      );

      if (error) {
        alert(error.message);
        return;
      }
    } catch (err) {
      alert("Fatal error signing up");
    }
  };

  /**
   * Handles logging a user out of a supabase session
   */
  const handleLogout = async () => {
    const { error } = await supabase.auth.signOut();

    router.push({ path: "/sign-in" });

    if (error) throw error;
  };

  const isLoggedIn = () => {
    return !!user.value;
  };

  return {
    user,
    handleLogin,
    handleLogout,
    handleSignup,
    isLoggedIn,
  };
}
