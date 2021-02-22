<template>
  <div class="row justify-content-center mb-5" data-app>
    <div class="col-xl-5 col-md-8">
      <blockquote class="bg-transparent">
        <h1 class="display-4 text-dark" style="font-size: 2rem">Fill out, Student Information</h1>
      </blockquote>
      <form @submit.prevent="saveStudent">
        <div class="row">
          <div class="col-12 py-0">
            <v-text-field :error-messages="form.errors.get('username')" small type="text" v-model="form.username" filled
                          placeholder="Student ID (Username)"/>
          </div>
        </div>
        <div class="row">
          <div class="col-12 py-0">
            <v-select
                v-model="form.course_id"
                :items="courses"
                :error-messages="form.errors.get('course_id')"
                item-value="id"
                item-text="title"
                filled
                placeholder="Course"
            ></v-select>
          </div>
        </div>
        <div class="row">
          <div class="col-12 py-0">
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <label class="input-group-text">Birthdate</label>
              </div>
              <input
                  v-model="form.birthdate" type="date" :class="{'is-invalid' : form.errors.get('birthdate')}"
                  class="form-control" placeholder="Birthdate"
                  aria-label="Birthdate"
                  aria-describedby="bdate">
              <div class="invalid-feedback">
                {{ form.errors.get('birthdate') }}
              </div>
            </div>
            <v-text-field
                :error-messages="form.errors.get('first_name')"
                v-model="form.first_name" small type="text" class="m-0" filled placeholder="First Name"/>
            <v-text-field
                :error-messages="form.errors.get('middle_name')"
                v-model="form.middle_name" small type="text" class="m-0" filled placeholder="Middle Name"/>
            <v-text-field
                :error-messages="form.errors.get('last_name')"
                v-model="form.last_name" small type="text" class="m-0" filled placeholder="Last Name"/>
            <v-btn type="submit" class="bg-success">Submit this student</v-btn>
          </div>
        </div>
      </form>
    </div>
    <div class="col-xl-6 col-md-4">
      <blockquote class="bg-transparent">
        <h1 class="display-4 text-dark" style="font-size: 2rem">Encode Students Using CSV File</h1>
      </blockquote>
      <form action="">
        <label for="csvFileUpload">Please select a valid CSV file.</label>
        <div class="input-group mb-3">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="csvFileUpload">
            <label class="custom-file-label" for="csvFileUpload">Choose .csv file</label>
          </div>
          <div class="input-group-append">
            <button class="btn bg-success" type="button" @click="encodeStudents">Encode</button>
          </div>
        </div>
      </form>
    </div>

    <div class="row ">
      <div class="col-12 mb-5">
        <blockquote class="bg-transparent">
          <h1 class="display-4 text-dark" style="font-size: 2rem">Students for this semester</h1>
        </blockquote>
        <v-card>
          <v-card-title>
            <v-text-field
                v-model="searchStudent"
                append-icon="mdi-magnify"
                label="Search (Name, Student No.)"
                single-line
                hide-details
                @keyup.enter="page = 1; fetchStudents()"
            ></v-text-field>
          </v-card-title>
          <v-simple-table
              fixed-header
              height="520px"
          >
            <template v-slot:default>
              <thead>
              <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Birthdate</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Status</th>
                <th style="width:25%"></th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(student,i) in students">
                <td>{{ rowsPerPage !== 99 ? (i + 1 + (rowsPerPage * page-1))-rowsPerPage + 1 : i+1 }}</td>
                <td>
                  <b>
                    {{ student.student_number }}
                  </b>
                  <br/>
                  <small>
                    {{ student.course }}
                  </small>
                </td>
                <td>{{ student.birthdate }}</td>
                <td>{{ student.first_name }}</td>
                <td>{{ student.middle_name }}</td>
                <td>{{ student.last_name }}</td>
                <td>
                  <span v-if="student.status == 1" class="badge badge-success">Active</span>
                  <span v-else class="badge badge-danger">Suspended</span>
                </td>
                <td class="project-actions text-right">
                  <v-btn x-small class="bg-primary" :href="/studentProfile/+student.student_id">
                    <i class="fas fa-folder">
                    </i>
                    View
                  </v-btn>
                  <v-btn x-small class="bg-success" @click="handleEditDialog(student)">
                    <i class="fas fa-pencil-alt mr-1">
                    </i>
                    Edit
                  </v-btn>
                  <v-btn x-small class="bg-warning" @click="updateUserStatus(student.user_id)">
                    <i class="fas fa-exclamation mr-1">
                    </i>
                    Suspend
                  </v-btn>
                  <v-btn x-small class="bg-danger" @click="handleDeleteDialog(student.user_id)">
                    <i class="fas fa-trash mr-1">
                    </i>
                    Trash
                  </v-btn>
                </td>
              </tr>
              </tbody>
            </template>
          </v-simple-table>
          <v-spacer></v-spacer>
          <div class="d-flex px-3 align-items-center">
            <v-select v-model="rowsPerPage" :items="rowsPerPages" item-text="title" item-value="value"
                      style="max-width: 85px">
            </v-select>
            <div v-if="rowsPerPage !== 99">
              <v-btn small class="ml-2" @click="handlePage(-1)">Prev</v-btn>
              <v-btn small class="ml-2" @click="handlePage(1)" :disabled="students.length === 0">Next</v-btn>
            </div>
            <v-spacer></v-spacer>
            <div >
              <v-text-field v-model="page" type="number" label="page" min="1" style="max-width: 90px"></v-text-field>
            </div>
          </div>
        </v-card>
      </div>
    </div>
    <dialog-message></dialog-message>

    <!--    Question Prompt -->
    <v-dialog
        v-model="questionDialog.state"
        persistent
        max-width="400"
    >
      <v-card>
        <v-card-title class="headline">
          {{questionDialog.title}}
        </v-card-title>
        <v-card-text>{{questionDialog.body}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="green darken-1"
              text
              @click="questionDialog.state = false"
          >
            No
          </v-btn>
          <v-btn
              color="green darken-1"
              text
              @click="questionDialog.handler"
          >
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
        v-model="duplicatedDialog.state"
        persistent
        max-width="680"
    >
      <v-card>
        <v-card-title class="headline">
          Duplicate Entries Found
        </v-card-title>
        <v-card-text>Students were stored successfully, but there are duplicate entries found.</v-card-text>
        <v-card-text>
          <v-card-title class="ml-0 pl-0">Duplicate Entries</v-card-title>
          <v-card-subtitle class="ml-0 pl-0 text-danger">*These students were not inserted.</v-card-subtitle>
          <v-simple-table dense height="300px">
            <template v-slot:default>
              <thead>
              <tr>
                <th class="text-left">
                  #
                </th>
                <th class="text-left">
                  Student No.
                </th>
              </tr>
              </thead>
              <tbody>
              <tr
                  v-for="(item,i) in duplicatedDialog.students"
                  :key="i"
              >
                <td>{{ i+1 }}</td>
                <td>{{ item }}</td>
              </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="green darken-1"
              text
              @click="handleCloseDuplicateDialog"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


    <!--  Edit student Dialog  -->
    <v-dialog
        v-model="editStudentDialog"
        persistent
        max-width="600px"
    >
      <v-card>
        <v-card-title>
          <span class="headline">User Profile</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="6">
                <v-text-field
                    v-model="editForm.username"
                    type="text"
                    filled
                    :error-messages="editForm.errors.get('username')"
                    placeholder="Student ID"
                />
              </v-col>
              <v-col cols="6">
                <v-select
                    v-model="editForm.course_id"
                    :items="courses"
                    :error-messages="editForm.errors.get('course_id')"
                    item-value="id"
                    item-text="title"
                    filled
                    placeholder="Course"
                ></v-select>
              </v-col>
              <v-col cols="12">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <label class="input-group-text">Birthdate</label>
                  </div>
                  <input
                      v-model="editForm.birthdate" type="date" :class="{'is-invalid' : editForm.errors.get('birthdate')}"
                      class="form-control" placeholder="Birthdate"
                      aria-label="Birthdate"
                      >
                  <div class="invalid-feedback">
                    {{ editForm.errors.get('birthdate') }}
                  </div>
                </div>
              </v-col>
              <v-col cols="6">
                <v-text-field
                    v-model="editForm.first_name"
                    type="text"
                    filled
                    :error-messages="editForm.errors.get('first_name')"
                    placeholder="First Name"
                />
              </v-col>
              <v-col cols="6">
                <v-text-field
                    v-model="editForm.middle_name"
                    type="text"
                    filled
                    :error-messages="editForm.errors.get('middle_name')"
                    placeholder="Middle Name"
                />
              </v-col>
              <v-col
                  cols="12"
              >
                <v-text-field
                    v-model="editForm.last_name"
                    type="text"
                    filled
                    :error-messages="editForm.errors.get('last_name')"
                    placeholder="Last Name"
                />
              </v-col>
            </v-row>
          </v-container>
          <small>*fill out this edit form to update the selected student</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="blue darken-1"
              text
              @click="editStudentDialog = false"
          >
            Close
          </v-btn>
          <v-btn
              color="blue darken-1"
              text
              @click="handleEditSave"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Form from "../tools/form";
import api from "../tools/api";
import {mapActions} from "vuex";

export default {
  name: "Student",
  data: () => ({
    form: new Form({
      username: null,
      course_id: null,
      birthdate: null,
      first_name: null,
      middle_name: null,
      last_name: null,
    }),
    courses: [],
    students: [],
    rowsPerPages: [
      {value: 1, title: 1},
      {value: 5, title: 5},
      {value: 10, title: 10},
      {value: 99, title: 'all'},
    ],
    rowsPerPage: 10,
    page: 1,
    searchStudent: null,
    questionDialog: {state: false, title: null, body: null,handler : null},
    selectedUserID : null,
    duplicatedDialog : {state: false, students: []},
    editForm: new Form({
      id: null,
      first_name: null,
      middle_name: null,
      last_name: null,
      birthdate: null,
      course_id: null,
      username: null,
    }),
    editStudentDialog: false,
  }),
  watch: {
    page() {
      if(!this.page) this.page = 1
        this.fetchStudents()
    },
    rowsPerPage() {
      this.fetchStudents()
    },
    searchStudent() {
      if (!this.searchStudent) {
        this.page = 1
        this.fetchStudents()
      }
    }
  },
  methods: {
    ...mapActions(['setDialog']),
    fetchCourses() {
      api.courses().then(r => {
        this.courses = r.data.data
      })
    },
    handleEditSave(){
      this.editForm.patch(api.updateStudent(this.editForm.id)).then(r=>{
        this.fetchStudents()
        this.editForm.reset()
        this.editStudentDialog = false
      }).catch(err=> this.editForm.errors.set(err.errors))
    },
    handleEditDialog(item) {
      this.editForm.id = item.user_id
      this.editForm.birthdate = item.birthdate_real
      this.editForm.username = item.student_number
      this.editForm.first_name = item.first_name
      this.editForm.middle_name = item.middle_name
      this.editForm.last_name = item.last_name
      this.editForm.course_id = item.course_id
      this.editStudentDialog = true
    },
    saveStudent() {
      this.form.errors.clear()
      this.form.post(api.storeStudent).then(r => {
        this.form.reset()
        this.fetchStudents()
        this.setDialog({state: true, message: r.message})
      })
      .catch(err => {
        this.form.errors.set(err.errors)
      })
    },
    fetchStudents() {
      api.students({search: this.searchStudent, rowsPerPage: this.rowsPerPage, page: this.page}).then(r => {
        this.students = r.data.data
      })
    },
    handlePage(val) {
      this.page += val

      if (this.page <= 1) {
        this.page = 1
      }
    },
    async encodeStudents() {
      //todo add a loading animation
      const file = document.getElementById('csvFileUpload').files.item(0)
      const text = await file.text();
      const data = text.split('\n')
      data.shift() //Removes the first column [name,course,etc...]
      data.pop()
      api.bulkStudents({data})
      .then(r=>{
        this.fetchStudents()
        if(r.data.exist.length){
          this.duplicatedDialog.students = r.data.exist
          this.duplicatedDialog.state = true
        }else{
          this.setDialog({state: true, message: r.data.message})
        }
      })
    },
    handleCloseDuplicateDialog(){
      this.duplicatedDialog.students = []
      this.duplicatedDialog.state = false
    },
    updateUserStatus(id){
      api.updateUserStatus(id).then(r=>{
        this.fetchStudents()
      })
    },
    deleteStudent(){
      api.destroyStudent(this.selectedUserID).then(r=>{
        this.fetchStudents()
        this.questionDialog.state = false
      }).catch(err=> {
        this.questionDialog.state = false
        this.setDialog({state: true, message: err.response.data.message + ". " + err.response.data.reason})
      })
    },
    handleDeleteDialog(id){
      this.questionDialog.body = "Are you sure you want to delete this student?"
      this.questionDialog.title = "Delete Student"
      this.questionDialog.state = true
      this.selectedUserID = id
      this.questionDialog.handler = this.deleteStudent
    }
  },
  mounted() {
    this.fetchCourses()
    this.fetchStudents()
  }
}
</script>

<style scoped>

</style>