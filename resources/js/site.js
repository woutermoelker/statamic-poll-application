// This is all you.

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
//
import { createApp, ref } from 'vue'

import TestComponent from "./components/TestComponent.vue";
import PollComponent from "./components/PollComponent.vue";
import CreatePollComponent from "./components/CreatePollComponent.vue";
import UpdatePollComponent from "./components/UpdatePollComponent.vue";
import MediaUpload from "./components/MediaUpload.vue";

import { autoAnimatePlugin } from '@formkit/auto-animate/vue'

let MediaComponent;
createApp({
    components: {
        'test-component': TestComponent,
        'poll-component': PollComponent,
        'media-upload': MediaUpload,
        'create-poll-component': CreatePollComponent,
        'update-poll-component': UpdatePollComponent
    },
}).use(autoAnimatePlugin).mount('#app')
//
// import { createApp } from 'vue';
// const app = createApp({});
//
// import TestComponent from './components/TestComponent.vue';
//
// app.component('test-component', TestComponent);
