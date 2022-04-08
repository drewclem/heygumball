<template>
  <a v-if="external" :class="`cursor-pointer ${classes}`" :href="href" v-bind="$attrs">
    <slot />
  </a>

  <nuxt-link v-else :class="`cursor-pointer ${classes}`" :to="href" v-bind="$attrs">
    <slot />
  </nuxt-link>
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
      default: '',
    },
  },
};
</script>
