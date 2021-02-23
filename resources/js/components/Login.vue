<template>
  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>EPCST</b> Grade Book <i class="fas fa-book-open"></i></a>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in to view your grades</p>
          <form @submit.prevent="submit">
            <div class="input-group mb-3">
              <input type="text"
                     class="form-control"
                     :class="{'is-invalid':form.errors.has('username')}"
                     v-model="form.username"
                     placeholder="Username">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
              <div class="invalid-feedback">
                {{form.errors.get('username')}}
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password"
                     v-model="form.password"
                     :class="{'is-invalid':form.errors.has('password')}"
                     class="form-control" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
              <div class="invalid-feedback">
                {{form.errors.get('password')}}
              </div>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember">
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <div class="col-4">
                <button type="submit" class="btn bg-primary btn-block">Sign In</button>
              </div>
            </div>
          </form>
          <div class="social-auth-links text-center mb-3">
            <p>- SOCIAL MEDIA -</p>
            <a href="https://www.facebook.com/EPCST" class="btn btn-block btn-primary">
              <i class="fab fa-facebook mr-2"></i> Visit Us On Facebook
            </a>
            <a href="https://www.youtube.com/channel/UCGQMVO8_CpGE_a4zVAgO50g" class="btn btn-block btn-danger">
              <i class="fab fa-youtube mr-2"></i> Check Out Our YouTube Channel
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../tools/api";
import Form from "../tools/form";

export default {
  name: "Login",
  data: () => ({
    valid: true,
    loading: false,
    form: new Form({
      username: '',
      password: ''
    })
  }),
  methods: {
    submit() {
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
</script>

<style>
.v-messages__message {
  color: red !important;
  font-weight: 500;
}

</style>