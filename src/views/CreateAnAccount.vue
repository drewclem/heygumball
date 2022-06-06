<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <form @submit.prevent="handleFormSubmission">
        <BaseHeading class="mb-5" size="h4" tag="h1">
          Create an account
        </BaseHeading>

        <div class="flex flex-col space-y-8">
          <div class="relative" :class="{ error: v$.username.$errors.length }">
            <BaseInput v-model="state.username"> User Name </BaseInput>
            <div
              class="input-errors"
              v-for="error of v$.username.$errors"
              :key="error.$uid"
            >
              <p class="error-msg absolute text-xs text-red-500 mt-1">
                {{ error.$message }}
              </p>
            </div>
          </div>

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
// utils
import { ref, reactive } from "vue";
import useAuthUser from "@/utils/useAuth";

// vuelidate form validation
import useVuelidate from "@vuelidate/core";
import { required, email, minLength, helpers } from "@vuelidate/validators";
import { useValidate } from "@/utils/validate";

//components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CreateAccount from "@/components/svg/CreateAccount.vue";

export default {
  components: {
    BaseHeading,
    BaseInput,
    BaseButton,
    CreateAccount,
  },
  setup() {
    const usernames = ref();
    const state = reactive({
      username: "",
      email: "",
      password: "",
    });

    const { handleSignup } = useAuthUser();
    const { mustBeUniqueUsername, notEmpty } = useValidate();

    const rules = {
      username: {
        required: helpers.withMessage(notEmpty, required),
        mustBeUniqueUsername: helpers.withMessage(
          "This username is already taken",
          mustBeUniqueUsername
        ),
      },
      email: {
        required: helpers.withMessage(notEmpty, required),
        email: helpers.withMessage(
          "This field must contain a valid email address",
          email
        ),
      },
      password: {
        required: helpers.withMessage(notEmpty, required),
        minLength: minLength(8),
      },
    };

    const v$ = useVuelidate(rules, state);

    return {
      usernames,
      state,
      handleSignup,
      v$,
    };
  },
  methods: {
    async handleFormSubmission() {
      const isFormValid = await this.v$.$validate();

      if (!isFormValid) return;

      this.handleSignup(this.state);
    },
  },
};
</script>
