<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-baseline">
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>

        <button
          class="ml-6 opacity-60"
          type="button"
          aria-label="Go back to account info page"
          @click="router.back()"
        >
          <IconArrowLeft class="h-3 w-3 inline -mt-0.5" />
          Back
        </button>
      </div>

      <KeywordSearch v-model="searchPhrase" />
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
          <CopyShareLink />
        </div>

        <template v-else>
          <CollectionSubmissionCard
            v-for="submission in filteredSubmissions"
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
import { ref, onMounted, onBeforeMount, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import useAuthUser from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";
import { supabase } from "@/supabase";

// components
import CollectionSubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";
import KeywordSearch from "@/components/dashboard/KeywordSearch.vue";
import IconSearch from "@/components/svg/IconSearch.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";

const route = useRoute();
const router = useRouter();
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
    .order("created_at", { ascending: false });

  submissions.value = data;
  loading.value = false;
}

// set current collection in pinia for use later
onBeforeMount(() => {
  setCurrentCollection(collection_id);
});

onMounted(() => {
  setSubmissions();
});

/**
 * Submission filtering
 */
const searchPhrase = ref(null);

const filteredSubmissions = computed(() => {
  return submissions.value.filter((submission) => {
    if (searchPhrase.value === null) return submission;
    const filter = searchPhrase.value.toLowerCase();

    const email = submission.email?.toLowerCase();
    const name = submission.name?.toLowerCase();
    const message = submission.message?.toLowerCase();

    if (
      searchPhrase.value !== null &&
      (email.includes(filter) ||
        name.includes(filter) ||
        message.includes(filter) ||
        submission.phone.includes(filter))
    )
      return submission;
  });
});
</script>
