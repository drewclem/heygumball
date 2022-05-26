<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
      </div>
    </div>

    <p v-if="loading">Loading...</p>

    <div v-else class="flex gap-12">
      <!-- left side -->
      <div class="text-sm w-44 flex flex-col space-y-5">
        <div>
          <BaseHeading size="h5" tag="h2">Name</BaseHeading>
          <p>{{ submission.name }}</p>
        </div>

        <div>
          <BaseHeading size="h5" tag="h2">Email</BaseHeading>
          <p>{{ submission.email }}</p>
        </div>

        <div>
          <BaseHeading size="h5" tag="h2">Phone</BaseHeading>
          <p>{{ submission.phone }}</p>
        </div>

        <button class="flex text-base" @click="saveSubmission">
          <IconHeart
            class="h-5 w-5 mr-2 -mt-px"
            :class="submission.saved ? 'text-red-500' : 'text-gray-300'"
          />
          <span v-if="!submission.saved">Save</span>
          <span v-else>Saved</span>
        </button>

        <a
          :href="`mailto:${submission.email}`"
          class="py-0.5 border-2 border-green-500 hover:bg-green-500 hover:text-white text-center rounded-md"
          :class="submission.booked ? 'opacity-50 pointer-events-none' : ''"
          :disabled="submission.booked"
        >
          Reply
        </a>

        <button
          type="button"
          class="py-0.5 border-2 border-blue-500 hover:bg-blue-500 hover:text-white text-center rounded-md"
          :class="`${submission.booked ? 'bg-blue-500 text-white' : ''}`"
          @click="markAsBooked"
        >
          <span v-if="!submission.booked">Mark as booked</span>
          <span v-else>Booked</span>
        </button>

        <button
          type="button"
          class="text-red-500 underline text-left"
          @click="deleteSubmission"
        >
          Delete
        </button>
      </div>

      <!-- right side -->
      <div class="w-3/4">
        <div class="card-shadow bg-white p-8 rounded-lg">
          <BaseHeading size="h5" tag="h2" class="mb-5">Message</BaseHeading>
          <div class="richtext" v-html="submission.message" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { onBeforeMount, onMounted, ref, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";
import { supabase } from "@/supabase";
import { useUserStore } from "@/stores/user";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import IconHeart from "@/components/svg/IconHeart.vue";

const route = useRoute();
const router = useRouter();
const submission = ref({});

const { setSavedSubmissions } = useUserStore();

// init loading state to true
const loading = ref(true);

// fetch request to get the submission data
async function fetchSubmission() {
  const { data } = await supabase
    .from("submissions")
    .select()
    .eq("id", route.params.submission_id)
    .order("created_at");

  submission.value = data[0];

  // set loading to false
  if (data) loading.value = false;
}

// toggle saving the submission
async function saveSubmission() {
  const { error } = await supabase
    .from("submissions")
    .update({ saved: !submission.value.saved })
    .match({ id: submission.value.id });

  if (error) {
    alert("Oops! Something went wrong.");
  }

  await fetchSubmission();
}

// toggle saving the submission
async function markAsBooked() {
  const { error } = await supabase
    .from("submissions")
    .update({ booked: !submission.value.booked })
    .match({ id: submission.value.id });

  if (error) {
    alert("Oops! Something went wrong.");
  }

  await fetchSubmission();
  await setSavedSubmissions()
}

// delete submission
async function deleteSubmission() {
  if (
    window.confirm(
      "Are you sure you want to delete this submission? This is an irreversible action."
    )
  ) {
    const { error } = await supabase
      .from("submissions")
      .delete()
      .match({ id: submission.value.id });

    if (error) {
      alert(error.message);
    } else {
      router.back();
    }
  }
}

onBeforeMount(async () => {
  await fetchSubmission();

  // update submission when viewed for the first time
  if (!submission?.value?.viewed) {
    const { error } = await supabase
      .from("submissions")
      .update({ viewed: true })
      .match({ id: submission.value.id });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  }
});
</script>

<style>
.richtext p,
.richtext ul,
.richtext ol {
  margin-bottom: 1.25rem !important;
}
</style>
