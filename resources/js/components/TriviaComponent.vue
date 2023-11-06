<template>
    <div class="container" v-if="dataLoaded">
        <div class="row">
            <div class="col-md-4" ></div>
            <div class="col-md-4" >
                <div v-if="triviaData.is_finished">
                  <h3 class="text-center text-success" v-if="triviaData.is_won">You win!</h3>
                  <h3 class="text-center text-danger" v-if="!triviaData.is_won">You lose!</h3>
                </div>
                <div >
                    <h3 class="text-center" v-if="!triviaData.is_finished">
                      Question {{triviaData.answered_questions_count + 1}} / {{triviaData.questions_count}}
                    </h3>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>

        <div v-if="triviaData.currentQuestion">
            <question :question="triviaData.currentQuestion" @questionSubmitted="loadGame" :key="triviaData.currentQuestion.id"></question>
        </div>
        <question v-for="question in reversedAnsweredQuestionsArray" :key="question.id" :question="question"></question>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data () {
          return {
            triviaData: null,
            dataLoaded: false
          }
        },
        mounted() {
          this.loadGame();
        },
        methods: {
            loadGame: function () {
                axios
                    .get('/api/trivia-game/' + this.id)
                    .then(response => {
                        this.triviaData = response.data.data;
                        this.dataLoaded = true;
                    })
            },
        },
        computed: {
            reversedAnsweredQuestionsArray: function () {
                let array = [...this.triviaData.answeredQuestions];
                return array.reverse()
            }
        }

    }
</script>
