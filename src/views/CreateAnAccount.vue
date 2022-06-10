<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <transition v-if="state.step === 0" name="fade" appear>
        <div class="flex flex-col space-y-5">
          <BaseHeading size="h4" tag="h1"> Create an account </BaseHeading>
          <BaseText size="small">
            We're invite only during testing. If the man in the hat gave you a
            password, please provide it now.
          </BaseText>

          <BaseInput v-model="state.invite">Invite Password</BaseInput>

          <div class="ml-auto">
            <BaseButton @click="verifyPassword">Verify</BaseButton>
          </div>
        </div>
      </transition>

      <transition v-else name="fade" appear>
        <form @submit.prevent="handleFormSubmission">
          <BaseHeading class="mb-5" size="h4" tag="h1">
            Create an account
          </BaseHeading>

          <div v-if="formState === 'submitted'">
            <BaseHeading tag="h2" size="h4" class="text-green-500 mb-5">
              Thanks for signing up!
            </BaseHeading>

            <BaseText>Check your email to confirm your registration</BaseText>
          </div>

          <div v-else class="flex flex-col space-y-8">
            <div
              class="relative"
              :class="{ error: v$.username.$errors.length }"
            >
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

            <div
              class="relative"
              :class="{ error: v$.password.$errors.length }"
            >
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
              <BaseButton
                type="submit"
                theme="tertiary"
                :disabled="formState === 'submitting'"
              >
                {{
                  formState === "submitting" ? "Submitting" : "Create Account"
                }}
              </BaseButton>
            </div>
          </div>
        </form>
      </transition>

      <CreateAccount />
    </div>
  </section>
</template>

<script>
// utils
import { ref, reactive, computed } from "vue";
import useAuthUser from "@/utils/useAuth";

// vuelidate form validation
import useVuelidate from "@vuelidate/core";
import { required, email, minLength, helpers } from "@vuelidate/validators";
import { useValidate } from "@/utils/validate";

//components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseText from "@/components/base/BaseText.vue";
import CreateAccount from "@/components/svg/CreateAccount.vue";

export default {
  components: {
    BaseHeading,
    BaseText,
    BaseInput,
    BaseButton,
    CreateAccount,
  },
  setup() {
    const usernames = ref();
    const formState = ref();
    const state = reactive({
      username: "",
      email: "",
      password: "",
      invite: "",
      step: 0,
    });

    const { handleSignup } = useAuthUser();
    const { mustBeUniqueUsername, notEmpty } = useValidate();

    const invitePasswords = computed(() => {
      return [
        process.env.VUE_APP_INVITE_ALBANY,
        process.env.VUE_APP_INVITE_MACON,
        process.env.VUE_APP_INVITE_INDIE,
      ];
    });

    function verifyPassword() {
      const passphrase = state.invite.toLowerCase();

      let verified = false;

      for (const password of invitePasswords.value) {
        if (passphrase.includes(password)) verified = true;
      }

      if (verified) state.step++;
    }

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
      formState,
      state,
      handleSignup,
      verifyPassword,
      v$,
    };
  },
  methods: {
    async handleFormSubmission() {
      const isFormValid = await this.v$.$validate();

      if (!isFormValid) return;

      this.formState = "submitting";

      this.handleSignup(this.state);

      setTimeout(() => {
        this.formState = "submitted";
      }, 500);
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 300ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
