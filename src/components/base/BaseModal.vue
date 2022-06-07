<template>
  <button
    class="open-button"
    @click="openModal"
    ref="openButtonRef"
    type="button"
  >
    <slot name="button" />
    <Teleport v-if="isOpen" to="body">
      <div class="modal-wrapper" @keydown.esc="closeModal">
        <div class="modal-content">
          <button @click="closeModal" ref="closeButtonRef" type="button">
            Close Modal
          </button>
          <slot name="content" />
        </div>
      </div>
    </Teleport>
  </button>
</template>

<script setup>
import { ref } from "vue";
import { useGlobalLayout } from "@/stores/global";

const { toggleModal } = useGlobalLayout();
const isOpen = ref(false);
const closeButtonRef = ref(null);
const openButtonRef = ref(null);

function closeModal() {
  isOpen.value = false;
  toggleModal(false);
  setTimeout(() => {
    openButtonRef.value.focus();
  }, 50);
}

function openModal() {
  isOpen.value = true;
  toggleModal(true);
  setTimeout(() => {
    closeButtonRef.value.focus();
  }, 50);
}
</script>

<style lang="postcss" scoped>
.modal-wrapper {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: rgba(1, 1, 1, 0.25);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  @apply relative bg-white p-6 rounded-lg;
}
</style>
