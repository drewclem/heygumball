<template>
  <header
    class="base-wrapper z-50 top-0 w-full flex items-center justify-between p-4"
  >
    <div id="top">
      <router-link to="/" class="block w-40 lg:w-48">
        <GumballLogo class="w-full" />
      </router-link>
    </div>

    <nav class="hidden lg:flex font-body items-center">
      <BaseButton href="/" theme="secondary">Sign in</BaseButton>
      <BaseButton class="ml-6" href="/" theme="primary"
        >Create an Account</BaseButton
      >
    </nav>

    <div class="mobile lg:hidden">
      <button
        class="p-2"
        ref="openButtonRef"
        @click="openMobileMenu"
        type="button"
        :inert="isMobileMenuOpen"
      >
        <span class="sr-only">Open main menu</span>
        <MenuIcon class="w-8 h-8" title="Open menu" />

        <Portal to="mobile-menu">
          <div
            :class="`z-50 fixed lg:hidden inset-0 bg-black transition duration-150 ${
              isMobileMenuOpen
                ? 'bg-opacity-75'
                : 'bg-opacity-0 pointer-events-none'
            }`"
            :inert="!isMobileMenuOpen"
            @keydown.esc="closeMenu"
          >
            <div
              :class="`flex transform transition-transform duration-150 ease-in-out ${
                isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'
              }`"
            >
              <div @click="closeMenu">
                <button
                  class="flex justify-center items-center bg-white rounded-full ml-4 mr-4 mt-4 h-12 w-12"
                  ref="closeButtonRef"
                  @click="closeMenu"
                  type="button"
                >
                  <span class="sr-only">Close menu</span>
                  <CloseIcon class="h-6 w-6" alt="close menu" />
                </button>
              </div>

              <div
                class="flex flex-col justify-between h-screen w-full bg-white p-6"
              >
                <nav class="" role="navigation">
                  <ul>
                    <li>
                      <router-link to="/">Sign In</router-link>
                    </li>
                    <li>
                      <router-link to="/">Create an Account</router-link>
                    </li>
                  </ul>
                </nav>

                <router-link to="/" class="block w-1/2">
                  <GumballLogo class="w-full" />
                </router-link>
              </div>
            </div>
          </div>
        </Portal>
      </button>
    </div>
  </header>
</template>

<script>
import BaseButton from "@/components/base/BaseButton.vue";
import GumballLogo from "@/components/global/GumballLogo.vue";
// import { mapState } from "vuex";
// import { Portal } from "portal-vue";

// import MenuIcon from "~/assets/icons/menu-icon.svg?inline";
// import CloseIcon from "~/assets/icons/close-icon.svg?inline";

export default {
  components: {
    BaseButton,
    GumballLogo,
  },
  computed: {
    // ...mapState("global", ["isMobileMenuOpen", "pageHasModalOpen", "loaded"]),
    isHomePage() {
      return this.$route.fullPath === "/";
    },
  },
  watch: {
    // $route(to, from) {
    //   this.closeMenu();
    // },
  },
  methods: {
    async openMobileMenu() {
      await this.$store.commit("global/isMobileMenuOpen", true);
      await this.$nextTick();
      await this.$nextTick();

      this.$refs.closeButtonRef?.focus();
    },
    async closeMenu() {
      await this.$store.commit("global/isMobileMenuOpen", false);
      await this.$nextTick();
      await this.$nextTick();
    },
  },
};
</script>

<style lang="postcss">
nav ul li div a {
  @apply tracking-wider;
}

.nuxt-link-exact-active {
  @apply opacity-100;
}
</style>
