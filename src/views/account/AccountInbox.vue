<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center">
        <BaseHeading size="h4" tag="h1">Inbox</BaseHeading>

        <div
          class="bg-white rounded-full px-4 py-2 shadow-inner flex space-x-6 text-sm ml-6"
        >
          <div class="flex space-x-2 items-center text-sm">
            <p class="text-blue-500">Message</p>
            <BaseChecboxToggle
              id="`viewMode`"
              v-model:checked="currentUser.default_view"
              :modelValue="currentUser.default_view"
              @update:checked="updateViewMode"
            />
            <p class="text-blue-500">Info</p>
          </div>
        </div>
      </div>

      <div class="relative hidden lg:block">
        <div
          class="absolute top-0 right-0 flex justify-center items-center -mt-4"
        >
          <KeywordSearch class="flex mr-4" v-model="searchPhrase" />
          <BaseSelect :options="currentUser.tags" v-model="filterWord">
            Filter
          </BaseSelect>
        </div>
      </div>

      <BaseSelect
        class="w-full lg:hidden"
        :options="currentUser.tags"
        v-model="filterWord"
      >
        Filter
      </BaseSelect>

      <KeywordSearch
        class="lg:hidden"
        v-model="searchPhrase"
        :value="searchPhrase"
      />
    </div>

    <div v-if="currentUser.default_view">
      <div
        class="grid grid-cols-6 gap-2 card-padding text-sm lg:text-base opacity-40 mb-4"
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

      <div class="flex flex-col space-y-8">
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
import BaseChecboxToggle from "@/components/base/BaseCheckboxToggle.vue";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";
import SubmissionCardLarge from "@/components/dashboard/SubmissionCardLarge.vue";
import SubmissionCard from "@/components/dashboard/SubmissionCard.vue";
import KeywordSearch from "@/components/dashboard/KeywordSearch.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";

const searchPhrase = ref(null);
const filterWord = ref(null);

const { setAllSubmissions, currentUser } = useUserStore();
const global = useUserStore();

const { allSubmissions } = storeToRefs(global);

setAllSubmissions();

/**
 * Filter through submissions
 */

const filteredSubmissions = computed(() => {
  let likedSubmissions = [];
  let midSubmissions = [];
  let dislikedSubmissions = [];
  let declinedSubmissions = [];

  allSubmissions.value.filter((submission) => {
    if (submission.is_declined) declinedSubmissions.push(submission);
    if (submission.is_liked === -1 && !submission.is_declined)
      dislikedSubmissions.push(submission);
    if (submission.is_liked === 1 && !submission.is_declined)
      likedSubmissions.push(submission);
    if (submission.is_liked === 0 && !submission.is_declined)
      midSubmissions.push(submission);
  });

  const sortedSubmissions = [
    ...likedSubmissions,
    ...midSubmissions,
    ...dislikedSubmissions,
    ...declinedSubmissions,
  ];

  return sortedSubmissions.filter((submission) => {
    let matched = false;

    if (
      (searchPhrase.value === null || searchPhrase.value === "") &&
      (filterWord.value === null || filterWord.value === "null")
    )
      return submission;

    if (
      searchPhrase.value !== null &&
      (filterWord.value === null || filterWord.value === "null")
    ) {
      const search = searchPhrase.value.toLowerCase();

      const email = submission.email?.toLowerCase();
      const name = submission.name?.toLowerCase();
      const message = submission.message?.toLowerCase();

      if (
        email.includes(search) ||
        name.includes(search) ||
        message.includes(search) ||
        submission.phone.includes(search)
      ) {
        matched = true;
      }
    }

    if (
      filterWord.value !== null &&
      (searchPhrase.value === null || searchPhrase.value === "")
    ) {
      const filter = filterWord.value.toLowerCase();
      const email = submission.email?.toLowerCase();
      const name = submission.name?.toLowerCase();
      const message = submission.message?.toLowerCase();

      submission.tags.filter((tag) => {
        const label = tag.label.toLowerCase();
        if (label.includes(filter)) matched = true;
      });
    }

    if (
      (filterWord.value !== null || filterWord.value !== "null") &&
      (searchPhrase.value !== null || searchPhrase.value !== "")
    ) {
      const search = searchPhrase?.value?.toLowerCase();
      const filter = filterWord?.value?.toLowerCase();

      const email = submission.email?.toLowerCase();
      const name = submission.name?.toLowerCase();
      const message = submission.message?.toLowerCase();

      submission.tags.filter((tag) => {
        const label = tag.label.toLowerCase();
        if (
          label.includes(filter) &&
          (email.includes(search) ||
            name.includes(search) ||
            message.includes(search) ||
            submission.phone.includes(search))
        )
          matched = true;
      });
    }

    if (matched) return submission;
  });
});

async function updateViewMode(e) {
  if (window.confirm("Would you like to set this as your default view?")) {
    const { error } = await supabase
      .from("profiles")
      .update({ default_view: e })
      .match({ id: currentUser.id });
  }
}
</script>
