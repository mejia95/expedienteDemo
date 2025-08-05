<script setup>
import { ref, onMounted, computed } from 'vue';
import App from '@/layouts/app/App.vue';

import axios from 'axios';
import Estadisticos from '@/layouts/principal/ResponsableMPSS/Estadisticas.vue'
import { Head } from '@inertiajs/vue3';
const breadcrumbs = [
  { title: 'Dashboard', href: '/dashboard' },
];

</script>

<template>
    <Head title="Dashboard"></Head>
  <App :breadcrumbs="breadcrumbs">
    <v-container fluid>
      <v-row class="my-4 mx-5" no-gutters>
        <v-col cols="12">
          <span class="poppins-semibold seccion-titulo">Panel principal</span>
        </v-col>
        <v-col cols="12">
          <div class="poppins-light text-grey-darken-1">
            Bienvenido al sistema. Aquí puedes ver un resumen de la actividad reciente y estadísticas generales.
          </div>
        </v-col>
      </v-row>
      <Estadisticos></Estadisticos>
      
      
    </v-container>
  </App>
</template>


