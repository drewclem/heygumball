<template>
  <div
    class="bg-white card-shadow rounded-lg w-full px-5 py-4 lg:px-8 lg:py-5 text-sm lg:text-base overflow-hidden"
  >
    <div class="grid grid-cols-5 gap-4">
      <div class="col-span-1">
        <img
          class="h-24 w-24 object-cover rounded-md"
          src="https://source.unsplash.com/random"
        />
      </div>
      <div
        class="col-span-4 relative"
        :class="{ 'max-h-24 overflow-hidden': !state.isOpen }"
      >
        <div v-html="submission.message" />
        <div
          v-if="!state.isOpen"
          class="absolute bottom-0 w-full h-1/2 bg-gradient-to-t from-white to-transparent z-50"
        />
      </div>
    </div>

    <div
      v-if="state.isOpen"
      class="grid grid-cols-6 px-5 py-3 lg:px-8 lg:py-4 text-sm lg:text-base"
    >
      <div class="relative col-span-2">
        <p
          v-if="submission.booked"
          class="text-[8px] lg:text-[10px] text-blue-500 bottom-0 -mb-[12px] absolute"
        >
          Booked
        </p>
        <p>{{ submission.name }}</p>
      </div>
      <p class="col-span-2 opacity-60">{{ submission.email }}</p>
      <p class="opacity-60">{{ submission.phone }}</p>
      <p class="opacity-60 ml-auto">{{ formatDate(submission.created_at) }}</p>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";

defineProps({
  submission: {
    type: Object,
    required: true,
  },
});

const state = reactive({
  isOpen: false,
});

// simple function to take the date returned from Supbase (yyyy-mm-dd) and format it to MMM-YY
function formatDate(date) {
  const dateObj = new Date(date);

  const dateFormatted = new Date(
    dateObj.getTime() - dateObj.getTimezoneOffset() * -60000
  );

  return `${dateFormatted.toLocaleString("default", {
    month: "short",
  })} ${dateFormatted.toLocaleString("default", {
    day: "numeric",
  })}`;
}
</script>

<style scoped></style>
