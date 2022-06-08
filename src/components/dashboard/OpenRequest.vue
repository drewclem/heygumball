<template>
  <div>
    <div class="flex flex-col space-y-6" v-if="state.step === 0">
      <div>
        <BaseHeading class="mb-1" size="h4" tag="h1">
          Would you like to schedule an end date?
        </BaseHeading>

        <BaseText size="small">
          If not, your profile will remain open until it's closed manually.
        </BaseText>
      </div>

      <div class="ml-auto">
        <BaseButton
          class="mr-4"
          theme="subdued"
          @user-click="createCollection"
          :disabled="state.submitting"
        >
          No
        </BaseButton>
        <BaseButton theme="tertiary" @user-click="state.step++">Yes</BaseButton>
      </div>
    </div>

    <div class="flex flex-col space-y-6 w-full" v-if="state.step === 1">
      <div>
        <button
          class="text-sm opacity-50 hover:opacity-100"
          type="button"
          @click="state.step--"
        >
          <IconArrowLeft class="h-3 w-3 inline -mt-0.5" />
          Back
        </button>
        <BaseHeading class="mb-1" size="h4" tag="h1">
          Select an end date
        </BaseHeading>
      </div>

      <div>
        <p class="font-display mb-2 text-sm">End date</p>
        <Datepicker class="mb-6" v-model="state.endDate" />

        <BaseButton
          class="block ml-auto"
          theme="tertiary"
          @user-click="createCollection"
          :disabled="state.submitting"
        >
          Confirm
        </BaseButton>
      </div>
    </div>

    <div class="flex flex-col space-y-6" v-if="state.step === 3">
      <BaseHeading class="mb-1 text-blue-500" size="h3" tag="h1">
        You're live!
      </BaseHeading>

      <BaseText> Share your public link for booking requests. </BaseText>

      <CopyShareLink />
    </div>
  </div>
</template>

<script setup>
// utils
import { reactive } from "vue";
import { supabase } from "@/supabase";
import { useAuthUser } from "@/utils/useAuth";
import { useUserStore } from "@/stores/user";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import IconArrowLeft from "@/components/svg/IconArrowLeft.vue";
import Datepicker from "vue3-datepicker";
import CopyShareLink from "@/components/dashboard/CopyShareLink.vue";

const state = reactive({
  step: 0,
  endDate: null,
  submitting: false,
});

const { currentUser, setCollections } = useUserStore();

function incrementStep() {
  state.step++;
}

function formatDate(date) {
  const dateObj = new Date(date);

  const year = dateObj.getFullYear();
  const month = ("0" + (dateObj.getMonth() + 1)).slice(-2);
  const day = dateObj.getDate();

  return year + "-" + month + "-" + day;
}

async function createCollection() {
  state.submitting = true;

  const currentDate = new Date();

  const nowDate = formatDate(currentDate);
  const endDate = formatDate(state.endDate);

  const { error } = await supabase.from("collections").insert([
    {
      user_id: currentUser.id,
      full_name: currentUser.full_name,
      start_date: nowDate,
      end_date: state.endDate !== null ? endDate : null,
    },
  ]);

  if (error) {
    alert(error);
  } else {
    setTimeout(() => {
      state.submitting = false;
      state.step = 3;
    }, 100);

    setCollections();
  }
}
</script>

<style>
.v3dp__input_wrapper input {
  @apply border border-gray-300 rounded-md px-2 py-1 w-full;
}
</style>
