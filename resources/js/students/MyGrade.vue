<template>
  <v-app id="inspire">
    <StudentBase></StudentBase>
    <v-main>
      <div class="row pt-5">
        <div class="col-md-4">
          <div class="pa-3">
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{getAuthUser.name}}</h3>
                <p class="text-muted text-center">{{getAuthUser.course}}</p>
                <ul class="list-group list-group-unbordered mb-3 pl-0">
                  <li class="list-group-item">
<!--                    TODO display na lang ng personal info, grades are all done na! testing na lang if na approve na ng registrar
                      ang grade-->
                    <b>Student No.</b> <a class="float-right">{{getAuthUser.student_number}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Birthdate</b> <a class="float-right">{{getAuthUser.birthdate}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Status</b> <a class="float-right">
                    <v-chip
                        class="bg-success"
                        small
                    >
                      {{getAuthUser.status == 1 ? 'Active' : 'Suspended'}}
                    </v-chip>
                  </a>
                  </li>
                </ul>
              </div>
            </div>
            <ul class="list-group pl-0" v-if="schoolYears.length > 0">
              <li class="list-group-item bg-primary">School Year</li>
              <li v-for="item in schoolYears" class="list-group-item" @click="handleClickYear(item)">
                {{ item.year }}
              </li>
            </ul>
            <v-alert
                v-else
                color="blue-grey"
                dark
                icon="mdi-school"
            >
              Your Grades Are Still On Process
            </v-alert>
          </div>
        </div>
        <div class="col-md-8" v-if="selectedSchoolYear">
          <div class="card m-3">
            <div class="card-header">
              <ul id="navSemester" class="nav nav-pills semesterItem">
                <li v-for="item in semesters" class="nav-item ml-1">
                  <a class="nav-link" @click="handleClickSemester(item)">{{ item.title }}</a></li>
              </ul>
            </div>
          </div>
          <v-alert
              class="m-3"
              v-if="grades.length > 0"
              color="blue-grey"
              dark
              icon="mdi-school"
          >
            Your Final Grade For School Year {{selectedSchoolYear.year}} ({{selectedSemester.title}})
          </v-alert>
          <div class="card m-3" v-if="grades.length > 0">
            <div class="card-body">
              <v-card-subtitle>
                <b>**Note: </b>
                If this is not you, message us at the Eastwoods
                <a href="https://www.facebook.com/eastwoodstech">Technical Assistance Page </a>
              </v-card-subtitle>
              <div class="printable">
                <v-simple-table dense>
                  <template v-slot:default>
                    <thead>
                    <tr>
                      <th class="text-left">
                        Subject Code
                      </th>
                      <th class="text-left">
                        Subject Name
                      </th>
                      <th class="text-left">
                        Credit
                      </th>
                      <th class="text-left">
                        Instructor
                      </th>
                      <th class="text-left">
                        Grade
                      </th>
                      <th class="text-left">
                        Remarks
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in grades">
                      <td>{{ item.code }}</td>
                      <td>{{ item.title }}</td>
                      <td>{{ item.units }}</td>
                      <td>{{ item.teacher }}</td>
                      <td v-if="item.grade === 'INC'" class="text-danger">INC</td>
                      <td v-else-if="item.grade === 'DRP'" class="text-danger">DRP</td>
                      <td v-else>{{ item.grade }}</td>
                      <td>{{ item.remarks }}</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td colspan="2"><b>{{totalUnits}}</b> <br>Total Credits</td>
                      <td colspan="2"><b>{{totalGrade}}</b> <br>Grade Average</td>
                    </tr>
                    </tfoot>
                  </template>
                </v-simple-table>
              </div>
            </div>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="success" :href="'/printGrade/'+selectedSchoolYear.id+'/'+selectedSemester.id">Print</v-btn>
            </v-card-actions>
          </div>
          <v-alert
              class="m-3"
              v-else
              text
              type="info"
          >
            Please select a semester to view your grades
          </v-alert>
        </div>
      </div>
      <dialog-message></dialog-message>
    </v-main>
  </v-app>
</template>

<script>
import StudentBase from "../base/StudentBase";
import api from "../tools/api";
import {mapActions, mapGetters} from "vuex";

export default {
  name: "MyGrade",
  components: {StudentBase},
  data: () => ({
    schoolYears: [],
    semesters: [],
    selectedSchoolYear: null,
    selectedSemester: null,
    grades: [],
    totalGrade: null,
    totalUnits: null,
  }),
  watch: {
    selectedSchoolYear() {
      this.grades = []
      this.fetchSemesters()
    },
    selectedSemester() {
      this.fetchMyGrades()
    }
  },
  computed: mapGetters(['getAuthUser']),
  methods: {
    ...mapActions(['setDialog','setAuthUser']),
    fetchYears() {
      api.mySchoolYear().then(r => {
        this.schoolYears = r.data.data
      })
    },
    fetchSemesters() {
      api.mySemester(this.selectedSchoolYear.id).then(r => {
        this.semesters = r.data.data
      })
    },
    fetchMyGrades() {
      api.myGradeForSemester(this.selectedSchoolYear.id, this.selectedSemester.id).then(r => {
        this.grades = r.data.data
        if (this.grades.length > 0 ){
          this.totalGrade = r.data.totalGrade
          this.totalUnits = r.data.totalUnits
        }else{
          this.setDialog({state: true, message: r.data.message})
        }
      })
    },
    handleClickYear(schoolYear) {
      this.selectedSchoolYear = schoolYear
    },
    handleClickSemester(semester) {
      this.selectedSemester = semester
    },
  },
  created() {
    this.setAuthUser()
  },
  mounted() {
    this.fetchYears()
  }
}
</script>

<style scoped>

</style>