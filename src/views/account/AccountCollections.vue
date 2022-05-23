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
      <div class="grid grid-cols-5 px-8 py-4 opacity-40 mb-4">
        <p class="col-span-2">Open Dates</p>
        <p>Year</p>
        <p>Requests Received</p>
        <p class="ml-auto">Approvals</p>
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
import { useUserStore } from "@/stores/user";
import BaseHeading from "@/components/base/BaseHeading.vue";
import AccountCollectionCard from "@/components/account/AccountCollectionCard.vue";
import IconSearch from "@/components/svg/IconSearch";

const { collections } = useUserStore();

function formatDate(date) {
  const dateObj = new Date(date);

  return `${dateObj.toLocaleString("default", {
    month: "short",
  })} ${dateObj.toLocaleString("default", {
    day: "numeric",
  })}`;
}
</script>
