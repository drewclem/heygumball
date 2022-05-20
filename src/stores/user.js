import { defineStore } from "pinia";
import { supabase } from "../supabase";

export const useUserStore = defineStore("currentUser", {
  state: () => {
    return {
      userID: undefined,
      collections: [],
      activeCollection: null,
      submissions: [],
    };
  },

  actions: {
    setCurrentUserId(id) {
      this.userID = id;
    },
    async setCollections() {
      const { data } = await supabase
        .from("collections")
        .select()
        .eq("user_id", this.userID);

      console.log(data);

      this.collections = data;
    },
  },
});
