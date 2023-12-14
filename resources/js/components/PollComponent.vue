<template>
    <div class="grid grid-cols-1 md:grid-cols-3 mb-20 gap-6">
        <div v-for="question in questions" :key="question.id">
            <div class="max-w-sm grid grid-cols-1 pb-6 rounded overflow-hidden relative shadow-lg p-4" v-if="question.is_visible">
                <div class="max-h-40" v-if="question.media_path">
                    <!-- Display Image if it's an image -->
                    <img v-if="isImage(question.media_path)" :src="'/storage/' + question.media_path" alt="Poll Media">
                    <!-- Display Video if it's a video -->
                    <video v-if="isVideo(question.media_path)" controls>
                        <source :src="'/storage/' + question.media_path" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="pt-2 float-right" v-if="isSuper">
                    <delete-component @pollDeleted="onVoted" :question="question"></delete-component>
                    <update-poll-component :question="question"></update-poll-component>
                </div>
                <div class="px-4 ">
                    <div class="float-left font-bold text-xl mb-2">{{ question.question }}</div>
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
    <create-poll-component @pollCreated="onVoted" v-if="isSuper"></create-poll-component>

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
        isImage(url) {
            return /\.(jpg|jpeg|png|gif|webp)$/i.test(url);
        },
        isVideo(url) {
            return /\.(mp4|mov|avi|flv|wmv)$/i.test(url);
        },
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
