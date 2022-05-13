<template>
  <router-link
    :class="`btn btn-${theme} group ${$slots.icon ? 'flex items-center' : ''}`"
    v-if="href"
    :to="href"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-3' : ''">
      <slot name="icon" />
    </span>

    <slot />
  </router-link>

  <button
    v-else
    :class="`btn btn-${theme} group ${$slots.icon ? 'flex items-center' : ''}`"
    :type="type"
    @click="$emit('click')"
  >
    <span v-if="$slots.icon" :class="$slots.icon ? 'mr-3 ' : ''">
      <slot name="icon" />
    </span>

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
    href: {
      type: String,
      required: false,
    },
  },
};
</script>

<style lang="postcss" scoped>
.btn {
  @apply px-3 py-0.5 font-display text-center rounded-md border-2 border-transparent transition duration-150 ease-in-out;
}
.btn-primary {
  @apply bg-red-500 text-white;
}

.btn-primary:hover {
  @apply bg-red-600;
}

.btn-secondary {
  @apply border-blue-500 text-black;
}

.btn-secondary:hover {
  @apply bg-blue-500 bg-opacity-10;
}

.btn-tertiary {
  @apply bg-green-500 text-white;
}

.btn-tertiary:hover {
  @apply bg-green-600;
}

@screen lg {
  .btn {
    @apply px-6;
  }
}
</style>
