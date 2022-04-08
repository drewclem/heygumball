<template>
  <div class="flex flex-col">
    <label class="text-sm mb-2 font-bold text-primary-dark" :for="id">
      <slot /> <span v-if="required">*</span>
    </label>

    <input
      :id="id"
      class="border border-gray-300 rounded-md px-2 py-1"
      :type="type"
      :placeholder="placeholder"
      :required="required"
      v-on="listeners"
      @input="$emit('input', $event.target.value)"
    />
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  model: {
    prop: 'value',
    event: 'input',
  },
  props: {
    type: {
      type: String,
      default: 'text',
      validator: (value) => {
        const validProps = ['text', 'email', 'tel', 'url', 'search', 'password'];
        return validProps.includes(value);
      },
    },
    placeholder: {
      type: String,
      default: '',
    },
    required: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    id() {
      return this._uid;
    },
    listeners() {
      const { input, ...listeners } = this.$listeners;
      return listeners;
    },
  },
};
</script>
