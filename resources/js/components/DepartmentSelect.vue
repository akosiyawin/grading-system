<template>
  
</template>

<script>
export default {
  name: "DepartmentSelect",
  data: () => ({
    items : [
      {title: "Gen Ed", value: 1},
      {title: "CS Department", value: 2},
      {title: "HRM", value: 3},
    ]
  }),
  created() {
    console.log(12)
  }
}
</script>

<style scoped>

</style>