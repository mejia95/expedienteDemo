<script setup>
import { AppStore } from '@/stores/AppStore';
import { Link } from '@inertiajs/vue3';
import { onMounted,ref } from 'vue';

const ListadePacientes = ref([]);
const PestaniaPacientes = ref(1);
const StoreApp = AppStore();
onMounted(async ()=>{
    console.log("Montado tabla recientes")
    StoreApp.LoaderPeticionenCurso = true;
    await getListaPacientesPestania('ultimos-pacientes-creados');
    StoreApp.LoaderPeticionenCurso = false;
})

const tabPacientes = ref('creados')
const getListaPacientesPestania = async(url)=>{
    StoreApp.LoaderPeticionenCurso = true;
    ListadePacientes.value = await getListaPacientes(`/api/reportes/${url}`);
    StoreApp.LoaderPeticionenCurso = false;
}

function getListaPacientes(url){
    return axios.get(url).then((response)=>{
        return response.data?.data ?? [];
    }).catch((error)=>{
        return  [];
    })
} 
</script>

<template>
    <v-row class="mx-5 mt-2">
        <v-col cols="12">
          <v-tabs v-model="tabPacientes" color="primary" :disabled="StoreApp.LoaderPeticionenCurso">
            <v-tab value="creados" @click="getListaPacientesPestania('ultimos-pacientes-creados')">
              <v-icon start>mdi-account-plus</v-icon>
              Recién creados
            </v-tab>
            <v-tab value="modificados" @click="getListaPacientesPestania('ultimos-pacientes-modificados')">
              <v-icon start>mdi-account-edit</v-icon>
              Modificados recientemente
            </v-tab>
          </v-tabs>
        </v-col>
        <v-col cols="12" class="mt-n2">         
          <v-card class="rounded-xl" elevation="0">
            <v-card-title class="d-flex align-center gap-2">
              <v-icon color="primary" size="20" class="mr-2">mdi-history</v-icon>
              <span class="poppins-semibold text-medium-emphasis" style="font-size: 0.975rem;">{{tabPacientes =='creados' ? 'Pacientes recién creados':'Pacientes modificados recientemente' }}</span>
              <v-spacer></v-spacer>
              <Link href="/reportes/pacientes">
                <v-btn class="text-none" color="primary" variant="text">Ver todos los pacientes</v-btn>
              </Link>
              
            </v-card-title>
            <v-card-text>
             <v-skeleton-loader type="table" v-if="StoreApp.LoaderPeticionenCurso" />
              <v-table  class="dashboard-table rounded-lg " v-if="ListadePacientes.length>0">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Género</th>
                    <th>Consultorio</th>
                    <th>{{tabPacientes =='creados' ? 'Fecha de registro':'Fecha de actualización' }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="paciente in ListadePacientes" :key="paciente.pid" class="hoverable-row">
                    <td>{{ paciente.paciente }}</td>
                    <td>{{ paciente.edad }}</td>
                    <td>
                      <v-chip
                        v-if="paciente.genero === 'Masculino'"
                        color="blue"
                        size="small"
                        class="text-white"
                      >
                        <v-icon start size="16">mdi-gender-male</v-icon>
                        Masculino
                      </v-chip>
                      <v-chip
                        v-else-if="paciente.genero === 'Femenino'"
                        color="pink"
                        size="small"
                        class="text-white"
                      >
                        <v-icon start size="16">mdi-gender-female</v-icon>
                        Femenino
                      </v-chip>
                      <v-chip
                        v-else
                        color="grey"
                        size="small"
                        class="text-white"
                      >
                        <v-icon start size="16">mdi-gender-male-female</v-icon>
                        {{ paciente.genero || 'Sin especificar' }}
                      </v-chip>
                    </td>
                    <td>{{ paciente.consultorio }}</td>
                    <td>{{ paciente.created }}</td>
                  </tr>
                </tbody>
              </v-table>
              <v-empty-state
                v-else
                icon="mdi-history"
                title="Sin registros para mostrar"
                text="No hay actividad reciente de pacientes en este momento. Cuando existan nuevos registros, podrás consultar la información aquí."
            ></v-empty-state>
            </v-card-text>
          </v-card>
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