<template>
    <!-- Button to Toggle Modal -->
    <button @click="showModal = true"
            class="bg-blue-500 fixed left-10 bottom-10 h-10 w-10 text-center rounded-full hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Poll type:</label>
                            <input v-model="newPoll.startDate" type="date" name="start_date" id="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="pollQuestion">Poll type:</label>
                            <input v-model="newPoll.endDate" type="date" name="end_date" id="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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
            </div>
        </div>
    </div>
</template>

<script>
import {PlusIcon} from "@heroicons/vue/24/solid";
import axios from 'axios';
import MediaUpload from "./MediaUpload.vue";

export default {
    components: {
        PlusIcon,
        MediaUpload
    },
    data() {
        return {
            showModal: false,
            newPoll: {
                startDate: null,
                endDate: null,
                questionText: '',
                type:'radio',
                options: [{ option_text: '' }],
            },
        };
    },
    methods: {
        async createPoll() {
            const questionData = {
                type: this.newPoll.type,
                start_date: this.newPoll.startDate,
                end_date: this.newPoll.endDate,
                question: this.newPoll.questionText,
                options: this.newPoll.options
            };

            await axios.post('/api/poll/create', questionData);

            this.showModal = false;
            this.resetForm();
            this.$emit('pollCreated');
        },
        addOption() {
            this.newPoll.options.push({ option_text: '' });
        },
        resetForm() {
            this.newPoll = { question: '', options: [{ option_text: '' }] };
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
