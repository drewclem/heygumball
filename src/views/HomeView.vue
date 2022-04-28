<template>
  <div class="home">
    <div>
      <section class="base-wrapper">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="rounded-2xl overflow-hidden lg:order-last">
            <BaseImage
              src="./hero_image.jpg"
              alt="A woman works efficiently at a laptop"
            />
          </div>

          <div class="flex flex-col space-y-6">
            <BaseHeading size="h1">Do great work. Stay busy.</BaseHeading>
            <BaseText class="lg:w-3/4">
              Manage your appointment &amp; booking requests better with
              Heygumball&apos;s calendar drop and management tool.
            </BaseText>

            <div>
              <BaseButton href="/create-an-account" theme="primary">
                Get Started
              </BaseButton>
            </div>
          </div>
        </div>
      </section>

      <section class="narrow-wrapper my-24 lg:my-52">
        <div>
          <BaseHeading size="h2" class="lg:text-center mb-8">
            Control
            <span class="text-green-500 underline">when</span> and
            <span class="text-blue-500 underline">how</span> your clients can
            request appointments.
          </BaseHeading>

          <BaseText
            class="text-lg lg:text-2xl max-w-4xl mx-auto lg:text-center"
          >
            Heygumball allows you to direct your clients to a single point of
            contact when it comes to requesting an appointment with you. We give
            <span class="italic font-semibold">you</span> the ability to decide
            and schedule when that window for booking is open.
          </BaseText>
        </div>

        <div class="max-w-3xl mx-auto">
          <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
            <div class="flex flex-col space-y-3">
              <ScheduleGraphic class="w-1/2 lg:hidden mx-auto mb-4" />
              <BaseHeading class="text-blue-500" size="h3"
                >Schedule Request Windows</BaseHeading
              >
              <BaseText>
                Pre-define moments for your requests form to be live and
                actively accepting new inquiries.<br /><br />Customize the
                prospects form with as many, or as little, fields as you need!
              </BaseText>
            </div>
            <div class="hidden lg:block">
              <ScheduleGraphic class="w-full" />
            </div>
          </div>

          <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
            <div class="hidden lg:block">
              <SearchGraphic class="w-full" />
            </div>
            <div class="flex flex-col space-y-3">
              <SearchGraphic class="w-1/2 lg:hidden mx-auto mb-4" />
              <BaseHeading class="text-blue-500" size="h3"
                >Search &amp; Tag your Backlog</BaseHeading
              >
              <BaseText>
                Review, search, & filter your backlog by keyword, general
                search, custom tags, or filtered down by booking windows.<br /><br />Finding
                the right prospects has never been easier!
              </BaseText>
            </div>
          </div>

          <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
            <div class="flex flex-col space-y-3">
              <ReviewGraphic class="w-1/2 lg:hidden mx-auto mb-4" />
              <BaseHeading class="text-blue-500" size="h3"
                >Review &amp; Respond to Submissions</BaseHeading
              >
              <BaseText>
                Review and respond to prospects requests from the easy-to-use
                dashboard. Mark requests as denied, approved, contacted, and
                booked!<br /><br />
                One-click reply to those that make the cut!
              </BaseText>
            </div>
            <div class="hidden lg:block">
              <ReviewGraphic class="w-full" />
            </div>
          </div>
        </div>
      </section>

      <section class="narrow-wrapper mb-20">
        <div
          class="grid grid-cols-1 lg:grid-cols-2 space-y-10 lg:space-y-0 items-center"
        >
          <div class="grid grid-cols-2 gap-4 lg:order-last">
            <div
              class="lg:w-3/4 lg:border-l border-gray-200 lg:px-6 mb-8 lg:mb-0"
            >
              <IconReply class="w-8 h-8 mb-4" />
              <BaseHeading class="mb-2" size="h5" tag="h3"
                >One-click reply</BaseHeading
              >
              <BaseText>Reply to requests easily with a single click.</BaseText>
            </div>

            <div class="lg:w-3/4 lg:border-l border-gray-200 lg:pl-6">
              <IconSorting class="w-8 h-8 mb-4" />
              <BaseHeading class="mb-2" size="h5" tag="h3"
                >Backlog Sorting</BaseHeading
              >
              <BaseText
                >View requests by date, keyword, or all at once. Your
                pick!</BaseText
              >
            </div>
          </div>

          <div class="flex flex-col space-y-8 lg:w-3/4">
            <div>
              <BaseHeading class="mb-3" size="h4" tag="h2"
                >Ready to get started?</BaseHeading
              >
              <BaseText>
                Sign up today and see how much better managing your booking
                requests can be!
              </BaseText>
            </div>

            <div>
              <BaseButton href="/create-an-account">
                Create an Account
              </BaseButton>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import BaseImage from "../components/base/BaseImage";
import BaseButton from "../components/base/BaseButton.vue";
import BaseText from "../components/base/BaseText.vue";
import BaseHeading from "../components/base/BaseHeading.vue";
import ScheduleGraphic from "../components/svg/ScheduleGraphic.vue";
import SearchGraphic from "../components/svg/SearchGraphic.vue";
import ReviewGraphic from "../components/svg/ReviewGraphic.vue";
import IconSorting from "../components/svg/IconSorting.vue";
import IconReply from "../components/svg/IconReply.vue";

export default {
  name: "HomeView",
  components: {
    BaseImage,
    BaseButton,
    BaseText,
    BaseHeading,
    ScheduleGraphic,
    SearchGraphic,
    ReviewGraphic,
    IconSorting,
    IconReply,
  },
};
</script>