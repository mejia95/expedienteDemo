<script setup >
import { router, Head, usePage } from '@inertiajs/vue3';
import { useGoTo, useDisplay } from 'vuetify';
import { ref, onMounted, computed, provide } from 'vue'
import App from '@/layouts/app/App.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Enfermedades from '@/pages/Pacientes/NuevoPacienteEnfermedades.vue';
import Antecedentes from '@/pages/Pacientes/NuevoPacienteAntecedentes.vue';
import { AplicacionStore } from '@/stores/Aplicacion';
import axios from 'axios';
import Alerta from '@/components/AppAlerta.vue';
import { id } from 'vuetify/locale';

const api = `/pacientes`;
const catalogos = "/catalogos";
const consulta_api = "/consulta";

const store = AplicacionStore();
const currentUrl = usePage().url;
const page = usePage()
const id_paciente = page.props.id
const MensajeAppAlerta = ref(null);
const AlertaCallback = ref(null);
const goTo = useGoTo();
const tab = ref('1');
const CatMesesDisponibles = ref([]);
const AnioActual = ref(null);
const MesActual = ref(null);
const DiaActual = ref(null);
const FechaNacimientoPaciente = ref(null);
const AccionFormularioPaciente = ref(1);
const AntecedentesFormularioComponenteRef = ref(null);
const tipoSangre = ref([]);
const CatGenero = ref([]);
const CatEstadoCivil = ref([]);
const CatNacionalidad = ref([]);
const id_exist = ref(null);

onMounted(async() => {
    await goTo(0, {});
    var FechaHoy =  new Date();
    var AnioHoy =  FechaHoy.getFullYear();
    var MesHoy =  FechaHoy.getMonth()+1;
    var DiaHoy =  FechaHoy.getUTCDate();

    AnioActual.value=AnioHoy;
    MesActual.value=MesHoy;
    DiaActual.value=DiaHoy;
    
   //console.log("Que traes en el id del paciente",id_paciente);
    if(id_paciente){
        // console.log("EL di del paciente", props.id);
	 	//store.paciente._id = props.id;
		ObtenerPerfilPaciente(id_paciente);
		MesesDisponibles(store.anio_nacimiento);
		//await AntecedentesFormularioComponenteRef.value.ConsultarAntecedente();
		AccionFormularioPaciente.value=2;

	}
    else{
            store.NombrePaciente = null;
            store.PrimerApellidoPaciente = null;
            store.SegundoApellidoPaciente = null;
            store.anio_nacimiento = null;
            store.mes_nacimiento = null;
            store.dia_nacimiento = null;
            FechaNacimientoPaciente.value = null;
			store.cirugias_hospitalizaciones = null;
			store.inmunizaciones = null;
            store.EdadPaciente = null;
            store.recien_nacido = false;
            store.CurpPaciente = null;
            store.TipoSangrePaciente = null;
            store.GeneroPaciente = null;
            store.OcupacionPaciente = null;
            store.EstadoCivilPaciente = null;
            store.NacionalidadPaciente = null;
            store.CorreoPaciente = null;
            store.telefono_casa = null;
            store.telefono_celular = null;

			store.antecedentesPerPatologicos = null;
			store.antecedentesNoPatologicos = null;
			store.antecedentesGinecoObs = null;
			store.toxicomanias = null;
			store.alergias = null;
			store.cardiovascular = null;
			store.respiratorio = null;
			store.NefroUrologico = null;
			store.Neurologico = null;
			store.Hematologicos = null;
			store.Ginecologicos = null;
			store.Infectologicos = null;
			store.Endocrinologicos = null;
			store.Quirurgicos = null;
			store.Alergicos = null;
			store.SocioecEpide = null;
			store.AntecedentesHeredo = null;
	}

    ConsultarCatalogos();
});

    const breadcrumbs = [
        { title: 'Inicio', href: '/' },
        { title: 'Pacientes', href: store.currentUrl },
        id_paciente
            ? { title: 'Perfil', href: `/pacientes/perfil/${id_paciente}` }
            : { title: 'Registro', href: '/pacientes/nuevo' }
    ];


const ConsultarCatalogos = async (valor)=>{
  const url = `${catalogos}/catalogos`;
  await axios.get(url).then((response)=>{
    tipoSangre.value= response.data.tipoSangre;
    CatGenero.value = response.data.CatGenero;
    CatEstadoCivil.value = response.data.CatEstadoCivil;
    CatNacionalidad.value = response.data.CatNacionalidad;
  }).catch((error)=>{
    console.log(error);
  })
}

const ObtenerPerfilPaciente = async(paciente)=>{
	var url = `${api}/paciente/${paciente}`;
	axios.get(url).then(async(response)=>{
		//console.log("Este es el response",response.data.nombre);
	 	store.NombrePaciente = response.data.nombre;
	 	store.PrimerApellidoPaciente = response.data.primer_apellido;
	 	store.SegundoApellidoPaciente = response.data.segundo_apellido;
	 	store.CurpPaciente = response.data.CURP	 ;
	 	store.GeneroPaciente = response.data.genero ;
        store.recien_nacido = response.data.recien_nacido ;
	 	var [anio , mes, dia] = response.data.fecha_nacimiento.split("-") ;
	// 	//console.log("Que traes en el dia",dia)
	 	store.anio_nacimiento = parseInt(anio);
	 	store.mes_nacimiento = parseInt(mes);
	    store.dia_nacimiento = parseInt(dia);
		//console.log("Que traes en dia de nacimiento",dia_nacimiento.value)
		//FechaNacimientoPaciente.value = (new Date(store.anio_nacimiento.value,store.mes_nacimiento.value - 1,store.dia_nacimiento.value));
		// Volver a calcular cuando se reciba la fecha Edad
		await CalcularEdadPorFechaNacimiento(response.data.fecha_nacimiento);
		store.OcupacionPaciente = response.data.ocupacion ;
		store.EstadoPaciente = response.data.estado ;
		store.EstadoCivilPaciente = response.data.estado_civil ;
		store.NacionalidadPaciente = response.data.nacionalidad ;
		store.TipoSangrePaciente = response.data.tipo_sangre ;
		store.CorreoPaciente = response.data.correo ;
		await nextTick(()=>{
			TelefonoPaciente.value = response.data.telefonoClear;
			CelularPaciente.value = response.data.celularClear;
			TelefonoExt.value = `${response.data.telefonoExt}`;
			CelularExt.value = `${response.data.celularExt}`;
			TelefonoCelPacienteRef.value.lazyCountry = `${response.data.celularExt}`;
			TelefonoPacienteRef.value.lazyCountry = `${response.data.telefonoExt}`;
		})
	}).catch((error)=>{
		//router.push("/pacientes");
	})
}
const CatMeses = ref([
	{ numero: 1, nombre: 'Enero' },
    { numero: 2, nombre: 'Febrero' },
    { numero: 3, nombre: 'Marzo' },
    { numero: 4, nombre: 'Abril' },
    { numero: 5, nombre: 'Mayo' },
    { numero: 6, nombre: 'Junio' },
    { numero: 7, nombre: 'Julio' },
    { numero: 8, nombre: 'Agosto' },
    { numero: 9, nombre: 'Septiembre' },
    { numero: 10, nombre: 'Octubre' },
    { numero: 11, nombre: 'Noviembre' },
    { numero: 12, nombre: 'Diciembre' },
]);

const CatAnios = computed(()=>{
	var anios = [];
	for(let i= 2025;i>=1900;i--){
		anios.push(i)
	}
	return anios;
});

const MesesDisponibles = (valor)=>{
	//console.log("que tiene aqui",valor)
	CatMesesDisponibles.value =  CatMeses.value;
	if(store.anio_nacimiento == AnioActual.value){
		const array = [];
		CatMeses.value.forEach((item)=>{
			if(item.numero<= MesActual.value){
				array.push(item);
			}
		})
	CatMesesDisponibles.value = array;
	store.mes_nacimiento = null;
	store.dia_nacimiento = null;
	}
};

const CatDiasMes = computed(()=>{
	FechaNacimientoPaciente.value = null;
	store.EdadPaciente = null;
	let date = new Date(store.anio_nacimiento,store.mes_nacimiento, 0);
  	var ultimo =  date.getDate();
  	var dias = [];
	for(let i= 1;i<=ultimo;i++){
		dias.push(i)
	}
	if(store.anio_nacimiento == AnioActual.value && store.mes_nacimiento == MesActual.value){
		var dias = [];
		for(let i= 1;i<=DiaActual.value;i++){
			dias.push(i)
		}
	}
	if(!dias.includes(store.dia_nacimiento)){
		store.dia_nacimiento =  null;
	}
	if(store.anio_nacimiento && store.mes_nacimiento && store.dia_nacimiento){
		FechaNacimientoPaciente.value = (new Date(store.anio_nacimiento,store.mes_nacimiento - 1,store.dia_nacimiento));
		CalcularEdadPorFechaNacimiento(new Date(store.anio_nacimiento,store.mes_nacimiento - 1,store.dia_nacimiento));
	}
	return dias;
})

const CalcularEdadPorFechaNacimiento = async (valor)=>{
	const meses = 12;
	var MesesCumplidos = 0;
	var FechaHoy =  new Date();

	var AnioHoy =  FechaHoy.getFullYear();
	var MesHoy =  FechaHoy.getMonth()+1;
	var DiaHoy =  FechaHoy.getUTCDate();

	//Fecha de nacimiento elegida
	var FechaNacimiento = new Date(valor);
	var AnioNacimiento = FechaNacimiento.getFullYear();
	var MesNacimiento = FechaNacimiento.getMonth()+1;
	var DiaNacimiento = FechaNacimiento.getUTCDate();

	//Años cumplidos
	var AniosCumplidos = AnioHoy - AnioNacimiento;

	//Calculo de edad con años,meses,dias
	var m = MesHoy - MesNacimiento;
	var d = DiaHoy - DiaNacimiento;
	var DiasCumplios = d<0 ? new Date(AnioNacimiento,(MesHoy-1),0).getUTCDate() + d: d;
	if(m<0){
		MesesCumplidos = meses + m;
		if(d<0){
			MesesCumplidos = MesesCumplidos - 1;
		}
    }else if(m>0){
        if(d<0){
            MesesCumplidos = m - 1;
        }else{
            MesesCumplidos =  m;
        }
	}else if(m==0){
		if(d<0){
			MesesCumplidos = meses - 1;
		}
	}
	if(m<0 || (m===0 && DiaHoy<DiaNacimiento)){
		AniosCumplidos -- ;
	}
	store.EdadPaciente = `${AniosCumplidos} años ${MesesCumplidos} mes(es) ${DiasCumplios} día(s)`;
} 

const GuardarPaciente = () => {    
   // router.post(route('guardar_paciente'),
   store.MostrarAlerta = false;
    var datos_registro = {
            id_paciente : id_paciente,
            AntecedentesHeredo : store.AntecedentesHeredo,
            antecedentesNoPatologicos : store.antecedentesNoPatologicos,
            toxicomanias : store.toxicomanias,
            antecedentesPerPatologicos : store.antecedentesPerPatologicos,
            cirugias_hospitalizaciones : store.cirugias_hospitalizaciones,
            inmunizaciones : store.inmunizaciones,
            antecedentesGinecoObs : store.antecedentesGinecoObs,
            alergias : store.alergias,
            cardiovascular : store.cardiovascular,
            respiratorio : store.respiratorio,
            NefroUrologico : store.NefroUrologico,
            Neurologico : store.Neurologico,
            Hematologicos : store.Hematologicos,
            Ginecologicos : store.Ginecologicos,
            Infectologicos : store.Infectologicos,
            Endocrinologicos : store.Endocrinologicos,
            Quirurgicos : store.Quirurgicos,
            Alergicos : store.Alergicos,
            SocioecEpide : store.SocioecEpide,
            NombrePaciente : store.NombrePaciente,
            PrimerApellidoPaciente : store.PrimerApellidoPaciente,
            SegundoApellidoPaciente : store.SegundoApellidoPaciente,
            anio_nacimiento : store.anio_nacimiento,
            mes_nacimiento : store.mes_nacimiento,
            dia_nacimiento : store.dia_nacimiento,
            FechaNacimientoPaciente : FechaNacimientoPaciente.value,
            EdadPaciente : store.EdadPaciente,
            recien_nacido : store.recien_nacido,
            CurpPaciente : store.CurpPaciente,
            TipoSangrePaciente : store.TipoSangrePaciente,
            GeneroPaciente : store.GeneroPaciente,
            OcupacionPaciente : store.OcupacionPaciente,
            EstadoCivilPaciente : store.EstadoCivilPaciente,
            NacionalidadPaciente : store.NacionalidadPaciente,
            CorreoPaciente : store.CorreoPaciente,
            telefono_casa : store.telefono_casa,
            telefono_celular : store.telefono_celular,
        };
        const url = `${api}/registrar`;		
			axios.post(url,datos_registro).then((response)=>{
                store.EstadoAlerta = 2;
                store.MostrarAlerta = true; 
                store.TituloAlerta = "Éxito";
                store.IconoAlerta = "mdi-check-circle";
				store.MensajeAlerta = response.data.message ;
                store.CallbackAlerta="RedireccionarConsulta"; 
			}).catch((error)=>{
				// console.log(error);
                store.EstadoAlerta = 0;
                store.MostrarAlerta = true; 
                store.CallbackAlerta=null; 				
                store.TituloAlerta = "Error";
                store.IconoAlerta = "mdi-close-circle";
				store.MensajeAlerta = error.response.data.mensaje ;
			});
}

	const RedireccionarConsulta = ()=>{
		//router.push(`/pacientes/${StoreAplicacion.RutaPacientes}`);
        store.MostrarAlerta = false;
        router.get('/pacientes/mis-pacientes');
	}

    const VerificarCurp = () => {
        const url = `${api}/verificar_curp`;	
        var curp = {
            id_paciente : id_paciente,
            recien_nacido : store.recien_nacido,
            CurpPaciente : store.CurpPaciente,
        }
		axios.post(url,curp).then((response)=>{
            console.log("Esta es la respuesta", response.data.estatus);
            store.EstadoAlerta =response.data.estatus ? 2 : 1;
            store.MostrarAlerta = true;
            store.TituloAlerta = response.data.estatus ? "Éxito" : 'Vincular';
            store.IconoAlerta = response.data.estatus ? "mdi-check-circle" : "mdi-help-circle";
			store.MensajeAlerta = response.data.mensaje ;
            store.CallbackAlerta=response.data.estatus ? null : "VincularSede"; 
		}).catch((error)=>{
			console.log(error);
            store.EstadoAlerta = 0;
            store.MostrarAlerta = true;
            store.CallbackAlerta=null; 				
            store.TituloAlerta = "Error";
            store.IconoAlerta = "mdi-close-circle";
			store.MensajeAlerta = error.response.data.mensaje ;
		})
    }

    const VincularSede = () =>{
             const url = `${api}/vincular_sede`;	
        var curp = {
            CurpPaciente : store.CurpPaciente,
        }
        axios.put(url,curp).then((response)=>{          
            id_exist.value = response.data.datos.id;            
            store.TituloAlerta = "Éxito";
            store.IconoAlerta = "mdi-check-circle";
            store.EstadoAlerta = 2;
            store.MostrarAlerta = true;
			store.MensajeAlerta = response.data.mensaje ;
            store.CallbackAlerta="PerfilExistentePaciente"; 
		}).catch((error)=>{
			// console.log(error);
            // store.CallbackAlerta=null; 				
            // store.TituloAlerta = "Error";
            // store.IconoAlerta = "mdi-close-circle";
			// store.MensajeAlerta = error.response.data.mensaje ;
		})
  
    }

    const PerfilExistentePaciente = () =>{
         store.MostrarAlerta = false;
        router.get(`/pacientes/perfil/${id_exist.value}`)
    }

    const AlertasFuncion = () => {
        if(id_paciente){
            store.EstadoAlerta = 1;
            store.MostrarAlerta = true;          
            store.TituloAlerta = "¿Estás seguro?";
            store.IconoAlerta = "mdi-help-circle";
			store.MensajeAlerta = "Se modificarán los datos del paciente." ;
            store.CallbackAlerta="GuardarPaciente"; 
        }else{
            store.EstadoAlerta = 1;
            store.MostrarAlerta = true;      
            store.TituloAlerta = "¿Estás seguro?";
            store.IconoAlerta = "mdi-help-circle";
			store.MensajeAlerta = "Se guardarán los datos del paciente." ;
            store.CallbackAlerta="GuardarPaciente"; 
        }
    }

    const NuevaConsulta = (id) => {		
        const url = `${consulta_api}/nueva`;
        //console.log("Esta es la url", url)
        axios.post(url,{id:id}).then((response)=>{
            //console.log("Este es el estatus",response.data.id_consulta);
            const id_consulta = response.data.id_consulta;
            if(response.data.estatus == 1){
                router.get(`/consulta/nuevo/${id_consulta}`);
                //  router.visit(`/consulta/nuevo/${id_consulta}`, {
                //     replace: true,
                //     preserveState: false,
                //     preserveScroll: false,
                // });
            }
        }).catch((error)=>{
            console.log(error)
        });

    }

   provide('FuncionesPadre',{RedireccionarConsulta, GuardarPaciente,VincularSede,PerfilExistentePaciente});
</script>
<template>
    <App :breadcrumbs="breadcrumbs">
        <v-container fluid>
            <v-row class="my-4 mx-5" no-gutters>
                <v-col cols="12">
                    <span class="poppins-semibold seccion-titulo">{{ id_paciente ? 'Modificar ': 'Registar un nuevo' }}  paciente</span>
                </v-col>
                <v-col cols="12">
                    <div class="poppins-light text-grey-darken-1">
                        Gestión de pacientes: agregar un nuevo paciente. 
                    </div>
                </v-col>
                <v-col cols="12" class="d-flex align-center justify-end mt-n5" v-if="id_paciente">
                <v-btn  color="primary" variant="text" rounded="lg" @click="NuevaConsulta(id_paciente)">
                    <v-icon size="25" color="primary">mdi-medical-bag</v-icon>
                    <span>Nueva consulta</span>
                    <v-tooltip
                                activator="parent"
                                location="bottom"
                                >Nueva consulta</v-tooltip>
                </v-btn>
                <v-btn  color="primary" variant="text" @click="router.get(`/pacientes/consulta/${id_paciente}`)" rounded="lg">
                    <v-icon size="25" color="primary">mdi-file-clock-outline</v-icon>
                    
                    <span >Historial de consultas</span>
                    <v-tooltip
                                activator="parent"
                                location="bottom"
                                >Historial de consultas</v-tooltip>
                </v-btn>
            </v-col>
            </v-row> 
            <v-tabs
                v-model="tab"                
                color="primary"
                class="mt-5"
            >
                <v-tab value="1" class="mr-2" variant="tonal" rounded="t-lg">Información</v-tab>
                <v-tab value="2" class="mr-2" variant="tonal" rounded="t-lg">Antecedentes</v-tab>
                <v-tab value="3" class="mr-2" variant="tonal" rounded="t-lg">Enfermedades</v-tab>
            </v-tabs>
             <v-row class="mt-10 mx-5" no-gutters>
                <v-col cols="12">
                    <v-card class="px-10 pt-5 pb-5 border-thin rounded-lg">
                        <v-tabs-window v-model="tab">
                            <v-tabs-window-item value="1">                            
                                <v-row class="" no-gutters>
                                    <v-col cols="12" >
                                        <div class="d-flex justify-strat poppins-medium">
                                            Información del paciente 
                                        </div>                              
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row class="mt-5">
                                    <v-col cols="12" md="4" xl="4" gd="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Nombre del paciente <span class="text-red">*</span></span>
                                        <v-text-field 
                                            v-model="store.NombrePaciente" 
                                            :counter="100" 
                                            maxlength="100" 
                                            variant="outlined" 
                                            color="primary"
                                            flat rounded="lg" 
                                            placeholder="Ingrese el nombre"      
                                            @update:modelValue="val => store.NombrePaciente = val ? val.toUpperCase() : ''"                                                                                          
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" xl="4" gd="4" >
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Primer apellido <span class="text-red">*</span></span>
                                        <v-text-field 
                                            v-model="store.PrimerApellidoPaciente" 
                                            :counter="100" 
                                            maxlength="100" 
                                            variant="outlined" 
                                            color="primary"
                                            flat 
                                            rounded="lg"  
                                            placeholder="Ingrese el primer apellido"
                                            @update:modelValue="val => store.PrimerApellidoPaciente = val ? val.toUpperCase() : ''"   
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4" xl="4" gd="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Segundo apellido</span>
                                        <v-text-field 
                                            v-model="store.SegundoApellidoPaciente" 
                                            :counter="100" 
                                            maxlength="100" 
                                            variant="outlined" 
                                            color="primary"
                                            flat 
                                            rounded="lg"  
                                            @update:modelValue="val => store.SegundoApellidoPaciente = val ? val.toUpperCase() : ''"   
                                            placeholder="Ingrese el segundo apellido (opcional)"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" md="8" xl="8" gd="8">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Fecha de nacimiento <span class="text-red">*</span></span>
                                            <v-row>
                                                <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                                    <v-autocomplete :menu-props="{ scrollStrategy: 'block', maxHeight:'200',contentClass:'rounded-lg'}"   hide-details placeholder="Año" v-model="store.anio_nacimiento"  @update:modelValue="MesesDisponibles"  :items="CatAnios" variant="outlined" 
                                            color="primary" flat>
                                                        <template v-slot:selection="{item}">
                                                            <span style="font-size:0.85rem;">{{item.title}}</span>
                                                        </template>
                                                    </v-autocomplete>
                                                </v-col>
                                                <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                                    <v-autocomplete @update:modelValue="MesesDisponibles" :menu-props="{ scrollStrategy: 'block', maxHeight:'200',contentClass:'rounded-lg'}"   hide-details placeholder="Mes" v-model="store.mes_nacimiento" :items="CatMesesDisponibles" variant="outlined" 
                                            color="primary" item-title="nombre" item-value="numero" flat>
                                                        <template v-slot:selection="{item}">
                                                            <span style="font-size:0.85rem;" class="text-truncate">{{item.title}}</span>
                                                        </template>
                                                    </v-autocomplete>

                                                </v-col>
                                                <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                                    <v-autocomplete :menu-props="{ scrollStrategy: 'block', maxHeight:'200',contentClass:'rounded-lg'}"   hide-details placeholder="Día" :items="CatDiasMes" v-model="store.dia_nacimiento" variant="outlined" 
                                            color="primary" flat>
                                                        <template v-slot:selection="{item}">
                                                            <span style="font-size:0.85rem;">{{item.title}}</span>
                                                        </template>
                                                    </v-autocomplete>
                                                </v-col>
                                            </v-row>
                                            </v-col>
                                    <v-col cols="12" sm="12" md="4" lg="4" xl="4" class="mt-6">
                                        <span>Edad</span><br>
                                        <span>{{ store.EdadPaciente }}</span>
                                    </v-col>
                                </v-row>
                                <v-row class="mt-7">
                                    <v-col cols="12" sm="3" md="4" lg="4" xl="4" class="d-flex align-center justify-center">                                
                                        <v-checkbox v-model="store.recien_nacido" label="Es recien nacido" @update:model-value="store.recien_nacido ? store.CurpPaciente = 'XXXXXXXXXXXXXXXXXX' : store.CurpPaciente = null"></v-checkbox>
                                    </v-col>
                                    <v-col cols="12" sm="5" md="6" lg="6" xl="6">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Clave única de registro de población (CURP) <span class="text-red">*</span></span>
                                        <v-text-field 
                                            v-model="store.CurpPaciente" 
                                            :counter="18" 
                                            maxlength="18"                                     
                                            variant="outlined" 
                                            color="primary"
                                            flat 
                                            rounded="lg" 
                                            placeholder="Ingrese el CURP (opcional)"
                                              @update:modelValue="val => store.CurpPaciente = val ? val.toUpperCase() : ''"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="2" md="3" lg="2" xl="2" class="mt-8" v-if="!id_paciente">
                                        <v-tooltip text="Verifica la curp" location="left">
                                             <template #activator="{ props }">
                                                <v-btn color="primary" rounded="lg" v-bind="props" @click="VerificarCurp()">
                                                    <v-icon >mdi-check</v-icon>Verificar
                                                </v-btn>
                                             </template>
                                        </v-tooltip>                                       
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="6" md="6" lg="6" xl="6">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Tipo de sangre</span>
                                        <v-select 
                                            v-model="store.TipoSangrePaciente"
                                            :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  
                                            color="primary"                                       
                                            :items="tipoSangre"
                                            placeholder="Seleccione el tipo de sangre del paciente" 
                                            item-value="valor" 
                                            item-title="etiqueta" 
                                            flat 
                                            rounded="lg" 
                                            variant="outlined"
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="6" lg="6" xl="6">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Género <span class="text-red">*</span></span>
                                        <v-select 
                                            v-model="store.GeneroPaciente"  
                                            :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  
                                            color="primary"                                       
                                            flat 
                                            rounded="lg" 
                                            :items="CatGenero"  
                                            variant="outlined" 
                                            item-title="GeneroEtiqueta" 
                                            item-value="GeneroValor" 
                                            placeholder="Seleccione un género"
                                        ></v-select>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Ocupación <span class="text-red">*</span></span>
                                        <v-text-field 
                                        v-model="store.OcupacionPaciente" 
                                        variant="outlined" 
                                        color="primary"
                                        flat 
                                        rounded="lg" 
                                        placeholder="Ingrese la ocupación del paciente" 
                                        :counter="100" 
                                        maxlength="100"
                                        ></v-text-field>

                                    </v-col>
                                    <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Estado civil <span class="text-red">*</span></span>
                                        <v-select 
                                            v-model="store.EstadoCivilPaciente"
                                            :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}"  
                                            color="primary"  
                                            :items="CatEstadoCivil" 
                                            placeholder="Seleccione el estado civil del paciente" 
                                            item-value="valor" 
                                            item-title="etiqueta"  
                                            variant="outlined"                                 
                                            flat 
                                            rounded="lg"                                     
                                        ></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Nacionalidad <span class="text-red">*</span></span>
                                        <v-autocomplete 
                                            :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" 
                                            v-model="store.NacionalidadPaciente" 
                                            :items="CatNacionalidad" 
                                            item-value="nacionalidadCod" 
                                            item-title="nacionalidadPais" 
                                            flat rounded="lg"  
                                            variant="outlined" 
                                            color="primary"
                                            placeholder="Seleccione la nacionalidad del paciente"
                                        ></v-autocomplete>
                                    </v-col>
                                </v-row>
                                <v-row class="mx-5 mt-8" no-gutters>
                                    <v-col cols="12" >
                                        <div class="d-flex justify-strat poppins-medium">
                                            Datos de contacto del paciente
                                        </div>
                                    </v-col>
                                </v-row>
                                <v-divider></v-divider>
                                <v-row class="mt-5">
                                    <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Correo electrónico</span>
                                        <v-text-field 
                                            :counter="100" 
                                            maxlength="100" 
                                            v-model="store.CorreoPaciente" 
                                            variant="outlined" 
                                            color="primary"
                                            flat 
                                            rounded="lg" 
                                            placeholder="usuario@ejemplo.com"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Teléfono de casa</span>
                                        <v-text-field
                                            v-model="store.telefono_casa"
                                            :counter="10"     
                                            maxlength="10"                                                  
                                            required
                                            color="primary"                       
                                            variant="outlined"
                                            flat
                                            rounded="lg"
                                            placeholder="Teléfono de casa"
                                            type="tel"
                                            @update:modelValue="store.telefono_casa = $event.replace(/\D/g, '')"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                                        <span class="etiqueta-formulario poppins-semibold text-grey-darken-2">Teléfono celular</span>
                                        <v-text-field
                                            v-model="store.telefono_celular"
                                            :counter="10"     
                                            maxlength="10"                                             
                                            required
                                            color="primary"                       
                                            variant="outlined"
                                            flat
                                            rounded="lg"
                                            placeholder="Teléfono celular"
                                            type="tel"
                                            @update:modelValue="store.telefono_celular = $event.replace(/\D/g, '')"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" class="d-flex justify-end">
                                        <v-btn color="grey" variant="outlined" rounded="lg" class="mr-2" @click="router.get('/pacientes/todos')">
                                            Cancelar
                                        </v-btn>
                                        <v-btn color="primary" rounded="lg" @click="tab = '2'">
                                            Siguiente
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-tabs-window-item>
                            <v-tabs-window-item value="2">
                                <Antecedentes :id_paciente="id_paciente"></Antecedentes>
                                    <v-row>
                                        <v-col cols="12" class="d-flex justify-end">
                                            <v-btn color="grey" variant="outlined" rounded="lg" class="mr-2" @click="router.get('/pacientes/todos')">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" rounded="lg" @click="tab = '3'">
                                                Siguiente
                                            </v-btn>
                                        </v-col>
                                    </v-row>  
                            </v-tabs-window-item>
                            <v-tabs-window-item value="3">
                                <Enfermedades></Enfermedades>
                                    <v-row>
                                        <v-col cols="12" class="d-flex justify-end">
                                            <v-btn color="grey" variant="outlined" rounded="lg" class="mr-2" @click="router.get('/pacientes/todos')">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" rounded="lg" @click="AlertasFuncion()">
                                               {{ id_paciente ? 'Modificar' : 'Guardar' }}
                                            </v-btn>
                                        </v-col>
                                    </v-row>  
                            </v-tabs-window-item>
                        </v-tabs-window>
                </v-card>
                </v-col>
            </v-row>
            <Alerta ref="MensajeAppAlerta" :callback="AlertaCallback"></Alerta>
        </v-container>
    </App>
  </template>