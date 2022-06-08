<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Inbox</BaseHeading>
      </div>

      <button
        class="rounded-full border border-gray-500 p-2 opacity-50 hover:opacity-100"
      >
        <IconSearch class="w-4 h-4 text-gray-500" />
      </button>
    </div>

    <div>
      <div class="grid grid-cols-6 px-8 py-4 opacity-40 mb-4">
        <p class="col-span-2">Name</p>
        <p class="col-span-2">Email</p>
        <p>Phone</p>
        <p class="ml-auto">Submitted On</p>
      </div>

      <div class="flex flex-col space-y-6">
        <CollectionSubmissionCard
          v-for="submission in allSubmissions"
          :key="submission.id"
          :submission="submission"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import CollectionSubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import IconSearch from "@/components/svg/IconSearch";

const { setAllSubmissions } = useUserStore();
const global = useUserStore();

const { allSubmissions } = storeToRefs(global);

setAllSubmissions();
</script>
