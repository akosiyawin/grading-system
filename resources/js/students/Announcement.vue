<template>
  <v-app>
    <StudentBase></StudentBase>
    <v-main>
      <div class="mt-10">
        <v-timeline
            v-if="announcements.length > 0"
            dense
        >
          <v-timeline-item
              v-for="announcement in announcements"
              :key="announcement.id"
          >
            <v-card class="elevation-2">
              <v-card-title class="headline">
                {{announcement.title}}
              </v-card-title>
              <v-card-subtitle>{{announcement.posted_at}}</v-card-subtitle>
              <v-card-text>
                {{announcement.message}}
              </v-card-text>
            </v-card>
          </v-timeline-item>
        </v-timeline>
        <v-alert
            class="ma-5"
            v-else
            text
            outlined
            color="deep-orange"
            icon="mdi-bullhorn"
        >
          There are no announcement yet!
        </v-alert>
      </div>
    </v-main>
  </v-app>
</template>

<script>
import StudentBase from "../base/StudentBase";
import api from "../tools/api";

export default {
  name: "Announcement",
  components: {StudentBase},
  data: () => ({
    announcements : []
  }),
  methods: {
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