<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
      </div>
    </div>

    <div>
      <div
        class="grid grid-cols-5 card-padding text-sm lg:text-base opacity-40 mb-4"
      >
        <p class="col-span-2">Open Dates</p>
        <p>Year</p>
        <p>Submissions</p>
        <p class="ml-auto">Booked</p>
      </div>

      <div class="flex flex-col space-y-6">
        <AccountCollectionCard
          v-for="collection in collections"
          :key="collection.id"
          :collection="collection"
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
import AccountCollectionCard from "@/components/dashboard/CollectionCard.vue";

const global = useUserStore();
const { collections } = storeToRefs(global);
</script>
