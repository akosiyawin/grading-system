<!-- Not Used!! Delete later -->
<template>
  <div class="row d-flex justify-content-center mt-5 mb-10">
    <div class="col-12">
      <blockquote class="bg-transparent">
        <h3 class="display-4 text-dark" style="font-size: 2rem">
          Teachers Management Section
        </h3>
      </blockquote>
      <v-card>
        <v-card-title>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>

        <v-data-table
          id="subjectEast144"
          fixed-header
          :headers="headers"
          :items="items"
          :search="search"
        >
          <template v-slot:item.action="{ item }">
            <v-btn small class="bg-primary">
              <i class="fas fa-folder mr-2"> </i>
              Manage
            </v-btn>
          </template>
        </v-data-table>
      </v-card>
    </div>
  </div>
</template>

<script>
export default {
    data: () => ({
        search : '',
        items: [],
        headers: [
            {text: '#', value: 'count'},
            {text: 'Name', value: 'name'},
            {text: 'Assigned Subjects', value: 'subjects'},
            {text: 'Assigned Students', value: 'students'},
            {text: '', value: 'action'},
        ]
    }),
    methods: {

    }
};
</script>

<style>
</style>