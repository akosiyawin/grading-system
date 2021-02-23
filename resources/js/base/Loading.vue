<template>
  <div data-app>
    <v-dialog v-model="dialog" persistent max-width="390">
    <v-card>
      <v-card-text>
        <div class="row justify-content-center aligin-items-center w-100 p-3">
          <div class="col-2">
            <v-progress-circular
              :size="50"
              indeterminate
              class="text-success mt-2"
            ></v-progress-circular>
          </div>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
  </div>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    loadingCount: 0,
  }),
  watch: {
    loadingCount() {
      if (!this.loadingCount) {
        this.dialog = false;
      }
    },
  },
  created() {
    axios.interceptors.request.use(
      (config) => {
        // Do something before request is sent
        this.loadingCount++;
        this.dialog = true;
        return config;
      },
      (error) => {
        // Do something with request error
        this.loadingCount--;
        return Promise.reject(error);
      }
    );

    axios.interceptors.response.use(
      (response) => {
        // trigger 'loading=false' event here
        this.loadingCount--;
        return response;
      },
      (error) => {
        // trigger 'loading=false' event here
        this.loadingCount--;
        return Promise.reject(error);
      }
    );
  }
};
</script>

<style>
</style>