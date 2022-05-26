<template>
  <div>
    <DashboardHeader :user="user" />

    <main class="flex">
      <div class="flex flex-col px-6 py-12">
        <div class="flex-grow">
          <div class="flex flex-col space-y-5 mb-12">
            <BaseButton theme="tertiary">
              <template #icon>
                <IconSchedule class="text-white w-5 h-5 opacity-75" />
              </template>
              Schedule Window
            </BaseButton>

            <BaseButton class="group" theme="secondary">
              <template #icon>
                <IconLock
                  class="text-blue-500 group-hover:text-white w-5 h-5 opacity-50"
                />
              </template>
              Open Requests
            </BaseButton>
          </div>

          <nav>
            <ul class="flex flex-col space-y-3">
              <li>
                <BaseLink
                  class="group"
                  :to="`/${user.user_metadata.username}/collections`"
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
                <BaseLink class="group" 
                  :to="`/${user.user_metadata.username}/saved`">
                  <template #icon>
                    <IconHeart
                      class="text-gray-200 group-hover:text-gray-300 w-5 h-5"
                    />
                  </template>
                  Saved
                </BaseLink>
              </li>
              <li>
                <BaseLink class="group" :to="`/${user.user_metadata.username}`">
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
            :to="`/${user.user_metadata.username}/account`"
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

      <div class="py-12 px-20 bg-gray-50 flex-grow">
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

//components
import DashboardHeader from "@/components/global/DashboardHeader.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseLink from "@/components/base/BaseLink.vue";
import IconSchedule from "@/components/svg/IconSchedule.vue";
import IconLock from "@/components/svg/IconLock.vue";
import IconCollection from "@/components/svg/IconCollection";
import IconHeart from "@/components/svg/IconHeart.vue";
import IconForm from "@/components/svg/IconForm.vue";
import IconUser from "@/components/svg/IconUser.vue";

const { user, handleLogout } = useAuthUser();

const { setCurrentUserId, setCollections, setSavedSubmissions } = useUserStore();

setCurrentUserId(user._rawValue.id);

// fetch collections for user and set in pinia
setCollections();
setSavedSubmissions();
</script>

<style scoped>
main {
  height: calc(100vh - 89.31px);
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
