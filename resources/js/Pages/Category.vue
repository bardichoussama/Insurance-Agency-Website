<template>
    <div class="container mx-auto pb-24">
        <NavBar></NavBar>
        <Slider class="mx-24  rounded-lg" />
        <div class="text-center mt-8">
            <p>
                Nous comprenons que chaque individu a des besoins d'assurance
                uniques. C'est pourquoi nous offrons une gamme complète de
                solutions d'assurance adaptées à vos besoins spécifiques, que
                vous soyez un particulier ou un professionnel.
            </p>
        </div>
        <div class="flex justify-center">
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16 mt-12">
                <ServiceCard
                    v-for="(service, index) in services"
                    :key="index"
                    :img-src="getImagePath(service.image)"
                    :name="service.title"
                    :link="generateCategoryLink(service.id)"
                />
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>

<script setup>
import { defineProps } from "vue";
import Slider from "../Components/Slider.vue";
import NavBar from "../Components/Header.vue";
import Footer from "../components/Footer.vue";
import ServiceCard from "../Components/ServiceCard.vue";

const props = defineProps({
    services: Array,
});

const getImagePath = (imageName) => {
    return new URL(`/resources/assets/imgs/${imageName}`, import.meta.url).href;
};

const generateCategoryLink = (serviceId) => {
    return `/services/${serviceId}`;
};

</script>
