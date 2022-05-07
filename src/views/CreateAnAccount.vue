<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <form @submit.prevent="handleSignup({ username, email, password })">
        <BaseHeading class="mb-5" size="h4" tag="h1">
          Create an account
        </BaseHeading>

        <div class="flex flex-col space-y-6">
          <BaseInput v-model="username" required>User Name</BaseInput>

          <BaseInput inputType="email" v-model="email" required>
            Email
          </BaseInput>

          <BaseInput inputType="password" v-model="password" required>
            Password
          </BaseInput>

          <div class="text-right">
            <BaseButton type="submit" theme="tertiary">
              Create Account
            </BaseButton>
          </div>
        </div>
      </form>

      <CreateAccount />
    </div>
  </section>
</template>

<script>
import { defineComponent, ref } from "vue";
import useAuthUser from "@/vuetils/useAuth";

import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CreateAccount from "@/components/svg/CreateAccount.vue";

export default defineComponent({
  name: "CreateAnAccount",
  components: {
    BaseButton,
    BaseHeading,
    BaseInput,
    CreateAccount,
  },
  setup() {
    const username = ref("");
    const email = ref("");
    const password = ref("");

    const { handleSignup } = useAuthUser();

    return {
      username,
      email,
      password,
      handleSignup,
    };
  },
});
</script>
