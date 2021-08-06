<template>
  <div>
    <!--    <v-fab-transition>
      <v-btn
          absolute
          class="v-btn&#45;&#45;example mt-5"
          dark
          fab
          right
          small
          top
      >
        <v-icon>mdi-download</v-icon>
      </v-btn>
    </v-fab-transition>-->
    <div class="container">
      <div
        class="row d-flex justify-content-center align-items-center"
        style="height: 100vh"
      >
        <div id="printable" class="col-md-8 bg-white p-0">
          <div
            class="
              d-flex
              flex-column flex-md-row
              align-items-center align-items-md-start
              pt-5
            "
          >
            <div class="mr-4">
              <div class="image">
                <img alt="" class="img-fluid" :src="logo" width="260px" />
              </div>
            </div>
            <div>
              <h2
                class="text-success text-center text-md-left"
                style="min-font-size: 5vw"
              >
                Eastwoods Professional College of Science and Technology
              </h2>
              <hr class="my-1" />
              <p class="font-italic text-center text-md-left">
                We Educate, Develop and Inspire.
              </p>
            </div>
          </div>
          <!--          Certification-->
          <div>
            <div>
              <h3 class="text-center m-3">COPY OF GRADES</h3>
            </div>
            <div
              class="bg-gray-light"
              id="marginize"
              style="border-left: 16px solid #28a745"
            >
              <h4 class="text-center pt-3 display-4" style="font-size: 1.3rem">
                {{ year }}
              </h4>
              <ul class="px-3 pb-4 pt-2 m-0">
                <li><span class="list-title">Name: </span>{{ user.name }}</li>
                <li>
                  <span class="list-title">Student No.: </span>
                  {{ user.student_number }}
                </li>
                <li>
                  <span class="list-title">Course: </span> {{ user.course }}
                </li>
                <li>
                  <span class="list-title">Birthdate: </span>
                  {{ user.birthdate }}
                </li>
              </ul>
            </div>
            <div class="mt-4">
              <v-simple-table class="m-5">
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">Code</th>
                      <th class="text-left">Subject</th>
                      <th class="text-left">Units</th>
                      <th class="text-left">Instructor</th>
                      <th class="text-left">Grade</th>
                      <th class="text-left">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in data">
                      <td class="font-weight-bold">{{ item.code }}</td>
                      <td>{{ item.title }}</td>
                      <td>{{ item.units }}</td>
                      <td>{{ item.teacher }}</td>
                      <td>{{ item.grade }}</td>
                      <td class="font-weight-bold">{{ item.remarks }}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td colspan="2">
                        <b>{{ units }}</b> <br />Total Credits
                      </td>
                      <td colspan="2">
                        <b>{{ grade }}</b> <br />Grade Average
                      </td>
                    </tr>
                  </tfoot>
                </template>
              </v-simple-table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import image from "./logo.png";

export default {
  name: "PrintGrade",
  data: () => ({
    logo: image,
  }),
  props: ["data", "grade", "units", "user", "year"],
  mounted() {
    console.log(this.year, this.user);
  },
};
</script>
