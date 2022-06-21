<template>
  <div>
    <div class="flex flex-col space-y-6" v-if="state.step === 0">
      <div>
        <BaseHeading class="mb-1" size="h4" tag="h1">
          Schedule Collection
        </BaseHeading>
      </div>

      <div class="flex flex-col lg:flex-row space-6">
        <div>
          <p class="font-display mb-2 text-sm">Start date</p>
          <Datepicker
            class="mb-6"
            v-model="state.startDate"
            :lower-limit="currentDate"
            :disabled-dates="disabledDates"
          />
        </div>

        <div>
          <p class="font-display mb-2 text-sm">End date</p>
          <Datepicker
            class="mb-6"
            v-model="state.endDate"
            :lower-limit="tomorrowDate"
            :disabled-dates="disabledDates"
          />
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
import useSupabase from "@/utils/useSupabase";
import { useDates } from "@/utils/dates";

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

const { currentUser, setCollections, disabledDates } = useUserStore();

const { createCollection } = useSupabase();

const { currentDate, tomorrowDate } = useDates();

function incrementStep() {
  state.step++;
}

async function scheduleCollection() {
  state.submitting = true;

  const { data, error } = await createCollection({
    startDate: state.startDate,
    endDate: state.endDate,
    currentUser: {
      ...currentUser,
    },
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
