<template>
    <form action="#" v-auto-animate>
        <template v-for="(option, index) in question.options" :key="option.id" class="option">
            <div class="py-4">
                <div class="float-left" v-if="question.type === 'radio'">
                    <input :checked="option.votes_count > 0" type="radio" :name="'radio' + question.id" :id="'option' + option.id"
                           @change="vote(question, option)">
                    <label class="pl-4" :for="'option' + option.id">{{ option.option_text }}</label>
                </div>
                <div class="float-left" v-if="question.type === 'checkbox'">
                    <input type="checkbox" :id="'option' + option.id"
                           @change="vote(question, option)">
                    <label class="pl-4" :for="'option' + option.id">{{ option.option_text }}</label>
                </div>
                <span class="pl-2 float-right">{{ option.votes_count }}</span>
            </div>
            <div>
                <div
                    class="mt-2 flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700"
                    role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div
                        class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-blue-500"
                        :style="{'width': calculatePercentage(option.votes_count) + '%'}"></div>
                </div>
            </div>
        </template>
    </form>
</template>
<script setup>

import axios from "axios";


const props = defineProps({
    question: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["voted"]);

async function vote(question, option) {
    await axios.post(`/api/vote/${question.id}/${option.id}`);
    // const {data: {question: {options}}} = await axios.get(`/api/questions/${question.id}`);
    // question.options = options;
    emit("voted");
}

function calculatePercentage(votes) {
    const highestVotes = Math.max(...this.props.question.options.map(option => option.votes_count));
    if (highestVotes === 0) return '0.00'; // Handle case with no votes
    return ((votes / highestVotes) * 100).toFixed(2);
}

</script>
