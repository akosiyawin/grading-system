<template>
  <v-app id="inspire">
    <StudentBase></StudentBase>
    <v-main>
      <div class="blockQuote p-sm-0  p-md-5 pb-0">
        <blockquote>
          <h3 class="display-1 text-dark">Grade Per Exam Period</h3>
        </blockquote>
      </div>
      <div class="row p-md-5 m-0 pb-0 pt-0 ">
        <div class="col-12">
          <div class="row">
            <!--            <v-col-->
            <!--                md="6"-->
            <!--                lg="3"-->
            <!--            >-->
            <!--              <v-select-->
            <!--                  :items="items"-->
            <!--                  label="Period"-->
            <!--                  outlined-->
            <!--              ></v-select>-->
            <!--            </v-col>-->
            <v-col
                md="6"
                lg="3"
            >
              <v-select
                  :items="semester"
                  label="Semester"
                  outlined
                  item-value="value"
                  item-text="title"
                  id="semester"
                  name="semester"
                  v-model="select_semester"
              ></v-select>
            </v-col>
            <v-col
                md="6"
                lg="3"
            >
              <v-text-field type="number" outlined label="School Year" id="year" name="year"
                            v-model="year"></v-text-field>
            </v-col>
            <v-col
                md="6"
                lg="3"
            >
              <v-btn
                  color="success"
                  x-large
                  id="view_grade"
              >
                View Grade
              </v-btn>
            </v-col>
          </div>
        </div>
      </div>
    </v-main>
    <div class="row p-md-5 p-sm-0 pt-0">
      <div class="col-lg-4">
        <v-card
            class="mx-auto"
            max-width="100%"
        >
          <v-img
              class="white--text align-end"
              height="200px"
              src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
          >
            <v-card-title>Student Information</v-card-title>
          </v-img>

          <v-card-title id="StudentId"></v-card-title>
          <v-card-subtitle>Student ID</v-card-subtitle>

          <v-card-title id="StudentName"></v-card-title>
          <v-card-subtitle>Student Name</v-card-subtitle>

          <v-card-title id="courses"></v-card-title>
          <v-card-subtitle>Course</v-card-subtitle>

          <!--            <v-card-title>First Year</v-card-title>-->
          <!--            <v-card-subtitle>Year Level</v-card-subtitle>-->

          <v-card-actions>

            <v-col cols="auto">
              <v-dialog
                  transition="dialog-bottom-transition"
                  max-width="600"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                      color="orange"
                      text
                      id = "print"
                      :disabled="!isprint"
                  >
                    Print
                  </v-btn>
                </template>

              </v-dialog>
            </v-col>
          </v-card-actions>
        </v-card>

      </div>
      <div class="col-lg-8">
        <blockquote class="quote-dark p-0">
          <v-card-title class="title_information"></v-card-title>
          <v-card-subtitle><b>*Note:</b> Red color indicates grades not verified by registrar.
          </v-card-subtitle>
        </blockquote>
        <v-simple-table>
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

            <tbody class="data">
            <tr>
            </tr>
            </tbody>
            <tfoot class="footer">
            <tr>
            </tr>
            </tfoot>
          </template>
        </v-simple-table>
      </div>
    </div>
  </v-app>
</template>

<script>
import StudentBase from "../base/StudentBase";

export default {
  components: {StudentBase},
  data: () => ({
    items: [
      {title: 'Dashboard', icon: 'mdi-view-dashboard', href: '/student'},

      {title: 'Chat', icon: 'mdi-image'},
    ],
    semester: [
      {title: "1st Semester", value: 1},
      {title: "2st Semester", value: 2},
    ],
    select_semester: null,
    year: null,
    id: null,
    right: null,
    drawer: true,
    isprint:false,
  }),
  async mounted() {
    // $('.title_information').html('Final Grade For' + " " + $("#semester").text());
    await this.getUserInfoNow()
    await this.getStudentInfoNow()
    await this.getActiveSemester()
    $('#print').click(function (){
      window.open("/print");
    });
  },
  methods: {
    async getUserInfoNow(){
      await axios.get('/api/userInfo').then(r => {
        // console.log(r.data.id);
        this.id = r.data.data.id;
      })
    },
    async getStudentInfoNow(){
      axios.post('/api/student/information/' + this.id).then(r => {
        // console.log(r);
        $('#StudentId').html(r.data.student_id);
        $('#StudentName').html(r.data.student_name);
        $('#courses').html(r.data.course);
        $('.course').html(r.data.course);
        $('.title').html(r.data.student_name);
      })
    },
    async getActiveSemester(){
      axios.get('/api/activated-semester').then(r => {
        // console.log(r.data.school_year);
        let semesterTitle;
        if (r.data.activated_semester[0].id == 1){
          semesterTitle = '1st Semester'
        }else if(r.data.activated_semester[0].id == 2){
          semesterTitle = '2nd Semester'
        }
        else if(r.data.activated_semester[0].id == 3){
          semesterTitle = '3rd Semester'
        }
        // console.log(semesterTitle);

        //
        //
        $('.title_information').html('Final Grade For' + " " + r.data.school_year + " " +semesterTitle);
      });
    },
g
  }
}

</script>