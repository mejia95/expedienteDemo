import { defineStore } from 'pinia';
export const AppStore = defineStore('store',{
    "state":()=>({
        SidebarApp:false,
        MostrarAlerta:false,
        CallbackAlerta:null,
        LoaderPeticionenCurso:false,
        EstadoAlerta:0,
        IconoAlerta:'mdi-check-circle',
        TituloAlerta:'Error',
        MensajeAlerta:'Ha ocurrido un error.'
    }),
    "actions":{
        debounce(func, delay) {
			let timeout;
			return async (...args)=>{
				clearTimeout(timeout);
                this.LoaderPeticionenCurso = true;
				timeout = setTimeout(async () => {
					await func.apply(this, args);
					this.LoaderPeticionenCurso = false;
				}, delay);
			};
		},
        async OcultarAlerta(){
            this.MostrarAlerta = false;
            this.CallbackAlerta = null;
            this.EstadoAlerta=0;
            this.IconoAlerta = 'mdi-check-circle';
            this.TituloAlerta = 'Error';
            this.MensajeAlerta = 'Ha ocurrido un error.';
        }
    }
})