<template>
  <div class="container">
    <div class="d-flex align-items-center flex-column w-100 justify-content-center" style="height: 90vh">
      <h3 class="p-2 display-4 text-center" style="font-size: 2rem">STRICTLY FOR TECHNICAL ASSISTANCE ONLY</h3>
      <div class="row d-flex justify-content-center w-100" v-if="students.length === 0">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 mt-5">
          <form class="d-flex justify-content-center mb-3" @submit.prevent="fetchStudents">
            <input v-model="password" class="form-control" placeholder="Password" type="password">
            <button class="btn bg-success ml-3" type="submit">Authenticate</button>
          </form>
        </div>
      </div>
      <v-card v-if="students.length > 0" class="w-100">
        <v-card-title>
          <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              hide-details
              label="Search"
              single-line
              class="mb-3"
          ></v-text-field>
        </v-card-title>
        <v-data-table
            height="400px"
            :headers="headers"
            :items="students"
            :search="search"
        ></v-data-table>
      </v-card>
    </div>
    <dialog-message></dialog-message>
  </div>
</template>

<script>
import api from "./tools/api";
import {mapActions} from "vuex";

export default {
  name: "Assistance",
  data: () => ({
    headers: [
      { text: 'Database ID', value: 'id' },
      { text: 'Name (Ln, Fn, M.)', value: 'name' },
      { text: 'Student No.', value: 'snum' },
      { text: 'Birthdate', value: 'birthdate' },
    ],
    students: [],
    password: null,
    search: null
  }),
  methods: {
    ...mapActions(['setDialog']),
    fetchStudents(){
      api.getAssistanceStudents({password: this.password}).then(r=>{
        this.students = r.data.data
      }).catch(err=>this.setDialog({state: true,message: err.response.data.message}))
    }
  }
}
</script>

<style scoped>

</style>