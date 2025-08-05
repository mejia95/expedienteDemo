import { defineStore } from 'pinia';

export const AplicacionStore = defineStore('store',{
    "state":()=>({
		SeccionConsulta:0,
        currentUrl:null,
        LoaderPeticionenCurso:false,
        idConsultaActual: null,
        enfermedades_pestania : 1,
        AntecedentesHeredo : null,
        antecedentesNoPatologicos : null,
        toxicomanias : [],
        antecedentesPerPatologicos : [],
        cirugias_hospitalizaciones : null,
        inmunizaciones : null,
        antecedentesGinecoObs : null,
        alergias : null,
        cardiovascular : null,
        respiratorio : null,
        NefroUrologico : null,
        Neurologico : null,
        Hematologicos : null,
        Ginecologicos : null,
        Infectologicos : null,
        Endocrinologicos : null,
        Quirurgicos : null,
        Alergicos : null,
        SocioecEpide : null,
        NombrePaciente : null,
        PrimerApellidoPaciente : null,
        SegundoApellidoPaciente : null,
        anio_nacimiento : null,
        mes_nacimiento : null,
        dia_nacimiento : null,
        EdadPaciente : null,
        recien_nacido : false,
        CurpPaciente : null,
        TipoSangrePaciente : null,
        GeneroPaciente : null,
        OcupacionPaciente : null,
        EstadoCivilPaciente : null,
        NacionalidadPaciente : null,
        CorreoPaciente : null,
        telefono_casa : null,
        telefono_celular : null,

        //////// OPCIONES DE LA APLICACION ////////
        cirugias_hospitalizaciones : null,
        inmunizaciones : null,
        antecedentesPerPatologicos : null,
        antecedentesNoPatologicos : null,
        antecedentesGinecoObs : null,
        toxicomanias : null,
        alergias : null,
        cardiovascular : null,
        respiratorio : null,
        NefroUrologico : null,
        Neurologico : null,
        Hematologicos : null,
        Ginecologicos : null,
        Infectologicos : null,
        Endocrinologicos : null,
        Quirurgicos : null,
        Alergicos : null,
        SocioecEpide : null,
        AntecedentesHeredo : null,
        paciente:{},


		//SECCIONES DE CONSULTA
		seccion_exploracion:false,
		seccion_antecedentes:false,
		seccion_estudios:false,
		seccion_diagnostico:false,

		
        ////////// EXPLORACIÓN ////////////
		SeccionExploracion : {},
		tab : 10,
		tension_sistolica : null,
		tension_diastolica : null,
		frecuencia_cardiaca : null,
		frecuencia_respiratoria : null,
		temperatura : 35,
		peso : null,
		peso_unidad : 'kg',
		altura : null,
		altura_unidad : 'm',
		imc : null,
		imc_unidad : 'Kg/m2',
        glucosa_sanguinea: null,
        glucosa_unidad: 'mg/dL',
        satuoxigeno: null,
		motivo_consulta : null,
		DatosDeSeccion : null,

        //Exploracion-Piel Variables
		aspecto : null,
		distribucion_pilosa : null,
		lesiones : null,
		topografia : null,
		sintomas : null,
		mucosas : null,
		unas : null,
		tejido_celular_subcutaneo : null,
		descripcion_piel : null,

        		//Exploracion-CabezaCuello Variables
		CraneoCara : null,
		CueroCabelludo : null,
		RegionOrbitoNasal : null,
		RegionOrofaringea : null,
		InspeccionCuello : null,
		PalpacionCuello : null,
		PercusionCuello : null,
		AuscultacionCuello : null,

		//Exploracion-Oftalmologico Variables
		cat_agudezas : [],
		AgudezaOjoDerecho : null,
		AgudezaOjoIzquierdo : null,
		RefraccionOjoDerecho : null,
		RefraccionOjoIzquierdo : null,
		PresionOcularOjoDerecho : null,
		PresionOcularOjoIzquierdo : null,
		ExploracionSegmentoAnterior : null,
		ExploracionRetinayVitreo : null,

		//Exploracion-Cuello Variables
		descripcion_cuello : null,
		//Exploracion-Respiratorio Variables
		SaturacionOxigeno : null,
		InspeccionRespiratorio : null,
		PalpacionRespiratorio : null,
		PercusionRespiratorio : null,
		AuscultacionRespiratorio : null,
		//Exploracion-Cardiovascular Variables
		descripcion_cardiovascular : null,
		//Exploracion-Abdomen Variables
		InspeccionAbdomen : null,
		PalpacionAbdomen : null,
		PercusionAbdomen : null,
		AuscultacionAbdomen : null,
		//Exploracion-Neurologico Variables
		Ocular : 0,
		Verbal : 0,
		Motora : 0,
		Total : 0,
		FuncionesMentalesSuperiores : null,
		ParesCranales : null,
		EsferaMotora : null,
		EsferaSensitiva : null,
		Reflejos : null,
		PruebasEspeciales : null,

		//Exploracion Gineco-obstetrico
		Mamas:null,
		Abdomen:null,
		Pelvis:null,

		//Diagnostico
		diagnosticos_sindromaticos:[],
		diagnosticos_etiologicos:null,
		diagnosticos_diferenciales:null,

		/// Tratamiento
		plan_terapeutico : null,
		ordenes_estudios : null,
		indicaciones_receta : null,
		arreglo_medicamento : [],

		/// MEDICAMENTOS

        /////// ACCIONES DE LA APLICACION ///////

        actions:{
		    debounce(func, delay) {
			    let timeout;
			    return (...args)=>{
				    clearTimeout(timeout);
				    timeout = setTimeout(() => {
				    	this.LoaderPeticionenCurso = true;
				    	func.apply(this, args);
				    	this.LoaderPeticionenCurso = false;
				    }, delay);
			    };
		    },
        }
    })
})