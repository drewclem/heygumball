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
        <CollectionSubmissionCard
          v-for="submission in submissions"
          :key="submission.id"
          :submission="submission"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from "vue-router";
import { useUserStore } from "@/stores/user";

import CollectionSubmissionCard from "@/components/collections/CollectionSubmissionCard.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import IconSearch from "@/components/svg/IconSearch.vue";

const route = useRoute();
const { setActiveCollection } = useUserStore();
const { submissions } = useUserStore();

setActiveCollection(route.params.collection_id);
</script>
