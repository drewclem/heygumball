<template>
  <div class="relative flex w-full items-center">
    <div
      v-if="!submission.viewed"
      class="absolute rounded-full bg-red-500 w-2 h-2 ml-3"
    />
    <div
      class="grid grid-cols-3 px-8 py-4 bg-white card-shadow rounded-lg w-full"
    >
      <p>{{ submission.name }}</p>
      <p>{{ submission.email }}</p>
      <p class="ml-auto">{{ submission.phone }}</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import useAuthUser from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";
import { useRoute } from "vue-router";

// components
import AccountCardGrid from "@/components/account/AccountCardGrid.vue";

const { user } = useAuthUser();
const { setActiveCollection } = useUserStore();
const route = useRoute();

onMounted(() => {
  setActiveCollection(route.params.collection_id);
});

defineProps({
  submission: {
    type: Object,
    required: true,
  },
});

function formatOpenDates(date) {
  const dateObj = new Date(date);
  return `${dateObj.toLocaleString("default", {
    month: "short",
  })} ${dateObj.getDay()}`;
}

function getYear(date) {
  const dateObj = new Date(date);

  return dateObj.getFullYear();
}
</script>
