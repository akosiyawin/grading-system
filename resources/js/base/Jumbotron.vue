<template>
  <div class="jumbotron">
    <blockquote class="bg-transparent">
      <h1 class="display-4 text-dark">{{title}}</h1>
    </blockquote>
    <p class="lead">{{subtitle}}</p>
    <hr class="my-4">
    <p>{{note}}</p>
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
  },
  mounted(){
    console.log(this.dashboardBtn)
  }
}
</script>

<style scoped>

</style>