<template>
  <Transition name="fade">
    <component :is="layout">
      <slot />
    </component>
  </Transition>
</template>

<script>
import AppLayoutDefault from "@/layouts/AppLayoutDefault.vue";
import AppLayoutAccountDashboard from "@/layouts/AppLayoutAccountDashboard.vue";
import AppLayoutAccountForm from "@/layouts/AppLayoutAccountForm.vue";

const defaultLayout = "AppLayoutDefault";

export default {
  components: {
    AppLayoutDefault,
    AppLayoutAccountDashboard,
    AppLayoutAccountForm,
  },
  computed: {
    layout() {
      return this.$route.meta.layout || defaultLayout;
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 300ms ease-in-out;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
