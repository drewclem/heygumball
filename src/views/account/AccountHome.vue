<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <BaseHeading size="h4" tag="h1">Settings</BaseHeading>

      <BaseLink to="account/edit">Edit</BaseLink>
    </div>

    <div class="flex flex-col space-y-6">account home</div>
  </div>
</template>

<script setup>
// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseLink from "@/components/base/BaseLink.vue";
</script>
