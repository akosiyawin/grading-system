<template>
  <div class="jumbotron">
    <blockquote class="bg-transparent">
      <h1 class="display-4 text-dark">{{Title}}</h1>
    </blockquote>
    <p class="lead">{{Subtitle}}</p>
    <hr class="my-4">
    <p>{{Note}}</p>
    <p class="lead">
      <v-btn
          rounded
          class="bg-primary"
          dark
          href="/handler"
      >
        Go to Dashboard
      </v-btn>
    </p>
  </div>
</template>

<script>
export default {
  name: "Jumbotron",
  props: {
    Title : String,
    Subtitle : String,
    Note : String
  },
  mounted() {
    console.log(this.Title)
  }
}
</script>

<style scoped>

</style>