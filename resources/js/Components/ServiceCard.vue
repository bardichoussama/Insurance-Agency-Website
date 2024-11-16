<template>
  <div  class="cursor-pointer bg-blue-600 w-64 h-52 rounded-xs overflow-hidden shadow-lg transform transition-all hover:scale-105">
    <Link :href="link" class="block h-full w-full">
    <img
      :src="imgSrc"
      :alt="name + ' Image'"
      class="object-cover h-5/6 w-full"
    />
    <p class="text-white text-center mt-2 font-semibold">{{ name }}</p>
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