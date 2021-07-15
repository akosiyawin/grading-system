<template>
  <v-app>
    <StudentBase></StudentBase>
    <v-main>
      <div class="mt-10">
        <v-timeline dense>
          <v-timeline-item
            v-for="announcement in announcements"
            :key="announcement.id"
          >
            <v-card class="elevation-2">
              <v-card-title class="headline">
                {{ announcement.title }}
              </v-card-title>
              <v-card-subtitle>{{ announcement.posted_at }}</v-card-subtitle>
              <v-card-text>
                {{ announcement.message }}
              </v-card-text>
            </v-card>
          </v-timeline-item>
        </v-timeline>

        <div class="pr-8 pb-8 d-flex justify-content-end">
          <v-btn
            @click="pageHandler(false)"
            color="blue-grey"
            class="white--text mr-4"
            large
            >PREV</v-btn
          >
          <v-btn color="success" @click="pageHandler(true)" large>NEXT</v-btn>
        </div>

        <v-alert
          class="ma-5"
          v-if="announcements.length == 0"
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
  components: { StudentBase },
  data: () => ({
    announcements: [],
    page: 1,
  }),
  watch: {
    page() {
      this.fetchAnnouncement();
    },
  },
  methods: {
    fetchAnnouncement() {
      api.announcement({ rowsPerPage: 10, page: this.page }).then((r) => {
        this.announcements = r.data.data;
      });
    },
    pageHandler(increased) {
      if (increased && this.announcements.length === 10) {
        this.page++;
      } else if (!increased && this.page > 1) {
        this.page--;
      }
    },
  },
  created() {
    this.fetchAnnouncement();
  },
};
</script>