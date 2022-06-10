<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
      </div>
    </div>

    <div>
      <div
        class="grid grid-cols-5 px-5 py-3 lg:px-8 lg:py-4 text-sm lg:text-base opacity-40 mb-4"
      >
        <p class="col-span-2">Open Dates</p>
        <p>Year</p>
        <p>Submissions</p>
        <p class="ml-auto">Booked</p>
      </div>

      <div
        v-if="collections && collections.length > 0"
        class="flex flex-col space-y-6"
      >
        <AccountCollectionCard
          v-for="collection in collections"
          :key="collection.id"
          :collection="collection"
        />
      </div>

      <div v-else>
        <p class="mb-5">Loading...</p>
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
import AccountCollectionCard from "@/components/dashboard/CollectionCard.vue";
import { computed } from "vue-demi";

const global = useUserStore();
const { collections } = storeToRefs(global);
</script>
