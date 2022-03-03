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
  }
</script>

<style lang="postcss" scoped>
  .btn {
    @apply px-3 py-0.5 font-semibold text-center rounded border-2 border-transparent;

    &.btn-primary {
      @apply bg-red-500 text-white;
    }

    &.btn-secondary {
      @apply border-blue-500 text-blue-500;
    }
  }

  @screen lg {
    .btn {
      @apply px-6 py-1;
    }
  }
</style>
