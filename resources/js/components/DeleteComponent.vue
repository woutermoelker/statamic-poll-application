<template>
    <button @click="deletePoll" class=" hover:bg-red-100 float-right text-white font-bold py-2 px-4 rounded">
        <trash-icon class="h-5 w-5 text-red-500" />
    </button>
</template>

<script>

import axios from "axios";
import {TrashIcon} from '@heroicons/vue/24/outline';
export default {
    props:['question'],
    name: "DeleteComponent",
    components:{TrashIcon},
    methods: {
        async deletePoll() {
            await axios.delete('/api/poll/' +  this.question.id + '/delete');
            this.$emit('pollDeleted');
        }
    }
}
</script>

<style scoped>

</style>
