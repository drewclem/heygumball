<template>
  <div class="flex flex-col min-h-screen">
    <SiteHeader />

    <main class="flex-grow mb-12">
      <slot />
    </main>

    <footer class="bg-gray-50 py-8">
      <a href="#top">
        <GumballLogo class="w-56 mx-auto" />
      </a>
    </footer>
  </div>
</template>

<script>
import SiteHeader from "@/components/global/SiteHeader";
import GumballLogo from "@/components/global/GumballLogo.vue";

export default {
  components: {
    GumballLogo,
    SiteHeader,
  },
};
</script>
