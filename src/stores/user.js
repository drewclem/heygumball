import { defineStore } from "pinia";
import { supabase } from "../supabase";

export const useUserStore = defineStore("currentUser", {
  state: () => {
    return {
      userID: undefined,
      collections: [],
      currentCollection: null,
      currentSubmissions: [],
    };
  },

  actions: {
    setCurrentUserId(id) {
      this.userID = id;
    },
    async setCollections(refetch) {
      if (!this.collections.length || refetch) {
        const { data } = await supabase
          .from("collections")
          .select()
          .eq("user_id", this.userID)
          .order("created_at", { ascending: false });

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
      }
    },
    setCurrentCollection(id) {
      const filteredCollections = this.collections.filter((collection) => {
        return collection.id === id;
      });

      this.currentCollection = filteredCollections[0];
    },
    setCurrentSubmissions(data) {
      this.currentSubmissions = data;
    },
  },
});
