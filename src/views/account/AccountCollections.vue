<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div>
        <BaseHeading size="h4" tag="h1">Collections</BaseHeading>
      </div>

      <button
        class="rounded-full border border-gray-500 p-2 opacity-50 hover:opacity-100"
      >
        <IconSearch class="w-4 h-4 text-gray-500" />
      </button>
    </div>

    <div>
      <div class="grid grid-cols-5 px-8 py-4 opacity-40 mb-4">
        <p class="col-span-2">Open Dates</p>
        <p>Year</p>
        <p>Requests Received</p>
        <p class="ml-auto">Approvals</p>
      </div>

      <div class="flex flex-col space-y-6">
        <AccountCardGrid class="card-shadow bg-white rounded-lg">
          <template #col-1>March 1 - March 12</template>
          <template #col-2>2022</template>
          <template #col-3>324</template>
          <template #col-4>23</template>
        </AccountCardGrid>

        <AccountCardGrid class="card-shadow bg-white rounded-lg">
          <template #col-1>December 12 - January 3</template>
          <template #col-2>2021-2022</template>
          <template #col-3>236</template>
          <template #col-4>58</template>
        </AccountCardGrid>
      </div>
    </div>
  </div>
</template>

<script setup>
import BaseHeading from "@/components/base/BaseHeading.vue";
import AccountCardGrid from "@/components/account/AccountCardGrid.vue";
import IconSearch from "@/components/svg/IconSearch";
</script>
