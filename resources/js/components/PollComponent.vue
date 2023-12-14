<template>
    <div class="grid grid-cols-3 gap-6">
        <div v-for="question in questions" :key="question.id">
            <div class="max-w-sm grid grid-cols-1 pb-4 rounded overflow-hidden shadow-lg" v-if="question.is_visible">
                <div class="pt-2" v-if="isSuper">
                    <delete-component :question="question"></delete-component>
                    <update-poll-component :question="question"></update-poll-component>
                </div>
                <div class="px-4">
                    <div class="font-bold text-xl mb-2">{{ question.question }}</div>
                </div>
                <div class="px-6">
                    <Question
                        @voted="onVoted"
                        :question="question"
                    />
                </div>

            </div>
        </div>
    </div>
    <create-poll-component v-if="isSuper"></create-poll-component>

</template>

<script>
import axios from "axios";
import Question from "./Question.vue";
import CreatePollComponent from "./CreatePollComponent.vue";
import DeleteComponent from "./DeleteComponent.vue";
import UpdatePollComponent from "./UpdatePollComponent.vue";

export default {
    components: {DeleteComponent, Question, CreatePollComponent, UpdatePollComponent},
    props: ['isSuper'],
    data() {
        return {
            questions: [], // Initialize as an empty array
            selectedOptions: {} // Track the selected radio option for each question
        };
    },
    mounted() {
        console.log(this.user);
        this.getPolls();
    },
    methods: {
        onVoted() {
            this.getPolls();
        },
        async vote(question, option) {

        },
        getPolls() {
            axios.get('/api/polls/get')
                .then(response => {
                    this.questions = response.data.map(question => ({
                        ...question,
                        options: question.options.sort((a, b) => b.votes_count - a.votes_count)
                    }));
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },

};
</script>

<style scoped>
.poll {
    text-align: center;
}

.option {
    margin: 10px 0;
}
</style>
