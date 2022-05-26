<template>
  <router-link
    :to="`/${user.user_metadata.username}/collections/${route.params.collection_id}/${submission.id}`"
    class="relative flex w-full items-center overflow-hidden"
  >
    <div
      v-if="!submission.viewed"
      class="absolute rounded-full bg-red-500 w-2 h-2 ml-3"
    />
    <div
      class="grid grid-cols-6 px-8 py-4 bg-white card-shadow rounded-lg w-full"
    >
      <div class="relative col-span-2">
        <p
          v-if="submission.booked"
          class="text-[10px] text-blue-500 bottom-0 -mb-[10px] absolute"
        >
          Booked
        </p>
        <p>{{ submission.name }}</p>
      </div>
      <p class="col-span-2">{{ submission.email }}</p>
      <p>{{ submission.phone }}</p>
      <p class="ml-auto">{{ formatDate(submission.created_at) }}</p>
    </div>
  </router-link>
</template>

<script setup>
// utils
import useAuthUser from "@/utils/useAuth";
import { useRoute } from "vue-router";

// components
import AccountCardGrid from "@/components/account/AccountCardGrid.vue";

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

  return `${dateObj.toLocaleString("default", {
    month: "short",
  })} ${dateObj.toLocaleString("default", {
    day: "numeric",
  })}`;
}
</script>
