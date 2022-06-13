<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <BaseHeading size="h4" tag="h1">Settings</BaseHeading>

      <BaseLink href="account/edit">Edit</BaseLink>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-10">
      <div class="lg:col-span-2">
        <div class="h-24 w-24 bg-gray-400 rounded-full"></div>
      </div>
      <div class="lg:col-span-8">
        <div class="info-group info-list">
          <div>
            <h2 class="info-heading">Username</h2>
            <p v-if="currentUser">{{ currentUser.username }}</p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">Full Name</h2>
            <p v-if="currentUser && currentUser.full_name !== null">
              {{ currentUser.full_name }}
            </p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">Email</h2>
            <p v-if="currentUser && currentUser.email !== null">
              {{ currentUser.email }}
            </p>
            <p v-else>--</p>
          </div>
        </div>

        <div class="info-group info-grid">
          <div>
            <h2 class="info-heading">Instagram</h2>
            <p v-if="currentUser && currentUser.instagram_url !== null">
              {{ currentUser.instagram_url }}
            </p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">TikTok</h2>
            <p v-if="currentUser && currentUser.tiktok_url !== null">
              {{ currentUser.tiktok_url }}
            </p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">Twitter</h2>
            <p v-if="currentUser && currentUser.twitter_url !== null">
              {{ currentUser.twitter_url }}
            </p>
            <p v-else>--</p>
          </div>

          <div>
            <h2 class="info-heading">Facebook</h2>
            <p v-if="currentUser && currentUser.facebook_url">
              {{ currentUser.facebook_url }}
            </p>
            <p v-else>--</p>
          </div>
        </div>

        <div class="info-group info-list">
          <p>
            Subscription status:
            <span
              :class="subscriptionStatus ? 'text-green-500' : 'text-red-500'"
              >{{ subscriptionStatus ? "Active" : "Inactive" }}</span
            >
          </p>

          <div>
            <BaseButton type="primary" @click="manageSubscription">
              Manage Subscription
            </BaseButton>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// utility
import { onMounted, computed } from "vue";
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";

// stripe
import { loadStripe } from "@stripe/stripe-js";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import BaseLink from "@/components/base/BaseLink.vue";
import BaseButton from "@/components/base/BaseButton.vue";

const global = useUserStore();
const { currentUser } = storeToRefs(global);

const subscriptionStatus = computed(() => {
  return currentUser.subscription_active;
});

/**
 * Stripe
 */
let stripe = null;

onMounted(async () => {
  try {
    stripe = await loadStripe(process.env.VUE_APP_STRIPE_PUBLIC_KEY);
  } catch (err) {
    alert(err);
  }
});

async function manageSubscription() {
  if (currentUser.value.subscription_active) {
    const data = {
      customer: `${currentUser.value.stripe_customer}`,
      return_url: `${process.env.VUE_APP_BASE_URL}/${currentUser.value.username}/account`,
    };

    try {
      const sessionUrl = await fetch(
        "/.netlify/functions/stripe-customer-portal",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );

      const rawData = await sessionUrl.json();

      if (rawData) {
        window.location.href = rawData.url;
      }
    } catch (error) {
      alert(error);
    }
  } else {
    stripe.redirectToCheckout({
      successUrl: `${process.env.VUE_APP_BASE_URL}/${currentUser.value.username}/account`,
      cancelUrl: `${process.env.VUE_APP_BASE_URL}/${currentUser.value.username}/account`,
      lineItems: [
        {
          price: `${process.env.VUE_APP_STRIPE_PRODUCT_ID}`,
          quantity: 1,
        },
      ],
      mode: "subscription",
    });
  }
}
</script>

<style scoped>
.info-heading {
  @apply opacity-50 mb-1 font-display;
}

.info-group {
  @apply border-b-2 border-gray-200 pb-12 mb-12;
}

.info-list {
  @apply flex flex-col space-y-6;
}

.info-grid {
  @apply grid lg:grid-cols-2 gap-6;
}
</style>
