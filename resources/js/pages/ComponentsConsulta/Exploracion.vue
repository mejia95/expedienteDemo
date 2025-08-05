<script setup>
    import App from '@/layouts/app/App.vue';

    const api_catalogos = "/catalogos/";
    import { useGoTo } from "vuetify";
    import { ref, onMounted, provide, computed, inject } from "vue";
    import { AplicacionStore } from "@/stores/Aplicacion";
    import PielFanerasTejido from "@/pages/ComponentsConsulta/PielFanerasTejido.vue";
    import Cabeza from "@/pages/ComponentsConsulta/Cabeza.vue";
    import Oftalmologico from "@/pages/ComponentsConsulta/Oftalmologico.vue";
    import Respiratorio from "@/pages/ComponentsConsulta/Respiratorio.vue";
    import Cardiovascular from "@/pages/ComponentsConsulta/Cadiovascular.vue";
    import Abdomen from "@/pages/ComponentsConsulta/Abdomen.vue";
    import Neurologico from "@/pages/ComponentsConsulta/Neurologico.vue";
    import GinecoObstetrico from "@/pages/ComponentsConsulta/GinecoObstetrico.vue";    
    import { watch } from "vue";
    import { router, Head, usePage } from '@inertiajs/vue3';
    import Alerta from '@/components/AppAlerta.vue';
    const api = "/consulta/";
// export default {
//     components: {
//         PielFanerasTejido,
//         Cabeza,
//         Cuello,
//         Oftalmologico,
//         Respiratorio,
//         Cardiovascular,
//         Abdomen,
//         Alerta,
//         Neurologico,
//         GinecoObstetrico,
//     },
//     props: ["consulta"],
    // setup(props) {

        const page = usePage()
        const id_consulta = page.props.id;
        const MensajeAppAlerta = ref(null);
        const AlertaCallback = ref(null);
       // const AlertaCallback = inject('AlertaCallback');
        const IndicarSeccion = computed(() => {
            switch (store.tab) {
                case 0:
                    return "Piel";
                case 1:
                    return "CyC";
                case 2:
                    return "Oft";
                case 4:
                    return "Res";
                case 5:
                    return "Car";
                case 6:
                    return "Abd";
                case 7:
                    return "Neu";
                case 8:
                    return "Gin";
                default:
                    return "";
            }
        });
        const IMCEntrada = () => {
            const digitosPermitidos = store.imc_unidad === "g/cm2" ? 2 : 3;

            let valor = store.imc;

            valor = valor.replace(/[^\d.]/g, "");
            if (valor.indexOf(".") !== -1) {
                let [entero, decimal] = valor.split(".");
                entero = entero.substring(0, digitosPermitidos);
                decimal = decimal.substring(0, 3);
                valor = `${entero}.${decimal}`;
            } else {
                valor = valor.substring(0, digitosPermitidos);
            }
            store.imc = valor;
        };

        //Funcion para validar que solo sean numeros en glucosa sanguinea
        const GLSEntrada = () => {
            const digitosPermitidos =
                store.glucosa_sanguinea === "mg/dL" ? 1 : 2;

            let valor = store.glucosa_sanguinea;
            valor = valor.replace(/[^\d.]/g, "");
            if (valor.indexOf(".") !== -1) {
                let [entero, decimal] = valor.split(".");
                entero = entero.substring(0, digitosPermitidos);
                decimal = decimal.substring(0, 3);
                valor = `${entero}.${decimal}`;
            } else {
                valor = valor.substring(0, digitosPermitidos);
            }
            store.glucosa_sanguinea = valor;
        };

        const SistolicaEntrada = () => {
            let valor = store.tension_sistolica;

            valor = valor.replace(/[^\d.]/g, "");
            if (valor.indexOf(".") !== -1) {
                let [entero, decimal] = valor.split(".");
                entero = entero.substring(0, 3);
                decimal = decimal.substring(0, 3);
                valor = `${entero}.${decimal}`;
            } else {
                valor = valor.substring(0, 3);
            }
            store.tension_sistolica = valor;
        };
        const DiastolicaEntrada = () => {
            let valor = store.tension_diastolica;

            valor = valor.replace(/[^\d.]/g, "");
            if (valor.indexOf(".") !== -1) {
                let [entero, decimal] = valor.split(".");
                entero = entero.substring(0, 3);
                decimal = decimal.substring(0, 3);
                valor = `${entero}.${decimal}`;
            } else {
                valor = valor.substring(0, 3);
            }
            store.tension_diastolica = valor;
        };
        const FCEntrada = () => {
            let valor = store.frecuencia_cardiaca;

            valor = valor.replace(/[^\d.]/g, "");
            if (valor.indexOf(".") !== -1) {
                let [entero, decimal] = valor.split(".");
                entero = entero.substring(0, 3);
                decimal = decimal.substring(0, 3);
                valor = `${entero}.${decimal}`;
            } else {
                valor = valor.substring(0, 3);
            }
            store.frecuencia_cardiaca = valor;
        };

        const FREntrada = () => {
            let valor = store.frecuencia_respiratoria;

            valor = valor.replace(/[^\d.]/g, "");
            if (valor.indexOf(".") !== -1) {
                let [entero, decimal] = valor.split(".");
                entero = entero.substring(0, 3);
                decimal = decimal.substring(0, 3);
                valor = `${entero}.${decimal}`;
            } else {
                valor = valor.substring(0, 3);
            }
            store.frecuencia_respiratoria = valor;
        };

        //Exploracion Variables
        const goTo = useGoTo();
        const FuncionCallback = ref(null);
        //const Alerta = ref(null);
        const store = AplicacionStore();
        const ConsultarInformacionGuardada = ref(0);
        //Exploracion Metodos
        const ConfirmacionDeGuardado = async () => {
            await goTo("#contenedor-consulta", {});
            
            store.EstadoAlerta = 1;
			store.MostrarAlerta = true;
            store.TituloAlerta = "¿Estás seguro?";
            store.IconoAlerta = "mdi-help-circle";
			store.MensajeAlerta = "La información que ha ingresado se guardará. Asegúrese de que todos los datos sean correctos antes de continuar." ;
            store.CallbackAlerta="GuardarSeccion"; 
           //  AlertaCallback.value = 'GuardarSeccion';
            //GuardarEstudiosLaboratorio(1);
            console.log("Se va a guardar la seccion de exploracion",store.MostrarAlerta );
        };

        //Funciones  saturacion oxigeno
        const opcionesSaturacion = ref([]);
        for (let i = 100; i >= 0; i--) {
            opcionesSaturacion.value.push(`${i}%`);
        }

        const SoloNumerosYDecimales = (event) => {
            var blockInput = 0;
            var flagEnteros = false;
            var flagDecimales = false;
            const allowedKeys = [
                "Backspace",
                "Tab",
                "ArrowLeft",
                "ArrowRight",
                "Delete",
            ];
            const isNumber = event.key >= "0" && event.key <= "9";
            const isDecimalPoint = event.key === ".";

            // Permitir solo un punto decimal
            if (
                isDecimalPoint &&
                (event.target.value.includes(".") || event.target.value === "")
            ) {
                event.preventDefault();
            }

            if (event.target.selectionStart == event.target.selectionEnd) {
                // Limitar a 4 dígitos enteros y 3 decimales
                const [integerPart, decimalPart] =
                    event.target.value.split(".");

                if (
                    !isNumber &&
                    !allowedKeys.includes(event.key) &&
                    !isDecimalPoint
                ) {
                    event.preventDefault();
                }

                var indexPoint = event.target.value.indexOf(".");

                if (indexPoint < 0) {
                    if (integerPart?.length >= 4 && isNumber) {
                        flagEnteros = true;
                        blockInput = blockInput + 1;
                        event.preventDefault();
                    }
                } else {
                    if (event.target.selectionStart - 1 < indexPoint) {
                        if (integerPart?.length >= 4 && isNumber) {
                            flagEnteros = true;
                            blockInput = blockInput + 1;
                            event.preventDefault();
                        }
                    } else {
                        if (
                            event.target.value.includes(".") &&
                            decimalPart?.length >= 3 &&
                            isNumber
                        ) {
                            flagDecimales = true;
                            blockInput = blockInput + 2;
                            event.preventDefault();
                        }
                    }
                }
            }

            console.log("Cuanto traes en blockInput", blockInput);
        };
        const ValidarTemperatura = (event) => {
            const allowedKeys = [
                "Backspace",
                "Tab",
                "ArrowLeft",
                "ArrowRight",
                "Delete",
            ];
            const isNumber = event.key >= "0" && event.key <= "9";
            const isDecimalPoint = event.key === ".";

            // Permitir solo un punto decimal
            if (
                isDecimalPoint &&
                (event.target.value.includes(".") || event.target.value === "")
            ) {
                event.preventDefault();
            }

            // Limitar a 4 dígitos enteros y 3 decimales
            const [integerPart, decimalPart] = event.target.value.split(".");

            // Limitar dígitos enteros a 2
            if (
                !event.target.value.includes(".") &&
                integerPart?.length >= 2 &&
                isNumber
            ) {
                event.preventDefault();
            }

            // Limitar decimales a 3
            if (
                event.target.value.includes(".") &&
                decimalPart?.length >= 1 &&
                isNumber
            ) {
                event.preventDefault();
            }

            // Permitir números y algunas teclas de control
            else if (
                !isNumber &&
                !allowedKeys.includes(event.key) &&
                !isDecimalPoint
            ) {
                event.preventDefault();
            }
        };
        const ValidarAltura = (event) => {
            const allowedKeys = [
                "Backspace",
                "Tab",
                "ArrowLeft",
                "ArrowRight",
                "Delete",
            ];
            const isNumber = event.key >= "0" && event.key <= "9";
            const isDecimalPoint = event.key === ".";

            // Permitir solo un punto decimal
            if (
                isDecimalPoint &&
                (event.target.value.includes(".") || event.target.value === "")
            ) {
                event.preventDefault();
            }

            // Limitar a 4 dígitos enteros y 3 decimales
            const [integerPart, decimalPart] = event.target.value.split(".");

            // let longitud = store.altura_unidad == "cm"
            // Limitar dígitos enteros a 2
            if (
                !event.target.value.includes(".") &&
                integerPart?.length >= 3 &&
                isNumber &&
                store.altura_unidad == "cm"
            ) {
                event.preventDefault();
            }
            if (
                !event.target.value.includes(".") &&
                integerPart?.length >= 1 &&
                isNumber &&
                store.altura_unidad == "m"
            ) {
                event.preventDefault();
            }

            // Limitar decimales a 3
            if (
                event.target.value.includes(".") &&
                decimalPart?.length >= 2 &&
                isNumber
            ) {
                event.preventDefault();
            }

            // Permitir números y algunas teclas de control
            else if (
                !isNumber &&
                !allowedKeys.includes(event.key) &&
                !isDecimalPoint
            ) {
                event.preventDefault();
            }
        };

        //Validar que glucosa sanguinea solo se puedan escribir numeros
        const ValidarGS = (event) => {
            const allowedKeys = [
                "Backspace",
                "Tab",
                "ArrowLeft",
                "ArrowRight",
                "Delete",
            ];
            const isNumber = event.key >= 0 && event.key <= "9";
            const [integerPart] = event.target.value.split(".");

            //Limitar digitos a 3
            if (
                !event.target.value.includes(".") &&
                integerPart?.length >= 3 &&
                isNumber
            ) {
                event.preventDefault();
            } else if (!isNumber && !allowedKeys.includes(event.key)) {
                event.preventDefault();
            }
        };

        const Decimales2 = (e) => {
            const allowedKeys = [
                "Backspace",
                "ArrowLeft",
                "ArrowRight",
                "Tab",
                ".",
            ];
            const char = String.fromCharCode(e.keyCode);
            const regex = /^\d{0,3}(\.\d{0,2})?$/;
            const hasDecimalPoint = event.target.value.includes(".");
            // Solo permite números y un único punto decimal
            if (
                !regex.test(event.target.value) &&
                !allowedKeys.includes(event.key)
            ) {
                console.log("Esta mal corrige");
                e.target.value.replace(/\D/g, "as");
                e.preventDefault();
            }
        };
        const Decimales = (event) => {
            if (store.peso_unidad == "Kg") {
                const allowedKeys = [
                    "Backspace",
                    "ArrowLeft",
                    "ArrowRight",
                    "Tab",
                    ".",
                ]; // Teclas permitidas
                const isNumber = /^[0-9]$/.test(event.key); // Solo números
                const hasDecimalPoint = event.target.value.includes("."); // Verifica si ya hay un punto decimal

                // Permitir solo un punto decimal, números o el signo negativo al inicio
                if (
                    !isNumber &&
                    !allowedKeys.includes(event.key) &&
                    !(event.key === "." && !hasDecimalPoint)
                ) {
                    event.preventDefault();
                }
            } else {
                const allowedKeys = [
                    "Backspace",
                    "ArrowLeft",
                    "ArrowRight",
                    "Tab",
                ]; // Teclas permitidas
                const isNumber = /^[0-9]$/.test(event.key); // Solo números

                // Permitir solo un punto decimal, números o el signo negativo al inicio
                if (!isNumber && !allowedKeys.includes(event.key)) {
                    event.preventDefault();
                }
            }
        };
        const ValidarPeso = () => {
            var valor = store.peso;
            if (store.peso_unidad == "Kg") {
                store.peso = Decimal3(valor);
            } else {
                store.peso = Enteros(valor);
            }
        };

        const KilosAGramos = (valor,unidad) => {
            if(unidad == "Kg"){
                return valor / 1000;
            }else{
                return valor * 1000;
            }
        }

        const MetrosACentimetros = (valor,unidad) => {
            if(unidad == "m"){
                return Math.floor((valor / 100) * 100) / 100;
            }else{
                return valor * 100;
            }
        }

        const CovertidorValoresAltura = () => {
            store.altura = MetrosACentimetros(store.altura,store.altura_unidad);
        }

        const CovertidorValoresPeso = () => {
            store.peso = KilosAGramos(store.peso,store.peso_unidad);
        }

        const TipoUnidadPeso = (value) => {
            if (value == "Kg") {
                var ValorPeso = store.peso ? store.peso / 1000 : null;
                store.peso = ValorPeso ? (ValorPeso % 1 === 0 ? Math.floor(ValorPeso) : ValorPeso.toFixed(3)) : null;
            } else {
                var ValorPeso = store.peso ? store.peso * 1000 : null;
                store.peso = ValorPeso ? ValorPeso : null;
            }
           // CovertidorValoresAltura();
           TipoUnidadIMC(store.imc_unidad);
        };

        const TipoUnidadAltura = (value) => {
            if(!value){return 0;}
            if (value == "m") {
                var ValorAltura = store.altura
                    ? Math.floor((store.altura / 100) * 100) / 100
                    : null;

                //store.altura = ValorAltura.toFixed(2);
                store.altura = ValorAltura ? ValorAltura : null;
            } else {
                var ValorAltura = store.altura ? store.altura * 100 : null;
                store.altura = ValorAltura ? ValorAltura : null;
            }
            TipoUnidadIMC(store.imc_unidad);
          //  CovertidorValoresPeso();
        };
        const TipoUnidadIMC = (value) => {
           store.imc_unidad = value;
           if(store.peso && store.altura){
                if(store.imc_unidad == "Kg/m2"){
                    let peso = store.peso_unidad == "g" ? store.peso / 1000 : store.peso;
                    let altura = store.altura_unidad == "cm" ? store.altura / 100 : store.altura;
                    let imc = peso / (altura * altura);
                    store.imc = imc.toFixed(3);
                }else{
                    let peso = store.peso_unidad == "Kg" ? store.peso * 1000 : store.peso;
                    let altura = store.altura_unidad == "m" ? store.altura * 100 : store.altura;
                    let imc = peso / (altura * altura);
                    store.imc = imc.toFixed(3);
                }
           }

        };

        //Funcion para convertir la glucosa de mg/dL a mmol/L
        const TipoUnidadGS = (value) => {
            if (value === "mg/dL") {
                var ValorGS = store.glucosa_sanguinea
                    ? (store.glucosa_sanguinea * 180.156) / 10
                    : null;
                if (ValorGS) {
                    store.glucosa_sanguinea = Number.isInteger(ValorGS)
                        ? ValorGS
                        : ValorGS.toFixed(0);
                } else {
                    store.glucosa_sanguinea = null;
                }
            } else {
                var valorGS = store.glucosa_sanguinea
                    ? (store.glucosa_sanguinea * 10) / 180.156
                    : null;
                if (valorGS) {
                    store.glucosa_sanguinea = Number.isInteger(valorGS)
                        ? valorGS
                        : valorGS.toFixed(1);
                } else {
                    store.glucosa_sanguinea;
                }
            }
        };


        //Funcion para calcular el IMC dado el peso y la altura
        const CalcularIMC = (value) => {
            if (store.peso && store.altura) {

                if(store.imc_unidad == "Kg/m2"){
                    let peso = store.peso_unidad == "g" ? store.peso / 1000 : store.peso;
                    let altura = store.altura_unidad == "cm" ? store.altura / 100 : store.altura;
                    let imc = peso / (altura * altura);
                    store.imc = imc.toFixed(3);
                }else{
                    let peso = store.peso_unidad == "Kg" ? store.peso * 1000 : store.peso;
                    let altura = store.altura_unidad == "m" ? store.altura * 100 : store.altura;
                    let imc = peso / (altura * altura);
                    store.imc = imc.toFixed(3);
                }
            } else {
                store.imc = null;
            }
        };

        //Si el valor de peso y altura cambia si es asi ejecutamos la funcion calcular IMC
        watch([() => store.peso, () => store.altura], CalcularIMC);
        watch(() => store.imc_unidad,(newValue) => {
            console.log("El valor de imc_unidad es: ",newValue);
        })
        const Decimal3 = (valor) => {
            const regex = /^[0-9]{0,3}(\.[0-9]{0,3})?$/;
            if (!regex.test(valor)) {
                console.log("No coincide el valor del decimal");
                valor = valor.slice(0, -1);
            }
            return valor;
        };
        const Enteros = (valor) => {
            const regex = /^[0-9]{0,6}$/;
            if (!regex.test(valor)) {
                valor = valor.slice(0, -1);
            }
            return valor;
        };

        const SetearValoresExploracion = (DatosExploracion) => {
            if (DatosExploracion) {
                store.tension_sistolica = DatosExploracion.tension_sistolica;
                store.tension_diastolica = DatosExploracion.tension_diastolica;
                store.frecuencia_cardiaca =
                    DatosExploracion.frecuencia_cardiaca;
                store.frecuencia_respiratoria =
                    DatosExploracion.frecuencia_respiratoria;
                store.temperatura = DatosExploracion.temperatura;
                store.peso = DatosExploracion.peso;
                store.peso_unidad = DatosExploracion.peso_unidad;
                store.altura = DatosExploracion.altura;
                store.altura_unidad = DatosExploracion.altura_unidad;
                store.imc = DatosExploracion.imc;
                store.imc_unidad = DatosExploracion.imc_unidad;
                store.glucosa_sanguinea = DatosExploracion.glucosa_sanguinea,
                store.glucosa_unidad = DatosExploracion.glucosa_unidad,
                store.satuoxigeno = DatosExploracion.satuoxigeno,
                store.motivo_consulta = DatosExploracion.motivo_consulta;
            }
        };
        const SetearValoresExploracionPiel = (DatosExploracion) => {
            if (DatosExploracion.ExploracionPiel.length > 0) {
                store.aspecto = DatosExploracion.ExploracionPiel[0].aspecto;
                store.descripcion_piel =
                    DatosExploracion.ExploracionPiel[0].descripcion_piel;
                store.distribucion_pilosa =
                    DatosExploracion.ExploracionPiel[0].distribucion_pilosa;
                store.lesiones = DatosExploracion.ExploracionPiel[0].lesiones;
                store.topografia =
                    DatosExploracion.ExploracionPiel[0].topografia;
                store.sintomas = DatosExploracion.ExploracionPiel[0].sintomas;
                store.mucosas = DatosExploracion.ExploracionPiel[0].mucosas;
                store.unas = DatosExploracion.ExploracionPiel[0].unas;
                store.tejido_celular_subcutaneo =
                    DatosExploracion.ExploracionPiel[0].tejido_celular_subcutaneo;
            }
        };
        const SetearValoresExploracionCabezaYCuello = (DatosExploracion) => {
            if (DatosExploracion.ExploracionCabezaCuello.length > 0) {
                store.CraneoCara =
                    DatosExploracion.ExploracionCabezaCuello[0].CraneoCara;
                store.CueroCabelludo =
                    DatosExploracion.ExploracionCabezaCuello[0].CueroCabelludo;
                store.RegionOrbitoNasal =
                    DatosExploracion.ExploracionCabezaCuello[0].RegionOrbitoNasal;
                store.RegionOrofaringea =
                    DatosExploracion.ExploracionCabezaCuello[0].RegionOrofaringea;
                store.InspeccionCuello =
                    DatosExploracion.ExploracionCabezaCuello[0].InspeccionCuello;
                store.PalpacionCuello =
                    DatosExploracion.ExploracionCabezaCuello[0].PalpacionCuello;
                store.PercusionCuello =
                    DatosExploracion.ExploracionCabezaCuello[0].PercusionCuello;
                store.AuscultacionCuello =
                    DatosExploracion.ExploracionCabezaCuello[0].AuscultacionCuello;
            }
        };
        const SetearValoresExploracionOftalmologico = (DatosExploracion) => {
            if (DatosExploracion.ExploracionOftalmologico.length > 0) {
                store.AgudezaOjoDerecho =
                    DatosExploracion.ExploracionOftalmologico[0].AgudezaOjoDerecho;
                store.AgudezaOjoIzquierdo =
                    DatosExploracion.ExploracionOftalmologico[0].AgudezaOjoIzquierdo;
                store.RefraccionOjoDerecho =
                    DatosExploracion.ExploracionOftalmologico[0].RefraccionOjoDerecho;
                store.RefraccionOjoIzquierdo =
                    DatosExploracion.ExploracionOftalmologico[0].RefraccionOjoIzquierdo;
                store.PresionOcularOjoDerecho =
                    DatosExploracion.ExploracionOftalmologico[0].PresionOcularOjoDerecho;
                store.PresionOcularOjoIzquierdo =
                    DatosExploracion.ExploracionOftalmologico[0].PresionOcularOjoIzquierdo;
                store.ExploracionSegmentoAnterior =
                    DatosExploracion.ExploracionOftalmologico[0].ExploracionSegmentoAnterior;
                store.ExploracionRetinayVitreo =
                    DatosExploracion.ExploracionOftalmologico[0].ExploracionRetinayVitreo;
            }
        };
        const SetearValoresExploracionRespiratorio = (DatosExploracion) => {
            if (DatosExploracion.ExploracionRespiratorio.length > 0) {
                store.SaturacionOxigeno =
                    DatosExploracion.ExploracionRespiratorio[0].SaturacionOxigeno;
                store.InspeccionRespiratorio =
                    DatosExploracion.ExploracionRespiratorio[0].InspeccionRespiratorio;
                store.PalpacionRespiratorio =
                    DatosExploracion.ExploracionRespiratorio[0].PalpacionRespiratorio;
                store.PercusionRespiratorio =
                    DatosExploracion.ExploracionRespiratorio[0].PercusionRespiratorio;
                store.AuscultacionRespiratorio =
                    DatosExploracion.ExploracionRespiratorio[0].AuscultacionRespiratorio;
            }
        };
        const SetearValoresExploracionCardiovascular = (DatosExploracion) => {
            if (DatosExploracion.ExploracionCardiovascular.length > 0) {
                //console.log("Ebtra a Cardiovascular");
                store.descripcion_cardiovascular =
                    DatosExploracion.ExploracionCardiovascular[0].descripcion_cardiovascular;
            }
        };
        const SetearValoresExploracionAbdomen = (DatosExploracion) => {
            if (DatosExploracion.ExploracionAbdomen.length > 0) {
                //console.log("Ebtra a Abdomen");
                store.InspeccionAbdomen =
                    DatosExploracion.ExploracionAbdomen[0].InspeccionAbdomen;
                store.PalpacionAbdomen =
                    DatosExploracion.ExploracionAbdomen[0].PalpacionAbdomen;
                store.PercusionAbdomen =
                    DatosExploracion.ExploracionAbdomen[0].PercusionAbdomen;
                store.AuscultacionAbdomen =
                    DatosExploracion.ExploracionAbdomen[0].AuscultacionAbdomen;
            }
        };
        const SetearValoresExploracionNeurologico = (DatosExploracion) => {
            if (DatosExploracion.ExploracionNeurologico.length > 0) {
                //console.log("Ebtra a Neurologico	");
                store.Ocular =
                    DatosExploracion.ExploracionNeurologico[0].Ocular;
                store.Verbal =
                    DatosExploracion.ExploracionNeurologico[0].Verbal;
                store.Motora =
                    DatosExploracion.ExploracionNeurologico[0].Motora;
                store.Total = DatosExploracion.ExploracionNeurologico[0].Total;
                store.EstadoAlerta =
                    DatosExploracion.ExploracionNeurologico[0].EstadoAlerta;
                store.FuncionesMentalesSuperiores =
                    DatosExploracion.ExploracionNeurologico[0].FuncionesMentalesSuperiores;
                store.ParesCranales =
                    DatosExploracion.ExploracionNeurologico[0].ParesCranales;
                store.EsferaMotora =
                    DatosExploracion.ExploracionNeurologico[0].EsferaMotora;
                store.EsferaSensitiva =
                    DatosExploracion.ExploracionNeurologico[0].EsferaSensitiva;
                store.Reflejos =
                    DatosExploracion.ExploracionNeurologico[0].Reflejos;
                store.PruebasEspeciales =
                    DatosExploracion.ExploracionNeurologico[0].PruebasEspeciales;
            }
        };
        const SetearValoresExploracionGinecoObstetrico = (DatosExploracion) => {
            if (DatosExploracion.ExploracionNeurologico.length > 0) {
                //console.log("Ebtra a Neurologico	");
                store.Mamas =
                    DatosExploracion.ExploracionGinecoObstetrico[0].Mamas;
                store.Abdomen =
                    DatosExploracion.ExploracionGinecoObstetrico[0].Abdomen;
                store.Pelvis =
                    DatosExploracion.ExploracionGinecoObstetrico[0].Pelvis;
            }
        };

        const ObtenerExploracionTodos = async () => {
 //           console.log("Que traes en ConsultarInformacionGuardada")
            if (ConsultarInformacionGuardada.value == 1) {
                return 0;
            }
            let url = `${api}${id_consulta}/exploracion/todo`;
            await axios
                .get(url, { params: { consulta: id_consulta } })
                .then(async (response) => {
                    await SetearValoresExploracion(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionPiel(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionCabezaYCuello(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionOftalmologico(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionRespiratorio(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionCardiovascular(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionAbdomen(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionNeurologico(
                        response.data.ExploracionTodos
                    );
                    await SetearValoresExploracionGinecoObstetrico(
                        response.data.ExploracionTodos
                    );
                    store.seccion_exploracion = true;
                    console.log("ok");
                })
                .catch((error) => {
                    console.log(error);
                });
        };
        const ObtenerPielFanerasTejido = async () => {
            //	console.log("Que traes en ConsultarInformacionGuardada",ConsultarInformacionGuardada.value)
            if (ConsultarInformacionGuardada.value == 1) {
                return 0;
            }
            let url = `${api}${id_consulta}/exploracion/piel`;
            await axios
                .get(url, { params: { consulta: id_consulta } })
                .then(async (response) => {
                    await SetearValoresExploracion(
                        response.data.ExploracionYPiel
                    );
                    await SetearValoresExploracionPiel(
                        response.data.ExploracionYPiel
                    );
                    console.log("ok");
                })
                .catch((error) => {
                    console.log(error);
                });
        };
        const ObtenerCabezaYCuello = async () => {
            //	console.log("Que traes en ConsultarInformacionGuardada",ConsultarInformacionGuardada.value)
            if (ConsultarInformacionGuardada.value == 1) {
                return 0;
            }
            let url = `${api}${id_consulta}/exploracion/cabeza-cuello`;
            await axios
                .get(url, { params: { consulta: id_consulta } })
                .then(async (response) => {
                    await SetearValoresExploracionCabezaYCuello(
                        response.data.CabezaCuello
                    );
                    console.log("ok");
                })
                .catch((error) => {
                    console.log(error);
                });
        };

        const seleccionarSeccion = async (seccion) => {
            store.tab = seccion;

            switch (store.tab) {
                case 0:
                    PielParametros();
                    break;
                case 1:
                    CabezaParametros();
                    break;
                case 2:
                    await ObtenerAgudezasVisuales();
                    OftalmologicoParametros();
                    break;
                case 3:
                    CuelloParametros();
                    break;
                case 4:
                    RespiratorioParametros();
                    break;
                case 5:
                    CardiovascularParametros();
                    break;
                case 6:
                    AbdomenParametros();
                    break;
                case 7:
                    NeurologicoParametros();
                    break;
                case 7:
                    GinecoObstetricoParametros();
                    break;
            }
        };

        const ObtenerAgudezasVisuales = async () => {
            const url = `${api_catalogos}agudeza-visual`;
            await axios
                .get(url)
                .then((response) => {
                    store.cat_agudezas = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        };

        const PielParametros = () => {
            store.SeccionExploracion.piel = {
                aspecto: store.aspecto,
                descripcion_piel: store.descripcion_piel,
                distribucion_pilosa: store.distribucion_pilosa,
                lesiones: store.lesiones,
                topografia: store.topografia,
                sintomas: store.sintomas,
                mucosas: store.mucosas,
                unas: store.unas,
                tejido_celular_subcutaneo: store.tejido_celular_subcutaneo,
            };
        };
        const CabezaParametros = () => {
            store.SeccionExploracion.cabeza = {
                CraneoCara: store.CraneoCara,
                CueroCabelludo: store.CueroCabelludo,
                RegionOrbitoNasal: store.RegionOrbitoNasal,
                RegionOrofaringea: store.RegionOrofaringea,
                InspeccionCuello: store.InspeccionCuello,
                PalpacionCuello: store.PalpacionCuello,
                PercusionCuello: store.PercusionCuello,
                AuscultacionCuello: store.AuscultacionCuello,
            };
        };
        const OftalmologicoParametros = () => {
            store.SeccionExploracion.oftalmologico = {
                AgudezaOjoDerecho: store.AgudezaOjoDerecho,
                AgudezaOjoIzquierdo: store.AgudezaOjoIzquierdo,
                RefraccionOjoDerecho: store.RefraccionOjoDerecho,
                RefraccionOjoIzquierdo: store.RefraccionOjoIzquierdo,
                PresionOcularOjoDerecho: store.PresionOcularOjoDerecho,
                PresionOcularOjoIzquierdo: store.PresionOcularOjoIzquierdo,
                ExploracionSegmentoAnterior: store.ExploracionSegmentoAnterior,
                ExploracionRetinayVitreo: store.ExploracionRetinayVitreo,
            };
        };
        const CuelloParametros = () => {
            store.SeccionExploracion.Cuello = {
                descripcion_cuello: store.descripcion_cuello,
            };
        };
        const RespiratorioParametros = () => {
            store.SeccionExploracion.respiratorio = {
                SaturacionOxigeno: store.SaturacionOxigeno,
                InspeccionRespiratorio: store.InspeccionRespiratorio,
                PalpacionRespiratorio: store.PalpacionRespiratorio,
                PercusionRespiratorio: store.PercusionRespiratorio,
                AuscultacionRespiratorio: store.AuscultacionRespiratorio,
            };
        };
        const CardiovascularParametros = () => {
            store.SeccionExploracion.cardiovascular = {
                descripcion_cardiovascular: store.descripcion_cardiovascular,
            };
        };
        const AbdomenParametros = () => {
            store.SeccionExploracion.abdomen = {
                InspeccionAbdomen: store.InspeccionAbdomen,
                PalpacionAbdomen: store.PalpacionAbdomen,
                PercusionAbdomen: store.PercusionAbdomen,
                AuscultacionAbdomen: store.AuscultacionAbdomen,
            };
        };
        const NeurologicoParametros = () => {
            store.Total = store.Ocular + store.Verbal + store.Motora;
            store.SeccionExploracion.neurologico = {
                Ocular: store.Ocular,
                Verbal: store.Verbal,
                Motora: store.Motora,
                Total: store.Total,
                EstadoAlerta: store.EstadoAlerta,
                FuncionesMentalesSuperiores: store.FuncionesMentalesSuperiores,
                ParesCranales: store.ParesCranales,
                EsferaMotora: store.EsferaMotora,
                EsferaSensitiva: store.EsferaSensitiva,
                Reflejos: store.Reflejos,
                PruebasEspeciales: store.PruebasEspeciales,
            };
        };
        const GinecoObstetricoParametros = () => {
            store.SeccionExploracion.GinecoObstetrico = {
                Mamas: store.Mamas,
                Abdomen: store.Abdomen,
                Pelvis: store.Pelvis,
            };
        };
        const GuardarSeccion = async () => {
            store.MostrarAlerta = false;
            PielParametros();
            CabezaParametros();
            OftalmologicoParametros();
            CuelloParametros();
            RespiratorioParametros();
            CardiovascularParametros();
            AbdomenParametros();
            NeurologicoParametros();
            GinecoObstetricoParametros();

            store.opcion_clinica_faltante = 0;
            store.opcion_faltante = 0;

            var parametros = {
                consulta: id_consulta,
                tension_sistolica: store.tension_sistolica,
                tension_diastolica: store.tension_diastolica,
                frecuencia_cardiaca: store.frecuencia_cardiaca,
                frecuencia_respiratoria: store.frecuencia_respiratoria,
                temperatura: store.temperatura,
                peso: store.peso,
                peso_unidad: store.peso_unidad,
                altura: store.altura,
                altura_unidad: store.altura_unidad,
                imc: store.imc,
                imc_unidad: store.imc_unidad,
                glucosa_sanguinea: store.glucosa_sanguinea,
                glucosa_unidad: store.glucosa_unidad,
                satuoxigeno: store.satuoxigeno,
                motivo_consulta: store.motivo_consulta,
                piel: store.SeccionExploracion.piel,
                cabeza: store.SeccionExploracion.cabeza,
                oftalmologico: store.SeccionExploracion.oftalmologico,
                cuello: store.SeccionExploracion.cuello,
                respiratorio: store.SeccionExploracion.respiratorio,
                cardiovascular: store.SeccionExploracion.cardiovascular,
                abdomen: store.SeccionExploracion.abdomen,
                neurologico: store.SeccionExploracion.neurologico,
                GinecoObstetrico: store.SeccionExploracion.GinecoObstetrico,
            };

            var url = `${api}${id_consulta}/exploracion`;
            axios
                .post(url, parametros)
                .then((response) => {
                    // Alerta.value.LoaderAlerta = false;
                    // Alerta.value.MostrarAlerta = true;
                    // Alerta.value.TipoAlerta = 2;
                    // Alerta.value.MensajeAlerta = response.data.message;
                    // FuncionCallback.value = null;
                    // store.seccion_exploracion = true;

                    store.EstadoAlerta = 2;
			        store.MostrarAlerta = true;
                    store.TituloAlerta = "Éxito";
                    store.IconoAlerta = "mdi-check-circle";
			        store.MensajeAlerta = response.data.message ;
                    store.CallbackAlerta=null; 
                    
                })
                .catch((error) => {
                    // console.log(error);
                    // Alerta.value.LoaderAlerta = false;
                    // Alerta.value.MostrarAlerta = true;
                    // Alerta.value.TipoAlerta = 0;
                    // Alerta.value.MensajeAlerta = error.response.data
                    //     ? error.response.data.message
                    //     : "Ha ocurrido un error. Por favor, inténtalo de nuevo más tarde.";
                    // FuncionCallback.value = null;
                    // store.seccion_exploracion = false;
                    store.EstadoAlerta = 0;
                    store.MostrarAlerta = true;
                    store.TituloAlerta = "Error";
                    store.IconoAlerta = "mdi-close-circle";
			        store.MensajeAlerta = error.response.data
                         ? error.response.data.message
                         : "Ha ocurrido un error. Por favor, inténtalo de nuevo más tarde.";
                    store.CallbackAlerta=null; 
                });
        };
    
    provide('FuncionesPadre', { GuardarSeccion });
 
// };
defineExpose({
    ObtenerExploracionTodos,
    seleccionarSeccion,
});
</script>
<template>
        <v-toolbar color="white" class="pa-0 border-sm rounded-t-lg">
        <v-row >
            <v-col class="d-flex align-center">
                <v-toolbar-title class="ps-5 text-primary-darken-3">
                    <v-icon size="18">mdi-stethoscope</v-icon>
                    <span class="mx-1 poppins-semibold" style="font-size: 1.1rem;">
                        Exploración y consulta 
                    </span>
                    <v-avatar size="25" rounded="pill" color="primary-darken-1" class="mx-2">
                        <v-icon size="15">
                            {{
                                store.seccion_exploracion
                                    ? "mdi-check-circle"
                                    : "mdi-content-save-alert"
                            }}
                        </v-icon>
                    </v-avatar>
                </v-toolbar-title>
            </v-col>
        </v-row>
    </v-toolbar>
    <v-container
        class="rounded-b-xl px-4 px-sm-6 px-md-6 px-lg-6 px-xl-6 border-sm"
        fluid
    >

        <v-row class="mt-0">

            <v-col cols="12">
                <v-tabs :color="store.tab == 10 ? 'primary-darken-1' : 'grey-darken-3'" density="compact"  mobile-breakpoint="800">
                    <v-tab  :variant="store.tab == 10 ? 'elevated' : 'tonal'" rounded="t-lg" class="mr-1 text-none" @click="store.tab = 10;" hide-slider>
                        <span style="font-size: 0.775rem;">Exploración Gral.</span>
                    </v-tab>
                    <v-menu scroll-strategy="block" location="bottom" color="green-darken-3">
                        <template v-slot:activator="{ props }">
                        <v-btn
                            class="align-self-center me-4 text-none"
                            height="100%"
                            rounded="t-lg"
                            :variant="store.tab != 10 ? 'elevated' : 'tonal'"
                            :color="store.tab != 10 ? 'primary-darken-1' : undefined"
                            v-bind="props"
                        >
                            <v-avatar size="25" rounded="lg" color="primary-darken-4" v-if="store.tab != 10">
                                <span style="font-size: 0.625rem;">{{ IndicarSeccion }}</span>
                            </v-avatar>
                            <span class="text-none mx-2" style="font-size: 0.775rem;">Exploración por secciones</span>



                            <v-icon icon="mdi-menu-down" end></v-icon>
                        </v-btn>
                        </template>

                        <v-list>
                        <v-list-item link @click="seleccionarSeccion(0)"  :class="store.tab ==0 ? 'bg-primary-lighten-4 text-primary' : ''">Piel, faneras y tejido celular subcutáneo</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(1)"  :class="store.tab ==1 ? 'bg-primary-lighten-4 text-primary' : ''" >Cabeza y Cuello</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(2)"  :class="store.tab ==2 ? 'bg-primary-lighten-4 text-primary' : ''" >Oftalmológico</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(4)"  :class="store.tab ==4 ? 'bg-primary-lighten-4 text-primary' : ''"     >Respiratorio</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(5)"  :class="store.tab ==5 ? 'bg-primary-lighten-4 text-primary' : ''" >Cardiovascular</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(6)"  :class="store.tab ==6 ? 'bg-primary-lighten-4 text-primary' : ''" >Abdomen</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(7)"  :class="store.tab ==7 ? 'bg-primary-lighten-4 text-primary' : ''"     >Neurológico</v-list-item>
                        <v-list-item link @click="seleccionarSeccion(8)"  :class="store.tab ==8 ? 'bg-primary-lighten-4 text-primary' : ''" >Gineco-obstétrico</v-list-item>

                        </v-list>
                    </v-menu>
                </v-tabs>
            </v-col>
            <v-alert
                class="my-1 mx-4"
                color="primary"
                variant="tonal"
                density="compact"
                rounded="lg"
                >
                    <v-icon size="15">mdi-information</v-icon>
                <span style="font-size: 0.875rem;" class="mx-2">Recuerde guardar cada sección para evitar la pérdida de información en caso de imprevistos.</span>
            </v-alert>
        </v-row>

        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-8" no-gutters v-if="store.tab == 10">
            <v-col cols="12" sm="4" md="4" lg="4" xl="4">
                <span style="font-size: 0.875rem" class="poppins-semibold">Temperatura</span> <span class="mx-2 text-red">*</span>
                <v-text-field
                    hide-details
                    @keydown="ValidarTemperatura"
                    placeholder="32°"
                    rounded="lg"
                    v-model="store.temperatura"
                    min="30"
                    max="42"
                    variant="outlined"
                    flat
                    class="mr-2"
                    color=primary
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 my-4" v-if="store.tab == 10">
            <v-col cols="12" sm="6" md="3" lg="3" xl="3">
                <span style="font-size: 0.875rem" class="poppins-semibold">Peso</span> <span class="mx-2 text-red">*</span>

                <v-text-field
                    hide-details
                    v-model="store.peso"
                    rounded="lg"
                    min="0"
                    variant="outlined"
                    flat
                    class="mr-2 "
                    placeholder="100 Kg"
                    color=primary
                    @keydown="SoloNumerosYDecimales"
                ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="3" lg="3" xl="2">
                <span style="font-size: 0.875rem" class="poppins-semibold">Unidad de medida</span>
                <v-select
                    :menu-props="{
                        scrollStrategy: 'block',
                        width: '200',
                        maxHeight: '200',
                        contentClass: 'rounded-lg',
                    }"
                    :items="['Kg', 'g']"
                    v-model="store.peso_unidad"
                    @update:modelValue="TipoUnidadPeso"
                    rounded="lg"
                    variant="outlined"
                    flat
                    hide-details
                    class=""
                    color=primary
                ></v-select>
            </v-col>
            <v-col cols="12" sm="6" md="3" lg="3" xl="3">
                <span style="font-size: 0.875rem" class="poppins-semibold">Altura</span> <span class="mx-2 text-red">*</span>
                <v-text-field
                    hide-details
                    @keydown="ValidarAltura"
                    v-model="store.altura"
                    rounded="lg"
                    min="0"
                    max="999"
                    variant="outlined"
                    flat
                    class="mr-2 "
                    placeholder="178 cm"
                    color=primary
                ></v-text-field>
            </v-col>

            <v-col cols="12" sm="6" md="3" lg="3" xl="2" class="poppins-semibold">
                Unidad de medida
                <v-select
                    :menu-props="{
                        scrollStrategy: 'block',
                        width: '200',
                        maxHeight: '200',
                        contentClass: 'rounded-lg',
                    }"
                    :items="['cm', 'm']"
                    v-model="store.altura_unidad"
                    rounded="lg"
                    @update:modelValue="TipoUnidadAltura"
                    variant="outlined"
                    flat
                    hide-details
                    color=primary
                ></v-select>
            </v-col>
        </v-row>
        <v-row class="px-sm-4 px-md-4 px-lg-4 px-xl-4 mt-5" no-gutters v-if="store.tab == 10">
            <v-col cols="8" sm="8" md="4" lg="4" xl="4" class="poppins-semibold">
            Índice de masa corporal
            <span class="text-primary" style="font-size: 1rem;">
                <p style="margin-bottom: 3px">{{ store.imc ? store.imc + ' ' + store.imc_unidad : 'N/A' }}</p>
            </span>
            </v-col>
        </v-row>
        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-4" no-gutters v-if="store.tab == 10">
            <v-col cols="8" sm="8" md="4" lg="4" xl="4">
                <span style="font-size: 0.875rem" class="poppins-semibold">Glucosa sanguínea</span>
                <v-text-field
                    hide-details
                    rounded="lg"
                    @keydown="ValidarGS"
                    variant="outlined"
                    flat
                    v-model="store.glucosa_sanguinea"
                    class="mr-2 "
                    placeholder="99 mg/dL"
                    color=primary
                ></v-text-field>
            </v-col>
            <v-col cols="4" sm="4" md="3" lg="2" xl="2">
                <span style="font-size: 0.875rem" class="poppins-semibold">Unidad de medida</span>
                <v-select
                    :menu-props="{
                        scrollStrategy: 'block',
                        width: '200',
                        maxHeight: '200',
                        contentClass: 'rounded-lg',
                    }"
                    :items="['mg/dL', 'mmol/L']"
                    rounded="lg"
                    v-model="store.glucosa_unidad"
                    variant="outlined"
                    @update:modelValue="TipoUnidadGS"
                    hide-details
                    class=""
                    flat
                    color=primary
                >
                </v-select>
            </v-col>
        </v-row>
        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-3" v-if="store.tab == 10">
            <v-col cols="12">
                <span style="font-size: 0.875rem" class="poppins-semibold">Saturación de oxígeno</span>
                <v-select
                    :items="opcionesSaturacion"
                    v-model="store.satuoxigeno"
                    rounded="lg"
                    variant="outlined"
                    flat
                    hide-details
                    class=""
                    placeholder="Seleccione el nivel de saturación de oxígeno"
                    color=primary
                ></v-select>
            </v-col>
        </v-row>

        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-2" no-gutters v-if="store.tab == 10">
            <v-col cols="12">
                <span style="font-size: 0.875rem" class="poppins-semibold">Tensión arterial</span>
            </v-col>
            <v-col>
                <v-container fluid class="pa-0">
                    <v-row no-gutters>
                        <v-col cols="12">
                            <v-container fluid class="pa-0">
                                <v-row no-gutters>
                                    <v-col cols="6" xl="2">
                                        <v-text-field
                                            @input="SistolicaEntrada"
                                            preppend="mdi-eye"
                                            placeholder="120 mmHg"
                                            hide-details
                                            v-model="store.tension_sistolica"
                                            rounded="lg"
                                            min="0"
                                            max="999"
                                            variant="outlined"
                                            class="mr-4"
                                            flat
                                            append-icon="mdi-slash-forward"
                                            color=primary
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="6" xl="2">
                                        <v-text-field
                                            hide-details
                                            placeholder="80 mmHg"
                                            @input="DiastolicaEntrada"
                                            flat
                                            v-model="store.tension_diastolica"
                                            rounded="lg"
                                            min="0"
                                            max="999"
                                            variant="outlined"
                                            class="mr-2 "
                                            color=primary
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col cols="6" xl="2"
                                        ><span
                                            class="text-primary"
                                            style="font-size: 0.825rem"
                                            >Sistólica</span
                                        ></v-col
                                    >
                                    <v-col cols="6" xl="2"
                                        ><span
                                            class="text-primary"
                                            style="font-size: 0.825rem"
                                            >Diastólica</span
                                        ></v-col
                                    >
                                </v-row>
                            </v-container>
                        </v-col>
                        <v-col> </v-col>
                    </v-row>
                </v-container>
            </v-col>
        </v-row>
        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 my-4" v-if="store.tab == 10">
            <v-col cols="6" sm="6" md="5" lg="4" xl="4">
                <span style="font-size: 0.875rem" class="poppins-semibold">Frecuencia cardiaca (lpm)</span>
                <v-text-field
                    hide-details="auto"
                    placeholder="60 lpm"
                    rounded="lg"
                    @input="FCEntrada"
                    @keydown="SoloNumerosYDecimales"
                    v-model="store.frecuencia_cardiaca"
                    min="0"
                    max="999"
                    variant="outlined"
                    flat
                    color=primary
                    class="mr-2"
                ></v-text-field>
            </v-col>
            <v-col cols="6" sm="6" md="5" lg="4" xl="4">
                <span style="font-size: 0.875rem" class="poppins-semibold">Frecuencia respiratoria (rpm)</span>
                <v-text-field
                    hide-details="auto"
                    rounded="lg"
                    placeholder="12 rpm"
                    @input="FREntrada"
                    @keydown="SoloNumerosYDecimales"
                    v-model="store.frecuencia_respiratoria"
                    min="0"
                    max="999"
                    variant="outlined"
                    flat
                    color=primary
                    class="mr-2"
                ></v-text-field>
            </v-col>
        </v-row>

        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4 mt-5" v-if="store.tab == 10">
            <v-col cols="12">
                <span style="font-size: 0.875rem" class="poppins-semibold">Motivo de consulta </span>
                <span class="mx-2 text-red">*</span>
                <v-textarea
                    :counter="255"
                    maxlength="255"
                    v-model="store.motivo_consulta"
                    placeholder="Describa brevemente el motivo de su consulta o los síntomas que presenta"
                    required
                    auto-grow
                    row-height="4"
                    rows="3"
                    hide-details="auto"
                    density="compact"
                    variant="outlined"
                    flat
                    rounded="lg"
                    color="primary"
                ></v-textarea>
            </v-col>
        </v-row>
        <v-row class="px-sm-2 px-md-4 px-lg-4 px-xl-4">
            <v-col class="rounded-sicodi mx-2 mb-4">
                <PielFanerasTejido
                    v-if="store.tab == 0"
                    v-model:Aspecto="store.aspecto"
                    v-model:DescripcionPiel="store.descripcion_piel"
                    v-model:DistribucionPilosa="store.distribucion_pilosa"
                    v-model:Lesiones="store.lesiones"
                    v-model:Topografia="store.topografia"
                    v-model:Sintomas="store.sintomas"
                    v-model:Mucosas="store.mucosas"
                    v-model:Unas="store.unas"
                    v-model:TejidoCelularSubcutaneo="
                        store.tejido_celular_subcutaneo
                    "
                ></PielFanerasTejido>
                <Cabeza
                    v-if="store.tab == 1"
                    v-model:CraneoCara="store.CraneoCara"
                    v-model:CueroCabelludo="store.CueroCabelludo"
                    v-model:RegionOrbitoNasal="store.RegionOrbitoNasal"
                    v-model:RegionOrofaringea="store.RegionOrofaringea"
                    v-model:InspeccionCuello="store.InspeccionCuello"
                    v-model:PalpacionCuello="store.PalpacionCuello"
                    v-model:PercusionCuello="store.PercusionCuello"
                    v-model:AuscultacionCuello="store.AuscultacionCuello"
                ></Cabeza>
                <Oftalmologico
                    v-if="store.tab == 2"
                    v-model:CatAgudezas="store.cat_agudezas"
                    v-model:AgudezaOjoDerecho="store.AgudezaOjoDerecho"
                    v-model:AgudezaOjoIzquierdo="store.AgudezaOjoIzquierdo"
                    v-model:RefraccionOjoDerecho="store.RefraccionOjoDerecho"
                    v-model:RefraccionOjoIzquierdo="
                        store.RefraccionOjoIzquierdo
                    "
                    v-model:PresionOcularOjoDerecho="
                        store.PresionOcularOjoDerecho
                    "
                    v-model:PresionOcularOjoIzquierdo="
                        store.PresionOcularOjoIzquierdo
                    "
                    v-model:ExploracionSegmentoAnterior="
                        store.ExploracionSegmentoAnterior
                    "
                    v-model:ExploracionRetinayVitreo="
                        store.ExploracionRetinayVitreo
                    "
                ></Oftalmologico>
                <Respiratorio
                    v-if="store.tab == 4"
                    v-model:SaturacionOxigeno="store.SaturacionOxigeno"
                    v-model:InspeccionRespiratorio="
                        store.InspeccionRespiratorio
                    "
                    v-model:PalpacionRespiratorio="store.PalpacionRespiratorio"
                    v-model:PercusionRespiratorio="store.PercusionRespiratorio"
                    v-model:AuscultacionRespiratorio="
                        store.AuscultacionRespiratorio
                    "
                ></Respiratorio>
                <Cardiovascular
                    v-if="store.tab == 5"
                    v-model:DescripcionCardiovascular="
                        store.descripcion_cardiovascular
                    "
                ></Cardiovascular>
                <Abdomen
                    v-if="store.tab == 6"
                    v-model:InspeccionAbdomen="store.InspeccionAbdomen"
                    v-model:PalpacionAbdomen="store.PalpacionAbdomen"
                    v-model:PercusionAbdomen="store.PercusionAbdomen"
                    v-model:AuscultacionAbdomen="store.AuscultacionAbdomen"
                ></Abdomen>
                <Neurologico
                    v-if="store.tab == 7"
                    v-model:Ocular="store.Ocular"
                    v-model:Verbal="store.Verbal"
                    v-model:Motora="store.Motora"
                    v-model:Total="store.Total"
                    v-model:EstadoAlerta="store.EstadoAlerta"
                    v-model:FuncionesMentalesSuperiores="
                        store.FuncionesMentalesSuperiores
                    "
                    v-model:ParesCranales="store.ParesCranales"
                    v-model:EsferaMotora="store.EsferaMotora"
                    v-model:EsferaSensitiva="store.EsferaSensitiva"
                    v-model:Reflejos="store.Reflejos"
                    v-model:PruebasEspeciales="store.PruebasEspeciales"
                ></Neurologico>
                <GinecoObstetrico
                    v-if="store.tab == 8"
                    v-model:Mamas="store.Mamas"
                    v-model:Abdomen="store.Abdomen"
                    v-model:Pelvis="store.Pelvis"
                ></GinecoObstetrico>
                <v-container> </v-container>
            </v-col>
        </v-row>
        <v-row class="d-flex align-center ma-2">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    @click="router.get('/pacientes/mis-pacientes')"
                    class="mx-2"
                    color="primary"
                    variant="text"
                    >Cancelar</v-btn
                >
                <v-btn class="mx-2" color="primary" @click="ConfirmacionDeGuardado"
                    >Guardar</v-btn
                >
            </v-col>
        </v-row>
        <Alerta ref="MensajeAppAlerta" :callback="AlertaCallback" v-if="store.SeccionConsulta == 0"></Alerta>
    </v-container>
</template>