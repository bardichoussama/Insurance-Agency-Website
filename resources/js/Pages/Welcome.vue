<script setup>
import { defineProps } from 'vue';
import EntrepriseCard from '../Components/CategoryCard.vue';
import homelogo from "../../../resources/assets/imgs/whiteLogo.png";
import homebackground from "../../../resources/assets/imgs/mainBackground.png";

const props = defineProps({
    categories: Array,
});

const getImagePath = (imageName) => {
    return new URL(`/resources/assets/imgs/${imageName}`, import.meta.url)
  
};

const generateCategoryLink = (categoryId) => {
  return `/categories/${categoryId}/services`;
};

</script>

<template>
  <div>
    <div class="home font-work-sans relative">
      <!-- Background section -->
      <div class="relative flex flex-col w-full items-center h-[80vh]">
        <!-- Logo -->
        <img
          :src="homelogo"
          alt="Main Logo"
          class="absolute z-40 top-20 w-[250px] h-auto"
        />

        <!-- Background Image -->
        <div
          class="flex-1 w-full bg-center bg-cover"
          :style="{ backgroundImage: `url(${homebackground})` }"
        >
          <div class="flex items-center justify-center h-full bg-gray-900 bg-opacity-40"></div>
        </div>

        <!-- Text -->
        <div class="absolute z-50 top-[45%] text-center w-full">
          <p class="text-3xl font-normal italic text-white">
  Nos Solutions Assurantielles <br />
  Selon Votre Statut
</p>

        </div>
      </div>

      <!-- Card section -->
      <div class="relative z-50 flex justify-center gap-x-32 mt-[-10rem] font-work-sans mb-20">
        <EntrepriseCard
          v-for="(category, index) in categories"
          :key="index"
          :img-src="getImagePath(category.image)"
          :name="category.name"
          :link="generateCategoryLink(category.id)"
        />
      </div>
    </div>
  </div>
</template>

