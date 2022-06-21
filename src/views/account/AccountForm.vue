<template>
  <div class="relative">
    <div class="absolute bg-gray-100 top-0 w-full h-56" />

    <div class="relative max-w-lg mx-auto mb-12">
      <div class="flex justify-between items-center py-8 lg:py-12 px-6 lg:p-11">
        <BaseHeading size="h4" tag="h1">
          Contact
          <span class="text-blue-500">
            <span v-if="currentUser.full_name !== ''">{{
              currentUser.full_name
            }}</span>
            <span v-else>@{{ username }}</span>
          </span>
        </BaseHeading>

        <div class="h-12 w-12 bg-gray-400 rounded-full"></div>
      </div>

      <div class="card-shadow bg-white rounded-xl mx-6 lg:mx-0 p-6 lg:p-11">
        <p v-if="loading">Loading...</p>

        <div
          v-else-if="
            Object.keys(activeForm).length && formState !== 'submitted'
          "
        >
          <form class="flex flex-col gap-8" @submit.prevent="submitForm">
            <div class="relative" :class="{ error: v$.name.$errors.length }">
              <BaseInput v-model="form.name"> Name * </BaseInput>
              <div
                class="input-errors"
                v-for="error of v$.name.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div class="relative" :class="{ error: v$.email.$errors.length }">
              <BaseInput v-model="form.email"> Email * </BaseInput>
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

            <div class="relative" :class="{ error: v$.phone.$errors.length }">
              <BaseInput v-model="form.phone"> Phone * </BaseInput>
              <div
                class="input-errors"
                v-for="error of v$.phone.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div class="relative" :class="{ error: v$.message.$errors.length }">
              <BaseRichText v-model="form.message">Message *</BaseRichText>

              <div
                class="input-errors"
                v-for="error of v$.message.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div></div>
            <!------------ form ends here -------------->

            <div>
              <VueRecaptcha
                :sitekey="siteKey"
                :load-recaptcha-script="true"
                @verify="form.recaptcha = true"
              />
            </div>

            <div class="relative" :class="{ error: v$.toc.$errors.length }">
              <input
                id="toc"
                name="toc"
                v-model="form.toc"
                type="checkbox"
                class="mr-1"
              />
              <label for="toc">
                I agree to the
                <router-link
                  to="/terms-and-conditions"
                  class="text-blue-500 underline"
                >
                  terms and conditions
                </router-link>
                of Heygumball
              </label>
              <div
                class="input-errors"
                v-for="error of v$.toc.$errors"
                :key="error.$uid"
              >
                <p class="error-msg absolute text-xs text-red-500 mt-1">
                  {{ error.$message }}
                </p>
              </div>
            </div>

            <div class="lg:ml-auto">
              <BaseButton
                class="w-full"
                theme="tertiary"
                type="submit"
                :disabled="
                  formState === 'submitting' || !form.toc || !form.recaptcha
                "
              >
                {{ formState === "submitting" ? "Submitting..." : "Send" }}
              </BaseButton>
            </div>
          </form>
        </div>

        <div v-else-if="formState === 'submitted'">
          <BaseHeading tag="h2" size="h3" class="text-green-500 mb-5">
            Thanks for submitting!
          </BaseHeading>

          <BaseText>
            <span>
              {{
                currentUser.full_name !== ""
                  ? currentUser.full_name
                  : `@${username}`
              }}
            </span>
            will be in touch!</BaseText
          >
        </div>

        <div v-else>
          <BaseHeading tag="h2" size="h3" class="text-red-500 mb-5">
            Uh oh!
          </BaseHeading>

          <BaseText>It looks like their books are currently closed.</BaseText>
          <BaseText>Please try again later.</BaseText>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// utils
import { defineComponent, onMounted, reactive, ref, computed } from "vue";
import { useRoute } from "vue-router";
import { supabase } from "@/supabase";

// vuelidate
import useVuelidate from "@vuelidate/core";
import { required, email, phone, helpers } from "@vuelidate/validators";
import { useValidate } from "@/utils/validate";

// recaptcha
import { VueRecaptcha } from "vue-recaptcha";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseRichText from "@/components/base/BaseRichText.vue";
import BaseButton from "@/components/base/BaseButton.vue";

export default {
  components: {
    BaseButton,
    BaseHeading,
    BaseInput,
    BaseRichText,
    BaseText,
    VueRecaptcha,
  },
  setup() {
    const { notEmpty } = useValidate();

    const activeForm = ref({});
    const loading = ref(true);
    const form = reactive({
      name: "",
      email: "",
      phone: "",
      message: "",
      toc: false,
      recaptcha: false,
    });

    const siteKey = computed(() => {
      return process.env.VUE_APP_RECAPTCHA_SITE_KEY;
    });

    const formState = ref();

    const currentUser = ref({});

    const route = useRoute();
    const username = route.params.id;

    /**
     * form validation
     */
    const rules = {
      name: {
        required: helpers.withMessage(notEmpty, required),
      },
      email: {
        required: helpers.withMessage(notEmpty, required),
        email: helpers.withMessage(
          "This field must contain a valid email address",
          email
        ),
      },
      phone: {
        required: helpers.withMessage(notEmpty, required),
      },
      message: {
        required: helpers.withMessage(notEmpty, required),
      },
      toc: {
        required: helpers.withMessage(
          "You must first agree to the terms and conditions",
          required
        ),
      },
    };

    const v$ = useVuelidate(rules, form);

    onMounted(async () => {
      /**
       * get user profile from username in url
       */
      const userDataRaw = await supabase
        .from("profiles")
        .select()
        .match({ username: username });

      currentUser.value = { ...userDataRaw.data[0] };

      /**
       * Retrieve all users collections
       */
      const { data } = await supabase
        .from("collections")
        .select()
        .eq("user_id", currentUser.value.id);

      const currentDate = new Date();

      /**
       * Check if there's an active collection
       */
      if (data !== null) {
        const activeCollection = data.filter((collection) => {
          if (collection.end_date === null) return collection;

          const startDate = new Date(collection.start_date);
          const endDate = new Date(collection.end_date);

          const startDateFormatted =
            startDate.getTime() - startDate.getTimezoneOffset() * -60000;
          const endDateFormatted =
            endDate.getTime() - endDate.getTimezoneOffset() * -60000;

          if (
            startDateFormatted <= currentDate &&
            endDateFormatted >= currentDate
          ) {
            return { ...collection };
          }
        });

        if (Object.keys(activeCollection).length) {
          activeForm.value = activeCollection[0];
        }
      }
      loading.value = false;
    });

    return {
      currentUser,
      username,
      loading,
      activeForm,
      formState,
      form,
      v$,
      siteKey,
    };
  },
  methods: {
    async submitForm() {
      const isFormValid = await this.v$.$validate();

      if (!isFormValid) return;

      this.formState = "submitting";

      const { error } = await supabase.from("submissions").insert(
        [
          {
            collection_id: this.activeForm.id,
            user_id: this.currentUser.id,
            viewed: false,
            booked: false,
            approved: false,
            name: this.form.name,
            email: this.form.email,
            phone: this.form.phone,
            message: this.form.message,
          },
        ],
        {
          returning: "minimal",
        }
      );

      if (error) {
        console.log(error.message);
        alert("Oops! Something went wrong");
      } else {
        setTimeout(() => {
          this.formState = "submitted";
        }, 1500);
      }
    },
  },
};
</script>
