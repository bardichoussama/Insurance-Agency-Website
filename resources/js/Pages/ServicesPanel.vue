<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// Props for services and categories
const props = defineProps({
    services: Array,
    categories: Array,
});

// Reactive form state for adding a service
const form = reactive({
    title: '',
    description: '',
    category_id: '',
    image: null,
});

// Reactive state for updating a service
const selectedService = reactive({
    id: null,
    title: '',
    description: '',
    category_id: '',
    image: null,
    oldImage: null,
});

// Function to open the update form and populate `selectedService`
const editService = (service) => {
    selectedService.id = service.id;
    selectedService.title = service.title;
    selectedService.description = service.description;
    selectedService.category_id = service.category_id;
    selectedService.oldImage = service.image; // Assume service.image contains the image filename
};

// Function to reset `selectedService`
const resetSelectedService = () => {
    selectedService.id = null;
    selectedService.title = '';
    selectedService.description = '';
    selectedService.category_id = '';
    selectedService.image = null;
    selectedService.oldImage = null;
};

// Function to add a new service
const addService = () => {
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('description', form.description);
    formData.append('category_id', form.category_id);
    if (form.image) formData.append('image', form.image);

    Inertia.post(route('services.store'), formData, {
        onSuccess: () => {
            console.log('Service added successfully');
            // Reset form
            form.title = '';
            form.description = '';
            form.category_id = '';
            form.image = null;
        },
        onError: (errors) => {
            console.error('Failed to add service:', errors);
        },
    });
};

// Function to update an existing service
const updateService = () => {
    if (!selectedService.id) {
        console.error('No service selected for update');
        return;
    }

    const formData = new FormData();
    formData.append('_method', 'PUT'); // Include the HTTP method override
    formData.append('title', selectedService.title);
    formData.append('description', selectedService.description);
    formData.append('category_id', selectedService.category_id);

    // Only include the image if it's updated
    if (selectedService.image) {
        formData.append('image', selectedService.image);
    }

    Inertia.post(route('services.update', selectedService.id), formData, {
        onSuccess: () => {
            console.log('Service updated successfully');
            resetSelectedService();
        },
        onError: (errors) => {
            console.error('Failed to update service:', errors);
        },
    });
};

// Function to handle file upload for adding a service
const handleFileUpload = (event) => {
    form.image = event.target.files[0];
};

// Function to handle file upload for updating a service
const handleImageUpload = (event) => {
    selectedService.image = event.target.files[0];
};

// Function to get the full image path for display
const getImagePath = (imageName) => {
    return new URL(`/resources/assets/imgs/${imageName}`, import.meta.url).href;
};
</script>



<template>
    <div>
        <AuthenticatedLayout>


            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Service Panel
                </h2>
            </template>

            <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
                <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                        <div
                            class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t dark:border-gray-700">
                            <div class="w-full md:w-1/2">

                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button @click="editService(service)" data-drawer-target="drawer-update-service"
                                    type="button" id="createServicetButton" data-modal-toggle="createServiceModal"
                                    class="flex items-center justify-center text-white bg-custom-blue hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                    <svg class="h-3.5 w-3.5 mr-1.5 -ml-1" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    Add Service
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="col" class="p-4">Service Name</th>

                                        <th scope="col" class="p-4">Category Name</th>

                                        <th scope="col" class="p-4">Image</th>
                                        <th scope="col" class="p-4">Description</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="service in services" :key="service.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">


                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center mr-3">
                                                {{ service.title }}
                                            </div>
                                        </th>
                                        <td class="px-4 py-3">
                                            {{ service.category?.name || 'No Category' }}

                                        </td>
                                        <td
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center">
                                                <img :src="getImagePath(service.image)" alt="Service Image"
                                                    class="w-16 h-16 object-cover rounded" />

                                            </div>
                                        </td>
                                        <td
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center">
                                                {{ service.description }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex items-center space-x-4">
                                                <button @click="editService(service)" type="button"
                                                    data-drawer-target="drawer-update-service"
                                                    data-drawer-show="drawer-update-service"
                                                    aria-controls="drawer-update-service"
                                                    class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-custom-blue rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5"
                                                        viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path
                                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd"
                                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Edit
                                                </button>

                                                <button type="button" data-modal-target="delete-modal"
                                                    data-modal-toggle="delete-modal"
                                                    class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5"
                                                        viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>



                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </section>
            <div id="createServiceModal" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] md:h-full">
                <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
                    <!-- Modal content -->
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                        <!-- Modal header -->
                        <div
                            class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Service</h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="createProductModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form @submit.prevent="addService">
                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                <div>
                                    <label for="title"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Service
                                        Title</label>
                                    <input name="title" id="title" v-model="form.title"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Type service title" required>
                                </div>
                            </div>
                            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                <div>
                                    <label for="description"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                    <textarea id="description" v-model="form.description" rows="3"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Type service description" required></textarea>
                                </div>
                            </div>
                            <div>
                                <label for="category"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                <select id="category" v-model="form.category_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                    <option value="" disabled>Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Service Image Upload -->
                            <div class="mb-4">
                                <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Service
                                    Image</span>
                                <div class="flex justify-center items-center w-full">
                                    <label for="dropzone-file"
                                        class="flex flex-col justify-center items-center w-full h-64 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col justify-center items-center pt-5 pb-6">
                                            <svg aria-hidden="true" class="mb-3 w-10 h-10 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Click to upload</span>
                                                or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, or GIF
                                                (MAX.
                                                800x400px)</p>
                                        </div>
                                        <input id="dropzone-file" @change="handleFileUpload" type="file" class="hidden"
                                            accept="image/*">
                                    </label>
                                </div>
                            </div>

                            <div class="items-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                                <button type="submit"
                                    class="w-full sm:w-auto justify-center text-white inline-flex bg-custom-blue hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Add
                                    Service</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <form @submit.prevent="updateService" id="drawer-update-service"
                class="fixed top-0 left-0 z-40 w-full h-screen max-w-3xl p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800"
                aria-labelledby="drawer-update-service-label" aria-hidden="true">
                <!-- Header -->
                <h5 id="drawer-update-service-label"
                    class="inline-flex items-center mb-6 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400">
                    Update Service
                </h5>

                <!-- Close Button -->
                <button type="button" data-drawer-dismiss="drawer-update-service" aria-controls="drawer-update-service"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    aria-label="Close Update Drawer">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Form Fields -->
                <div class="grid gap-4 sm:grid-cols-3 sm:gap-6">
                    <div class="space-y-4 sm:col-span-2 sm:space-y-6">
                        <!-- Service Title -->
                        <div>
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Service Title
                            </label>
                            <input v-model="selectedService.title" type="text" id="title" name="title"
                                placeholder="Enter service title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required />
                        </div>

                        <!-- Service Description -->
                        <div>
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Service Description
                            </label>
                            <textarea v-model="selectedService.description" id="description" name="description"
                                placeholder="Enter service description" rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required></textarea>
                        </div>

                        <!-- Service Category -->
                        <div>
                            <label for="category_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Service Category
                            </label>
                            <select v-model="selectedService.category_id" id="category_id" name="category_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                <option value="" disabled>Select a category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Service Image -->
                        <div>
                            <label for="service-image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Service Image
                            </label>
                            <input id="service-image" type="file"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                accept="image/*" @change="handleImageUpload" />

                            <!-- Preview Current Image -->
                            <div v-if="selectedService.oldImage" class="mt-4">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Current Image:
                                </span>
                                <img :src="getImagePath(selectedService.oldImage)" alt="Service Image"
                                    class="mt-2 h-32 rounded-lg" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="flex justify-between mt-6">
                    <button type="submit"
                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                        Update Service
                    </button>
                    <button type="button" data-drawer-dismiss="drawer-update-service"
                        class="text-red-600 border border-red-600 hover:text-white hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                        Cancel
                    </button>
                </div>
            </form>


        </AuthenticatedLayout>

    </div>
</template>