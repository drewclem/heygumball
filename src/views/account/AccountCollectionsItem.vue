<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
      </div>

      <button
        class="rounded-full border border-gray-500 p-2 opacity-50 hover:opacity-100"
      >
        <IconSearch class="w-4 h-4 text-gray-500" />
      </button>
    </div>

    <div>
      <div class="grid grid-cols-3 px-8 py-4 opacity-40 mb-4">
        <p>Name</p>
        <p>Email</p>
        <p class="ml-auto">Phone</p>
      </div>

      <div class="flex flex-col space-y-6">
        <p class="opacity-50" v-if="loading">Loading...</p>

        <template v-else-if="submissions.length">
          <CollectionSubmissionCard
            v-for="submission in submissions"
            :key="submission.id"
            :submission="submission"
          />
        </template>

        <div v-else>
          <p class="mb-5">No submissions yet! Share that link!</p>
          <p class="font-bold text-green-500">
            https://heygumball.com/{{ user.user_metadata.username }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import useAuthUser from "@/utils/useAuth";

import { supabase } from "@/supabase";

// components
import CollectionSubmissionCard from "@/components/collections/CollectionSubmissionCard.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import IconSearch from "@/components/svg/IconSearch.vue";

const route = useRoute();
let submissions = ref([]);
let loading = ref(true);
const { user } = useAuthUser();

async function setSubmissions() {
  const { data } = await supabase
    .from("submissions")
    .select()
    .eq("collection_id", route.params.collection_id);

  submissions.value = data;
  loading.value = false;
}

onMounted(() => {
  setSubmissions();
});
</script>
