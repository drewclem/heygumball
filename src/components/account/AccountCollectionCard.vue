<template>
  <router-link :to="`collections/${collection.id}`">
    <AccountCardGrid class="card-shadow bg-white rounded-lg">
      <template #col-1>
        {{ formatOpenDates(collection.start_date) }} -
        {{ formatOpenDates(collection.end_date) }}
      </template>
      <template #col-2>{{ getYear(collection.end_date) }}</template>
      <template #col-3>{{ collection.requests_received }}</template>
      <template #col-4>{{ collection.approved_requests }}</template>
    </AccountCardGrid>
  </router-link>
</template>

<script setup>
import AccountCardGrid from "@/components/account/AccountCardGrid.vue";

defineProps({
  collection: {
    type: Object,
    required: true,
  },
});

function formatOpenDates(date) {
  const dateObj = new Date(date);

  return `${dateObj.toLocaleString("default", {
    month: "short",
  })} ${dateObj.toLocaleString("default", {
    day: "numeric",
  })}`;
}

function getYear(date) {
  const dateObj = new Date(date);

  return dateObj.getFullYear();
}
</script>
