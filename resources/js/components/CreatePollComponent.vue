<template>
    <!-- Button to Toggle Modal -->
    <button @click="showModal = true"
            class="concept7-bg bounce fixed left-10 bottom-10 h-10 w-10 text-center rounded-full hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
        <PlusIcon class="absolute left-0 top-0 text-white h-10 w-10"></PlusIcon>
    </button>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div @click.self="showModal = false" class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Create Poll</h3>
                <div class="mt-2 px-7 py-3">
                    <form @submit.prevent="createPoll">

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Poll Question:</label>
                            <input type="text" id="pollQuestion" v-model="newPoll.questionText"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div v-for="(option, index) in newPoll.options" :key="index" class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" :for="'option' + index">Option {{ index + 1 }}:</label>
                            <input type="text" :id="'option' + index" v-model="option.option_text"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Poll type:</label>
                            <select v-model="newPoll.type" name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Available from:</label>
                            <DatePicker v-model="newPoll.startDate" :lowerLimit="new Date()" name="start_date" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></DatePicker>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Available till:</label>
                            <DatePicker v-model="newPoll.endDate" :lowerLimit="new Date()" name="end_date" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></DatePicker>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollImage">Poll Image:</label>
                            <input type="file" id="pollImage" @change="onImageChange" accept="image/*"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollVideo">Poll Video:</label>
                            <input type="file" id="pollVideo" @change="onVideoChange" accept="video/*"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>



                        <button @click="addOption" type="button"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add Option
                        </button>

                        <div class="mt-5">
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Poll
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Close Button -->
                <button @click="showModal = false"
                        class="mt-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Close
                </button>
                <Errors :errors="errors"></Errors>
            </div>
        </div>
    </div>
</template>

<script>
import {PlusIcon} from "@heroicons/vue/24/solid";
import axios from 'axios';
import MediaUpload from "./MediaUpload.vue";
import Errors from "./Errors.vue";
import DatePicker from 'vue3-datepicker'
import moment from "moment";


export default {

    components: {
        Errors,
        PlusIcon,
        MediaUpload,
        DatePicker,
    },
    data() {
        return {
            showModal: false,
            now: '',
            newPoll: {
                startDate: '',
                endDate: '',
                questionText: '',
                type:'radio',
                options: [{ option_text: '' }],

            },
            errors: [],
            selectedImage: null,
            selectedVideo: null,
        };
    },
    mounted() {
        this.now = moment().format('YYYY-MM-DD');
    },
    methods: {
        onImageChange(e) {
            this.selectedImage = e.target.files[0];
        },
        onVideoChange(e) {
            this.selectedVideo = e.target.files[0];
        },
        addOption() {
            this.newPoll.options.push({ option_text: '' });
        },
        resetForm() {
            this.newPoll = { questionText: '', type: 'radio', options: [{ option_text: '' }], startDate: null, endDate: null };
            this.selectedImage = null;
            this.selectedVideo = null;
        },
        async createPoll() {
            const formData = new FormData();
            formData.append('question', this.newPoll.questionText);
            formData.append('type', this.newPoll.type);
            formData.append('start_date', moment(this.newPoll.startDate).format('YYYY-MM-DD HH:mm:ss'));
            formData.append('end_date', moment(this.newPoll.endDate).format('YYYY-MM-DD HH:mm:ss'));

            // Append each option as a separate field
            this.newPoll.options.forEach((option, index) => {
                formData.append(`options[${index}][option_text]`, option.option_text);
            });

            if (this.selectedImage) {
                formData.append('image', this.selectedImage);
            }

            if (this.selectedVideo) {
                formData.append('video', this.selectedVideo);
            }

            try {
                await axios.post('/api/poll/create', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.showModal = false;
                this.resetForm();
                this.$emit('pollCreated');
            } catch (error) {
                this.errors = error.response.data.errors;
                console.error(error);
            }
        }
    }
};
</script>
<style scoped>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
