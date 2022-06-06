<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div class="flex">
        <BaseHeading size="h4" tag="h1">Settings</BaseHeading>
      </div>

      <div>
        <BaseLink
          :to="`/${user.user_metadata.username}/account`"
          class="text-red-500 mr-5 underline"
        >
          Cancel
        </BaseLink>
        <button
          type="submit"
          @click="updateUserInfo"
          class="underline"
          :disabled="userForm.submitting"
        >
          {{ userForm.submitting ? "Submitting..." : "Save Changes" }}
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-10">
      <div class="lg:col-span-2">
        <div class="h-24 w-24 bg-gray-400 rounded-full"></div>
      </div>

      <div class="lg:col-span-8">
        <div class="info-group info-list">
          <div>
            <BaseInput class="mb-2" v-model="userForm.username" disabled>
              User Name
            </BaseInput>
            <p class="text-xs opacity-50">
              Contact support to update your username
            </p>
          </div>

          <div>
            <BaseInput v-model="userForm.full_name">Full Name</BaseInput>
          </div>

          <div>
            <BaseInput
              class="mb-2"
              inputType="email"
              v-model="userForm.email"
              disabled
            >
              Email
            </BaseInput>
            <p class="text-xs opacity-50">
              Contact support to update your email
            </p>
          </div>
        </div>

        <div class="info-group info-grid">
          <div>
            <BaseInput v-model="userForm.instagram_url">Instagram</BaseInput>
          </div>

          <div>
            <BaseInput v-model="userForm.tiktok_url">Tiktok</BaseInput>
          </div>

          <div>
            <BaseInput v-model="userForm.twitter_url">Twitter</BaseInput>
          </div>

          <div>
            <BaseInput v-model="userForm.facebook_url">Facebook</BaseInput>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utils
import { reactive, watchEffect } from "vue";
import useAuthUser from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";
import { supabase } from "@/supabase";

// components
import BaseLink from "@/components/base/BaseLink.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";

const { user } = useAuthUser();
const { currentUser, setCurrentUserId } = useUserStore();

const userForm = reactive({
  username: null,
  full_name: null,
  email: null,
  submitting: false,
});

watchEffect(() => {
  (userForm.username = currentUser.username),
    (userForm.full_name = currentUser.full_name),
    (userForm.email = currentUser.email),
    (userForm.instagram_url = currentUser.instagram_url),
    (userForm.tiktok_url = currentUser.tiktok_url),
    (userForm.twitter_url = currentUser.twitter_url),
    (userForm.facebook_url = currentUser.facebook_url);
});

async function updateUserInfo() {
  userForm.submitting = true;
  const { error } = await supabase.from("profiles").upsert({
    id: currentUser.id,
    full_name: userForm.full_name,
    email: userForm.email,
    instagram_url:
      userForm.instagram_url === "" ? null : userForm.instagram_url,
    tiktok_url: userForm.tiktok_url === "" ? null : userForm.tiktok_url,
    twitter_url: userForm.twitter_url === "" ? null : userForm.twitter_url,
    facebook_url: userForm.facebook_url === "" ? null : userForm.facebook_url,
  });
  if (error) {
    alert("Oops! Something went wrong.");
  } else {
    setTimeout(() => {
      userForm.submitting = false;
    }, 300);
  }

  setCurrentUserId(currentUser.id);
}
</script>

<style scoped>
.info-heading {
  @apply opacity-50 mb-1;
}

.info-group {
  @apply border-b-2 border-gray-200 pb-12 mb-12;
}

.info-list {
  @apply flex flex-col space-y-6;
}

.info-grid {
  @apply grid lg:grid-cols-2 gap-6;
}
</style>
