<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Inbox</BaseHeading>
      </div>

      <KeywordSearch v-model="searchPhrase" :value="searchPhrase" />
    </div>

    <div>
      <div
        class="grid grid-cols-6 px-5 py-3 lg:px-8 lg:py-4 text-sm lg:text-base opacity-40 mb-4"
      >
        <p class="col-span-2">Name</p>
        <p class="col-span-2">Email</p>
        <p>Phone</p>
        <p class="ml-auto">Submitted On</p>
      </div>

      <div class="flex flex-col space-y-6">
        <div v-if="!allSubmissions.length">
          <p class="mb-5">No submissions yet! Share that link!</p>
          <CopyShareLink />
        </div>

        <div v-else-if="!filteredSubmissions.length">
          <BaseHeading class="text-red-500 mb-5" size="h3" tag="h2">
            Uh oh!
          </BaseHeading>
          <BaseText> Looks like we couldn't find anything. </BaseText>
          <BaseText size="small">Check for typos!</BaseText>
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
import { ref, computed } from "vue";
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";
import CollectionSubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import KeywordSearch from "@/components/dashboard/KeywordSearch.vue";

const searchPhrase = ref(null);

const { setAllSubmissions } = useUserStore();
const global = useUserStore();

const { allSubmissions } = storeToRefs(global);

setAllSubmissions();

/**
 * Filter through submissions
 */

const filteredSubmissions = computed(() => {
  return allSubmissions.value.filter((submission) => {
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
