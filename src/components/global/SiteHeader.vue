<template>
  <header
    class="base-wrapper z-50 top-0 w-full flex items-center justify-between p-6"
  >
    <div id="top" :inert="isOpen">
      <router-link to="/" class="block w-40 lg:w-48">
        <GumballLogo class="w-full" />
      </router-link>
    </div>

    <nav class="hidden lg:flex font-body items-center">
      <div class="flex flex-col items-end">
        <div>
          <router-link
            v-if="user"
            :to="`/${user.user_metadata.username}/collections`"
          >
            {{ user.user_metadata.username }}
          </router-link>

          <div v-else>
            <BaseButton href="/sign-in" theme="secondary"> Sign in </BaseButton>

            <BaseButton class="ml-6" href="/create-an-account" theme="primary">
              Create an Account
            </BaseButton>
          </div>
        </div>

        <button
          v-if="user"
          class="mt-2 text-xs opacity-75 hover:opacity-100"
          @click="handleLogout"
        >
          Log Out
        </button>
      </div>
    </nav>

    <div class="mobile lg:hidden">
      <button
        class="p-2"
        ref="openButtonRef"
        @click="openMenu"
        type="button"
        :inert="isOpen"
        aria-label="Open menu"
      >
        <MenuIcon class="w-8 h-8" title="Open menu" />

        <Teleport to="body">
          <div
            :class="`z-50 fixed lg:hidden inset-0 bg-black transition duration-150 ${
              isOpen ? 'bg-opacity-50' : 'bg-opacity-0 pointer-events-none'
            }`"
            :inert="!isOpen"
            @keydown.esc="closeMenu"
          >
            <div
              :class="`flex transform transition-transform duration-150 ease-in-out ${
                isOpen ? 'translate-x-0' : 'translate-x-full'
              }`"
            >
              <div @click="closeMenu">
                <button
                  class="flex justify-center items-center bg-white rounded-full ml-4 mr-4 mt-4 h-12 w-12"
                  ref="closeButtonRef"
                  @click="closeMenu"
                  type="button"
                  aria-label="Close menu"
                >
                  <IconClose class="h-6 w-6" alt="close menu" />
                </button>
              </div>

              <div class="h-screen w-full bg-white">
                <div class="flex flex-col h-full">
                  <div v-if="!user">
                    <nav role="navigation">
                      <ul class="nav">
                        <li>
                          <router-link to="/sign-in">Sign In</router-link>
                        </li>
                        <li>
                          <router-link to="/create-an-account">
                            Create an Account
                          </router-link>
                        </li>
                        <li>
                          <router-link to="/terms-and-conditions">
                            Terms and Conditions
                          </router-link>
                        </li>
                        <li>
                          <router-link to="/privacy-policy">
                            Privacy Policy
                          </router-link>
                        </li>
                      </ul>
                    </nav>

                    <router-link to="/" class="p-6 block w-3/4">
                      <GumballLogo class="w-full" />
                    </router-link>
                  </div>
                  <MobileMenuUser v-else :user="user" :currentUser="user" />
                </div>
              </div>
            </div>
          </div>
        </Teleport>
      </button>
    </div>
  </header>
</template>

<script setup>
import { defineComponent, ref, watch, computed } from "vue";
import { useRoute } from "vue-router";
import useAuthUser from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";
import { useGlobalLayout } from "@/stores/global";
import "wicg-inert";

import BaseButton from "@/components/base/BaseButton.vue";
import GumballLogo from "@/components/global/GumballLogo.vue";
import MobileMenuUser from "@/components/global/MobileMenuUser.vue";

import MenuIcon from "@/components/icons/MenuIcon.vue";
import IconClose from "@/components/svg/IconClose.vue";

const openButtonRef = ref(null);
const closeButtonRef = ref(null);
const isOpen = ref(false);
const route = useRoute();

const { user, handleLogout } = useAuthUser();
const { hasOpenModal, toggleMobileMenu } = useGlobalLayout();
const { currentUser } = useUserStore();

const path = computed(() => {
  return route.path;
});

watch(path, (newPath, oldPath) => {
  if (newPath) isOpen.value = false;
  toggleMobileMenu(false);
});

function openMenu() {
  isOpen.value = true;
  toggleMobileMenu(true);

  setTimeout(() => {
    closeButtonRef?.value.focus();
  }, 50);
}
function closeMenu() {
  isOpen.value = false;
  toggleMobileMenu(false);

  setTimeout(() => {
    openButtonRef?.value.focus();
  }, 50);
}
</script>

<style lang="postcss" scoped>
nav ul li div a {
  @apply tracking-wider;
}

.nuxt-link-exact-active {
  @apply opacity-100;
}

.nav li a {
  @apply p-6 border-b border-gray-100 block;
}
</style>
