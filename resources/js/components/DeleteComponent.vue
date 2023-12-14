<template>
        <button @click="showConfirmationModal = true" class="hover:bg-red-100 float-right text-white font-bold py-2 px-4 rounded">
            <TrashIcon class="h-5 w-5 text-red-500" />
        </button>

        <!-- Confirmation Modal -->
        <div v-if="showConfirmationModal" class="fixed z-10 inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Are you sure?</h3>
                    <p class="text-gray-500">Do you really want to delete this poll? This action cannot be undone.</p>
                    <div class="mt-4">
                        <button @click="deletePoll" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Yes, delete it</button>
                        <button @click="showConfirmationModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
</template>
<script>
import axios from "axios";
import { TrashIcon } from '@heroicons/vue/24/outline';

export default {
    props: ['question'],
    name: "DeleteComponent",
    components: { TrashIcon },
    data() {
        return {
            showConfirmationModal: false,
        };
    },
    methods: {
        async deletePoll() {
            await axios.delete('/api/poll/' +  this.question.id + '/delete');
            this.showConfirmationModal = false;
            this.$emit('pollDeleted');
        }
    }
}
</script>

<style scoped>

</style>
