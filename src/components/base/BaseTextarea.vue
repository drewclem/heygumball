<template>
  <div class="flex flex-col">
    <label class="text-sm mb-2 font-bold text-primary-dark" :for="id">
      <slot /> <span v-if="required">*</span>
    </label>

    <textarea
      :id="id"
      class="border border-gray-300 rounded-md px-2 py-1"
      :placeholder="placeholder"
      :required="required"
      :rows="rows"
      v-on="listeners"
      @input="$emit('update:modalValue', $event.target.value)"
    />
  </div>
</template>

<script setup>
import { v4 as uuidv4 } from "uuid";

const id = uuidv4();

defineProps({
  modelValue: {
    type: String,
  },
  rows: {
    type: Number,
    default: 4,
  },
  placeholder: {
    type: String,
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["update:modelValue"]);
</script>
