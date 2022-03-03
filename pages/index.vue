<template>
  <div>
    <section class="base-wrapper">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="rounded-2xl overflow-hidden lg:order-last">
          <BaseImage src="./hero_image.jpg" alt="A woman works efficiently at a laptop" />
        </div>

        <div class="flex flex-col space-y-6">
          <BaseHeading size="h1">You're in<br />high demand.</BaseHeading>
          <BaseText class="lg:w-3/4">
            Take control of managing and scheduling your booking requests, easily.
          </BaseText>

          <div>
            <BaseButton href="/" theme="primary">Get Started</BaseButton>
          </div>
        </div>
      </div>
    </section>

    <section class="narrow-wrapper my-24 lg:my-52">
      <BaseHeading size="h2" class="text-center">
        The world's <span class="text-green-500 underline">first</span> limited window requests
        management tool.
      </BaseHeading>

      <div class="max-w-3xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
          <div class="flex flex-col space-y-3">
            <ScheduleGraphic class="w-1/2 lg:hidden mx-auto mb-4" />
            <BaseHeading class="text-blue-500" size="h3">Schedule Request Windows</BaseHeading>
            <BaseText>
              Pre-define moments for your requests form to be live and actively accepting new
              inquiries.<br /><br />Customize the prospects form with as many, or as little, fields
              as you need!
            </BaseText>
          </div>
          <div class="hidden lg:block">
            <ScheduleGraphic class="hiddenw-full" />
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center my-20 lg:my-48">
          <div class="hidden lg:block">
            <SearchGraphic class="w-full" />
          </div>
          <div class="flex flex-col space-y-3">
            <SearchGraphic class="w-1/2 lg:hidden mx-auto mb-4" />
            <BaseHeading class="text-blue-500" size="h3">Search &amp; Tag your Backlog</BaseHeading>
            <BaseText>
              Review, search, & filter your backlog by keyword, general search, custom tags, or
              filtered down by booking windows.<br /><br />Finding the right prospects has never
              been easier!
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
              Review and respond to prospects requests from the easy-to-use dashboard. Mark requests
              as denied, approved, contacted, and booked!<br /><br />
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
      <div class="grid grid-cols-1 lg:grid-cols-2 space-y-10 lg:space-y-0 items-center">
        <div class="grid grid-cols-2 gap-4 lg:order-last">
          <div class="lg:w-3/4 lg:border-l border-gray-200 lg:px-6 mb-8 lg:mb-0">
            <IconReply class="w-8 h-8 mb-4" />
            <BaseHeading class="mb-2" size="h5" tag="h3">One-click reply</BaseHeading>
            <BaseText>Reply to requests easily with a single click.</BaseText>
          </div>

          <div class="lg:w-3/4 lg:border-l border-gray-200 lg:pl-6">
            <IconSorting class="w-8 h-8 mb-4" />
            <BaseHeading class="mb-2" size="h5" tag="h3">Backlog Sorting</BaseHeading>
            <BaseText>View requests by date, keyword, or all at once. Your pick!</BaseText>
          </div>
        </div>

        <div class="flex flex-col space-y-8 lg:w-3/4">
          <div>
            <BaseHeading class="mb-3" size="h4" tag="h2">Ready to get started?</BaseHeading>
            <BaseText>
              Sign up today and see how much better managing your booking requests can be!
            </BaseText>
          </div>

          <div>
            <BaseButton href="/">Create an Account</BaseButton>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import ScheduleGraphic from '~/assets/Schedule.svg?inline'
  import SearchGraphic from '~/assets/Search.svg?inline'
  import ReviewGraphic from '~/assets/Review.svg?inline'
  import IconReply from '~/assets/IconReply.svg?inline'
  import IconSorting from '~/assets/IconSorting.svg?inline'

  export default {
    name: 'Home',
    components: {
      IconReply,
      IconSorting,
      ScheduleGraphic,
      SearchGraphic,
      ReviewGraphic,
    },
  }
</script>
