<template>
  <div class="home">
    <section class="hero base-wrapper flex">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="rounded-2xl overflow-hidden lg:order-last">
          <BaseImage
            src="./hero_image.jpg"
            alt="A woman works efficiently at a laptop"
          />
        </div>

        <div class="flex flex-col space-y-6">
          <BaseHeading size="h1">
            Do great work.<br />
            Stay busy.
          </BaseHeading>

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
        <BaseHeading size="h2" class="lg:text-center mb-8 !leading-tight">
          Managing booking requests <br />just got
          <span class="text-green-500">awesome</span>
        </BaseHeading>

        <BaseText class="text-lg lg:text-xl max-w-5xl mx-auto lg:text-center">
          Everyone wants to book time with you, and for good reason! But your
          time is best spent creating the value everyone wants. Heygumball puts
          the power back in your hands by letting you decide when you&apos;re
          open to take requests and by giving you tools to manage, sort, and
          respond in a simple and efficient way!
        </BaseText>
      </div>

      <div class="max-w-3xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
          <div class="flex flex-col space-y-3">
            <ScheduleGraphic class="w-1/2 lg:hidden mx-auto mb-4" />

            <BaseHeading class="text-blue-500" size="h3">
              Schedule when your books are open
            </BaseHeading>

            <BaseText>
              Do you know when you'll be booking next? Get ahead of the curve by
              scheduling your Heygumball form to be live and open to the
              public.<br /><br />You'll get notifications when requests start
              coming in <span class="italic">and</span> your request form will
              close itself when it's time has lapsed.
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

            <BaseHeading class="text-blue-500" size="h3">
              Search, filter, &amp; tag your backlog
            </BaseHeading>

            <BaseText>
              Navigating your requests has never been easier! Search by keyword,
              name, date.. you name it, we find it.<br /><br />requests are
              grouped by booking windows by default, making it easy for you to
              see the most recent and relevant items.
            </BaseText>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
          <div class="flex flex-col space-y-3">
            <ReviewGraphic class="w-1/2 lg:hidden mx-auto mb-4" />

            <BaseHeading class="text-blue-500" size="h3">
              Review &amp; respond to requests
            </BaseHeading>

            <BaseText>
              Review and respond to prospective requests from the easy-to-use
              dashboard. Mark requests as denied, approved, contacted, or
              booked!<br /><br />
              Once you've narrowed down your options, one-click reply to those
              that made the cut and start filling up your calendar!
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

            <BaseHeading class="mb-2" size="h5" tag="h3">
              One-click reply
            </BaseHeading>

            <BaseText>Reply to requests easily with a single click.</BaseText>
          </div>

          <div class="lg:w-3/4 lg:border-l border-gray-200 lg:pl-6">
            <IconSorting class="w-8 h-8 mb-4" />

            <BaseHeading class="mb-2" size="h5" tag="h3">
              Backlog Sorting
            </BaseHeading>

            <BaseText>
              View requests by date, keyword, or all at once. Your pick!
            </BaseText>
          </div>
        </div>

        <div class="flex flex-col space-y-8 lg:w-3/4">
          <div>
            <BaseHeading class="mb-3" size="h4" tag="h2">
              Ready to get started?
            </BaseHeading>

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

<style scoped>
@screen lg {
  .hero {
    height: calc(100vh - 96px);
  }
}
</style>
