<template>
  <div data-app>
    <div class="row">
      <div class="col-12">
        <blockquote class="bg-transparent">
          <h2 class="display-4" style="font-size: 2rem">My Subjects</h2>
        </blockquote>
        <div class="row">
          <v-col>
            <v-card>
              <v-card-title>
                <v-text-field
                    v-model="searchMySubjects"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
              </v-card-title>
              <v-data-table
                  :headers="headers"
                  :items="mySubjects"
                  :search="searchMySubjects"
                  height="500px"
                  :footer-props="{
                  prevIcon: 'fa-arrow-left',
                  nextIcon: 'fa-arrow-right'
                  }"
              >
                <template v-slot:item.action="{ item }">
                  <v-btn x-small class="bg-danger"  @click="handleRemoveSubject(item,0)">
                    <i class="fas fa-folder mr-2"> </i>
                    Remove
                  </v-btn>
                </template>
              </v-data-table>
            </v-card>
          </v-col>
        </div>
      </div>
    </div>
    <blockquote class="bg-transparent">
      <h2 class="display-4" style="font-size: 2rem">Choose Subjects</h2>
    </blockquote>
    <div class="row">
      <v-col>
        <v-card>
          <v-card-title>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          </v-card-title>
          <v-data-table
              :headers="headers"
              :items="subjects"
              :search="search"
              height="500px"
              :footer-props="{
              prevIcon: 'fa-arrow-left',
              nextIcon: 'fa-arrow-right'
              }"
          >
            <template v-slot:item.action="{ item }">
              <v-btn x-small class="bg-success" @click="handleAddSubjectDialog(item,1)">
                <i class="fas fa-folder mr-2"> </i>
                Add
              </v-btn>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </div>
    <dialog-message></dialog-message>

     <v-dialog
      v-model="remarksDialog"
      max-width="400"
    >
      <v-card>
        <v-card-title class="headline">
          ENTER SUBJECT REMARKS
        </v-card-title>
        <v-card-text>
          <input type="text" v-model="remarksModel" placeholder="Subject Remarks" class="form-control">
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="updateSubject"
          >
            Confirm
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </div>
</template>

<script>
import api from "../tools/api";
import {mapActions} from "vuex";

export default {
  name: "Subject",
  remarksDialog: false,
  data: () => ({
    search: '',
    searchMySubjects: '',
    headers: [
      {text: '#', value: 'subject_id'},
      {text: 'Title', value: 'title'},
      {text: 'Code', value: 'code'},
      {text: 'Units', value: 'units'},
      {text: 'Department', value: 'department_title'},
      {text: 'Action', value: 'action'},
    ],
    subjects: [],
    mySubjects: [],
    selectedItem: null,
    selectedStatus: 0,
    remarksDialog: false,
    remarksModel: ''
  }),
  methods: {
    ...mapActions(['setDialog']),
    async fetchSubjects() {
      await api.departmentSubjects().then(r => {
        this.subjects = r.data.data
        this.mySubjects = r.data.mySubjects
      })
    },
    updateSubject(){
      const remarks = this.remarksModel !== "" ? this.remarksModel : null
      api.updateSubjectStatus({status : this.selectedStatus,remarks}, this.selectedItem.subject_id)
      .then(r => {
        if(this.selectedStatus === 1){
          this.remarksDialog = false
        }
        this.remarksModel = ''
        this.fetchSubjects()
      }).catch(err=> {
        this.setDialog({state: true, message : err.response.data.message})
      })
    },
    handleRemoveSubject(item,status){
      this.selectedItem = item
      this.selectedStatus = status
      this.updateSubject()
    },
    handleAddSubjectDialog(item,status) {
      this.selectedItem = item
      this.selectedStatus = status
      this.remarksDialog = true
    },
  },
  mounted() {
    this.fetchSubjects()
  }
}
</script>

<style >

</style>