<template>
  <div>
    <!-- Swiper Slider -->
    <swiper 
      :modules="modules"
      :slides-per-view="1"
      :space-between="0"
      :autoplay="{ delay: 3000, disableOnInteraction: false }"
      loop
      navigation
      pagination
      @swiper="onSwiper"
      @slideChange="onSlideChange"
    >
      <swiper-slide class=" rounded-lg">
        <img  src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c"  alt="Slide 1 Image" />
      </swiper-slide>
      <swiper-slide>
        <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994" alt="Slide 2 Image" />
      </swiper-slide>
      <swiper-slide>
        <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994" alt="Slide 2 Image" />
      </swiper-slide>
    </swiper>
  </div>
</template>

<script>
// Import Swiper core and required modules
import { Navigation, Pagination, Scrollbar, A11y, Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    const onSwiper = (swiper) => {
      console.log(swiper);
    };
    const onSlideChange = () => {
      console.log('slide change');
    };
    return {
      onSwiper,
      onSlideChange,
      modules: [Navigation, Pagination, A11y, Autoplay],
    };
  },
};
</script>

<style>
img {
  width: 100%;
  height: 450px;
  object-fit: cover;
}
</style>
