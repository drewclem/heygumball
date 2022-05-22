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

      data.forEach(async (item) => {
        const requestsReceived = await supabase
          .from("submissions")
          .select("id")
          .match({ collection_id: item.id });

        const approvedRequests = await supabase
          .from("submissions")
          .select("id")
          .eq("approved", true);

        this.collections.push({
          ...item,
          requests_received: requestsReceived.data.length,
          approved_requests: approvedRequests.data.length,
        });
      });
    },
  },
});
