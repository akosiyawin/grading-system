<template>
  <div class="jumbotron">
    <blockquote class="bg-transparent">
      <h1 class="display-4 text-dark">{{title}}</h1>
    </blockquote>
    <p class="lead" v-html="subtitle"></p>
    <hr class="my-4">
    <p v-html="note"></p>
    <p class="lead" v-if="dashboardBtn">
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
    title : String,
    subtitle : String,
    note : String,
    dashboardBtn: {
      default: true,
      type: Boolean
    }
  }
}
</script>

<style scoped>

</style>