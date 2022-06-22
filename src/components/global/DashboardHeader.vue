<template>
  <header
    class="px-6 py-3 lg:p-6 flex justify-between w-full items-center border-b-2 border-gray-100"
  >
    <div id="top">
      <router-link
        :to="`/${currentUser.username}/collections`"
        class="block w-40 lg:w-48"
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

              <div
                class="flex flex-col justify-between h-screen w-full bg-white"
              >
                <nav class="" role="navigation">
                  <ul class="mobile-nav">
                    <li>
                      <BaseLink
                        class="group"
                        :class="{
                          'router-link-active':
                            route.fullPath.includes('collections'),
                        }"
                        :href="`/${user.user_metadata.username}/collections`"
                      >
                        <template #icon>
                          <IconCollection
                            class="text-gray-200 group-hover:text-gray-300 w-5 h-5"
                          />
                        </template>
                        Collections
                      </BaseLink>
                    </li>
                    <li>
                      <BaseLink
                        class="group"
                        :class="{
                          'router-link-active':
                            route.fullPath.includes('inbox'),
                        }"
                        :href="`/${user.user_metadata.username}/inbox`"
                      >
                        <template #icon>
                          <IconInbox
                            class="text-gray-200 group-hover:text-gray-300 w-5 h-5"
                          />
                        </template>
                        Inbox
                      </BaseLink>
                    </li>
                    <li>
                      <BaseLink
                        class="group"
                        :class="{
                          'router-link-active':
                            route.fullPath.includes('saved'),
                        }"
                        :href="`/${user.user_metadata.username}/saved`"
                      >
                        <template #icon>
                          <IconHeart
                            class="text-gray-200 group-hover:text-gray-300 w-5 h-5"
                          />
                        </template>
                        Saved
                      </BaseLink>
                    </li>
                    <li>
                      <BaseLink
                        class="group"
                        :href="`/${user.user_metadata.username}`"
                      >
                        <template #icon>
                          <IconForm
                            class="text-gray-200 group-hover:text-gray-300 w-5 h-5"
                          />
                        </template>
                        Form
                      </BaseLink>
                    </li>
                  </ul>
                </nav>

                <div>
                  <ul>
                    <li>
                      <BaseModal
                        class="px-6 py-3"
                        :disabled="!currentUser.subscription_active"
                      >
                        <template #button>
                          <div
                            class="flex items-center group px-3 lg:px-6 py-0.5 font-display text-center rounded-md transition duration-150 text-white ease-in-out bg-green-500 hover:bg-green-600 border-2 border-transparent w-full"
                          >
                            <IconSchedule
                              class="text-white w-5 h-5 opacity-75 mr-2"
                            />
                            <span>Schedule Collection </span>
                          </div>
                        </template>

                        <template #content>
                          <ScheduleCollection />
                        </template>
                      </BaseModal>
                    </li>
                    <li>
                      <BaseModal
                        class="px-6 py-3"
                        :disabled="
                          hasActiveCollection ||
                          !currentUser.subscription_active
                        "
                      >
                        <template #button>
                          <div
                            class="flex items-center group px-3 lg:px-6 py-0.5 font-display text-center rounded-md border-2 border-transparent transition duration-150 ease-in-out border-blue-500 text-black hover:bg-blue-500 hover:text-white mb-2"
                          >
                            <IconLock
                              class="text-blue-500 group-hover:text-white w-5 h-5 opacity-50 mr-2"
                            />
                            <span> Open Collection</span>
                          </div>
                        </template>

                        <template #content>
                          <OpenCollection />
                        </template>
                      </BaseModal>
                    </li>
                    <li>
                      <BaseLink
                        class="px-6 py-3 group"
                        :class="{
                          'router-link-active':
                            $route.fullPath.includes('account'),
                        }"
                        :href="`/${user.user_metadata.username}/account`"
                      >
                        <template #icon>
                          <IconUser
                            class="text-gray-200 group-hover:text-gray-300 w-5 h-5"
                          />
                        </template>
                        Account
                      </BaseLink>
                    </li>

                    <li>
                      <button
                        class="px-6 py-3"
                        @click="handleLogout"
                        type="button"
                      >
                        Sign out
                      </button>
                    </li>
                  </ul>
                </div>
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
import ScheduleCollection from "@/components/dashboard/ScheduleCollection.vue";
import OpenCollection from "@/components/dashboard/OpenCollection.vue";

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

const state = reactive({
  isOpen: false,
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
const { currentUser, hasActiveCollection } = storeToRefs(userStore);
const { user, handleLogout } = useAuthUser();
const { toggleMobileMenu } = useGlobalLayout();
const global = useGlobalLayout();
const { isMobileMenuOpen } = storeToRefs(global);

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

<style scoped>
.router-link-active svg {
  @apply text-green-500;
}

.mobile-nav li a {
  @apply p-6 border-b border-gray-100;
}
</style>
