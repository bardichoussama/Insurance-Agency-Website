<template>
    <div class="min-h-screen flex flex-col">
        <!-- Header Section -->
        <NavBar />

        <!-- Main Content Section -->
        <main class="flex-grow container mx-auto mt-12 px-4">
            <div class="service-details text-center max-w-3xl mx-auto">
                <h1 class="text-5xl font-bold mb-8 text-gray-800">{{ service.title }}</h1>
                <img :src="getImagePath(service.image)" alt="Service Image" class="w-full h-96 object-cover rounded-lg mb-8 shadow-lg" />
                <p class="text-lg leading-relaxed text-gray-700 px-4">{{ service.description }}</p>
            </div>
        </main>

        <!-- Footer Section -->
        <Footer />
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import NavBar from "../Components/Header.vue";
import Footer from "../Components/Footer.vue";

// Define props for service details
const props = defineProps({
    service: Object,
});

// Utility function for getting image path
const getImagePath = (imageName) => {
    return new URL(`/resources/assets/imgs/${imageName}`, import.meta.url).href;
};
</script>

<style scoped>
/* Additional styling */

</style>