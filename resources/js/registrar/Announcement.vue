<template>
  <div>
    <form class="row pb-5" @submit.prevent="submitAnnouncement">
      <div class="col-7">
        <v-text-field v-model="form.title" type="text" :error-messages="form.errors.get('title')" outlined placeholder="Title"></v-text-field>
        <v-textarea v-model="form.message" style="height: 250px" :error-messages="form.errors.get('message')" outlined placeholder="Message"></v-textarea>
        <button type="submit" class="btn btn-success mt-4" style="width: 250px; font-size: 1.2rem">Announce!</button>
      </div>
      <div class="col-5">
        <v-simple-table
        height="300px"
        >
          <template v-slot:default>
            <thead>
            <tr>
              <th class="text-left">
               Announcement
              </th>
            </tr>
            </thead>
            <tbody>
              <tr v-for="announcement in announcements">
                <td>
                  <h5>{{announcement.title}}</h5>
                  <small>{{announcement.posted_at}}</small>
                  <p class="text-justify">{{announcement.message}}</p>
                </td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </div>
    </form>
  </div>
</template>

<script>
import api from "../tools/api";
import Form from "../tools/form";

export default {
  name: "Announcement",
  data: () => ({
    form : new Form({
      title : null,
      message : null
    }),
    announcements : []
  }),
  methods: {
    submitAnnouncement(){
      this.form.post(api.announcementStore).then(r=>{
        this.fetchAnnouncement()
        this.form.reset()
      }).catch(err=> {
        this.form.errors.set(err.errors)
      })
    },
    fetchAnnouncement(){
      api.announcement().then(r=>{
        this.announcements = r.data.data
      })
    }
  },
  mounted() {
    this.fetchAnnouncement()
  }
}
</script>

<style scoped>

</style>