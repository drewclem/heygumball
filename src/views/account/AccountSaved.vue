<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center">
        <BaseHeading size="h4" tag="h1">Saved</BaseHeading>

        <div
          class="bg-white rounded-full px-4 py-2 shadow-inner flex space-x-6 text-sm ml-6"
        >
          <div class="flex space-x-2 items-center text-sm">
            <p class="text-blue-500">Message</p>
            <BaseCheckboxToggle
              id="`viewMode`"
              v-model:checked="currentUser.default_view"
              :modelValue="currentUser.default_view"
              @update:checked="updateViewMode"
            />
            <p class="text-blue-500">Info</p>
          </div>
        </div>
      </div>

      <KeywordSearch v-model="searchPhrase" />
    </div>

    <div v-if="currentUser.default_view">
      <div
        class="grid grid-cols-6 card-padding text-sm lg:text-base opacity-40 mb-4"
      >
        <p class="col-span-2">Name</p>
        <p class="col-span-2">Email</p>
        <p>Phone</p>
        <p class="ml-auto">Submitted On</p>
      </div>

      <div class="flex flex-col space-y-6">
        <div v-if="!savedSubmissions.length">
          <p class="mb-5">No saved submissions</p>
        </div>

        <div v-else-if="!filteredSubmissions.length">
          <BaseHeading class="text-red-500 mb-5" size="h3" tag="h2">
            Uh oh!
          </BaseHeading>
          <BaseText> Looks like we couldn't find anything. </BaseText>
          <BaseText size="small">Check for typos!</BaseText>
        </div>

        <template v-else>
          <SubmissionCard
            v-for="submission in filteredSubmissions"
            :key="submission.id"
            :submission="submission"
          />
        </template>
      </div>
    </div>

    <div v-else>
      <div
        class="grid grid-cols-5 px-5 gap-4 py-3 lg:px-8 text-sm lg:text-base lg:py-4 opacity-40 mb-4"
      >
        <p class="col-span-1">Thumbnail</p>
        <p class="col-span-4">Message</p>
      </div>

      <div class="flex flex-col space-y-6">
        <div v-if="!savedSubmissions.length">
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
          <SubmissionCardLarge
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
import { supabase } from "@/supabase";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseCheckboxToggle from "@/components/base/BaseCheckboxToggle.vue";
import SubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import SubmissionCardLarge from "@/components/dashboard/SubmissionCardLarge.vue";
import KeywordSearch from "@/components/dashboard/KeywordSearch.vue";
import IconSearch from "@/components/svg/IconSearch";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";

const global = useUserStore();
const { setSavedSubmissions, currentUser } = useUserStore();

const { savedSubmissions } = storeToRefs(global);

setSavedSubmissions();

/**
 * Filter through submissions
 */

const searchPhrase = ref(null);

const filteredSubmissions = computed(() => {
  return savedSubmissions.value.filter((submission) => {
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

async function updateViewMode(e) {
  if (window.confirm("Would you like to set this as your default view?")) {
    const { error } = await supabase
      .from("profiles")
      .update({ default_view: e })
      .match({ id: currentUser.value?.id });
  }
}
</script>
