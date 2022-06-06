import { ref } from "vue";
import { supabase } from "@/supabase";

export function useValidate() {
  const usernames = ref([]);

  /**
   * Fetch usernames and compare requested name to ensure uniqueness
   */
  async function getAllUserNames() {
    const { data } = await supabase.from("profiles").select("username");
    usernames.value = data;
  }

  getAllUserNames();

  /**
   * Custom error messages
   */
  const notEmpty = "This field cannot be empty.";

  /**
   * Custom validation rules
   */
  const mustBeUniqueUsername = (value) => {
    const nameFilter = usernames.value.filter((name) => {
      return name.username === value;
    });

    const nameUnavailable = nameFilter.length > 0;

    return !nameUnavailable;
  };

  return { notEmpty, mustBeUniqueUsername };
}
