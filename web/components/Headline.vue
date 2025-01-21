<template>
  <h1
    :class="[
      `text-${align}`,
      `text-${color}`,
      'text-ellipsis text-balance text-xl font-[700] leading-[1.5]',
    ]"
  >
    {{ text }}
  </h1>
</template>

<script setup lang="ts">
defineProps({
  text: {
    type: String,
    required: true,
  },
  color: {
    type: String,
    default: "black",
  },
  align: {
    type: String,
    default: "left",
    validator: (value: string) =>
      ["left", "center", "right", "justify"].includes(value),
  },
});
</script>
