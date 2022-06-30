<template>
  <div class="relative">
    <div class="absolute bg-gray-100 top-0 w-full h-56" />

    <transition name="fade" appear>
      <div class="relative max-w-lg mx-auto mb-12">
        <div
          class="flex justify-between items-center py-8 lg:py-12 px-6 lg:p-11"
        >
          <BaseHeading size="h4" tag="h1">
            Contact
            <span class="text-blue-500">
              <span>@{{ username }}</span>
            </span>
          </BaseHeading>

          <div class="h-16 w-16 bg-gray-400 rounded-full overflow-hidden">
            <BaseImage
              v-if="state.avatar_url !== null"
              :src="state.avatar_url"
              :alt="currentUser.username"
              class="h-16 w-16 object-cover"
            />
          </div>
        </div>

        <div class="card-shadow bg-white rounded-xl mx-6 lg:mx-0 p-6 lg:p-11">
          <div class="flex flex-col space-y-6" v-if="!showForm">
            <BaseHeading size="h3" tag="h2"> Read first: </BaseHeading>

            <div v-html="currentUser.prescreen" />

            <div>
              <BaseButton @click="showForm = true" theme="tertiary">
                Confirm
              </BaseButton>
            </div>
          </div>

          <div v-else>
            <p v-if="loading">Loading...</p>
            <div
              v-else-if="
                Object.keys(activeForm).length && formState !== 'submitted'
              "
            >
              <form class="flex flex-col gap-8" @submit.prevent="submitForm">
                <div
                  class="relative"
                  :class="{ error: v$.name.$errors.length }"
                >
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

                <div
                  class="relative"
                  :class="{ error: v$.email.$errors.length }"
                >
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

                <div
                  class="relative"
                  :class="{ error: v$.phone.$errors.length }"
                >
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

                <div
                  class="relative"
                  :class="{ error: v$.message.$errors.length }"
                >
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

                <div>
                  <p class="font-display text-sm">Reference media</p>
                  <p class="text-xs mb-4 opacity-50">
                    Accepted file types: jpeg, jpg, png
                  </p>
                  <BaseDropzone
                    @files-dropped="addFiles"
                    #default="{ dropZoneActive }"
                  >
                    <label class="text-sm" for="file-input">
                      <span v-if="dropZoneActive">
                        <span>Drop Them Here</span>
                      </span>
                      <span v-else>
                        <span>Drag Your Files Here</span>
                        <span class="text-sm">
                          or <strong><em>click here</em></strong> to select
                          files
                        </span>
                      </span>

                      <input
                        type="file"
                        id="file-input"
                        accept="image/*"
                        multiple
                        @change="onInputChange"
                      />
                    </label>

                    <ul
                      class="grid grid-cols-4 gap-2 mt-3"
                      v-show="files.length"
                    >
                      <BaseFilePreview
                        v-for="file of files"
                        :key="file.id"
                        :file="file"
                        tag="li"
                        @remove="removeFile"
                      />
                    </ul>
                  </BaseDropzone>
                </div>
                <!------------ form ends here -------------->
                <div class="border-t-2 border-gray-100" />

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

              <BaseText
                >It looks like their books are currently closed.</BaseText
              >
              <BaseText>Please try again later.</BaseText>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
// utils
import {
  defineComponent,
  onMounted,
  reactive,
  ref,
  computed,
  watch,
} from "vue";
import { useRoute } from "vue-router";
import { supabase } from "@/supabase";

// vuelidate
import useVuelidate from "@vuelidate/core";
import { required, email, phone, helpers } from "@vuelidate/validators";
import { useValidate } from "@/utils/validate";
import useFileList from "@/utils/file-list";

// recaptcha
import { VueRecaptcha } from "vue-recaptcha";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseRichText from "@/components/base/BaseRichText.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseImage from "@/components/base/BaseImage.vue";
import BaseDropzone from "@/components/base/BaseDropzone.vue";
import BaseFilePreview from "@/components/base/BaseFilePreview.vue";

export default {
  components: {
    BaseButton,
    BaseDropzone,
    BaseFilePreview,
    BaseHeading,
    BaseInput,
    BaseImage,
    BaseRichText,
    BaseText,
    VueRecaptcha,
  },
  setup() {
    const { notEmpty } = useValidate();

    const { files, addFiles, removeFile } = useFileList();

    function onInputChange(e) {
      addFiles(e.target.files);
      e.target.value = null;
    }

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

    /**
     * User Avatar
     */

    const state = reactive({
      avatar_url: null,
    });

    async function downloadAvatar(fileName) {
      const { error, data } = await supabase.storage
        .from("avatars")
        .createSignedUrl(fileName, 60000);

      state.avatar_url = data.signedURL;
    }

    const siteKey = computed(() => {
      return process.env.VUE_APP_RECAPTCHA_SITE_KEY;
    });

    const formState = ref();

    const currentUser = ref({});

    const route = useRoute();
    const username = route.params.id;

    const showForm = ref(true);

    watch(currentUser, (newVal, oldVal) => {
      if (newVal.prescreen !== null) showForm.value = false;
    });

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

      if (currentUser.value.user_avatar !== null) {
        downloadAvatar(currentUser?.value.user_avatar);
      }

      /**
       * Retrieve all users collections
       */
      const { data } = await supabase
        .from("collections")
        .select()
        .eq("user_id", currentUser.value?.id);

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
      files,
      addFiles,
      removeFile,
      onInputChange,
      currentUser,
      showForm,
      username,
      loading,
      state,
      activeForm,
      formState,
      form,
      v$,
      siteKey,
    };
  },
  methods: {
    randomNumber(min, max) {
      min = Math.ceil(min);
      max = Math.floor(max);
      return Math.floor(Math.random() * (max - min) + min);
    },
    async submitForm() {
      const isFormValid = await this.v$.$validate();

      if (!isFormValid) return;

      this.formState = "submitting";

      const { error, data } = await supabase.from("submissions").insert([
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
      ]);

      const sumbissionId = data[0].id;

      this.files.forEach(async (file) => {
        const fileExt = file.name.split(".").pop();
        const fileName = file.name.split(".");
        const formattedName = `${fileName[0]}-${this.randomNumber(
          1,
          10000
        )}.${fileExt}`;

        await supabase.storage
          .from("submission-uploads")
          .upload(`${sumbissionId}/${formattedName}`, file.file);

        await supabase.from("submission-uploads").insert([
          {
            submission_id: sumbissionId,
            file_name: formattedName,
          },
        ]);
      });

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

<style scoped>
.fade-enter-active,
.fade-leave-active {
  opacity: 1;
  transition: 300ms ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
