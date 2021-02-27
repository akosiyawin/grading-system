<template>
  <div class="container" data-app>
    <div class="row">
      <div class="col-12">
        <h2 class="display-4" style="font-size: 2rem">
          {{active}}
        </h2>
      </div>
    </div>
    <div class="row mb-3">
      <form class="col-8" @submit.prevent="saveYear">
        <label>School Year</label>
        <div class="d-flex align-items-baseline justify-content-center">
          <v-text-field
            :error-messages="form.errors.get('year')"
            filled
            v-model="form.year"
            placeholder="Enter School Year"
            name="school-year"
            type="number"
            min="1900"
            max="2099"
            step="1"
            value="2016"
          />
          <v-btn class="bg-success ml-3" x-large type="submit">Save Year</v-btn>
        </div>
      </form>
    </div>
    <div class="row">
      <div class="col-12">
        <h4>Activate one semester at once!</h4>
      </div>
      <div class="col-12">
        <label><span class="badge badge-danger p-2">Active</span></label>
        <label><span class="badge badge-success p-2">Activate</span></label>
      </div>

      <div class="col-4 my-2" v-for="year in getSchoolYears" :key="year.id">
        <div class="year-content bg-dark p-4">
          <h3>{{ year.year + " - " + (parseInt(year.year) + 1) }}</h3>

          <button
            class="btn mr-2 mt-2"
            @click="activateSemester(sem.id)"
            :class="sem.status == 1 ? 'btn-danger' : 'btn-success'"
            v-for="sem in year.semesters"
            :key="sem.id"
            v-text="sem.title"
          ></button>
        </div>
      </div>
      <dialog-message></dialog-message>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import DialogMessage from '../base/DialogMessage.vue';
import api from "../tools/api";
import Form from "../tools/form";
export default {
  components: { DialogMessage },
  name: "YearSem",
  data: () => ({
    form: new Form({
      year: "",
    }),
    active: null
  }),
  methods: {
    ...mapActions(["setSchoolYears","setDialog","pushSchoolYear"]),
    activateSemester(id) {
      api.activateSemester(id).then((r) => {
          location.reload()
      });
    },
    saveYear() {
      this.form
        .post(api.yearStore)
        .then((r) => {
          this.form.reset();
          this.pushSchoolYear(r.data)
            this.setDialog({state:true,message:r.message})
        })
        .catch((err) => this.form.errors.set(err.errors));
    },
    loadYear() {
      api.yearIndex().then((r) => {
        this.setSchoolYears(r.data.data);
        this.active = r.data.active
      });
    },
  }, 
  computed: mapGetters(["getSchoolYears","getDialog"]),
  mounted() {
    this.loadYear();
  },
};
</script>

<style>
</style>