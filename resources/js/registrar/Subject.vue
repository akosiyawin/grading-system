<!-- Assign Subjects to teacher na! -->
<template>
  <div data-app class="pb-5">
    <div class="row">
      <div class="col-6">
        <blockquote class="bg-transparent">
          <h1 class="display-4 text-dark" style="font-size: 2rem">
            Encode Subjects Using CSV File
          </h1>
        </blockquote>
        <form action="">
          <label for="csvFileUpload">Please select a valid CSV file.</label>
          <div class="input-group mb-3">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="csvFileUpload"/>
              <label class="custom-file-label" for="csvFileUpload"
              >Choose .csv file</label
              >
            </div>
            <div class="input-group-append">
              <button
                  class="btn bg-success"
                  type="button"
                  @click="encodeSubject"
              >
                Encode
              </button>
            </div>
          </div>
        </form>
        <hr/>
        <blockquote class="bg-transparent">
          <h3 class="display-4 text-dark" style="font-size: 2rem">
            Fill out, Subject Information
          </h3>
        </blockquote>
        <form @submit.prevent="saveSubject">
          <v-text-field
              disabled
              type="text"
              outlined
              class="font-weight-bold"
              :value="displaySemester"
          />
          <v-select
              v-model="form.department_id"
              label="Department"
              :items="departments"
              item-value="id"
              :error-messages="form.errors.get('department_id')"
              item-text="title"
          >
          </v-select>
          <div class="row">
            <div class="col-4">
              <v-text-field
                  v-model="form.code"
                  type="text"
                  filled
                  :error-messages="form.errors.get('code')"
                  placeholder="Subject Code"
              />
            </div>
            <div class="col-8">
              <v-text-field
                  v-model="form.title"
                  type="text"
                  filled
                  :error-messages="form.errors.get('title')"
                  placeholder="Subject Title"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-6 pt-0">
              <v-text-field
                  v-model="form.lecture"
                  type="number"
                  filled
                  :error-messages="form.errors.get('lecture')"
                  placeholder="Lec (Units)"
              />
            </div>
            <div class="col-6 pt-0">
              <v-text-field
                  v-model="form.lab"
                  filled
                  type="number"
                  :error-messages="form.errors.get('lab')"
                  placeholder="Lab (Units)"
              />
            </div>
          </div>
          <v-btn class="bg-success btn-block" type="submit" dark>
            SUBMIT THIS RECORD
          </v-btn>
        </form>
      </div>
      <div class="col-6">
        <blockquote class="bg-transparent">
          <h3 class="display-4 text-dark" style="font-size: 2rem">
            Designated Subjects
          </h3>
        </blockquote>
        <v-card>
          <v-card-title>
            <v-text-field
                v-model="designatedSearch"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
              height="450px"
              id="subjectEast144"
              fixed-header
              :headers="designatedHeaders"
              :items="designatedSubjects"
              :search="designatedSearch"
          >
            <template v-slot:item.actions="{ item }">
              <v-btn small class="bg-danger btn-sm" @click="handleRevokeDialog(item)">
                <i class="fas fa-trash mr-2"> </i>
                Revoke
              </v-btn>
            </template>
          </v-data-table>
        </v-card>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <blockquote class="bg-transparent">
          <h3 class="display-4 text-dark" style="font-size: 2rem">
            Subjects Management Section
          </h3>
        </blockquote>
        <v-card>
          <v-card-title>
            <v-text-field
                v-model="subjectSearch"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
              height="500px"
              fixed-header
              :headers="subjectHeaders"
              :items="subjects"
              :search="subjectSearch"
          >
            <template v-slot:item.actions="{ item }">
              <v-btn small class="bg-primary" @click="handleAssignDialog(item)">
                <i class="fas fa-folder mr-2"> </i>
                Assign
              </v-btn>
              <v-btn small class="bg-success" @click="handleEdit(item)">
                <v-icon small class="mr-2 mt-1"> mdi-pencil</v-icon>
                Edit
              </v-btn>
              <v-btn small class="bg-danger" @click="handleDeleteDialog(item.id)">
                <v-icon small> mdi-delete</v-icon>
              </v-btn>
            </template>
            <template v-slot:item.index="{ item }">
              {{ item.id }}
            </template>
          </v-data-table>
        </v-card>
      </div>
    </div>
    <v-dialog v-model="dialog" persistent max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">Select Teachers</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <div class="table-responsive" style="height: fit-content">
                  <v-data-table
                      id="teachersTableRegistrar"
                      :headers="headers"
                      :items="getTeachers"
                      hide-default-footer
                  >
                    <template v-slot:item.action="{ item }">
                      <v-btn
                          class="bg-danger"
                          @click="unPushToTeachers"
                          v-if="
                          selectedTeachers && selectedTeachers.id === item.id
                        "
                      >
                        Unselect
                      </v-btn>
                      <v-btn
                          class="bg-success"
                          @click="pushToTeachers(item)"
                          v-else
                      >
                        Select
                      </v-btn>
                    </template>
                  </v-data-table>
                </div>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <dialog-message></dialog-message>

    <v-dialog v-model="dialogManage" max-width="800px">
      <v-card>
        <v-card-title>
          <span>Assign Subject</span>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-container>
          <v-card>
            <v-card-title>
              <v-text-field
                  v-model="teacherSearch"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
              ></v-text-field>
            </v-card-title>
            <v-data-table
                height="340px"
                fixed-header
                :headers="teacherHeaders"
                :items="teachers"
                :search="teacherSearch"
            >
              <template v-slot:item.action="{ item }">
                <v-btn
                    x-small
                    class="bg-success"
                    @click="handleDesignateSubject(item)"
                >
                  <i class="fas fa-folder mr-2"> </i>
                  Assign
                </v-btn>
              </template>
              <template v-slot:item.count="{ item }">
                {{ teachers.indexOf(item) + 1 }}
              </template>
            </v-data-table>
          </v-card>
        </v-container>
        <v-card-actions>
          <v-btn color="primary" text @click="dialogManage = false">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="remarksDialog" max-width="400">
      <v-card>
        <v-card-title class="headline"> ENTER SECTION OF SUBJECT</v-card-title>
        <v-card-text>
          <input
              type="text"
              v-model="remarksModel"
              placeholder="Section"
              class="form-control"
          />
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="handlePostDesignateSubject">
            Confirm
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!--  Edit Dialog  -->
    <v-dialog
        v-model="editDialog"
        persistent
        max-width="600px"
    >
      <v-card>
        <v-card-title>
          <span class="headline">Subject Details</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-select
                    v-model="editForm.department_id"
                    label="Department"
                    :items="departments"
                    item-value="id"
                    :error-messages="editForm.errors.get('department_id')"
                    item-text="title"
                >
                </v-select>
              </v-col>
              <v-col cols="4">
                <v-text-field
                    v-model="editForm.code"
                    type="text"
                    filled
                    :error-messages="editForm.errors.get('code')"
                    placeholder="Subject Code"
                />

              </v-col>
              <v-col
                  cols="8"
              >
                <v-text-field
                    v-model="editForm.title"
                    type="text"
                    filled
                    :error-messages="editForm.errors.get('title')"
                    placeholder="Subject Title"
                />
              </v-col>
              <v-col
                  cols="6"
              >
                <v-text-field
                    v-model="editForm.lecture"
                    type="number"
                    filled
                    :error-messages="editForm.errors.get('lecture')"
                    placeholder="Lec (Units)"
                />
              </v-col>
              <v-col
                  cols="6"
              >
                <v-text-field
                    v-model="editForm.lab"
                    filled
                    type="number"
                    :error-messages="editForm.errors.get('lab')"
                    placeholder="Lab (Units)"
                />
              </v-col>
            </v-row>
          </v-container>
          <small>*fill out this edit form to update the selected subject</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="blue darken-1"
              text
              @click="editDialog = false"
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


    <!--    Delete Dialog-->
    <v-dialog
        v-model="deleteDialog"
        persistent
        max-width="390"
    >
      <v-card>
        <v-card-title class="headline">
          Are you sure you want to delete this subject?
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

    <!--    Revoke Dialog-->
    <v-dialog
        v-model="revokeDialog"
        persistent
        max-width="390"
    >
      <v-card>
        <v-card-title class="headline">
          Are you sure you want to revoke this subject?
        </v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="green darken-1"
              text
              @click="revokeDialog = false"
              class="text-danger"
          >
            No
          </v-btn>
          <v-btn
              color="green darken-1"
              text
              @click="handleRevokeYes"
          >
            Yes
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </div>
</template>

<script>
import axios from "axios";
import {mapActions, mapGetters} from "vuex";
import api from "../tools/api";
import Form from "../tools/form";
import DialogMessage from "../base/DialogMessage.vue";
import TeacherManagement from "./TeacherManagement.vue";

export default {
  components: {DialogMessage, TeacherManagement},
  data() {
    return {
      dialog: false,
      dialogManage: false,
      search: "",
      subjectSearch: "",
      designatedSearch: "",
      displaySemester: "",
      teacherSearch: "",
      selectedSubject: null,
      subjects: [],
      departments: [],
      page: 1,
      teacherTotal: 0,
      rowsPerPage: 10,
      selectedTeachers: null,
      form: new Form({
        department_id: "",
        code: "",
        title: "",
        lecture: "",
        lab: "",
        teachers: [],
      }),
      rowsPerPages: [
        {title: 1, value: 1},
        {title: 5, value: 5},
        {title: 10, value: 10},
        {title: 15, value: 15},
        {title: "all", value: "all"},
      ],
      headers: [
        {
          text: "#",
          value: "id",
        },
        {text: "Username", value: "username"},
        {text: "Name", value: "name"},
        {text: "Subjects", value: "subject"},
        {text: "Students", value: "student"},
        {text: "", value: "action"},
      ],
      subjectHeaders: [
        {text: "#", value: "index"},
        {text: "Subject Code", value: "code"},
        {text: "Subject Title", value: "title"},
        {text: "Department", value: "department"},
        {text: "Subject Units", value: "units"},
        {text: "", value: "actions"},
      ],
      designatedHeaders: [
        {text: "Code", value: "code"},
        {text: "Title", value: "title"},
        {text: "Units", value: "units"},
        {text: "Teacher", value: "name"},
        {text: "", value: "actions"},
      ],
      teacherHeaders: [
        {text: "#", value: "count"},
        {text: "Name", value: "name"},
        {text: "", value: "action"},
      ],
      designatedSubjects: [],
      teachers: [],
      selectedTeacher: null,
      remarksModel: null,
      remarksDialog: false,
      editDialog: false,
      editForm: new Form({
        department_id: null,
        code: null,
        title: null,
        lecture: null,
        lab: null,
        id: null
      }),
      deleteDialog: false,
      subjectToDelete: null,
      revokeDialog: false,
      subjectToRevoke: null
    };
  },
  computed: mapGetters(["getTeachers", "getSemester"]),
  // Todo:: Move some unregistered API to api.js
  methods: {
    ...mapActions(["setTeachers", "setSemester", "setDialog"]),
    next(page) {
      this.fetchTeachers();
    },
    handleRevokeDialog(item) {
      this.revokeDialog = true
      this.subjectToRevoke = item
    },
    handleRevokeYes() {
      this.revoke(this.subjectToRevoke)
    },
    handleYesDelete() {
      api.deleteSubject(this.subjectToDelete).then(r => {
        this.setDialog({state: true, message: r.data.message})
        this.fetchSubjects()
      }).catch(err =>
          this.setDialog({state: true, message: err.response.data.message})
      ).finally(() => this.deleteDialog = false)
    },
    handleDeleteDialog(id) {
      this.subjectToDelete = id
      this.deleteDialog = true
    },
    saveSubject() {
      this.form.errors.clear();
      this.form
          .post(api.subjectStore)
          .then((r) => {
            this.form.reset();
            this.selectedTeachers = null;
            this.fetchSubjects();
            this.fetchDesignatedSubjects();
            this.setDialog({state: true, message: r.message});
          })
          .catch((err) => {
            this.form.errors.set(err.errors);
          });
    },
    fetchTeachers() {
      api
          .teacherIndex({rowsPerPage: this.rowsPerPage, page: this.page})
          .then((r) => {
            this.setTeachers(r.data.data);
            this.teacherTotal = r.data.total;
          });
    },
    fetchDisplaySemester() {
      axios.get("/api/semester-active").then((r) => {
        this.setSemester(r.data.data);
        this.displaySemester =
            "School Year of " +
            this.getSemester.year +
            "-" +
            (parseInt(this.getSemester.year) + 1) +
            ` (${this.getSemester.semester})`;
      });
    },
    fetchSubjects() {
      axios.get("/api/subjects").then((r) => {
        this.subjects = r.data.data;
      });
    },
    fetchDesignatedSubjects() {
      axios.get("/api/subjects-designated-to-teacher").then((r) => {
        this.designatedSubjects = r.data.data;
      });
    },
    fetchSimpleTeachers(data) {
      return axios.get("/api/teachers-assigned/" + data).then((r) => {
        this.teachers = r.data.data;
      });
    },
    handleAssignDialog(data) {
      this.fetchSimpleTeachers(data.id).then((r) => {
        document.body.classList.add('sidebar-collapse')
        this.dialogManage = true;
        this.selectedSubject = data;
      });
    },
    handleDesignateSubject(teacher) {
      this.selectedTeacher = teacher
      this.remarksDialog = true
    },
    async handlePostDesignateSubject() {
      const teacher = this.selectedTeacher
      await axios
          .patch(
              `/api/subjects-designate/${teacher.id}/${this.selectedSubject.id}`, {section: this.remarksModel}
          )
          .then((r) => {
            this.fetchDesignatedSubjects();
            this.fetchSubjects();
          })
          .catch(err => {
            this.setDialog({state: true, message: err.response.data.message})
          }).finally(() => this.remarksModel = null)
      this.remarksDialog = false
    },
    revoke(item) {
      axios
          .delete(`/api/subjects-revoke/${item.teacher_id}/${item.subject_id}`)
          .then((r) => {
            this.fetchDesignatedSubjects();
            this.fetchSubjects();
          })
          .catch((err) => {
            this.setDialog({state: true, message: err.response.data.message});
          }).finally(() => {
        this.revokeDialog = false
      })
    },
    handleEdit(item) {
      const units = item.units.replace(/[()]/g, '').split(' ')
      this.editForm.id = item.id
      this.editForm.code = item.code
      this.editForm.title = item.title
      this.editForm.lecture = units[0]
      this.editForm.lab = units[1]
      this.editForm.department_id = item.department_id
      document.body.classList.add('sidebar-collapse')
      this.editDialog = true
    },
    handleEditSave() {
      this.editForm.patch(api.updateSubject(this.editForm.id)).then(r => {
        this.editForm.reset()
        this.setDialog({state: true, message: r.message})
        this.fetchSubjects()
        this.editDialog = false
      }).catch(err => this.editForm.errors.set(err.errors))
    },
    fetchDepartments() {
      api.departments().then((r) => {
        this.departments = r.data.data;
      });
    },
    async encodeSubject() {
      //todo add a loading animation
      const file = document.getElementById("csvFileUpload").files.item(0);
      const text = await file.text();
      const data = text.split("\n");
      data.pop();
      api.bulkSubjects({data}).then((r) => {
        this.setDialog({state: true, message: r.data.message});
      });
    },
  },
  mounted() {
    this.fetchDisplaySemester();
    this.fetchSubjects();
    this.fetchDesignatedSubjects();
    this.fetchDepartments();
  },
};
</script>

<style>
</style>