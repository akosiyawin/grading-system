<template>
  <div id="teacherStudent" class="row" data-app>
    <div class="col-12">
      <div class="container">
        <div class="card">
          <div class="card-body">
            <h1 class="display-4" style="font-size: 2rem">Assigned Subjects</h1>
            <div class="table-responsive mt-3" style="height: 500px">
              <table class="table table-striped projects">
                <thead>
                <tr>
                  <th style="width: 1%">
                    #
                  </th>
                  <th style="width: 45%">
                    Subject Name
                  </th>
                  <th style="width: 20%">
                    Total Students
                  </th>
                  <th style="width: 30%">
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(subject,index) in subjects">
                  <td>
                    {{ index + 1 }}
                  </td>
                  <td>
                    <b>
                      {{ subject.title }}
                    </b>
                    <br/>
                    <small>
                      {{ subject.code }}
                    </small>
                  </td>
                  <td class="font-weight-bold" style="font-size: 2rem">
                    {{ subject.students }}
                  </td>
                  <td class="project-actions text-right">
                    <v-btn small @click="handleViewStudents(subject)">
                      <i class="fas fa-folder mr-1">
                      </i>
                      Students
                    </v-btn>
                    <v-btn small @click="handleViewDialog(subject)">
                      <i class="fas fa-folder mr-1">
                      </i>
                      Enlist
                    </v-btn>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--    <div class="col-6">
          <div class="container">
            <div class="card">
              <div class="card-body">
                <h1 class="display-4" style="font-size: 2rem">Assigned Students</h1>
                <div class="table-responsive mt-3" style="height: 500px">
                  <table class="table table-striped projects">
                    <thead>
                    <tr>
                      <th style="width: 1%">
                        #
                      </th>
                      <th style="width: 40%">
                        Student Name / Course
                      </th>
                      <th style="width: 20%">
                        Student ID
                      </th>
                      <th style="width: 15%">
                        Subjects
                      </th>
                      <th style="width: 35%">
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>
                        1
                      </td>
                      <td>
                        <b>
                          Darwin Ferrer Marcelo
                        </b>
                        <br/>
                        <small>
                          BS Computer Science in Academy of Switzerland
                        </small>
                      </td>
                      <td class="font-weight-bold" style="font-size: 1rem">
                        12-10908
                      </td>
                      <td class="font-weight-bold" style="font-size: 1rem">
                        8
                      </td>
                      <td class="project-actions text-right">
                        <a class="btn btn-primary btn-sm" href="#">
                          <i class="fas fa-folder">
                          </i>
                          View
                        </a>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer">
                <input type="text" class="form-control float-right w-50" placeholder="Search">
              </div>
            </div>
          </div>
        </div>-->
    <!--  Students Add  -->
    <v-dialog
        v-model="viewDialog"
        max-width="50vw"
        persistent
    >
      <v-card>
        <v-card-title class="headline">
          Student Listing
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col sm="12">
              <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  class="mb-3"
                  hide-details
                  label="Search"
                  single-line
                  @keyup.enter="page = 1; fetchStudents()"
              ></v-text-field>
              <v-simple-table
                  dense
                  fixed-header
                  height="400px"
              >
                <template v-slot:default>
                  <thead>
                  <tr>
                    <th style="width: 5%">
                      <div class="icheck-success d-inline">
                        <input id="SelectAllSubject" v-model="selectAll" type="checkbox">
                        <label for="SelectAllSubject"></label>
                      </div>
                    </th>
                    <th style="width: 5%">#</th>
                    <th class="text-left">
                      Name
                    </th>
                    <th class="text-left">
                      Student No.
                    </th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(student,i) in allStudents">
                    <td>
                      <div class="custom-control custom-checkbox">
                        <input :id="'checkBox-'+student.student_id" :checked="selectedStudents.includes(student.student_id) ||
                         (takenStudents.includes(student.student_id) && !unselectedStudents.includes(student.student_id))"
                               class="custom-control-input" type="checkbox"
                               @change="handleSelected(student.student_id)">
                        <label :for="'checkBox-'+student.student_id" class="custom-control-label"></label>
                      </div>
                    </td>
                    <td>{{ rowsPerPage !== 99 ? (i + 1 + (rowsPerPage * page - 1)) - rowsPerPage + 1 : i + 1 }}</td>
                    <td>
                      <b>{{ student.name }}</b> <br>
                      {{ student.course }}
                    </td>
                    <td>{{ student.student_number }}</td>
                    <td>{{ student.status == 1 ? 'Active' : 'Suspended' }}</td>
                  </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </v-col>
            <v-col sm="12">
              <div class="d-flex align-items-center">
                <v-select
                    v-model="rowsPerPage"
                    :items="rowsPerPages"
                    class="mr-3"
                    item-text="text"
                    item-value="value"
                    style="max-width: 70px"
                ></v-select>
                <v-btn class="mr-2" small @click="handlePage(-1)">Prev</v-btn>
                <v-btn :disabled="allStudents.length === 0" small @click="handlePage(1)">Next</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-if="selectedStudents.length > 0" class="bg-dark" x-small @click="clearSelected">Clear
                  Selected
                </v-btn>
                <v-btn v-if="selectedStudents.length > 0 || (takenStudents.length !== unselectedStudents.length)" class="bg-danger ml-1" x-small
                       @click="clearAll">Clear
                  All
                </v-btn>
              </div>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-card-subtitle>{{selectedSubject.title}}</v-card-subtitle>
          <v-spacer></v-spacer>
          <v-btn
              class="text-danger"
              color="green darken-1"
              text
              @click="handleCancel"
          >
            Cancel
          </v-btn>
          <v-btn
              color="green darken-1"
              text
              @click="handleConfirm"
          >
            Confirm
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>


    <v-dialog
        v-model="studentViewDialog"
        max-width="780px"
    >
      <v-card>
        <v-card-title class="headline">
          Encode Grades In Table Mode
        </v-card-title>
        <v-card-subtitle><b>**Note: </b>
          <ul class="ml-4">
            <li>Approved grades by Registrar will be disabled.</li>
            <li> -Grades with value of <b>4/4.00</b> will be recognized as <i>dropped</i>.</li>
            <li> -Grades with value of <b>0</b> will be recognized as <i>incompelete</i>.</li>
          </ul>
        </v-card-subtitle>
        <v-card-text>
          <v-row>
            <v-col sm="12" class="mt-0 pt-0">
              <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  class="mb-3 mt-0 pt-0"
                  hide-details
                  label="Search"
                  single-line
                  @keyup.enter="page = 1; fetchViewStudents()"
              ></v-text-field>
              <v-card-text class="p-0 m-0">
                <v-simple-table dense height="320px">
                  <template v-slot:default>
                    <thead>
                    <tr>
                      <th class="text-left">
                        #
                      </th>
                      <th class="text-left">
                        Name
                      </th>
                      <th class="text-left">
                        Student Number
                      </th>
                      <th class="text-left">
                        Grade
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(student,i) in studentsViewData">
                      <td>{{ rowsPerPage !== 99 ? (i + 1 + (rowsPerPage * page - 1)) - rowsPerPage + 1 : i + 1 }}</td>
                      <td><b>{{ student.name }} </b>
                        <span v-if="parseInt(student.grade) == 4" class="text-danger">(DRP)</span>
                        <span v-else-if="parseInt(student.grade) == 0" class="text-danger">(INC)</span>
                        <br> {{ student.course }}
                      </td>
                      <td>{{ student.student_number }}</td>
                      <td>
                        <div class="d-flex p-1">
                          <input v-model="student.grade" :disabled="student.grade_status == 1" class="form-control" max="101"
                                 min="0" type="number">
                          <v-btn :disabled="student.grade_status == 1" class="bg-danger ml-1"
                                 @click="student.grade = 4">
                            DROPPED
                          </v-btn>
                        </div>
                      </td>
                    </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-card-text>
            </v-col>
            <v-col sm="12" class="my-0 py-0">
              <div class="d-flex align-items-center">
                <v-select
                    v-model="rowsPerPage"
                    :items="rowsPerPages"
                    class="mr-3"
                    item-text="text"
                    item-value="value"
                    style="max-width: 70px"
                ></v-select>
                <div v-if="rowsPerPage !== 99">
                  <v-btn class="mr-2" small @click="handlePage(-1)">Prev</v-btn>
                  <v-btn :disabled="studentsViewData.length === 0" small @click="handlePage(1)">Next</v-btn>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-card-subtitle>{{selectedSubject.title}}</v-card-subtitle>
          <v-spacer></v-spacer>
          <v-btn
              color="green darken-1"
              text
              @click="handleEncodeGradeCancel"
          >
            Close
          </v-btn>
          <v-btn
              color="green darken-1"
              text
              @click="handleEncodeGradeConfirm"
              v-if="studentsViewData.length > 0"
          >
            Confirm
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <dialog-message></dialog-message>

    <!--  SnackBar  -->
    <v-snackbar
        v-model="snackbar.state"
        :timeout="4000"
    >
      {{ snackbar.text }}
      <template v-slot:action="{ attrs }">
        <v-btn
            v-bind="attrs"
            color="blue"
            text
            @click="snackbar.state = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import api from "../tools/api";
import {mapActions} from "vuex";

export default {
  name: "Student",
  data: () => ({
    allStudents: [],
    selectedStudents: [],
    takenStudents: [],
    unselectedStudents: [],
    mySubjects: [],
    viewDialog: false,
    snackbar: {state: false, text: null},
    search: null,
    selectAll: false,
    selectedSubject: {subject_id: null},
    rowsPerPages: [
      {text: 5, value: 5},
      {text: 10, value: 10},
      {text: 15, value: 15},
      {text: 'All', value: 99},
    ],
    page: 1,
    rowsPerPage: 10,
    /*Student Dialog*/
    studentViewDialog: false,
    studentsViewData: [],
    enterGrade: {state: false, grade: null, approval_status: false},
    selectedStudent: null,
    restrictedStudents: [],
    encodeGrading: {state: false, records: []}
  }),
  computed: {
    subjects() {
      return this.mySubjects
    },
    /*    students() {
          return this.allStudents
        },*/
    subjectSelected() {
      return this.selectedSubject.subject_id
    },
    /*    studentsSelected() {
          return this.selectedStudents
        },*/
    /*    studentsTaken() {
          return this.takenStudents
        },*/
  },
  watch: {
    page() {
      if (!this.studentViewDialog) {
        this.fetchStudents()
            .then(() => this.selectAll = false)
      } else {
        this.fetchViewStudents()
      }
    },
    search() {
      if (this.search === "") {
        if (!this.studentViewDialog) {
          this.fetchStudents()
        } else {
          this.fetchViewStudents()
        }
      }
    },
    rowsPerPage() {
      if (!this.studentViewDialog) {
        this.fetchStudents()
      } else {
        this.fetchViewStudents()
      }
    },
    selectAll() {
      if (this.selectAll) {
        const studentsID = this.allStudents.map(e => e.student_id)
        this.selectedStudents = [...this.selectedStudents, ...studentsID.filter(e => !this.selectedStudents.includes(e))]
      }
    },
    restrictedStudents() {
      if (this.restrictedStudents.length > 0) {
        const message = "Failed to remove! There are students, with existing grades already. " + this.restrictedStudents.map(e => e.name).join(', ')
        this.setDialog({state: true, message})
        this.restrictedStudents = []
      }
    }
  },
  methods: {
    ...mapActions(['setDialog']),
    encodeGrades() {
      this.encodeGrading.state = true
    },
    handleEncodeGradeConfirm() {
      const subject_id = this.selectedSubject.subject_id
      const gradesOfStudents = this.studentsViewData.map(e => ({grade: e.grade, student_id: e.student_id}))
      api.updateGrades({students: gradesOfStudents, subject_id}).then(r => {
        this.snackbar.text = r.data.message
        this.snackbar.state = true
        // this.handleEncodeGradeCancel()
      }).catch(err => {
        this.setDialog({state: true, message: err.response.data.message})
      })
    },
    handleEncodeGradeCancel() {
        this.search = null
        this.studentViewDialog = false
    },
    async fetchStudents() {
      return await api.studentWithoutSubject(this.selectedSubject.subject_id, {
        rowsPerPage: this.rowsPerPage,
        page: this.page,
        search: this.search
      }).then(r => {
        this.allStudents = r.data.data
        this.takenStudents = r.data.takenStudents
      })
    },
    clearSelected() {
      this.selectedStudents = []
      this.selectAll = false
    },
    clearAll() {
      this.selectedStudents = []
      this.unselectedStudents = this.takenStudents
    },
    handleViewDialog(subject) {
      this.selectedSubject = subject
      this.fetchStudents().then(r => {
        document.body.classList.add('sidebar-collapse')
        this.viewDialog = !this.viewDialog
      })
    },
    handlePage(val) {
      this.page += val
      if (this.page <= 1) {
        this.page = 1
      }
    },
    async destroyStudentRecord() {
      return await api.destroyStudentSubject(this.selectedSubject.subject_id, {students: this.unselectedStudents})
    },
    async addStudentRecord() {
      return await api.storeStudentSubject({students: this.selectedStudents, subject_id: this.selectedSubject.subject_id})
    },
    handleSelected(student_id) {
      /*Selecting student*/
      if (this.selectedStudents.includes(student_id) && !this.takenStudents.includes(student_id)) {
        this.selectedStudents = this.selectedStudents.filter(e => e !== student_id)
      } else if (!this.takenStudents.includes(student_id) && !this.selectedStudents.includes(student_id)) {
        this.selectedStudents.push(student_id)
      } else if (this.takenStudents.includes(student_id) && this.unselectedStudents.includes(student_id) && !this.selectedStudents.includes(student_id)) {
        this.unselectedStudents = this.unselectedStudents.filter(e => e !== student_id)
        this.selectedStudents.push(student_id)
      } else if (this.takenStudents.includes(student_id) && this.unselectedStudents.includes(student_id)) {
        this.unselectedStudents = this.unselectedStudents.filter(e => e !== student_id)
      } else if (this.takenStudents.includes(student_id) && !this.unselectedStudents.includes(student_id) && this.selectedStudents.includes(student_id)) {
        this.unselectedStudents.push(student_id)
        this.selectedStudents = this.selectedStudents.filter(e => e !== student_id)
      } else if (this.takenStudents.includes(student_id) && !this.unselectedStudents.includes(student_id)) {
        this.unselectedStudents.push(student_id)
      }
    },
    /*todo add a loading animation, disable the confirm to avoid spam*/
    async handleConfirm() {
      if (this.unselectedStudents.length > 0 && this.selectedStudents.length > 0) {
        await this.destroyStudentRecord().then((r) => {
        })
        await this.addStudentRecord().then(() => {
          this.snackbar.text = "Students Saved Successfully!"
        })
      } else if (this.unselectedStudents.length > 0) {
        await this.destroyStudentRecord().then(r => {
          this.restrictedStudents = r.data.restricted
          this.snackbar.text = r.data.message
        })
      } else if (this.selectedStudents.length > 0) {
        await this.addStudentRecord().then(r => {
          this.snackbar.text = r.data.message
        })
      }
      if (this.unselectedStudents.length > 0 || this.selectedStudents.length > 0) {
        this.snackbar.state = true
        this.handleCancel()
        await this.fetchMySubjects()
      } else {
        this.handleCancel()
      }

    },
    handleCancel() {
      this.selectedStudents = []
      this.allStudents = []
      this.takenStudents = []
      this.viewDialog = false
      this.unselectedStudents = []
      this.page = 1
      this.search = null
      this.selectAll = false
    },
    async fetchViewStudents() {
      return await api.studentOfMySubject(this.selectedSubject.subject_id, {
        rowsPerPage: this.rowsPerPage,
        page: this.page,
        search: this.search
      }).then(r => {
        this.studentsViewData = r.data.data
      })
    },
    handleViewStudents(subject) {
      this.search = null
      this.selectedSubject = subject
      this.fetchViewStudents().then(() => {
        if (this.studentsViewData.length > 0) {
          document.body.classList.add('sidebar-collapse')
          this.studentViewDialog = !this.studentViewDialog
        } else {
          this.setDialog({state: true, message: "There are no students for this subject"})
        }
      })
    },
    /*    handleGradeView(student_id) {
          this.selectedStudent = student_id
          api.studentGrade(student_id, this.subjectSelected).then(r => {
            if (!this.enterGrade.state) this.enterGrade.state = true
            this.enterGrade.grade = r.data.data.grade
            this.enterGrade.approval_status = r.data.data.approval_status !== 0
          })
        },*/
    saveGrade() {
      api.updateGrade(this.selectedStudent, this.subjectSelected, {
        grade: this.enterGrade.grade,
      }).then(r => {
        this.enterGrade.state = false
        this.snackbar.text = "Grades Updated Successfully! Waiting for Approval."
        this.snackbar.state = true
        this.fetchViewStudents()
      }).catch(err => {
        this.snackbar.text = err.response.data.message
        this.snackbar.state = true
      })
    },
    handleCloseViewStudent() {
      this.studentViewDialog = !this.studentViewDialog
      this.enterGrade.state = false
      this.search = null
      this.page = 1
      this.rowsPerPage = 10
    },
    async fetchMySubjects() {
      await api.getTeacherSubjects().then(r => {
        this.mySubjects = r.data.data
      })
    }
  },
  mounted() {
    this.fetchMySubjects()
  }
}
</script>

<style>
/*#teacherStudent .v-dialog {
  overflow-y: hidden;
}*/
</style>