<template>
  <button :class="`btn btn-${theme}`" :type="type" @click="$emit('click')">
    <slot />
  </button>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "button",
    },
    theme: {
      type: String,
      default: "primary",
    },
  },
};
</script>

<style lang="postcss" scoped>
.btn {
  @apply px-4 py-1 rounded-md shadow transform ease-in-out duration-150 border-2 border-transparent;

  &.btn-primary {
    @apply bg-red-600 text-white;

    &:hover {
      @apply bg-red-700;
    }
  }

  &.btn-secondary {
    @apply bg-blue-700 text-white;

    &:hover {
      @apply bg-blue-800;
    }
  }

  &.btn-tertiary {
    @apply border-blue-700 text-blue-700;

    &:hover {
      @apply bg-blue-700 bg-opacity-75 text-white;
    }
  }
}
</style>
