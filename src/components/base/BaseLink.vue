<template>
  <a
    v-if="external"
    :class="`link cursor-pointer ${classes}`"
    :href="href"
    v-bind="$attrs"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-3' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </a>

  <router-link
    v-else
    :class="`link cursor-pointer ${classes}`"
    :to="href"
    v-bind="$attrs"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-3' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </router-link>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    href: {
      type: String,
      required: true,
    },
    external: {
      type: Boolean,
      default: false,
    },
    classes: {
      type: String,
      default: "",
    },
  },
};
</script>

<style scoped>
.link {
  @apply flex items-center;
}
</style>
