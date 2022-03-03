<template>
  <nuxt-link :class="`btn btn-${theme}`" v-if="href" :to="href">
    <slot />
  </nuxt-link>

  <button v-else :class="`btn btn-${theme}`" :type="type" @click="$emit('click')">
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'button',
    },
    theme: {
      type: String,
      default: 'primary',
    },
    href: {
      type: String,
      required: false,
    },
  },
};
</script>

<style lang="postcss" scoped>
.btn {
  @apply uppercase px-8 py-2 font-semibold text-center;

}
</style>
