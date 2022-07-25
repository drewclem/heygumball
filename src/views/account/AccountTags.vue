<template>
  <div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center">
        <BaseHeading size="h4" tag="h1">Tags</BaseHeading>
      </div>
    </div>

    <div>
      <ul class="flex flex-wrap">
        <li
          v-for="tag in currentUser.tags"
          :key="tag.id"
          class="inline bg-white p-4 rounded-md card-shadow mr-6 mb-6"
        >
          <span class="text-blue-500 mr-2">{{ tag.number_of_references }}</span>
          <span class="mr-4">{{ tag.label }}</span>
          <button
            @click="deleteTag(tag.id)"
            class="text-red-500 border border-red-500 rounded-full p-1"
          >
            <IconClose class="w-3 h-3" />
          </button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
// utils
import { ref, computed } from "vue";
import { useUserStore } from "@/stores/user";
import { storeToRefs } from "pinia";
import { supabase } from "@/supabase";

// components
import BaseHeading from "@/components/base/BaseHeading.vue";
import IconClose from "@/components/svg/IconClose.vue";

const global = useUserStore();
const { setUser, currentUser, setTags } = storeToRefs(global);
const reload = ref(false);

async function deleteTag(tagId) {
  reload.value = true;
  if (
    window.confirm(
      "Are you sure you want to delete this tag? This will remove it and any reference to it permanently."
    )
  ) {
    const references = await supabase
      .from("tag_relations")
      .delete()
      .match({ tag_id: tagId });

    if (references.error) {
      alert("Oops! Something went wrong. Please try again.");
    } else {
      const { error } = await supabase
        .from("tags")
        .delete()
        .match({ id: tagId });

      setUser(currentUser.id);
      reload.value = false;
    }
  }
}
</script>
