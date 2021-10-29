/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import VueRouter from 'vue-router';

window.Vue.use(VueRouter);

Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('trivia-game', require('./components/TriviaComponent.vue').default);
Vue.component('question', require('./components/QuestionComponent.vue').default);

const app = new Vue({
    el: '#app',
});
