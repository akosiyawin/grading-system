<template>
  <div class="row" data-app>
    <div class="mb-5" :class="teacherInfos.user_id ? 'col-8' : 'col-12'">
      <v-card>
        <v-card-title>
          Teachers Information
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            @keyup.enter="fetchTeachers"
          ></v-text-field>
        </v-card-title>
        <div class="table-responsive" style="height: 500px">
          <v-data-table
            id="teachersTableRegistrar"
            :headers="headers"
            :items="getTeachers"
            :search="search"
            hide-default-footer
          >
            <template v-slot:item.status="{ item }">
              <v-chip small class="bg-success" v-if="item.status">
                Active
              </v-chip>
              <v-chip small class="bg-danger" v-else> Suspended </v-chip>
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn
                :disabled="item.subject === 0"
                x-small
                class="bg-success"
                @click="handleViewGrade(item)"
              >
                <i class="fas fa-folder mr-1"> </i>
                Grades
              </v-btn>
              <v-btn x-small @click="rowClick(item)">
                <i class="fas fa-eye mr-1"> </i>
              </v-btn>
            </template>
          </v-data-table>
        </div>
        <v-footer>
          <div class="d-flex justify-content-center align-items-center">
            <v-select
              @change="fetchTeachers"
              v-model="rowsPerPage"
              :items="rowsPerPages"
              item-value="value"
              item-text="title"
              style="max-width: 40px; max-width: 55px"
            ></v-select>
            <div v-if="rowsPerPage !== 99">
              <v-btn small class="ml-2" @click="handlePage(-1)">
                <v-icon> mdi-chevron-left </v-icon>
              </v-btn>
              <v-btn
                class="ml-2"
                small
                @click="handlePage(1)"
                :disabled="getTeachers.length === 0"
              >
                <v-icon> mdi-chevron-right </v-icon>
              </v-btn>
            </div>
          </div>
        </v-footer>
      </v-card>
    </div>
    <div class="col-4" v-if="teacherInfos.user_id">
      <v-card class="mx-auto" max-width="500" id="teacherAboutCard">
        <v-img
          class="white--text"
          height="200px"
          src="https://epcst.files.wordpress.com/2012/08/eastwoods-3.jpg"
        >
          <v-card-title class=" d-flex align-items-end h-100"
            ><span class="bg-success px-3 py-2 text-white ">More Description</span>
          </v-card-title>
        </v-img>
        <v-card-title> Teacher Name</v-card-title>
        <v-card-subtitle> {{ teacherInfos.name }}</v-card-subtitle>

        <v-card-title> Subjects</v-card-title>
        <v-card-subtitle>
          {{ teacherInfos.data }}
        </v-card-subtitle>

        <v-card-title>Status</v-card-title>
        <v-card-subtitle>
          {{ teacherInfos.status }}
        </v-card-subtitle>

        <v-card-actions>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                text
                class="text-danger"
                type="submit"
                v-bind="attrs"
                v-on="on"
                @click="dialog = true"
              >
                Delete
              </v-btn>
            </template>
            <span>Permanently remove this teacher to the system.</span>
          </v-tooltip>

          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                text
                class="ml-2 text-warning"
                type="submit"
                v-bind="attrs"
                v-on="on"
                @click="updateStatus"
              >
                {{ teacherInfos.status === "Active" ? "Suspend" : "Unsuspend" }}
              </v-btn>
            </template>
            <span>Suspend this user from accessing the system</span>
          </v-tooltip>

<!--          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                text
                class="ml-2"
                type="submit"
                v-bind="attrs"
                v-on="on"
              >
                More
              </v-btn>
            </template>
            <span>Manage students and subjects.</span>
          </v-tooltip>-->
        </v-card-actions>
      </v-card>
    </div>

    <!--    Delete Prompt -->
    <v-dialog v-model="dialog" persistent max-width="400">
      <v-card>
        <v-card-title class="headline">
          Delete Teacher Confirmation
        </v-card-title>
        <v-card-text>Are you sure you want to delete this teacher?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">
            No
          </v-btn>
          <v-btn color="green darken-1" text @click="deleteTeacher">
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!--    Failed Delete -->
    <v-dialog v-model="dialogFailed.state" persistent max-width="400">
      <v-card>
        <v-card-title class="headline"> Delete Failed </v-card-title>
        <v-card-text>{{ dialogFailed.message }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="dialogFailed.state = false"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!--    Assign student-->
    <v-dialog v-model="assignStudentDialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span>View Students</span>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-container>
          <v-card>
            <v-card-title>
              <v-text-field
                v-model="students.search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
                @keyup.enter="fetchStudents"
              ></v-text-field>
            </v-card-title>

            <div class="table-responsive" style="height: 400px">
              <v-simple-table>
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">#</th>
                      <th class="text-left">Student No.</th>
                      <th class="text-left">Name</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in students.data">
                      <td>{{ index + 1 }}</td>
                      <td>{{ item.student_number }}</td>
                      <td>{{ item.name }}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </div>
          </v-card>
        </v-container>
        <v-card-actions>
          <v-btn color="primary" text @click="assignStudentDialog = false">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!--    View Grades-->
    <v-dialog v-model="viewGradeDialog" persistent max-width="980px">
      <v-card>
        <v-card-title>
          <span>Grade Submissions</span>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-container>
          <v-card>
            <v-card-title>
              <v-text-field
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
                @keyup.enter="fetchSubjectStudentsGrade"
                v-model="searchGrade"
                v-if="activeSubject"
              ></v-text-field>
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col md="4">
                  <ul class="list-group">
                    <li class="list-group-item bg-success">
                      <b>SUBJECT LISTS</b>
                    </li>
                    <li
                      class="list-group-item hovered"
                      @click="handleSubjectClick(subject.subject_id)"
                      v-for="subject in subjectLists"
                    >
                      {{ subject.title }}<br /><b>{{ subject.code }}</b>
                    </li>
                  </ul>
                </v-col>
                <v-col md="8">
                  <v-simple-table height="500px">
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">#</th>
                          <th class="text-left">Student Info</th>
                          <th class="text-left">
                            Grade
                            <v-btn
                              class="ml-1"
                              x-small
                              text
                              icon
                              color="blue lighten-2"
                              @click="checkPrelim"
                            >
                              <v-icon small class="text-success">mdi-check</v-icon>
                            </v-btn>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(student, i) in studentsGradeLists">
                          <td>
                            {{
                              rowsPerPageGrade !== 99
                                ? i +
                                  1 +
                                  (rowsPerPageGrade * pageGrade - 1) -
                                  rowsPerPageGrade +
                                  1
                                : i + 1
                            }}
                          </td>
                          <td>
                            <b>{{ student.name }}</b
                            ><br />
                            {{ student.student_number }}
                          </td>
                          <td v-if="student.grade!==0">
                          <span v-if="student.grade != 4">{{student.grade}}</span>
                          <span v-else class="text-danger">(DROPPED)</span>
                          <br>
                          <v-btn x-small class="bg-danger" v-if="student.status == 1" @click="approveGrade(student.student_id)">Cancel</v-btn>
                          <v-btn x-small class="bg-success" v-else @click="approveGrade(student.student_id)">Approve</v-btn>
                          </td>
                          <td v-else></td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </v-col>
              </v-row>
              <v-footer v-if="activeSubject">
                <div class="d-flex justify-content-center align-items-center">
                  <v-select
                    @change="fetchSubjectStudentsGrade"
                    v-model="rowsPerPageGrade"
                    :items="rowsPerPages"
                    item-value="value"
                    item-text="title"
                    style="max-width: 55px"
                  ></v-select>
                  <div v-if="rowsPerPageGrade !== 99">
                    <v-btn small class="ml-2" @click="handlePageGrade(-1)">
                      <v-icon> mdi-chevron-left </v-icon>
                    </v-btn>
                    <v-btn
                      class="ml-2"
                      small
                      @click="handlePageGrade(1)"
                      :disabled="studentsGradeLists.length === 0"
                    >
                      <v-icon> mdi-chevron-right </v-icon>
                    </v-btn>
                  </div>
                </div>
              </v-footer>
            </v-card-text>
          </v-card>
        </v-container>
        <v-card-actions>
          <v-btn color="primary" text @click="handleCloseViewGrade">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-snackbar
        v-model="snackbar.state"
        :timeout="4000"
    >
      {{ snackbar.text }}

      <template v-slot:action="{ attrs }">
        <v-btn
            color="blue"
            text
            v-bind="attrs"
            @click="snackbar.state = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import api from "../tools/api";

export default {
  name: "TeacherTable",
  data() {
    return {
      search: "",
      dialogFailed: { state: false, message: null },
      page: 1,
      rowsPerPage: 10,
      students: { data: [], search: null },
      assignStudentDialog: false,
      teacherTotal: 0,
      dialog: false,
      selectedTeacher: null,
      rowsPerPages: [
        { title: 5, value: 5 },
        { title: 10, value: 10 },
        { title: 15, value: 15 },
        { title: "all", value: 99 },
      ],
      headers: [
        {
          text: "#",
          value: "id",
        },
        { text: "Username", value: "username" },
        { text: "Name", value: "name" },
        { text: "Subjects", value: "subject" },
        { text: "Students", value: "student" },
        { text: "Status", value: "status" },
        { text: "", value: "action" },
      ],
      teacherInfos: { data: "", name: null, user_id: null },
      /*View Grades*/
      viewGradeDialog: false,
      subjectLists: [],
      studentsGradeLists: [],
      rowsPerPageGrade: 10,
      pageGrade: 1,
      searchGrade: null,
      activeSubject: null,
      snackbar : {state: false,text: null},
      activeTeacher : null
    };
  },
  computed: {
    ...mapGetters(["getTeachers"]),
  },
  watch: {
    page() {
      this.fetchTeachers();
    },
    search() {
      if (this.search === "") {
        this.fetchTeachers();
      }
    },
    pageGrade() {
      this.fetchSubjectStudentsGrade();
    },
    searchGrade() {
      if (this.searchGrade === "") {
        this.fetchSubjectStudentsGrade();
      }
    },
  },
  methods: {
    ...mapActions(["setTeachers", "setDialog"]),
    fetchTeachers() {
      return api
        .teacherIndex({
          rowsPerPage: this.rowsPerPage,
          page: this.page,
          search: this.search,
        })
        .then((r) => {
          this.setTeachers(r.data.data);
          this.teacherTotal = r.data.total;
        });
    },
    handlePage(val) {
      this.page += val;
      if (this.page <= 1) {
        this.page = 1;
      }
    },
    handlePageGrade(val) {
      this.pageGrade += val;
      if (this.pageGrade <= 1) {
        this.pageGrade = 1;
      }
    },
    getTeacherInfo(item) {
      api.teacherInfo(item.id).then((r) => {
        this.teacherInfos.data = r.data.data.map((e) => e.title).join(", ");
        this.teacherInfos.data = !this.teacherInfos.data
          ? "None"
          : this.teacherInfos.data;
        this.teacherInfos.name = r.data.name;
        this.teacherInfos.user_id = r.data.user_id;
        this.teacherInfos.status = r.data.status ? "Active" : "Suspended";
      });
    },
    rowClick(item) {
      this.selectedTeacher = item.id;
      this.getTeacherInfo(item);
    },
    deleteTeacher() {
      this.dialog = false;
      api
        .deleteTeacher(this.teacherInfos.user_id)
        .then((r) => {
          this.fetchTeachers().then(() => {
            this.teacherInfos.user_id = null;
            this.setDialog({ state: true, message: r.data.message });
          });
        })
        .catch((err) => {
          this.dialogFailed.state = true;
          this.dialogFailed.message = err.response.data.message;
        });
    },
    updateStatus() {
      api
        .updateUserStatus(this.teacherInfos.user_id)
        .then((r) => {
          this.getTeacherInfo({ id: this.selectedTeacher });
          this.fetchTeachers();
        })
        .catch((err) => {
          this.setDialog({ state: true, message: err.response.data.message });
        });
    },
    fetchStudents() {
      api.students({ search: this.students.search }).then((r) => {
        this.students.data = r.data.data;
      });
    },
    /*Grade Viewing*/
    handleViewGrade(item) {
      this.activeTeacher = item.id
      this.fetchTeacherSubjects(item.id).then((r) => {
        this.subjectLists = r.data.data;
        this.viewGradeDialog = true;
      });
    },
    approveGrade(student_id) {
      api
        .approveGrade(this.activeSubject, { student_id, teacher: this.activeTeacher })
        .then((r) => {
          this.fetchSubjectStudentsGrade();
        });
    },
    handleSubjectClick(id) {
      this.activeSubject = id;
      this.pageGrade = 1;
      this.searchGrade = null;
      this.fetchSubjectStudentsGrade();
    },
    fetchSubjectStudentsGrade() {
      api
        .subjectStudentsGrade(this.activeSubject, {
          rowsPerPage: this.rowsPerPageGrade,
          page: this.pageGrade,
          search: this.searchGrade,
          teacher: this.activeTeacher
        })
        .then((r) => {
          this.studentsGradeLists = r.data.data;
        });
    },
    handleCloseViewGrade() {
      this.viewGradeDialog = false;
      this.subjectLists = [];
      this.studentsGradeLists = [];
      this.activeSubject = null;
      this.searchGrade = null;
    },
    async fetchTeacherSubjects(id) {
      return await api.teacherSubjects(id);
    },
    checkPrelim(){
      const forApproval = this.studentsGradeLists.filter(e=> e.grade !== 0).map(e=> e.student_id)
      api.approveAllGrade(this.activeSubject,{students: forApproval,teacher: this.activeTeacher}).then(r=>{
        this.fetchSubjectStudentsGrade()
        this.snackbar.text = "Grade has been approved for all students!"
        this.snackbar.state = true
      })
    }
  },
  mounted() {
    this.fetchTeachers();
  },
};
</script>

<style>
#teacherAboutCard .v-responsive__sizer {
  flex: none;
}

#teachersTableRegistrar .v-data-footer {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: #fff;
}
.hovered:hover {
  background: #fbfbfb;
  cursor: pointer;
}
</style>

<!--TODO Search for grades-->
<!--TODO Paginate Grades Table-->
<!--TODO Hide Grades Button if teacher does not have subjects yet-->
<!--TODO auto select first list of subject in grades-->
<!--TODO Approval for grades API-->
