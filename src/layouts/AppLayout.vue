<template>
  <component :is="layout">
    <slot />
  </component>
</template>

<script>
import AppLayoutDefault from "@/layouts/AppLayoutDefault.vue";
const defaultLayout = "AppLayoutDefault";

export default {
  components: {
    AppLayoutDefault,
  },
  computed: {
    layout() {
      return this.$route.meta.layout || defaultLayout;
    },
  },
};
</script>
