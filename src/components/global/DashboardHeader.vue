<template>
  <header
    class="px-6 py-3 lg:p-6 flex justify-between w-full items-center border-b-2 border-gray-100"
  >
    <div id="top">
      <router-link
        v-if="currentUser"
        :to="`/${currentUser.username}/collections`"
        class="block w-40 lg:w-48"
        :inert="hasOpenModal || isMobileMenuOpen"
      >
        <GumballLogo class="w-full" />
      </router-link>
    </div>

    <!-- Mobile nav -->
    <div class="mobile lg:hidden">
      <button
        class="py-2"
        ref="openButtonRef"
        @click="openMenu"
        type="button"
        aria-label="Open menu"
        :inert="hasOpenModal || isMobileMenuOpen"
      >
        <MenuIcon class="w-8 h-8" title="Open Menu" />

        <Teleport to="body">
          <div
            :class="`z-50 fixed lg:hidden inset-0 bg-black transition duration-150 ${
              isMobileMenuOpen
                ? 'bg-opacity-50'
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
                  aria-label="Close menu"
                >
                  <IconClose class="h-6 w-6" alt="close menu" />
                </button>
              </div>

              <div class="flex flex-col h-screen w-full bg-white">
                <MobileMenuUser :user="user" :currentUser="currentUser" />
              </div>
            </div>
          </div>
        </Teleport>
      </button>
    </div>

    <!-- Desktop nav -->
    <div class="hidden lg:flex items-center space-x-6">
      <p>{{ user.user_metadata.username }}</p>

      <BaseButton
        class="text-sm lg:text-base"
        @user-click="handleLogout"
        theme="secondary"
      >
        Sign Out
      </BaseButton>
    </div>
  </header>
</template>

<script setup>
import { computed, reactive, ref, watch } from "vue";
import useAuthUser from "@/utils/useAuth";
import { useGlobalLayout } from "@/stores/global";
import { useRoute } from "vue-router";
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";

import GumballLogo from "@/components/global/GumballLogo.vue";
import GumballLogoMark from "@/components/global/GumballLogoMark.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseLink from "@/components/base/BaseLink.vue";
import BaseModal from "@/components/base/BaseModal.vue";
import MobileMenuUser from "@/components/global/MobileMenuUser.vue";

import IconCollection from "@/components/svg/IconCollection.vue";
import IconSchedule from "@/components/svg/IconSchedule.vue";
import IconInbox from "@/components/svg/IconInbox.vue";
import MenuIcon from "@/components/icons/MenuIcon.vue";
import IconClose from "@/components/svg/IconClose.vue";
import IconHeart from "@/components/svg/IconHeart.vue";
import IconForm from "@/components/svg/IconForm.vue";
import IconUser from "@/components/svg/IconUser.vue";
import IconLock from "@/components/svg/IconLock.vue";

defineProps({
  user: {
    type: Object,
    required: true,
  },
});

/**
 * Init refs and state
 */
const openButtonRef = ref(null);
const closeButtonRef = ref(null);

const isOpen = computed(() => {
  return isMobileMenuOpen;
});

/**
 * Watch for route change to close menu
 */
const route = useRoute();
const path = computed(() => {
  return route.path;
});

watch(path, (newPath, oldPath) => {
  toggleMobileMenu(false);
});

const userStore = useUserStore();
const { currentUser } = storeToRefs(userStore);
const { user, handleLogout } = useAuthUser();
const { toggleMobileMenu } = useGlobalLayout();
const global = useGlobalLayout();
const { isMobileMenuOpen, hasOpenModal } = storeToRefs(global);

function openMenu() {
  toggleMobileMenu(true);

  setTimeout(() => {
    closeButtonRef?.value.focus();
  }, 50);
}

function closeMenu() {
  toggleMobileMenu(false);

  setTimeout(() => {
    openButtonRef?.value.focus();
  }, 50);
}
</script>
