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
                  @click="viewGrade"
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
              src="https://epcst.files.wordpress.com/2012/08/eastwoods-3.jpg"
          >
            <v-card-title><span class="bg-success pa-2">Student Information</span></v-card-title>
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
          <v-card-title class="title_information">Records of your Grades</v-card-title>
          <v-card-subtitle><b>*Note:</b> If this is not you, message us at the Eastwoods
            <a href="https://www.facebook.com/eastwoodstech">Technical Assistance Page </a>
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
      {title: "2nd Semester", value: 2},
      {title: "Summer", value: 3},
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
      // window.open("/print");
      //   $('#print').attr('href','/printGrade/1/1');
      let schoolYearID = localStorage.getItem('year');
      let semesterID = localStorage.getItem('semester_id');
      window.open('/printGrade/'+schoolYearID+"/"+semesterID);
    });
  },
  methods: {
    async getUserInfoNow(){
      await axios.get('/api/userInfo').then(r => {
        this.id = r.data.data.id;
      })
    },
    async getStudentInfoNow(){
      axios.post('/api/student/information/' + this.id).then(r => {
        $('#StudentId').html(r.data.student_id);
        $('#StudentName').html(r.data.student_name);
        $('#courses').html(r.data.course);
        $('.course').html(r.data.course);
        $('.title').html(r.data.student_name);
      })
    },
    async getActiveSemester(){
      axios.get('/api/activated-semester').then(r => {
        let semesterTitle;
        if (r.data.activated_semester[0].id == 1){
          semesterTitle = '1st Semester'
        }else if(r.data.activated_semester[0].id == 2){
          semesterTitle = '2nd Semester'
        }
        else if(r.data.activated_semester[0].id == 3){
          semesterTitle = '3rd Semester'
        }
      });
    },
    viewGrade() {
      this.grade()
      let user_id = this.id;
      let semester_id = this.select_semester;

      localStorage.setItem("user_id", user_id);
      localStorage.setItem("semester_id", semester_id);
      // console.log()
    },
    grade() {
      $.ajax({
        url: '/api/grades/' + this.id + '/' + this.select_semester + '/' + this.year,
        TYPE: 'GET',
        success:(r)=> {
          this.isprint= true;
          let $tr = $('.data');
          let html = "";
          $(r).each(function (r, v) {
            // console.log(grade);
            localStorage.setItem("year", v.year_id);
            let instructor = v.first_name.substring(0,1) + ".";
            html += '<tr>'
            html += '<td>' + v.code + '</td>'
            html += '<td>' + v.title + '</td>'
            html += '<td>' + v.units + '</td>'
            html += '<td>' + instructor + " " + v.last_name +'</td>'
            const grade = parseFloat(v.grade);
            if (grade >= 97.5) {
              html += '<td>1.0</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 94.5) {
              html += '<td>1.25</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 91.5) {
              html += '<td>1.50</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 87.5) {
              html += '<td>1.75</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 84.5) {
              html += '<td>2.0</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 81.5) {
              html += '<td>2.25</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 78.5) {
              html += '<td>2.50</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 75.5) {
              html += '<td>2.75</td>'
              html += '<td>Passed</td>'
            } else if (grade >= 74.5) {
              html += '<td>3.0</td>'
              html += '<td>Passed</td>'
            } else if (grade == 4) {
              html += '<td class="text-danger">DRP</td>'
              html += '<td>Dropped</td>'
            } else if (grade == 0) {
              html += '<td class="text-danger">INC</td>'
              html += '<td>Incomplete</td>'
            } else {
              html += '<td class="text-danger">5.0</td>'
              html += '<td>Failed</td>'
            }
            html += '</tr>'
          });
          $('.data').html(html);
          $tr.html(html);
        }, error:  (r)=> {
          $('.data').html('');
          $('.data').html('<tr>\n' +
              '                <td colspan="99" class="text-center text-danger errormes">No records found</td>\n' +
              '              </tr>');
          this.isprint= false;
        }
      })
      $.ajax({
        url: '/api/footer/total/' + this.id + '/' + this.select_semester + '/' + this.year,
        TYPE: 'GET',
        success: function (r) {
          // console.log(r.units);
          let tr = $('.footer');
          let html = "";
          html += '<tr>'
          html += '<td><td>'
          html += '<td class="font-weight-bold">' + r.units + '<td>'
          html += '<td class="font-weight-bold">' + parseFloat(r.Average) + '<td>'
          html += '<td></td>'
          html += '</tr>'
          $('.footer').html(html);
          tr.html(html);
        }, error: function (r) {
          $('.footer').html('');
          $('.footer').html('<tr>\n' +
              '                <td></td>\n' +
              '              </tr>');
        }
      })
    }
  }
}

</script>