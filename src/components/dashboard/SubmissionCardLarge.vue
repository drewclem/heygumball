<template>
  <div
    class="relative bg-white card-shadow rounded-lg w-full px-5 pt-4 pb-6 lg:px-8 lg:py-5 text-sm lg:text-base"
  >
    <div class="absolute left-0 top-0 flex items-center h-full ml-0.5 lg:ml-1">
      <IconDecline
        v-if="submission.is_declined"
        class="transform scale-50 lg:scale-75 text-gray-300"
      />
      <IconThumbDown
        v-else-if="submission.is_liked === -1 && !submission.is_declined"
        class="transform scale-50 lg:scale-75 text-red-200"
      />
      <IconThumbUp
        v-if="submission.is_liked === 1 && !submission.is_declined"
        class="transform scale-50 lg:scale-75 text-green-200"
      />
    </div>

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
            v-if="state.thumbnailUrl"
            class="h-12 w-12 lg:h-24 lg:w-24 object-cover rounded-md"
            :src="state.thumbnailUrl"
          />
          <div
            v-else
            class="flex justify-center items-center h-12 w-12 lg:h-24 lg:w-24 rounded-lg bg-gray-300"
          >
            <p class="text-sm text-gray-500 text-center">No images provided</p>
          </div>
        </div>

        <transition name="fade" opacity>
          <div v-if="state.isOpen" class="hidden lg:block mt-6">
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

          <div class="grid grid-cols-4 gap-2 text-sm lg:text-base pb-1">
            <div class="relative">
              <p class="text-xs opacity-50">Name</p>
              <p>{{ submission.name }}</p>
            </div>

            <div class="col-span-2">
              <p class="text-xs opacity-50">Email</p>
              <p class="opacity-60 truncate">{{ submission.email }}</p>
            </div>

            <div>
              <p class="text-xs opacity-50">Phone</p>
              <p class="opacity-60">{{ submission.phone }}</p>
            </div>
          </div>

          <div class="mt-4 lg:hidden flex space-x-4">
            <a
              class="border-2 border-transparent bg-green-500 text-white text-center w-24 block rounded-md mb-4"
              v-if="submission.phone"
              :href="`tel:${submission.phone}`"
            >
              Quick Call
            </a>
            <div>
              <router-link
                class="border-2 border-blue-500 w-24 text-center block rounded-md"
                :to="`/${currentUser.username}/collections/${submission.collection_id}/${submission.id}`"
                >View</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="absolute w-full bottom-0 left-0 right-0 text-blue-500 mb-1 z-20"
    >
      <button
        class="block mx-auto border border-blue-500 lg:border-transparent lg:bg-transparent bg-white rounded-full p-1 -mb-4 lg:mb-0"
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
import { onMounted, reactive } from "vue";
import { useRoute } from "vue-router";
import { useUserStore } from "@/stores/user";
import { supabase } from "@/supabase";

// components
import BaseButton from "@/components/base/BaseButton.vue";
import IconChevronDown from "@/components/svg/IconChevronDown.vue";
import IconThumbDown from "@/components/svg/IconThumbDown.vue";
import IconThumbUp from "@/components/svg/IconThumbUp.vue";
import IconDecline from "@/components/svg/IconDecline.vue";

const route = useRoute();
const { currentUser } = useUserStore();

const props = defineProps({
  submission: {
    type: Object,
    required: true,
  },
});

const state = reactive({
  isOpen: false,
  thumbnailUrl: null,
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

async function retrieveImages() {
  const { data } = await supabase
    .from("submission-uploads")
    .select()
    .limit(1)
    .match({ submission_id: props.submission.id });

  console.log(data);

  if (data.length) {
    const imgData = await supabase.storage
      .from(`submission-uploads/${props.submission.id}`)
      .createSignedUrl(data[0].file_name, 60);

    const img = await imgData.data;

    state.thumbnailUrl = img.signedURL;
  }
}

onMounted(() => {
  retrieveImages();
});
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
