<template>
  <router-link
    :to="`/${user.user_metadata.username}/collections/${submission.collection_id}/${submission.id}`"
    class="relative flex w-full items-center overflow-hidden card-shadow"
  >
    <div
      v-if="!submission.viewed"
      class="absolute rounded-full bg-red-500 w-2 h-2 ml-3"
    />
    <div class="grid grid-cols-6 px-8 py-4 bg-white rounded-lg w-full">
      <div class="relative col-span-2">
        <p
          v-if="submission.booked"
          class="text-[10px] text-blue-500 bottom-0 -mb-[10px] absolute"
        >
          Booked
        </p>
        <p>{{ submission.name }}</p>
      </div>
      <p class="col-span-2 opacity-60">{{ submission.email }}</p>
      <p class="opacity-60">{{ submission.phone }}</p>
      <p class="opacity-60 ml-auto">{{ formatDate(submission.created_at) }}</p>
    </div>
  </router-link>
</template>

<script setup>
// utils
import useAuthUser from "@/utils/useAuth";
import { useRoute } from "vue-router";

// components
import AccountCardGrid from "@/components/dashboard/AccountCardGrid.vue";

const { user } = useAuthUser();
const route = useRoute();

defineProps({
  submission: {
    type: Object,
    required: true,
  },
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
