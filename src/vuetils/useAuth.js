import { ref } from "vue";
import { supabase } from "../supabase";

const userSession = ref();

async function handleLogin(credentials) {
  try {
    const { error } = await supabase.auth.signIn({
      email: credentials.email,
      password: credentials.password,
    });

    if (error) {
      alert("Error logging in: " + error.message);
    }
  } catch (error) {
    alert(error.error_description || error);
  }
}

async function handleSignup(credentials) {
  try {
    const { fullName, email, password } = credentials;

    const { error } = await supabase.auth.signUp(
      {
        email,
        password,
      },
      {
        data: {
          full_name: fullName,
        },
      }
    );

    if (error) {
      alert(error.message);
      console.error(error, error.message);
      return;
    }
    alert("Signup successful, confirmation mail should be sent soon!");
  } catch (err) {
    alert("Fatal error signing up");
    console.error("signup error", err);
  }
}

/**
 * Handles logging a user out of a supabase session
 */
async function handleLogout() {
  console.log("logging out");
  try {
    const { error } = await supabase.auth.signOut();

    if (error) {
      alert("Error signing out");
      console.error("Error", error);
      return;
    }

    alert("You have signed out!");
  } catch (err) {
    alert("Unknown error signing out");
    console.error("Error", err);
  }
}

export { userSession, handleLogin, handleLogout, handleSignup };
