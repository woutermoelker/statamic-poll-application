<template>
    <!-- Button to Toggle Modal -->
    <button @click="initializeComponent"
            class=" hover:bg-blue-100 text-white font-bold py-2 px-4 rounded float-right">
        <pencil-icon class="text-blue-400 h-5 w-5"></pencil-icon>
    </button>

    <!-- Modal -->
    <div v-if="showModal" class="fixed z-10 inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div @click.self="showModal = false" class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Update Poll</h3>
                <div class="mt-2 px-7 py-3">
                    <form @submit.prevent="updatePoll">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Poll Question:</label>
                            <input type="text" id="pollQuestion" v-model="question.question"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div v-for="(option, index) in question.options" :key="index" class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" :for="'option' + index">Option {{ index + 1 }}:</label>
                            <input type="text" :id="'option' + index" v-model="option.option_text"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollType">Poll Type:</label>
                            <select v-model="question.type" id="pollType" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Available from:</label>
                            <DatePicker v-model="question.start_date" :lowerLimit="new Date()" name="start_date" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></DatePicker>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Available till:</label>
                            <DatePicker v-model="question.end_date" :lowerLimit="question.start_date" name="end_date" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></DatePicker>
                        </div>

                        <div class="mt-5">
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Poll
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
import {PencilIcon} from '@heroicons/vue/24/outline';
import axios from 'axios';
import Errors from "./Errors.vue";
import DatePicker from "vue3-datepicker";
export default {
    components:{Errors, PencilIcon, DatePicker},
    props: {
        question: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            errors: [],
            showModal: false,
        };
    },
    mounted() {
        this.question.start_date = new Date(this.question.start_date);
        this.question.end_date = new Date(this.question.end_date);
    },
    methods: {
        initializeComponent() {
            this.showModal = true;
        },
        addOption() {
            this.question.options.push({ text: '' });
        },
        async updatePoll() {
            await axios.post(`/api/poll/${this.question.id}/update`, {
                question: this.question.question,
                start_date: this.question.start_date,
                end_date: this.question.end_date,
                type: this.question.type,
                options: this.question.options
            }) .then(response => {
                this.$emit('pollUpdated', response.data);
                this.showModal = false;
            }).catch(error => {
                this.errors = error.response.data.errors;
                console.log(error);
            });

            this.$emit('pollUpdated');
        }
    },
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

