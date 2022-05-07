<template>
  <component :is="layout">
    <slot />
  </component>
</template>

<script>
import AppLayoutDefault from "@/layouts/AppLayoutDefault.vue";
import AppLayoutAccountDashboard from "@/layouts/AppLayoutAccountDashboard.vue";

const defaultLayout = "AppLayoutDefault";

export default {
  components: {
    AppLayoutDefault,
    AppLayoutAccountDashboard,
  },
  computed: {
    layout() {
      return this.$route.meta.layout || defaultLayout;
    },
  },
};
</script>
