<template>
  <v-form
      ref="form"
      v-model="valid"
      lazy-validation
      @submit.prevent="saveTeacher"
  >
    <blockquote class="bg-transparent">
      <h3 class="display-4 text-dark" style="font-size: 2rem">Fill out, Teacher Credentials</h3>
    </blockquote>
    <v-text-field
        v-model="form.username"
        label="Username"
        :rules="required"
        :error-messages="form.errors.get('username')"
        required
    ></v-text-field>
    <v-text-field
        v-model="form.first_name"
        :rules="required"
        label="First Name"
        :error-messages="form.errors.get('first_name')"
        required
    ></v-text-field>
    <v-row>
      <v-col>
        <v-text-field
            v-model="form.middle_name"
            :rules="required"
            label="Middle Name"
            :error-messages="form.errors.get('middle_name')"
            required
        ></v-text-field>
      </v-col>
      <v-col>
        <v-text-field
            v-model="form.last_name"
            label="Last Name"
            :rules="required"
            :error-messages="form.errors.get('last_name')"
            required
        ></v-text-field>
      </v-col>
    </v-row>
    <v-btn
        class="mr-4 mt-3 bg-success"
        type="submit"
    >
      Submit This Record
    </v-btn>
    <dialog-message></dialog-message>
  </v-form>
</template>

<script>
import { mapActions } from 'vuex'
import api from '../tools/api'
import Form from '../tools/form'
export default {
  name: "AddTeacher",
  data: () => ({
    valid: true,
    form: new Form({
      username: '',
      first_name: '',
      middle_name: '',
      last_name: '',
    }),
    required: [v => !!v || 'This field is required']
  }),
  methods: {
    ...mapActions(['setDialog','setTeachers']),
    validate () {
      this.$refs.form.validate()
    },
    fetchTeachers(){
      return api.teacherIndex({rowsPerPage: 15, page: 1}).then((r) => {
        this.setTeachers(r.data.data)
        this.teacherTotal = r.data.total
      })
    },
    saveTeacher(){
      this.form.post(api.teacherStore)
      .then(r=>{
        this.form.reset()
        this.$refs.form.reset()
        this.fetchTeachers()
        .then(()=>{
          this.setDialog({message: r.message,state: true})
        })
      })
      .catch(err => this.form.errors.set(err.errors))
    },
  }
}
</script>

<style scoped>

</style>