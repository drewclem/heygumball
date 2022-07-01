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
    </div>

    <p v-if="loading">Loading...</p>

    <div v-else class="flex flex-col lg:flex-row gap-12">
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

        <button
          class="flex text-base"
          @click="saveSubmission"
          :disabled="submission.is_declined"
        >
          <IconHeart
            class="h-5 w-5 mr-2 -mt-px"
            :class="submission.saved ? 'text-red-500' : 'text-gray-300'"
          />
          <span v-if="!submission.saved">Favorite</span>
          <span v-else>Favorited</span>
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
          :disabled="submission.is_declined"
        >
          <span v-if="!submission.booked">Mark as booked</span>
          <span v-else>Booked</span>
        </button>

        <div class="relative">
          <p class="text-xs opacity-50 mb-3">
            Automatically sends an email notifying your client they won't be
            selected this round.
          </p>
          <button
            type="button"
            class="py-0.5 border-2 border-gray-300 text-center rounded-md w-full mb-6"
            :class="{ 'hover:border-gray-400': !submission.is_declined }"
            @click="declineSubmission"
            :disabled="submission.is_declined || isSubmitting"
          >
            <span v-if="isSubmitting">Sending...</span>
            <span v-else-if="submission.is_declined">Declined</span>
            <span v-else>Decline</span>
          </button>
          <p
            class="absolute bottom-0 text-xs mt-8"
            :class="isError ? 'text-red-500' : 'text-green-500'"
          >
            {{ resMessage }}
          </p>
        </div>

        <button
          type="button"
          class="text-red-500 underline text-left"
          @click="deleteSubmission"
        >
          Delete
        </button>
      </div>

      <!-- right side -->
      <div class="order-first lg:order-last lg:w-3/4">
        <div class="card-shadow bg-white p-8 lg:p-12 rounded-lg mb-8">
          <div class="flex justify-between" items-center>
            <BaseHeading size="h5" tag="h2" class="mb-5">Message</BaseHeading>

            <div class="flex flex-row space-x-4 mb-4">
              <button class="flex text-base" @click="dislikeSubmission">
                <IconThumbDown
                  :class="
                    submission.is_liked === -1
                      ? 'text-red-500'
                      : 'text-gray-300 hover:text-gray-500'
                  "
                />
                <span class="sr-only" v-if="submission.is_liked === -1">
                  Disliked
                </span>
              </button>

              <button class="flex text-base" @click="likeSubmission">
                <IconThumbUp
                  :class="
                    submission.is_liked === 1
                      ? 'text-green-500'
                      : 'text-gray-300 hover:text-gray-500'
                  "
                />
                <span class="sr-only" v-if="submission.is_liked === 1"
                  >Liked</span
                >
              </button>
            </div>
          </div>
          <div class="richtext" v-html="submission.message" />
        </div>

        <div
          v-if="images.length > 0"
          class="card-shadow bg-white p-8 lg:p-12 rounded-lg"
        >
          <BaseHeading size="h5" tag="h2" class="mb-5">
            Reference media
          </BaseHeading>

          <ul class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
            <li v-for="(image, index) in images" :key="index">
              <BaseModal>
                <template #button>
                  <BaseImage
                    class="img-list hover:scale-150 ease-in-out transform"
                    :src="image"
                  />
                </template>
                <template #content>
                  <BaseImage class="rounded-lg" :src="image" />
                </template>
              </BaseModal>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { onBeforeMount, onMounted, ref, nextTick, reactive } from "vue";
import { useRoute, useRouter } from "vue-router";
import { supabase } from "@/supabase";
import { useUserStore } from "@/stores/user";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseImage from "@/components/base/BaseImage.vue";
import BaseModal from "@/components/base/BaseModal.vue";
import IconHeart from "@/components/svg/IconHeart.vue";
import IconThumbDown from "@/components/svg/IconThumbDown.vue";
import IconThumbUp from "@/components/svg/IconThumbUp.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";

const route = useRoute();
const router = useRouter();
const submission = ref({});

const { currentUser, setSavedSubmissions, setCollections } = useUserStore();

const submissionId = route.params.submission_id;

// init loading state to true
const loading = ref(true);
const images = ref([]);

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
  await setCollections();
  await setSavedSubmissions();
}

async function dislikeSubmission() {
  if (submission.value.is_liked !== -1) {
    const { error } = await supabase
      .from("submissions")
      .update({ is_liked: -1 })
      .match({ id: submission.value.id });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  } else {
    const { error } = await supabase
      .from("submissions")
      .update({ is_liked: 0 })
      .match({ id: submission.value.id });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  }

  await fetchSubmission();
  await setCollections();
  await setSavedSubmissions();
}

async function likeSubmission() {
  if (submission.value.is_liked !== 1) {
    const { error } = await supabase
      .from("submissions")
      .update({ is_liked: 1 })
      .match({ id: submission.value.id });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  } else {
    const { error } = await supabase
      .from("submissions")
      .update({ is_liked: 0 })
      .match({ id: submission.value.id });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  }

  await fetchSubmission();
  await setCollections();
  await setSavedSubmissions();
}
// toggle saving the submission
async function markAsBooked() {
  const { error } = await supabase
    .from("submissions")
    .update({ booked: !submission.value.booked })
    .match({ id: submissionId });

  if (error) {
    alert("Oops! Something went wrong.");
  }

  await fetchSubmission();
  await setCollections();
  await setSavedSubmissions();
}

// delete submission
async function deleteSubmission() {
  if (
    window.confirm(
      "Are you sure you want to delete this submission? This is an irreversible action."
    )
  ) {
    await supabase
      .from("submission-uploads")
      .delete()
      .match({ submission_id: submissionId });

    const { error } = await supabase
      .from("submissions")
      .delete()
      .match({ id: submissionId });

    if (error) {
      alert(error.message);
    } else {
      router.back();
    }
  }
}

/**
 * Submission images
 */
async function retrieveImages() {
  const { data } = await supabase
    .from("submission-uploads")
    .select()
    .match({ submission_id: submissionId });

  if (data) {
    data.forEach(async (image, index) => {
      const imgData = await supabase.storage
        .from(`submission-uploads/${submissionId}`)
        .createSignedUrl(image.file_name, 60);

      const img = await imgData.data;

      images.value.push(img.signedURL);
    });
  }
}

/**
 * Decline submission
 */

const isError = ref(false);
const resMessage = ref();
const isSubmitting = ref(false);

async function declineSubmission() {
  isSubmitting.value = true;

  const data = {
    sender: currentUser.email,
    receiver: submission.value.email,
    subject: "Thank you for contacting me. However...",
    text: currentUser.decline_response,
  };

  try {
    const message = await fetch("/.netlify/functions/send-user-email", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    const mailgunData = await message;

    if (mailgunData.status === 200) {
      const { error } = await supabase
        .from("submissions")
        .update({ is_declined: true })
        .match({ id: submissionId });

      resMessage.value = "Email sent!";
    }
    isSubmitting.value = false;
  } catch (error) {
    console.log(error.message);

    isError.value = true;
    resMessage.value = "Error sending.";
    isSubmitting.value = false;
  }

  setTimeout(() => {
    resMessage.value = null;
    isError.value = false;
  }, 6000);

  await fetchSubmission();
  await setCollections();
  await setSavedSubmissions();
}

onBeforeMount(async () => {
  await fetchSubmission();

  // update submission when viewed for the first time
  if (!submission?.value?.viewed) {
    const { error } = await supabase
      .from("submissions")
      .update({ viewed: true })
      .match({ id: submissionId });

    if (error) {
      alert("Oops! Something went wrong.");
    }
  }
});

onMounted(() => {
  retrieveImages();
});
</script>

<style>
.richtext {
  @apply leading-relaxed;
}

.richtext ul {
  @apply list-disc;
  list-style-position: inside;
}

.img-list {
  @apply rounded-lg shadow-md;
  aspect-ratio: 1/1;
  object-fit: cover;
}

button:disabled {
  @apply opacity-50 pointer-events-none;
}
</style>
