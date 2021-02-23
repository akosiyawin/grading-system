<template>
  <div class="row" data-app>
    <div class="col">
      <blockquote class="bg-transparent">
        <h3 class="display-4 text-dark" style="font-size: 2rem">Departments</h3>
      </blockquote>
      <div class="table-responsive" style="height: 300px">
        <table class="table">
          <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="department in getDepartments">
            <td>{{ department.id }}</td>
            <td>{{ department.title }}</td>
            <td class="project-actions text-right">
              <v-btn class="btn bg-primary" small href="#">
                <i class="fas fa-folder">
                </i>
                View
              </v-btn>
              <v-btn class="btn bg-danger" small @click="deleteDepartment(department.id)">
                <i class="fas fa-trash">
                </i>
                Delete
              </v-btn>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col">
      <blockquote class="bg-transparent">
        <h3 class="display-4 text-dark" style="font-size: 2rem">Fill out, Department Information</h3>
      </blockquote>
      <form @submit.prevent="saveDepartment">
        <v-text-field :error-messages="form.errors.get('title')"
                      filled
                      v-model="form.title"
                      placeholder="Department Description (ex. Computer Studies Department)"></v-text-field>
        <v-btn type="submit" class="bg-success">Save this record</v-btn>
      </form>
    </div>

    <!--    Delete Dialog-->
    <v-dialog
        v-model="deleteDialog"
        persistent
        max-width="390"
    >
      <v-card>
        <v-card-title class="headline">
          Are you sure you want to delete this course?
        </v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="green darken-1"
              text
              @click="deleteDialog = false"
              class="text-danger"
          >
            No
          </v-btn>
          <v-btn
              color="green darken-1"
              text
              @click="handleYesDelete"
          >
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


  </div>
</template>

<script>
import api from "../tools/api";
import Form from "../tools/form";
import {mapActions, mapGetters} from "vuex";
import DialogMessage from "../base/DialogMessage";


/*Tapos na department fetching and storing*/
export default {
  name: "Department",
  data: () => ({
    form: new Form({
      title: null
    }),
    deleteDialog: false,
    departmentToDelete: null
  }),
  computed: {
    ...mapGetters(['getDepartments'])
  },
  methods: {
    ...mapActions(['setDepartments', 'setDialog']),
    handleYesDelete(){
      api.destroyDepartment(this.departmentToDelete)
      .then((r) => {
        this.fetchDepartments().then(()=>{
          this.setDialog({state: true, message: r.data.message})
        })
      })
      .catch(err=>{
        const r = err.response.data
        this.setDialog({message: r.message +", "+ r.reason,state: true})
      }).finally(() => this.deleteDialog = false)
    },
    saveDepartment() {
      this.form.post(api.departmentStore)
      .then(r => {
        this.setDialog({state: true, message: r.message})
        this.fetchDepartments()
        this.form.reset()
      })
      .catch(err => this.form.errors.set(err.errors))
    },
    async fetchDepartments() {
      return await api.departments().then(r => {
        this.setDepartments(r.data.data)
      })
    },
    deleteDepartment(id) {
      this.departmentToDelete = id
      this.deleteDialog = true
    }
  },
  mounted() {
    this.fetchDepartments()
  }
}
</script>

<style scoped>

</style>