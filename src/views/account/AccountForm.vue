<template>
  <div class="relative">
    <div class="absolute bg-gray-100 top-0 w-full h-56" />

    <div class="relative max-w-lg mx-auto">
      <div class="flex justify-between items-center py-12 px-6 lg:p-11">
        <BaseHeading size="h4" tag="h1">
          Contact <span class="text-red-500">John Doe</span>
        </BaseHeading>

        <div class="h-12 w-12 bg-gray-400 rounded-full"></div>
      </div>

      <form
        class="card-shadow bg-white rounded-xl mx-6 lg:mx-0 p-6 lg:p-11 flex flex-col gap-6"
      >
        <BaseInput required>Full Name</BaseInput>
        <BaseInput type="email" required>Email</BaseInput>
        <BaseInput>Phone</BaseInput>
        <BaseTextarea rows="8" required>Message</BaseTextarea>

        <div class="ml-auto">
          <BaseButton theme="tertiary">Send</BaseButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseButton from "@/components/base/BaseButton.vue";
</script>
