import { defineStore } from "pinia";
import { supabase } from "../supabase";

export const useUserStore = defineStore("currentUser", {
  persist: {
    enabled: true,
  },
  state: () => {
    return {
      userID: undefined,
      currentUser: undefined,
      collections: [],
      currentCollection: null,
      currentSubmissions: [],
      savedSubmissions: [],
      allSubmissions: [],
      disabledDates: {
        dates: [],
      },
    };
  },

  getters: {
    disabledDates: (state) => {
      const datesArray = [];

      for (const collection of state.collections) {
        const startDate = new Date(collection.start_date);
        const endDate = new Date(collection.end_date);

        let currentDate = startDate;

        while (currentDate <= endDate) {
          datesArray.push(new Date(currentDate));
          currentDate.setDate(currentDate.getDate() + 1);
        }
      }

      return { dates: datesArray };
    },
    hasActiveCollection: (state) => {
      const currentDate = new Date();

      if (state.collections.length > 0)
        for (const collection of state.collections) {
          if (collection.end_date === null) return true;

          const startDate = new Date(collection.start_date);
          const endDate = new Date(collection.end_date);

          const currentDateFormatted = currentDate.getTime();
          const startDateFormatted =
            startDate.getTime() - startDate.getTimezoneOffset() * -60000;
          const endDateFormatted =
            endDate.getTime() - endDate.getTimezoneOffset() * -60000;

          if (
            (startDateFormatted < currentDateFormatted &&
              endDateFormatted > currentDateFormatted) ||
            (startDateFormatted < currentDateFormatted &&
              collection.endDate === null)
          )
            return true;
        }
    },
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

      const tags = await supabase.from("tags").select().eq("id", id);

      if (data.user_avatar !== null) {
        const avatarData = await supabase.storage
          .from("avatars")
          .createSignedUrl(data.user_avatar, 6000);

        this.currentUser = { ...data, avatar_url: avatarData.signedURL };
      } else {
        this.currentUser = data;
      }

      this.setTags(id);
    },
    async setCollections() {
      const { data } = await supabase
        .from("collections")
        .select()
        .eq("user_id", this.userID)
        .eq("archived", false)
        .order("created_at", { ascending: false });

      data.forEach(async (item, index) => {
        const requestsReceived = await supabase
          .from("submissions")
          .select("id")
          .match({ collection_id: item.id });

        const bookedRequests = await supabase
          .from("submissions")
          .select("id")
          .eq("booked", true)
          .match({ collection_id: item.id });

        this.collections[index] = {
          ...item,
          requests_received: requestsReceived.data.length,
          booked_requests: bookedRequests.data.length,
        };
      });
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

      data.forEach(async (sub, index) => {
        const tags = [];

        const tagRelations = await supabase
          .from("tag_relations")
          .select()
          .eq("submission_id", sub.id)
          .order("created_at", { ascending: true });

        const rawData = await tagRelations.data;

        rawData.forEach(async (tag) => {
          const tagInfo = await supabase
            .from("tags")
            .select()
            .eq("id", tag.tag_id);

          const tagObj = {
            relation_id: tag.id,
            ...tagInfo.data[0],
          };

          tags.push(tagObj);
        });

        this.savedSubmissions[index] = {
          ...sub,
          tags: tags,
        };
      });
    },
    async setAllSubmissions() {
      const { data } = await supabase
        .from("submissions")
        .select()
        .eq("user_id", this.userID);

      data.forEach(async (sub, index) => {
        const tags = [];

        const tagRelations = await supabase
          .from("tag_relations")
          .select()
          .eq("submission_id", sub.id)
          .order("created_at", { ascending: true });

        const rawData = await tagRelations.data;

        rawData.forEach(async (tag) => {
          const tagInfo = await supabase
            .from("tags")
            .select()
            .eq("id", tag.tag_id);

          const tagObj = {
            relation_id: tag.id,
            ...tagInfo.data[0],
          };

          tags.push(tagObj);
        });

        this.allSubmissions[index] = {
          ...sub,
          tags: tags,
        };
      });
    },
    async setTags(id) {
      const { data } = await supabase.from("tags").select().eq("user_id", id);

      let tagArray = [];

      data.forEach(async (tag, index) => {
        const timesUsed = await supabase
          .from("tag_relations")
          .select("id")
          .match({ tag_id: tag.id });

        tagArray[index] = {
          ...tag,
          number_of_references: timesUsed.data.length,
        };
      });

      this.currentUser = {
        ...this.currentUser,
        tags: tagArray,
      };
    },
    formatDate(date) {
      const dateObj = new Date(date);

      const year = dateObj.getFullYear();
      const month = ("0" + (dateObj.getMonth() + 1)).slice(-2);
      const day = dateObj.getDate();

      return year + "-" + month + "-" + day;
    },
  },
});
