import { defineStore } from "pinia";
import { supabase } from "../supabase";

export const useUserStore = defineStore("currentUser", {
  state: () => {
    return {
      userID: undefined,
      collections: [],
      activeCollection: {},
      submissions: [],
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
      }
    },
    async setActiveCollection(id) {
      const filteredCollections = this.collections.filter((collection) => {
        return collection.id === id;
      });

      this.activeCollection = filteredCollections[0];

      await this.setSubmissions();
    },
    async setSubmissions() {
      const { data } = await supabase
        .from("submissions")
        .select()
        .eq("collection_id", this.activeCollection.id);

      this.submissions = data;
    },
  },
});
