<script setup>
import { AppStore } from '@/stores/AppStore';

const StoreApp = AppStore();
defineProps({
    totales:Array
})

</script>

<template>
    <v-row class="mx-5 mt-5" align="stretch" >
        <v-col
            v-if="totales"
          v-for="(registro, i) in totales"
          :key="i"
          cols="12" sm="6" md="3" class="mb-4"
        >
          <v-card :class="`rounded-xl stat-card ${registro.bg}`" elevation="0">
            <v-card-title class="d-flex align-center gap-2 justify-center text-center mt-3">
                <v-icon :color="registro.color" size="25" class="mr-2">{{ registro.icon }}</v-icon>
                <span class="poppins-semibold">{{ registro.title }}</span>
            </v-card-title>
            <v-card-text class="d-flex flex-column align-center justify-center">
              <v-skeleton-loader v-if="StoreApp.LoaderPeticionenCurso" type="heading" width="80" />
              <span v-else :class="`display-2 font-weight-bold text-${registro.color}`">{{ registro.value }}</span>
              <span class="text-caption text-grey-darken-2 mt-1">{{ registro.subtitle }}</span>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col v-else  cols="12" sm="6" md="3"class="d-flex justify-center" v-for="i in 4">
            <v-skeleton-loader boilerplate type="image" width="200" />
        </v-col>
        
      </v-row>
</template>

<style scoped>
.seccion-titulo {
  font-size: 1.5rem;
}
.stat-card {
  min-height: 150px;
  transition: box-shadow 0.2s, transform 0.2s;
}
.stat-card:hover {
  box-shadow: 0 4px 24px 0 rgba(60,60,60,0.10);
  transform: translateY(-2px) scale(1.02);
}
.dashboard-table {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
}
.hoverable-row:hover {
  background: #e3f2fd !important;
  transition: background 0.2s;
}
.v-card-title {
  font-size: 1.1rem;
}
@media (max-width: 960px) {
  .stat-card {
    min-height: 120px;
  }
}
</style>