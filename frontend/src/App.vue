<template>
  <v-app>
    <v-main style='background-color: rgb(210, 210, 210);'>
      <toolbar-component></toolbar-component>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<style>
* {
  font-family: 'Courier New', Courier, monospace;
  font-weight: 900;
}
</style>

<script setup>
import ToolbarComponent from './components/ToolbarComponent.vue';

//
</script>
