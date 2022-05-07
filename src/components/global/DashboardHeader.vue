<template>
  <header
    class="p-6 flex justify-between items-center border-b-2 border-gray-100"
  >
    <div id="top">
      <router-link to="/" class="block w-40 lg:w-48">
        <GumballLogo class="w-full" />
      </router-link>
    </div>

    <div class="flex items-center space-x-8">
      <p>{{ user.user_metadata.username }}</p>

      <BaseButton @click="handleLogout" theme="secondary">
        Sign Out
      </BaseButton>
    </div>
  </header>
</template>

<script>
import useAuthUser from "@/vuetils/useAuth";

import GumballLogo from "@/components/global/GumballLogo.vue";
import BaseButton from "@/components/base/BaseButton.vue";

export default {
  components: {
    GumballLogo,
    BaseButton,
  },
  setup() {
    const { user, handleLogout } = useAuthUser();
    return {
      user,
      handleLogout,
    };
  },
};
</script>
