<template>
    <div
      class="relative p-2 bg-white border rounded-sm shadow-lg h-64 w-64 hover:text-amber-500 hover:shadow-md hover:shadow-amber-300"
    >
      <Link :href="link" class="block h-full w-full">
        <div class="flex flex-col h-full w-full">
          <img
            :src="imgSrc"
            :alt="name + ' Image'"
            class="rounded-sm object-cover h-3/4 w-full"
          />
          <div class="p-4">
            <p class="text-lg font-semibold">{{ name }}</p>
          </div>
        </div>
      </Link>
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue'
  import { Link } from '@inertiajs/inertia-vue3'
  
  const props = defineProps({
      imgSrc: {
          type: String,
          required: true,
      },
      name: {
          type: String,
          required: true,
      },
      link: {
          type: String,
          required: true,
      },
  });
  </script>
  