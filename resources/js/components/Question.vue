<template>
    <form action="#" v-auto-animate>
        <template v-for="(option, index) in question.options" :key="option.id" class="option">
            <div class="py-4" style="padding-bottom: 20px">
                <div class="float-left" v-if="question.type === 'radio'">
                    <input
                        @click="vote(question, option, question.type)"
                        :checked="isChecked(option)"
                        type="radio"
                    >
                    <label class="pl-4" :for="'option' + option.id">{{ option.option_text }}</label>
                </div>
                <div class="float-left" v-if="question.type === 'checkbox'">
                    <input
                        @click="vote(question, option, question.type)"
                        :checked="isChecked(option)"
                        type="checkbox"
                    >
                    <label class="pl-4" :for="'option' + option.id">{{ option.option_text }}</label>
                </div>
                <span class="pl-2 float-right">{{ option.votes_count }}</span>
            </div>
            <div style="padding-left:20px;">
                <div
                    class="mt-2 flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700"
                    role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div
                        class="flex flex-col justify-center rounded-full overflow-hidden concept7-bg text-xs text-white text-center whitespace-nowrap transition duration-500 dark:bg-purple-700"
                        :style="{'width': calculatePercentage(option.votes_count) + '%'}"></div>
                </div>
            </div>
        </template>
    </form>
</template>
<script setup>

import axios from "axios";
import {ref} from "vue";

const props = defineProps({
    question: {
        type: Object,
        required: true,
    },
});

const checked = ref(getLocalStorage(props.question.id));

const emit = defineEmits(["voted"]);

async function vote(question, option) {
    const {data: {question_id, selected_options}} = await axios.post(`/api/vote/${question.id}/${option.id}`);

    saveLocalStorage(question_id, selected_options);

    checked.value = getLocalStorage(question_id);

    emit("voted");

    console.log(checked.value.length);
}

function isChecked(option) {
    return checked.value.includes(option.id);
}

function calculatePercentage(votes) {
    const highestVotes = Math.max(...this.props.question.options.map(option => option.votes_count));
    if (highestVotes === 0) return '0.00'; // Handle case with no votes
    return ((votes / highestVotes) * 100).toFixed(2);
}

function getLocalStorage(questionId) {
    const storage = JSON.parse(localStorage.getItem(`question.${questionId}`)) || [];

    if (!Array.isArray(storage)) {
        return [];
    }

    return storage;
}

function saveLocalStorage(questionId, storage) {
    localStorage.setItem(`question.${questionId}`, JSON.stringify(storage));
}

</script>
