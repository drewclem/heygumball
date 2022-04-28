<template>
  <div class="flex flex-col">
    <label class="font-display text-sm mb-2 font-bold" :for="id">
      <slot /> <span v-if="required">*</span>
    </label>

    <input
      :id="id"
      class="border border-gray-300 rounded-md px-2 py-1"
      :type="inputType"
      :placeholder="placeholder"
      :required="required"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
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
  inputType: {
    type: String,
    default: "text",
    validator: (value) => {
      const validProps = ["text", "email", "tel", "url", "search", "password"];
      return validProps.includes(value);
    },
    placeholder: {
      type: String,
      default: "",
    },
    required: {
      type: Boolean,
      default: false,
    },
  },
});
defineEmits(["update:modelValue"]);
</script>
