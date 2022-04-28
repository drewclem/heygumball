<template>
  <section>
    <div
      class="max-w-3xl mx-auto px-6 xl:px-0 grid lg:grid-cols-2 gap-24 items-center"
    >
      <form>
        <BaseHeading class="mb-5" size="h4" tag="h1">Welcome back!</BaseHeading>

        <div class="flex flex-col space-y-6">
          <BaseInput inputType="email" v-model="form.email">Email</BaseInput>

          <BaseInput inputType="password" v-model="form.password">
            Password
          </BaseInput>

          <div class="text-right">
            <BaseButton theme="tertiary">Sign In</BaseButton>
          </div>
        </div>
      </form>

      <WelcomeBack />
    </div>
  </section>
</template>

<script>
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import WelcomeBack from "@/components/svg/WelcomeBack.vue";

export default {
  components: {
    BaseButton,
    BaseHeading,
    BaseInput,
    WelcomeBack,
  },
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
};
</script>

<style lang="scss" scoped></style>
