<template>
  <div>
    <div class="flex flex-col space-y-6" v-if="state.step === 0">
      <div>
        <BaseHeading class="mb-1" size="h4" tag="h1">
          Schedule Collection
        </BaseHeading>
      </div>

      <div class="flex space-x-6">
        <div>
          <p class="font-display mb-2 text-sm">Start date</p>
          <Datepicker class="mb-6" v-model="state.startDate" />
        </div>

        <div>
          <p class="font-display mb-2 text-sm">End date</p>
          <Datepicker class="mb-6" v-model="state.endDate" />
        </div>
      </div>

      <div class="ml-auto">
        <BaseButton
          theme="tertiary"
          @user-click="scheduleCollection"
          :disabled="state.submitting"
        >
          {{ !state.submitting ? "Schedule" : "Submitting..." }}
        </BaseButton>
      </div>
    </div>

    <div class="flex flex-col space-y-6" v-if="state.step === 1">
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
  startDate: null,
  endDate: null,
  submitting: false,
});

const { currentUser, setCollections, createCollection } = useUserStore();

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

async function scheduleCollection() {
  state.submitting = true;

  const { data, error } = await createCollection({
    startDate: state.startDate,
    endDate: state.endDate,
  });

  if (error) {
    alert(error);
  } else {
    setCollections();

    setTimeout(() => {
      state.submitting = false;
      state.step = 1;
    }, 100);
  }
}
</script>
