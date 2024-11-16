<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { reactive, ref } from 'vue'; // Added ref for modal management
import { Inertia } from '@inertiajs/inertia';

// Reactive form for creating/editing a category
const form = reactive({
    id: null, // Added id for edit functionality
    name: '',
    image: null,
});

// Modal visibility state for delete confirmation
const showDeleteModal = ref(false); // Tracks if the delete modal is open
const deleteCategoryId = ref(null); // Tracks the ID of the category to delete

// Handle file selection for the image
const handleImageUpload = (event) => {
    form.image = event.target.files[0];
};

// Submit category data with image (Create or Update)
const saveCategory = () => {
    const formData = new FormData();
    formData.append('name', form.name);
    if (form.image) formData.append('image', form.image);

    if (form.id) {
        // Update existing category
        Inertia.post(route('dashboard.categories.update', form.id), formData, {
            onSuccess: () => resetForm(),
        });
    } else {
        // Create new category
        Inertia.post(route('dashboard.categories.store'), formData, {
            onSuccess: () => resetForm(),
        });
    }
};

// Open the delete confirmation modal
const confirmDelete = (id) => {
    deleteCategoryId.value = id; // Set the ID of the category to delete
    showDeleteModal.value = true; // Open the delete modal
};

// Delete Category
const deleteCategory = () => {
    if (deleteCategoryId.value) {
        Inertia.delete(route('dashboard.categories.destroy', deleteCategoryId.value), {
            onSuccess: () => {
                showDeleteModal.value = false; // Close modal
                deleteCategoryId.value = null; // Reset the ID
            },
        });
    }
};

// Reset form
const resetForm = () => {
    form.id = null;
    form.name = '';
    form.image = null;
};

// Props passed to the component
const props = defineProps({
    categories: Array,
});

// Generate image path (used for displaying images)
const getImagePath = (imageName) => {
    return new URL(`/resources/assets/imgs/${imageName}`, import.meta.url);
};
</script>



<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
               Category Panel
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>

                    <!-- Start block -->
                    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
                        <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                                <div
                                    class="flex flex-col md:flex-row items-stretch md:items-center md:space-x-3 space-y-3 md:space-y-0 justify-between mx-4 py-4 border-t dark:border-gray-700">
                                    <div class="w-full md:w-1/2">

                                    </div>
                                    <div
                                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                        <button type="button" id="createProductButton"
                                            data-modal-toggle="createProductModal"
                                            class="flex items-center justify-center text-white bg-custom-blue hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                            <svg class="h-3.5 w-3.5 mr-1.5 -ml-1" fill="currentColor"
                                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                                aria-hidden="true">
                                                <path clip-rule="evenodd" fill-rule="evenodd"
                                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                            </svg>
                                            Add Category
                                        </button>
                                    </div>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>

                                                <th scope="col" class="p-4">Category Id</th>
                                                <th scope="col" class="p-4">Image</th>
                                                <th scope="col" class="p-4">Title</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="category in categories" :key="category.id"
                                                class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">

                                                <th scope="row"
                                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    <div class="flex items-center mr-3">

                                                        {{ category.id }}
                                                    </div>
                                                </th>
                                                <td class="px-4 py-3">
                                                    <img :src="getImagePath(category.image)" alt="iMac Front Image"
                                                        class="h-8 w-auto mr-3">
                                                </td>
                                                <td
                                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    <div class="flex items-center">

                                                        {{ category.name }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    <div class="flex items-center space-x-4">
                                                        <button type="button" data-drawer-target="drawer-update-product"
                                                            data-drawer-show="drawer-update-product"
                                                            aria-controls="drawer-update-product"
                                                            class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-custom-blue rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path
                                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Edit
                                                        </button>

                                                        <button  @click="confirmDelete(category.id)" type="button" data-modal-target="delete-modal"
                                                            data-modal-toggle="delete-modal"
                                                            class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
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
                    <!-- End block -->
                    <div id="createProductModal" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] md:h-full">
                        <div class="relative p-4 w-full max-w-3xl h-full md:h-auto">
                            <!-- Modal content -->
                            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                                <!-- Modal header -->
                                <div
                                    class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Product</h3>
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
                                <form @submit.prevent="saveCategory">
                                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                        <div>
                                            <label for="name"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category
                                                Name</label>
                                            <input v-model="form.name" name="name" id="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="Type category name" required>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <span
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category
                                            Image</span>
                                        <div class="flex justify-center items-center w-full">
                                            <label for="dropzone-file"
                                                class="flex flex-col justify-center items-center w-full h-64 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                <div class="flex flex-col justify-center items-center pt-5 pb-6">
                                                    <svg aria-hidden="true" class="mb-3 w-10 h-10 text-gray-400"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                    </svg>
                                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-semibold">Click to upload</span>
                                                        or drag and drop
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG,
                                                        or GIF
                                                        (MAX. 800x400px)</p>
                                                </div>
                                                <input id="dropzone-file" @change="handleImageUpload" type="file"
                                                    class="hidden" accept="image/*">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="items-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                                        <button type="submit"
                                            class="w-full sm:w-auto justify-center text-white inline-flex bg-custom-blue hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Add
                                            Category</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- drawer component -->
                    <form @submit.prevent="saveCategory" id="drawer-update-product"
                        class="fixed top-0 left-0 z-40 w-full h-screen max-w-3xl p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800"
                        tabindex="-1" aria-labelledby="drawer-update-product-label" aria-hidden="true">
                        <h5 id="drawer-label"
                            class="inline-flex items-center mb-6 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400">
                            Update Category</h5>
                        <button type="button" data-drawer-dismiss="drawer-update-product"
                            aria-controls="drawer-update-product"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Close menu</span>
                        </button>
                        <div class="grid gap-4 sm:grid-cols-3 sm:gap-6 ">
                            <div class="space-y-4 sm:col-span-2 sm:space-y-6">

                                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                                    <div>
                                        <label for="name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category
                                            Name</label>
                                            <input v-model="form.name" name="name" id="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                placeholder="Type category name" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category
                                        Image</span>
                                    <div class="flex justify-center items-center w-full">
                                        <label for="dropzone-file"
                                            class="flex flex-col justify-center items-center w-full h-64 bg-gray-50 rounded-lg border-2 border-gray-300 border-dashed cursor-pointer dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                            <div class="flex flex-col justify-center items-center pt-5 pb-6">
                                                <svg aria-hidden="true" class="mb-3 w-10 h-10 text-gray-400" fill="none"
                                                    stroke="currentColor" viewbox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">Click to upload</span>
                                                    or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF
                                                    (MAX.
                                                    800x400px)</p>
                                            </div>
                                            <input id="dropzone-file" @change="handleImageUpload" type="file"
                                            class="hidden" accept="image/*">
                                        </label>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-6 sm:w-1/2">
                            <button type="submit"
                                class="text-white bg-custom-blue hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Update
                                product</button>
                            <button type="button"
                                class="text-red-600 inline-flex justify-center items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                <svg aria-hidden="true" class="w-5 h-5 mr-1 -ml-1" fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    </form>
                    <!-- Preview Drawer -->
                    <div id="drawer-read-product-advanced"
                        class="overflow-y-auto fixed top-0 left-0 z-40 p-4 w-full max-w-lg h-screen bg-white transition-transform -translate-x-full dark:bg-gray-800"
                        tabindex="-1" aria-labelledby="drawer-label" aria-hidden="true">
                        <div class="flex bottom-0 left-0 justify-center pb-4 space-x-4 w-full">
                            <button type="button"
                                class="text-white w-full inline-flex items-center justify-center bg-custom-blue hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <svg aria-hidden="true" class="mr-1 -ml-1 w-5 h-5" fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd"
                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Edit
                            </button>
                    
                        </div>
                    </div>
                    <!-- Delete Modal -->
                    <div id="delete-modal" tabindex="-1"
                        class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full h-auto max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button"
                                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                                    data-modal-toggle="delete-modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-6 text-center">
                                    <svg aria-hidden="true"
                                        class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none"
                                        stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure
                                        you want
                                        to delete this category?</h3>
                                        <button @click="deleteCategory(deleteCategoryId.value)"
                                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                        Yes, I'm sure
                                    </button>
                                    <button data-modal-toggle="delete-modal" type="button"
                                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No,
                                        cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
