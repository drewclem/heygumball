<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <form @submit.prevent="handleFormSubmission">
        <BaseHeading class="mb-5" size="h4" tag="h1">Welcome back!</BaseHeading>

        <div class="flex flex-col space-y-6">
          <div class="relative" :class="{ error: v$.email.$errors.length }">
            <BaseInput v-model="state.email"> Email </BaseInput>
            <div
              class="input-errors"
              v-for="error of v$.email.$errors"
              :key="error.$uid"
            >
              <p class="error-msg absolute text-xs text-red-500 mt-1">
                {{ error.$message }}
              </p>
            </div>
          </div>

          <div class="relative" :class="{ error: v$.password.$errors.length }">
            <BaseInput inputType="password" v-model="state.password">
              Password
            </BaseInput>
            <div
              class="input-errors"
              v-for="error of v$.password.$errors"
              :key="error.$uid"
            >
              <p class="error-msg absolute text-xs text-red-500 mt-1">
                {{ error.$message }}
              </p>
            </div>
          </div>

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
// utils
import { defineComponent, reactive } from "vue";
import useAuthUser from "@/utils/useAuth";

// vuelidate
import useVuelidate from "@vuelidate/core";
import { required, email, helpers } from "@vuelidate/validators";
import { useValidate } from "@/utils/validate";

//components
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
    const state = reactive({
      email: "",
      password: "",
    });

    const { handleLogin } = useAuthUser();
    const { notEmpty } = useValidate();

    const rules = {
      email: {
        required: helpers.withMessage(notEmpty, required),
        email: helpers.withMessage(
          "This field must contain a valid email address",
          email
        ),
      },
      password: {
        required: helpers.withMessage(notEmpty, required),
      },
    };

    const v$ = useVuelidate(rules, state);

    return { state, handleLogin, v$ };
  },
  methods: {
    async handleFormSubmission() {
      const isFormValid = await this.v$.$validate();

      if (!isFormValid) return;

      this.handleLogin(this.state);
    },
  },
});
</script>
