<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <form @submit.prevent="handleLogin({ email, password })">
        <BaseHeading class="mb-5" size="h4" tag="h1">Welcome back!</BaseHeading>

        <div class="flex flex-col space-y-6">
          <BaseInput inputType="email" v-model="email">Email</BaseInput>

          <BaseInput inputType="password" v-model="password">
            Password
          </BaseInput>

          <div class="text-right">
            <BaseButton type="submit" theme="tertiary">Sign In</BaseButton>
          </div>
        </div>
      </form>

      <WelcomeBack />
    </div>
  </section>
</template>

<script>
import { defineComponent, ref } from "vue";
import useAuthUser from "@/vuetils/useAuth";

import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import WelcomeBack from "@/components/svg/WelcomeBack.vue";

export default defineComponent({
  name: "SignIn",
  components: {
    BaseButton,
    BaseHeading,
    BaseInput,
    WelcomeBack,
  },
  setup() {
    const email = ref("");
    const password = ref("");

    const { handleLogin } = useAuthUser();

    return {
      email,
      password,
      handleLogin,
    };
  },
});
</script>
