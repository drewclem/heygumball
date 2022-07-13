<template>
  <div class="relative flex flex-col">
    <label class="absolute text-sm -mt-5 ml-3 text-gray-500" :for="id">
      <slot>Select an option</slot> <span v-if="isRequired">*</span>
    </label>

    <select
      :id="id"
      class="border border-gray-300 rounded-md px-2 py-1"
      :required="isRequired"
      :disabled="disabled"
      @input="$emit('update:modelValue', $event.target.value)"
    >
      <option :value="null" selected>Select</option>

      <option v-for="option in options" :key="option.id" :value="option.label">
        {{ option.label }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { v4 as uuidv4 } from "uuid";

const id = uuidv4();

defineEmits(["update:modelValue"]);

const props = defineProps({
  modelValue: {
    type: String,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  isRequired: {
    type: Boolean,
    default: false,
  },
  options: {
    type: Array,
    default: () => [],
  },
});
</script>
