<template>
  <v-dialog
      v-model="getDialog.state"
      max-width="600px"
  >
    <v-card>
      <v-card-title>
       {{ getDialog.message }}
        <v-spacer></v-spacer>
      </v-card-title>
      <v-card-actions>
        <v-btn
            color="primary"
            text
            @click="handleDialog"
        >
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
  name: "DialogMessage",
  computed: mapGetters(['getDialog']),
  methods: {
    ... mapActions(['setDialog']),
    handleDialog(){
      this.setDialog({state: false,message: null,value:null})
    }
  }
}
</script>

<style scoped>

</style>