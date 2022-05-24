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
      <div class="grid grid-cols-6 px-8 py-4 opacity-40 mb-4">
        <p class="col-span-2">Name</p>
        <p class="col-span-2">Email</p>
        <p>Phone</p>
        <p class="ml-auto">Submitted On</p>
      </div>

      <div class="flex flex-col space-y-6">
        <p class="opacity-50" v-if="loading">Loading...</p>

        <div v-else-if="!submissions.length">
          <p class="mb-5">No submissions yet! Share that link!</p>
          <div class="flex items-center">
            <p id="copy" class="font-bold text-green-500 mr-4">
              https://heygumball.com/{{ user.user_metadata.username }}
            </p>
            <button
              class="text-xs opacity-50 hover:opacity-100"
              type="button"
              @click="copyText"
            >
              <span v-if="copySuccess">Copied!</span>
              <div v-else>
                <IconCopy class="h-4 w-4 -mt-0.5" />
                <span class="sr-only">Copy to clipboard</span>
              </div>
            </button>
          </div>
        </div>

        <template v-else>
          <CollectionSubmissionCard
            v-for="submission in submissions"
            :key="submission.id"
            :submission="submission"
          />
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { ref, onMounted, onBeforeMount } from "vue";
import { useRoute } from "vue-router";
import useAuthUser from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";
import { supabase } from "@/supabase";

// components
import CollectionSubmissionCard from "@/components/collections/CollectionSubmissionCard.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import IconSearch from "@/components/svg/IconSearch.vue";
import IconCopy from "@/components/svg/IconCopy.vue";

const route = useRoute();
const { setCurrentCollection } = useUserStore();
const { user } = useAuthUser();

const submissions = ref([]);
const loading = ref(true);
const copySuccess = ref(false);

const collection_id = route.params.collection_id;

// fetch request to get all  submissions from collections
async function setSubmissions() {
  const { data } = await supabase
    .from("submissions")
    .select()
    .eq("collection_id", collection_id)
    .order("created_at");

  submissions.value = data;
  loading.value = false;
}

function copyText() {
  const text = document.getElementById("copy");

  navigator.clipboard.writeText(text.innerText);

  copySuccess.value = true;

  setTimeout(() => {
    copySuccess.value = false;
  }, 3000);
}

// set current collection in pinia for use later
onBeforeMount(() => {
  setCurrentCollection(collection_id);
});

onMounted(() => {
  setSubmissions();
});
</script>
