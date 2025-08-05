<template>
  <v-dialog
    v-model="StoreApp.MostrarAlerta"
    :max-width="390"
    persistent
    transition="dialog-bottom-transition"
    class="alert-dialog"
    scrim="black"
    opacity="0.80"
  >
    
    <v-card class="alert-card rounded-xl">
      
      <v-avatar color="blue-lighten-4" size="60">
        <v-icon
          :icon="StoreApp.IconoAlerta"
          size="35"
          color="primary"
        />
      </v-avatar>
      <!--<div class="alert-icon-container bg-blue-lighten-4" >
        <v-icon
          :icon="icon"
          size="32"
          color="primary"
        />
      </div>-->

      <!-- Content Section -->


      <div class="alert-content px-4" >
        <h3 class="alert-title text-primary" >
          {{ StoreApp.TituloAlerta }} 
        </h3>
        <p class="alert-message text-medium-emphasis" v-html="StoreApp.MensajeAlerta">
     
        </p>
      </div>

      <v-progress-circular indeterminate  color="primary" v-if="LoaderPeticionenCurso" class="mt-5"></v-progress-circular>
      <!-- Actions Section -->
      <div class="alert-actions">
        <v-btn
          v-if="StoreApp.EstadoAlerta==0"
          variant="tonal"
          color="grey-darken-2"
          @click="StoreApp.MostrarAlerta=false;"
          class="action-btn cancel-btn"
          :disabled="LoaderPeticionenCurso"
        >
          Cerrar
        </v-btn>
        <v-btn
          v-if="StoreApp.EstadoAlerta==1"
          variant="tonal"
          color="grey-darken-2"
          @click="StoreApp.MostrarAlerta=false;"
          class="action-btn cancel-btn"
          :disabled="LoaderPeticionenCurso"
        >
          Cancelar
        </v-btn>
        <v-btn
          v-if="StoreApp.EstadoAlerta!=0 || StoreApp.EstadoAlerta==2"
          color="primary"
          class="action-btn confirm-btn"
          :disabled="LoaderPeticionenCurso"
          @click="AccionBtnAlerta(StoreApp.CallbackAlerta)"
        >
          Aceptar
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
import { AppStore } from "@/stores/AppStore";
import { Store } from "lucide-vue-next";
import {ref,inject,nextTick} from 'vue';
export default {
    props:{
        callback:{
            type:String,
            default:null
        }
    },
    setup(props){
      const LoaderPeticionenCurso = ref(false);
    const FuncionesPadre = inject('FuncionesPadre');
    const StoreApp = AppStore();
    const AccionBtnAlerta = async (accion)=>{
        console.log("que traes en el accion",accion)
        if(accion){
            LoaderPeticionenCurso.value = true;
            await FuncionesPadre[accion]();
            LoaderPeticionenCurso.value = false;
        }else{
            StoreApp.MostrarAlerta=false;
            LoaderPeticionenCurso.value = false;
        }
				
	}
    return {
        StoreApp,
        AccionBtnAlerta,
        LoaderPeticionenCurso
    }
  }
}
</script>

<style scoped>
.alert-dialog {
  backdrop-filter: blur(4px);
}

.alert-card {
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 16px;
}

.alert-icon-container {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 8px;
}

.alert-icon-container.info {
  background: rgba(var(--v-theme-primary), 0.1);
  color: rgb(var(--v-theme-primary));
}

.alert-icon-container.success {
  background: rgba(var(--v-theme-success), 0.1);
  color: rgb(var(--v-theme-success));
}

.alert-icon-container.warning {
  background: rgba(var(--v-theme-warning), 0.1);
  color: rgb(var(--v-theme-warning));
}

.alert-icon-container.error {
  background: rgba(var(--v-theme-error), 0.1);
  color: rgb(var(--v-theme-error));
}

.alert-content {
  width: 100%;
}

.alert-title {
  font-size: 1.25rem;
  font-weight: 600;
  line-height: 1.5;
  margin-bottom: 8px;
}

.alert-message {
  font-size: 0.895rem;
  line-height: 1.5;
  color: rgba(0, 0, 0, 0.7);
  margin: 0;
}

.alert-actions {
  display: flex;
  gap: 12px;
  margin-top: 8px;
}

.action-btn {
  min-width: 100px;
  text-transform: none;
  font-weight: 500;
  letter-spacing: 0;
  border-radius: 8px;
}

.confirm-btn {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Animation classes */
.dialog-bottom-transition-enter-active,
.dialog-bottom-transition-leave-active {
  transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

.dialog-bottom-transition-enter-from,
.dialog-bottom-transition-leave-to {
  transform: translateY(20px);
  opacity: 0;
}
</style>
