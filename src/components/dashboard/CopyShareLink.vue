<template>
  <div class="flex items-center">
    <p id="copy" class="font-bold text-green-500 mr-4">
      https://heygumball.com/{{ user.user_metadata.username }}
    </p>
    <button
      class="text-xs opacity-50 hover:opacity-100"
      type="button"
      @click="copyText"
    >
      <span v-if="copySuccess">Copied!</span>
      <div v-else>
        <IconCopy class="h-4 w-4 -mt-0.5" />
        <span class="sr-only">Copy to clipboard</span>
      </div>
    </button>
  </div>
</template>

<script setup>
import { ref } from "vue";
import useAuthUser from "@/utils/useAuth";

import IconCopy from "@/components/svg/IconCopy.vue";

const copySuccess = ref(false);
const { user } = useAuthUser();

function copyText() {
  const text = document.getElementById("copy");

  navigator.clipboard.writeText(text.innerText);

  copySuccess.value = true;

  setTimeout(() => {
    copySuccess.value = false;
  }, 3000);
}
</script>
