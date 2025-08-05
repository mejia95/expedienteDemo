<script setup>
import {ref} from 'vue'
    const CatAspectos = ref([
        {valor:"hidratada",etiqueta:"Hidratada"},
        {valor:"deshidratada",etiqueta:"Deshidratada"},
        {valor:"ictericia",etiqueta:"Ictericia"},
        {valor:"otras",etiqueta:"Otras"}
    ]);
    const CatDistribuciones = ref([
        {valor:"normal",etiqueta:"Normal"},
        {valor:"deshidratada",etiqueta:"Deshidratada"},
        {valor:"hipertricosis",etiqueta:"Hipertricosis"},
        {valor:"disminucion",etiqueta:"Disminución"},
        {valor:"sin-pelo",etiqueta:"Sin Pelo"},
    ]);
    const CatLesiones= ref([
        { valor: "solidas", etiqueta: "Sólidas" },
        { valor: "contenido-liquido", etiqueta: "Contenido Líquido" },
        { valor: "tumores", etiqueta: "Tumores" },
        { valor: "cicatrices", etiqueta: "Cicatrices" },
        { valor: "infiltradas", etiqueta: "Infiltradas" },
        { valor: "exantemas", etiqueta: "Exantemas" },
        { valor: "otras", etiqueta: "Otras" }
    ]);
    const CatTopografias= ref([
        { valor: "localizada", etiqueta: "Localizada" },
        { valor: "diseminada", etiqueta: "Diseminada" },
        { valor: "generalizada", etiqueta: "Generalizada" }
    ]);
    const CatSintomas= ref([
        { valor: "dolor", etiqueta: "Dolor" },
        { valor: "comezon", etiqueta: "Comezón" },
        { valor: "ausentes", etiqueta: "Ausentes" }
    ]);
    const CatMucosas= ref([
        { valor: "hidratada", etiqueta: "Hidratada" },
        { valor: "deshidratada", etiqueta: "Deshidratada" },
        { valor: "con-lesiones", etiqueta: "Con Lesiones" }
    ]);
    const CatUnas= ref([
        { valor: "ausencia", etiqueta: "Ausencia" },
        { valor: "grasas", etiqueta: "Grasas" },
        { valor: "hipertroficas", etiqueta: "Hipertróficas" },
        { valor: "amarillas", etiqueta: "Amarillas" },
        { valor: "negras", etiqueta: "Negras" }
    ]);
    const CatTejido= ref([
        { valor: "presente", etiqueta: "Presente" },
        { valor: "ausente", etiqueta: "Ausente" }
    ]);
    const Aspecto = defineModel('Aspecto');
    const DescripcionPiel = defineModel('DescripcionPiel');
    const DistribucionPilosa = defineModel('DistribucionPilosa');
    const Lesiones = defineModel('Lesiones');
    const Topografia = defineModel('Topografia');
    const Sintomas = defineModel('Sintomas');
    const Mucosas = defineModel('Mucosas');
    const Unas = defineModel('Unas');
    const TejidoCelularSubcutaneo = defineModel('TejidoCelularSubcutaneo');

</script>
<template>
        <v-container fluid class="pa-0">
        <v-row class="rounded-t-lg mt-0">
            <v-col class="text-primary">
                <span style="font-size: 1.025rem;">Piel, faneras y tejido celular subcutáneo</span>
            </v-col>
        </v-row>
        <v-row class="mx-2">
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Aspecto general</span>
                <v-select :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="Aspecto" placeholder="Ingrese el aspecto general (ej. Hidratada,Deshidatrada)" variant="outlined" flat rounded="lg" :items="CatAspectos" item-value="valor" item-title="etiqueta" hide-details ></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Distribución de pelo / Implantación</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="DistribucionPilosa" variant="outlined" placeholder="Ingrese la distribución de pelo o tipo de implantación (ej. normal, hipertricosis, etc)" flat rounded="lg" :items="CatDistribuciones" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Lesiones</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="Lesiones" variant="outlined" placeholder="Describa las lesiones (ej. sólidas, tumores, etc)" flat rounded="lg" :items="CatLesiones" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Topografía</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="Topografia" placeholder="Especifique la topografía (ej. localizada, diseminada)" variant="outlined" flat rounded="lg" :items="CatTopografias" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Síntomas</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="Sintomas" placeholder="Ingrese los síntomas observados (ej. dolor, comezón)" variant="outlined" flat rounded="lg" :items="CatSintomas" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>

            <v-col cols="12">
                <span style="font-size: 0.875rem;">Mucosas</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="Mucosas" placeholder="Describa el estado de las mucosas (ej. hidratada, deshidratada, con lesiones)"  variant="outlined" flat rounded="lg" :items="CatMucosas" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Uñas</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="Unas" placeholder="Describa el estado de las uñas (ej. grasas, hipertróficas, amarillas)"  variant="outlined" flat rounded="lg" :items="CatUnas" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Tejido celular subcutáneo</span>
                <v-select hide-details :menu-props="{ scrollStrategy: 'block', width:'200', maxHeight:'200',contentClass:'rounded-lg'}" clearable v-model="TejidoCelularSubcutaneo" placeholder="Describa el estado del tejido celular subcutáneo (ej. presente, ausente)"  variant="outlined" flat rounded="lg" :items="CatTejido" item-value="valor" item-title="etiqueta"></v-select>
            </v-col>
            <v-col cols="12">
                <span style="font-size: 0.875rem;">Descripción</span>
                <v-textarea placeholder="Describa el estado de la piel: color, textura, lesiones, cicatrices u otros hallazgos relevantes." v-model="DescripcionPiel" :counter="255" maxlength="255"auto-grow row-height="4"rows="3"variant="outlined" flat rounded="lg"></v-textarea>
            </v-col>
        </v-row>
    </v-container>
</template>