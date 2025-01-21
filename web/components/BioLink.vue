<template>
  <NuxtLink
    :to="link"
    target="_blank"
    class="block w-full h-full text-secondary border-2 border-main relative hover:bg-main hover:text-white cursor-pointer py-6 rounded-lg transition-colors duration-300 ease-in-out"
  >
    <div
      class="absolute top-1/2 left-4 transform -translate-y-1/2 flex items-center justify-center"
    >
      <Icon :name="icon" size="30" />
    </div>
    <p class="text-center font-[500]">{{ text }}</p>
  </NuxtLink>
</template>

<script setup lang="ts">
defineProps({
  link: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  text: {
    type: String,
    required: true,
  },
});
</script>
