<template>
    <div class="media-upload">
        <input type="file" @change="onFileChange" accept="video/*,image/*" multiple>
        <button @click="uploadMedia">Upload Media</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            video: null,
            image: null,
            questionId: 1 // Assuming you have the question ID available
        };
    },
    methods: {
        onFileChange(e) {
            const files = e.target.files;
            for (let file of files) {
                if (file.type.startsWith('video/')) {
                    this.video = file;
                } else if (file.type.startsWith('image/')) {
                    this.image = file;
                }
            }
        },
        async uploadMedia() {
            const formData = new FormData();
            if (this.video) {
                formData.append('video', this.video);
            }
            if (this.image) {
                formData.append('image', this.image);
            }

            try {
                await axios.post(`/api/questions/${this.questionId}/upload-media`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                alert('Media uploaded successfully');
            } catch (error) {
                console.error(error);
                alert('Failed to upload media');
            }
        }
    }
};
</script>

<style scoped>
/* Add your styles here */
</style>
