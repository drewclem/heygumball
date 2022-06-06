<template>
  <router-link :to="`collections/${collection.id}`">
    <AccountCardGrid
      class="card-shadow bg-white rounded-lg"
      :class="{ 'py-6 bg-blue-100': isActive }"
    >
      <template #col-1>
        {{ formatOpenDates(collection.start_date) }} -
        {{ formatOpenDates(collection.end_date) }}
      </template>
      <template #col-2>{{ getYear(collection.end_date) }}</template>
      <template #col-3>{{ collection.requests_received }}</template>
      <template #col-4>{{ collection.booked_requests }}</template>
    </AccountCardGrid>
  </router-link>
</template>

<script setup>
// utility
import { ref, toRefs, onMounted } from "vue";

// components
import AccountCardGrid from "@/components/account/AccountCardGrid.vue";

const props = defineProps({
  collection: {
    type: Object,
    required: true,
  },
});

const { collection } = toRefs(props);

const isActive = ref(false);

function checkIsActive() {
  const currentDate = new Date();
  const startDate = new Date(collection.value.start_date);
  const endDate = new Date(collection.value.end_date);

  const currentDateFormatted =
    currentDate.getTime() - currentDate.getTimezoneOffset() * -60000;
  const startDateFormatted =
    startDate.getTime() - startDate.getTimezoneOffset() * -60000;
  const endDateFormatted =
    endDate.getTime() - endDate.getTimezoneOffset() * -60000;

  if (
    startDateFormatted <= currentDateFormatted &&
    endDateFormatted >= currentDateFormatted
  ) {
    isActive.value = true;
  }
}

onMounted(() => {
  checkIsActive();
});

// format collection dates
function formatOpenDates(date) {
  const dateObj = new Date(date);

  const dateFormatted = new Date(
    dateObj.getTime() - dateObj.getTimezoneOffset() * -60000
  );

  console.log(dateFormatted);

  return `${dateFormatted.toLocaleString("default", {
    month: "short",
  })} ${dateFormatted.toLocaleString("default", {
    day: "numeric",
  })}`;
}

// format collection year
function getYear(date) {
  const dateObj = new Date(date);

  return dateObj.getFullYear();
}
</script>
