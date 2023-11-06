<template>
    <div class="border border-dark mb-4">
        <div class="row mb-6">
          <span class="text-center">{{this.question.text}}</span>
        </div>

        <div class="row">
            <div class="col-md-4" ></div>
            <div class="col-md-4" >
                <div class="row mt-5">
                    <div class="col-md-6" v-for="answer in question.answers">
                        <button class="mb-3 btn w-100 border" v-on:click="submitAnswer(answer.id)"
                                v-bind:class="getAnswerButtonClass(answer)">{{answer.text}}</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4" ></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['question'],
        methods: {
            submitAnswer: function (id) {
                if (!this.question.userAnswer) {
                    axios
                        .post('api/trivia-game/submit-answer/' + id)
                        .then(response => {
                            this.$emit('questionSubmitted')
                        })
                }
            },
            getAnswerButtonClass: function (answer) {
                if (this.question.userAnswer) {
                    if (answer.id === this.question.rightAnswer.id) {
                        return 'btn-success';
                    }

                    if (
                        this.question.rightAnswer.id !== this.question.userAnswer.id
                        && answer.id === this.question.userAnswer.id
                    ) {
                        return 'btn-danger';
                    }
                }
                //not answered
                return '';
            },
        }
    }
</script>
