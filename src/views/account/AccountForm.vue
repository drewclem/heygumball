<template>
  <div class="relative">
    <div class="absolute bg-gray-100 top-0 w-full h-56" />

    <div class="relative max-w-lg mx-auto mb-12">
      <div class="flex justify-between items-center py-8 lg:py-12 px-6 lg:p-11">
        <BaseHeading size="h4" tag="h1">
          Contact <span class="text-blue-500">{{ currentUser.full_name }}</span>
        </BaseHeading>

        <div class="h-12 w-12 bg-gray-400 rounded-full"></div>
      </div>

      <div class="card-shadow bg-white rounded-xl mx-6 lg:mx-0 p-6 lg:p-11">
        <p v-if="loading">Loading...</p>
        <form
          v-else-if="Object.keys(activeForm).length"
          class="flex flex-col gap-6"
          @submit.prevent="submitForm"
        >
          <BaseInput v-model="form.name" required>Full Name</BaseInput>
          <BaseInput v-model="form.email" type="email" required>
            Email
          </BaseInput>
          <BaseInput v-model="form.phone">Phone</BaseInput>
          <BaseTextarea v-model="form.message" :rows="8" required>
            Message
          </BaseTextarea>

          <div class="lg:ml-auto">
            <BaseButton class="w-full" theme="tertiary" type="submit">
              Send
            </BaseButton>
          </div>
        </form>

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

<script setup>
// utils
import { onMounted, reactive, ref } from "vue";
import { useRoute } from "vue-router";
import { supabase } from "@/supabase";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseText from "@/components/base/BaseText.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseButton from "@/components/base/BaseButton.vue";

const activeForm = ref({});
const loading = ref(true);
const form = reactive({
  name: "",
  email: "",
  phone: "",
  message: "",
});

const currentUser = ref({});

const route = useRoute();
const username = route.params.id;

onMounted(async () => {
  const userDataRaw = await supabase
    .from("profiles")
    .select()
    .eq("username", username);

  currentUser.value = { ...userDataRaw.data[0] };

  const { data } = await supabase
    .from("collections")
    .select()
    .eq("user_id", currentUser.value.id);

  const currentDate = new Date();

  const activeCollection = data.filter((collection) => {
    const startDate = new Date(collection.start_date);
    const endDate = new Date(collection.end_date);

    if (startDate <= currentDate && endDate >= currentDate) {
      return { ...collection };
    }
  });

  if (Object.keys(activeCollection).length) {
    activeForm.value = activeCollection[0];
  }
  loading.value = false;
});

async function submitForm() {
  const { data, error } = await supabase.from("submissions").insert([
    {
      collection_id: activeForm.value.id,
      user_id: currentUser.value.id,
      viewed: false,
      booked: false,
      approved: false,
      name: form.name,
      email: form.email,
      phone: form.phone,
      message: form.message,
    },
  ]);

  if (error) {
    alert("Oops! Something went wrong");
  }
}
</script>
