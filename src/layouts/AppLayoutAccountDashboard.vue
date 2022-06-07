<template>
  <div class="here" :inert="hasOpenModal">
    <DashboardHeader :user="user" />

    <main class="flex">
      <div class="flex flex-col px-6 py-12">
        <div class="flex-grow">
          <div class="flex flex-col space-y-5 mb-12">
            <BaseModal>
              <template #button>
                <div
                  class="flex items-center group px-3 lg:px-6 py-0.5 font-display text-center rounded-md transition duration-150 text-white ease-in-out bg-green-500 hover:bg-green-600 border-2 border-transparent"
                >
                  <IconSchedule class="text-white w-5 h-5 opacity-75 mr-3" />
                  Schedule window
                </div>
              </template>

              <template #content>
                <p>Schedule window</p>
              </template>
            </BaseModal>

            <BaseModal>
              <template #button>
                <div
                  class="flex items-center group px-3 lg:px-6 py-0.5 font-display text-center rounded-md border-2 border-transparent transition duration-150 ease-in-out border-blue-500 text-black hover:bg-blue-500 hover:text-white"
                >
                  <IconLock
                    class="text-blue-500 group-hover:text-white w-5 h-5 opacity-50 mr-3"
                  />
                  Open Requests
                </div>
              </template>

              <template #content>
                <p>hello</p>
              </template>
            </BaseModal>
          </div>

          <nav>
            <ul class="flex flex-col space-y-3">
              <li>
                <BaseLink
                  class="group"
                  :class="{
                    'router-link-active':
                      $route.fullPath.includes('collections'),
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
                    'router-link-active': $route.fullPath.includes('inbox'),
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
                  Request Form
                </BaseLink>
              </li>
            </ul>
          </nav>
        </div>

        <footer class="flex flex-col space-y-3">
          <BaseLink
            class="group"
            :class="{
              'router-link-active': $route.fullPath.includes('account'),
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

          <div>
            <button @click="handleLogout" type="button">Sign out</button>
          </div>
        </footer>
      </div>

      <div class="py-12 px-20 bg-gray-50 flex-grow overflow-y-scroll">
        <Transition name="fade">
          <slot />
        </Transition>
      </div>
    </main>
  </div>
</template>

<script setup>
// utils
import useAuthUser from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";
import { useGlobalLayout } from "@/stores/global";
import { storeToRefs } from "pinia";
import "wicg-inert";

// components
import DashboardHeader from "@/components/global/DashboardHeader.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseLink from "@/components/base/BaseLink.vue";
import BaseModal from "@/components/base/BaseModal.vue";

// icons
import IconSchedule from "@/components/svg/IconSchedule.vue";
import IconLock from "@/components/svg/IconLock.vue";
import IconCollection from "@/components/svg/IconCollection";
import IconHeart from "@/components/svg/IconHeart.vue";
import IconForm from "@/components/svg/IconForm.vue";
import IconUser from "@/components/svg/IconUser.vue";
import IconInbox from "@/components/svg/IconInbox.vue";

const { user, handleLogout } = useAuthUser();

const {
  setCurrentUserId,
  setUser,
  setCollections,
  setSavedSubmissions,
  setAllSubmissions,
} = useUserStore();

const global = useGlobalLayout();
const { hasOpenModal } = storeToRefs(global);

setCurrentUserId(user.value.id);

// fetch collections for user and set in pinia
setCollections();
setSavedSubmissions();
setAllSubmissions();

/**
 * Create open collection
 */

function createOpenCollection() {}
</script>

<style scoped>
main {
  height: calc(100vh - 89.31px);
  overflow: hidden;
}

.router-link-active svg {
  @apply text-green-500;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 300ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
