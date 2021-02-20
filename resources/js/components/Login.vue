<template>
  <v-form
      ref="form"
      v-model="valid"
      lazy-validation
      @submit.prevent="submit"
  >
    <div class="row d-flex justify-content-center">
      <div class="col-8">
        <v-text-field
            label="Username"
            :error-messages="form.errors.get('username')"
            :rules="rules.username"
            v-model="form.username"
        ></v-text-field>
        <v-text-field
            label="Password"
            hide-details="auto"
            :rules="rules.password"
            class="my-2"
            type="password"
            :error-messages="form.errors.get('password')"
            v-model="form.password"
        ></v-text-field>
        <v-btn
            class="bg-success mt-2"
            :loading="loading"
            :disabled="loading"
            type="submit"
        >
          Login
        </v-btn>
      </div>
    </div>
  </v-form>
</template>

<script>
import api from "../tools/api";
import Form from "../tools/form";

export default {
  name: "Login",
  data: () => ({
    valid: true,
    loading: false,
    rules: {
      username: [
        v => !!v || 'Username is required'
      ],
      password: [
        v => !!v || 'Password is required'
      ],
    },
    form: new Form({
      username: '',
      password: ''
    })
  }),
  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        this.form.post(api.login)
        .then(r => {
          location.replace('/handler')
        })
        .catch(err => {
          this.form.errors.set(err.errors)
        })
      }
    }
  }
}
</script>

<style>
.v-messages__message {
  color: red !important;
  font-weight: 500;
}
</style>