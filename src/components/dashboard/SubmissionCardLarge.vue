<template>
  <div
    class="relative bg-white card-shadow rounded-lg w-full px-5 py-4 lg:px-8 lg:py-5 text-sm lg:text-base"
  >
    <p
      v-if="submission.booked"
      class="absolute left-0 top-0 ml-5 lg:ml-8 text-[8px] lg:text-[10px] text-blue-500"
    >
      Booked
    </p>

    <div class="relative grid grid-cols-5 gap-4 overflow-hidden">
      <div class="col-span-1">
        <div class="relative">
          <img
            v-if="submission.image"
            class="h-24 w-24 object-cover rounded-md"
            src="https://source.unsplash.com/random"
          />
          <div v-else class="h-24 w-24 rounded-lg bg-gray-300" />
        </div>

        <transition name="fade" opacity>
          <div v-if="state.isOpen" class="mt-6">
            <a
              class="border-2 border-transparent bg-green-500 text-white text-center w-24 block rounded-md mb-4"
              v-if="submission.phone"
              :href="`tel:${submission.phone}`"
            >
              Quick Call
            </a>
            <router-link
              class="border-2 border-blue-500 w-24 text-center block rounded-md"
              :to="`/${currentUser.username}/collections/${submission.collection_id}/${submission.id}`"
              >View</router-link
            >
          </div>
        </transition>
      </div>

      <div class="col-span-4">
        <div
          class="relative overflow-hidden"
          :class="state.isOpen ? 'expanded' : 'collapsed'"
        >
          <div v-html="submission.message" class="mb-4" />

          <div
            v-if="!state.isOpen"
            class="absolute w-full bottom-0 bg-gradient-to-t from-white to-transparent h-1/3 z-10"
          />

          <div class="grid grid-cols-4 text-sm lg:text-base pb-1">
            <div class="relative">
              <p class="text-xs opacity-50">Name</p>
              <p>{{ submission.name }}</p>
            </div>

            <div class="col-span-2">
              <p class="text-xs opacity-50">Email</p>
              <p class="opacity-60">{{ submission.email }}</p>
            </div>

            <div>
              <p class="text-xs opacity-50">Phone</p>
              <p class="opacity-60">{{ submission.phone }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="absolute w-full bottom-0 left-0 right-0 text-blue-500 mb-1">
      <button
        class="block mx-auto"
        @click="state.isOpen = !state.isOpen"
        aria-label="Expand card"
      >
        <IconChevronDown
          class="h-4 w-4 transition duration-150 ease-in-out"
          :class="state.isOpen ? 'rotate-180' : ''"
        />
      </button>
    </div>
  </div>
</template>

<script setup>
// utils
import { reactive } from "vue";
import { useRoute } from "vue-router";
import { useUserStore } from "@/stores/user";

// components
import BaseButton from "@/components/base/BaseButton.vue";
import IconChevronDown from "@/components/svg/IconChevronDown.vue";

const route = useRoute();
const { currentUser } = useUserStore();

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

<style scoped>
.expanded {
  max-height: 1000px;
  transition: max-height 300ms;
}

.collapsed {
  max-height: 100px;
  transition: max-height 300ms;
}

.fade-ender-active,
.fade-leave-active {
  opacity: 1;
  transition: 300ms;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
