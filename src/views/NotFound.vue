<template>
  <section
    class="max-w-3xl mx-auto px-6 xl:px-0 flex flex-col items-center justify-center"
  >
    <div class="text-center">
      <BaseHeading class="text-red-500" size="h1">Uh oh!</BaseHeading>
      <BaseText>Looks like we can't find that route. Try another one!</BaseText>
    </div>

    <NotFoundGraphic class="w-1/2" />
  </section>
</template>

<script setup>
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import NotFoundGraphic from "@/components/svg/NotFoundGraphic.vue";
</script>
