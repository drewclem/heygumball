<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <BaseHeading size="h4" tag="h1">Settings</BaseHeading>

      <BaseLink to="account/edit">Edit</BaseLink>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-10">
      <div class="lg:col-span-2">
        <div class="h-24 w-24 bg-gray-400 rounded-full"></div>
      </div>
      <div class="lg:col-span-8">
        <div class="info-group info-list">
          <div>
            <h2 class="info-heading">Username</h2>
            <p>{{ user.username }}</p>
          </div>

          <div>
            <h2 class="info-heading">Full Name</h2>
            <p>{{ user.full_name }}</p>
          </div>

          <div>
            <h2 class="info-heading">Email</h2>
            <p v-if="user.email !== null">{{ user.email }}</p>
            <p v-else>--</p>
          </div>
        </div>

        <div class="info-group info-grid">
          <div>
            <h2 class="info-heading">Instagram</h2>
            <p v-if="user.instagram_url !== null">{{ user.instagram_url }}</p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">TikTok</h2>
            <p v-if="user.tiktok_url !== null">{{ user.tiktoko_url }}</p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">Twitter</h2>
            <p v-if="user.twitter_url !== null">{{ user.twitter }}</p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">Facebook</h2>
            <p v-if="user.facebook_url !== null">{{ user.facebook }}</p>
            <p v-else>--</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utility
import { useUserStore } from "@/stores/user";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseLink from "@/components/base/BaseLink.vue";

const { user } = useUserStore();
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
