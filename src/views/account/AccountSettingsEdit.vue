<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <BaseHeading size="h4" tag="h1">Settings</BaseHeading>

      <div>
        <BaseLink
          :to="`/${user.user_metadata.username}/account`"
          class="text-red-500 mr-5 underline"
          >Cancel</BaseLink
        >
        <button class="underline">Save Changes</button>
      </div>
    </div>

    <div class="flex flex-col space-y-6">
      <BaseInput v-model="username" disabled>User Name</BaseInput>

      <BaseInput inputType="email" v-model="email" required> Email </BaseInput>
    </div>
  </div>
</template>

<script setup>
import useAuthUser from "@/vuetils/useAuth";
import BaseLink from "@/components/base/BaseLink.vue";
import BaseHeading from "@/components/base/BaseHeading.vue";

const { user } = useAuthUser();
</script>
