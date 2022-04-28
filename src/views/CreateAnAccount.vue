<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <form>
        <BaseHeading class="mb-5" size="h4" tag="h1"
          >Create an account</BaseHeading
        >

        <div class="flex flex-col space-y-6">
          <BaseInput v-model="form.fullName">Full Name</BaseInput>

          <BaseInput inputType="email" v-model="form.email">Email</BaseInput>

          <BaseInput inputType="password" v-model="form.password">
            Password
          </BaseInput>

          <div class="text-right">
            <BaseButton theme="tertiary">Create Account</BaseButton>
          </div>
        </div>
      </form>

      <CreateAccount />
    </div>
  </section>
</template>

<script>
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import CreateAccount from "@/components/svg/CreateAccount.vue";

export default {
  components: {
    BaseButton,
    BaseHeading,
    BaseInput,
    CreateAccount,
  },
  data() {
    return {
      form: {
        fullName: "",
        email: "",
        password: "",
      },
    };
  },
};
</script>

<style lang="scss" scoped></style>
