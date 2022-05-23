<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
      </div>
    </div>

    <p v-if="loading">Loading...</p>

    <div v-else class="flex w-full gap-12">
      <div class="w-52 text-sm flex flex-col space-y-5">
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
      </div>
      <div class="flex-grow">
        <div class="card-shadow bg-white p-8 rounded-lg">
          <BaseHeading size="h5" tag="h2" class="mb-5">Message</BaseHeading>
          <p>{{ submission.message }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { onBeforeMount, onMounted, ref, nextTick } from "vue";
import { useRoute } from "vue-router";
import { supabase } from "@/supabase";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";

const route = useRoute();
const submission = ref({});

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

async function updateViewed() {
  if (!submission.value.viewed) {
    const { error } = await supabase
      .from("submissions")
      .update("viewed", true)
      .match({ id: submission.value.id });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  }
}

onBeforeMount(async () => {
  await fetchSubmission();

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