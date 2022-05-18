<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <BaseHeading size="h4" tag="h1">Settings</BaseHeading>
    </div>

    <div class="flex flex-col space-y-6">
      <BaseInput v-model="username" disabled>User Name</BaseInput>

      <BaseInput inputType="email" v-model="email" required> Email </BaseInput>
    </div>
  </div>
</template>

<script setup>
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
</script>
