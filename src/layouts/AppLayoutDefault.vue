<template>
  <div class="flex flex-col min-h-screen">
    <SiteHeader />

    <main class="flex-grow mb-12" :inert="hasOpenModal || isMobileMenuOpen">
      <slot />
    </main>

    <footer class="bg-gray-50 py-8" :inert="hasOpenModal || isMobileMenuOpen">
      <div class="flex flex-col lg:flex-row space-y-6 lg:justify-between items-center base-wrapper">
        <a href="block #top ">
          <GumballLogo class="w-56" />
        </a>

        <nav class="text-sm">
          <ul class="flex flex-row items-center">
            <router-link class="mr-4" to="/terms-and-conditions">
              Terms and Conditions
            </router-link>
            <router-link to="/privacy-policy">Privacy Policy</router-link>
          </ul>
        </nav>
      </div>
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
