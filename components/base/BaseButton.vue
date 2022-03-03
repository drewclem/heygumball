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

  &.btn-primary {
    @apply bg-secondary-light text-white;

    &:hover {
      @apply bg-secondary-dark;
    }
  }

  &.btn-secondary {
    @apply bg-primary-dark text-white;

    &:hover {
      @apply bg-primary-darker;
    }
  }

  &.btn-outline {
    @apply border-2 border-primary-dark text-primary-dark;

    &:hover {
      @apply bg-primary-light text-white;
    }
  }
}
</style>
