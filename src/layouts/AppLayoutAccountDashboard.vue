<template>
  <div>
    <DashboardHeader />

    <main class="flex">
      <div class="flex flex-col px-6 py-12">
        <div class="flex-grow">
          <div class="flex flex-col space-y-5 mb-12">
            <BaseButton theme="tertiary">Schedule Window</BaseButton>
            <BaseButton theme="secondary">Open Requests</BaseButton>
          </div>

          <nav>
            <ul class="flex flex-col space-y-3">
              <li><router-link to="/">Collections</router-link></li>
              <li><router-link to="/">Saved</router-link></li>
              <li><router-link to="/">Request Form</router-link></li>
            </ul>
          </nav>
        </div>

        <footer class="flex flex-col space-y-3">
          <router-link :to="`/account/${user.user_metadata.username}`">
            Account
          </router-link>

          <div>
            <button>Sign out</button>
          </div>
        </footer>
      </div>

      <div class="py-12 px-20 bg-gray-50 flex-grow">
        <slot />
      </div>
    </main>
  </div>
</template>

<script>
import useAuthUser from "@/vuetils/useAuth";

import DashboardHeader from "@/components/global/DashboardHeader.vue";
import BaseButton from "@/components/base/BaseButton.vue";

export default {
  components: { DashboardHeader, BaseButton },
  setup() {
    const { user, handleLogout } = useAuthUser();
    return { user, handleLogout };
  },
};
</script>

<style scoped>
main {
  height: calc(100vh - 89.31px);
}
</style>
