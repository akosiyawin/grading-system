<template>
  <div class="row">
    <div class="col">
      <blockquote class="bg-transparent">
        <h3 class="display-4 text-dark" style="font-size: 2rem">Courses</h3>
      </blockquote>
      <div class="table-responsive" style="height: 400px">
        <table class="table">
          <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Department</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="course in getCourses">
            <td>{{course.id}}</td>
            <td>{{course.title}}</td>
            <td>{{course.department}}</td>
            <td class="project-actions text-right">
              <v-btn class="btn bg-danger" small @click="deestroyCourse(course.id)">
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
        <h3 class="display-4 text-dark" style="font-size: 2rem">Fill out, Course Information</h3>
      </blockquote>
      <form @submit.prevent="saveCourse">
        <v-text-field :error-messages="form.errors.get('title')"
                      filled
                      v-model="form.title"
                      placeholder="Course Description (ex. BS Computer Science)"
        ></v-text-field>
        <v-select
            :items="getDepartments"
            v-model="form.department_id"
            item-text="title"
            item-value="id"
            label="Choose Department"
            :error-messages="form.errors.get('department_id')"
            solo
        ></v-select>
        <v-btn type="submit" class="bg-success mt-3">Save this record</v-btn>
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

export default {
  name: "Courses",
  components: {DialogMessage},
  data: () => ({
    form: new Form({
      title: '',
      department_id: ''
    }),
    deleteDialog: false,
    courseToDelete: null
  }),
  computed: {
    ...mapGetters(['getCourses','getDepartments']),
  },
  methods: {
    ...mapActions(['setCourses','setDialog','pushToCourses']),
    handleYesDelete(){
      api.destroyCourse(this.courseToDelete).then(r=>{
        this.fetchCourses().then(()=>{
          this.setDialog({message: r.data.message,state: true})
        })
      })
      .catch(err=>{
        const r = err.response.data
        this.setDialog({message: r.message +", "+ r.reason,state: true})
      }).finally(() => this.deleteDialog = false)
    },
    handleDeleteDialog(id){
      this.courseToDelete = id
      this.deleteDialog = true
    },
    async fetchCourses(){
      return await api.courses().then(r=>{
        this.setCourses(r.data.data)
      })
    },
    saveCourse(){
      this.form.post(api.courseStore)
      .then(r=>{
        this.pushToCourses(r.data)
        this.form.reset()
        this.setDialog({message: r.message,state: true})
      })
      .catch(err=>{
        this.form.errors.set(err.errors)
      })
    },
    deestroyCourse(id){
      this.handleDeleteDialog(id)
    }
  },
  mounted() {
    this.fetchCourses()
  }
}
</script>

<style scoped>

</style>