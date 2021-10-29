<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-2">
          <button v-if="!trivia_id" v-on:click="startNewGame">
            <span> Start new game </span>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="gameLoading"></span>
          </button>
          </div>
      </div>

      <div v-if="trivia_id" >
        <div class="row">
          <div class="col-md-8"></div>
          <div class="col-md-4">
            <button v-on:click="startNewGame">
              <span>Restart </span>
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="gameLoading"></span>
            </button>
          </div>
        </div>
        <trivia-game :id="trivia_id" ref="triviaGameComponent" :key="trivia_id"></trivia-game>
      </div>
    </div>
</template>

<script>
    export default {
        data () {
          return {
            trivia_id: null,
            gameLoading: false,
          }
        },
        mounted() {
            const triviaId = localStorage.getItem('trivia_id');
            if (triviaId) {
              this.trivia_id = triviaId;
            }
        },
        methods: {
          startNewGame: function () {
            this.gameLoading = true;

            axios
                .post('/api/trivia-game/create')
                .then(response => {
                    this.trivia_id = response.data.data.id;
                    localStorage.setItem('trivia_id', response.data.data.id);
                }).finally(() => this.gameLoading = false);
          }
        }
    }
</script>
