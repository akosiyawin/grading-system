<template>
  <div>
    <div class="row">
      <div class="col">
        <blockquote class="bg-transparent">
          <h3 class="display-4 text-dark" style="font-size: 2rem">Semester Period</h3>
        </blockquote>
        <div class="table-responsive" style="height: 300px">
          <table class="table">
            <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="period in getPeriods">
              <td>{{period.id}}</td>
              <td>{{period.title}}</td>
              <td class="project-actions text-right">
                <v-btn class="btn bg-primary" small href="#">
                  <i class="fas fa-folder">
                  </i>
                  View
                </v-btn>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col">
        <blockquote class="bg-transparent">
          <h3 class="display-4 text-dark" style="font-size: 2rem">Roles</h3>
        </blockquote>
        <ul class="list-group">
          <li v-for="role in getRoles" class="list-group-item d-flex justify-content-between align-items-center">
            {{role.title}}
            <div>
              <span class="badge badge-success badge-pill">Total: 14</span>
              <v-btn small class="btn bg-primary">Manage</v-btn>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="row">
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import api from "../tools/api";

export default {
  name: "StaticSetup",
  computed: mapGetters(['getRoles','getPeriods']),
  methods: {
    ...mapActions([
        'setPeriods',
        'setRoles',
    ]),
    fetchPeriods(){
      api.periods()
      .then(r=>{
        this.setPeriods(r.data.data)
      })
    },
    fetchRoles(){
      api.roles()
      .then(r=>{
        this.setRoles(r.data.data)
      })
    },
  },
  mounted() {
    this.fetchPeriods()
    this.fetchRoles()
  }
}
</script>

<style scoped>

</style>