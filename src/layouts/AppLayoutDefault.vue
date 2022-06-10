<template>
  <div class="flex flex-col min-h-screen">
    <SiteHeader />

    <main class="flex-grow mb-12" :inert="hasOpenModal || isMobileMenuOpen">
      <slot />
    </main>

    <footer class="bg-gray-50 py-8" :inert="hasOpenModal || isMobileMenuOpen">
      <a href="#top">
        <GumballLogo class="w-56 mx-auto" />
      </a>
    </footer>
  </div>
</template>

<script setup>
// utils
import { ref } from "vue";
import { useGlobalLayout } from "@/stores/global";
import { storeToRefs } from "pinia";
import "wicg-inert";

// components
import SiteHeader from "@/components/global/SiteHeader";
import GumballLogo from "@/components/global/GumballLogo.vue";

const global = useGlobalLayout();
const { hasOpenModal } = storeToRefs(global);
const { isMobileMenuOpen } = storeToRefs(global);
</script>
