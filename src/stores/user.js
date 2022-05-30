import { defineStore } from "pinia";
import { supabase } from "../supabase";

export const useUserStore = defineStore("currentUser", {
  state: () => {
    return {
      userID: undefined,
      user: undefined,
      collections: [],
      currentCollection: null,
      currentSubmissions: [],
      savedSubmissions: [],
    };
  },

  actions: {
    setCurrentUserId(id) {
      this.userID = id;

      this.setUser(id);
    },
    async setUser(id) {
      const { data } = await supabase
        .from("profiles")
        .select()
        .eq("id", id)
        .single();

      this.user = data;
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

          const savedRequests = await supabase
            .from("submissions")
            .select("id")
            .eq("saved", true)
            .match({ collection_id: item.id });

          this.collections.push({
            ...item,
            requests_received: requestsReceived.data.length,
            saved_requests: savedRequests.data.length,
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
    async setSavedSubmissions() {
      const { data } = await supabase
        .from("submissions")
        .select()
        .eq("saved", true)
        .eq("user_id", this.userID);

      this.savedSubmissions = data;
    },
  },
});
