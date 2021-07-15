<template>
  <div>
    <v-navigation-drawer
        v-model="drawer"
        app
    >
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">
            {{getAuthUser.name}}
          </v-list-item-title>
          <v-list-item-subtitle>
            EPCST Grade Book <v-icon class="ml-1">mdi-book-open-variant</v-icon>
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list
          dense
          nav
      >
        <v-list-item
            v-for="item in items"
            :key="item.title"
            link
            :href="item.href"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <template v-slot:append>
        <div class="pa-2">
          <v-btn block color="success" href="https://www.facebook.com/eastwoodstech" class="mb-2" small>
            <v-icon small class="mr-2">mdi-alert</v-icon>
            Report An Issue
          </v-btn>
          <v-btn block color="error" @click="logout">
            Logout
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>
    <v-app-bar app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>EPCST Grading System</v-toolbar-title>
    </v-app-bar>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
  name: "StudentBase",
  data: () => ({
    items: [
      // {title: 'Periodic Search', icon: 'mdi-magnify', href: '/student'},
      {title: 'My Grades', icon: 'mdi-star', href: '/myGrade'},
      {title: 'Announcement', icon: 'mdi-comment', href: '/announcement'},
    ],
    right: null,
    drawer: false,
  }),
  computed: mapGetters(['getAuthUser']),
  methods: {
    ...mapActions(['setAuthUser']),
    logout(){
      axios.post('/logout')
          .then(() => location.replace('/login'))
    }
  },
  async created() {
    await this.setAuthUser()
  }
}
</script>

<style scoped>

</style>
