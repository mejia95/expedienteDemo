<?php 

namespace App\Http\Repositorios;
use App\Models\ConsultaDiagnosticos;
use App\Models\Antecedentes;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Validator;
use Log;

class AntecedentesRepositorio 
{
    public function Agregar($request){

        $id_paciente = $request->id_paciente;
        $cirugias_hospitalizaciones = $request->cirugias_hospitalizaciones;
        $inmunizaciones = $request->inmunizaciones; 
        $antecedentesPerPatologicos = $request->antecedentesPerPatologicos;
        $antecedentesNoPatologicos = $request->antecedentesNoPatologicos;
        $antecedentesGinecoObs = $request->antecedentesGinecoObs;
        $toxicomanias = $request->toxicomanias;
        $alergias = $request->alergias;
        $cardiovascular = $request->cardiovascular;
        $respiratorio = $request->respiratorio;
        $NefroUrologico = $request->NefroUrologico;
        $Neurologico = $request->Neurologico;
        $Hematologicos = $request->Hematologicos;
        $Ginecologicos = $request->Ginecologicos;
        $Infectologicos = $request->Infectologicos;
        $Endocrinologicos = $request->Endocrinologicos;
        $Quirurgicos = $request->Quirurgicos;
        $Alergicos = $request->Alergicos;
        $SocioecEpide = $request->SocioecEpide;
        $AntecedentesHeredo = $request->AntecedentesHeredo;

        $validator = Validator::make($request->all(), [
            'cirugias_hospitalizaciones' => 'bail|nullable|string|max:255|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'inmunizaciones' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'antecedentesNoPatologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'antecedentesGinecoObs' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'alergias' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'cardiovascular' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'respiratorio' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'NefroUrologico' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Neurologico' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Hematologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Ginecologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Infectologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Endocrinologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Quirurgicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Alergicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'SocioecEpide' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'AntecedentesHeredo' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors(); 
            if ($errors->has('cirugias_hospitalizaciones')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>cirugias y hospitalizaciones.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('inmunizaciones')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong> inmunizaciones. (Números, emojis, etc)</p>";
            }
            else if ($errors->has('antecedentesNoPatologicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Antecedentes personales no patológicos.</strong> (Números, emojis, etc)</p>";
            }else if ($errors->has('antecedentesGinecoObs')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Antecedentes gineco-obstétricos. </strong>(Números, emojis, etc)</p>";
            } 
             else if ($errors->has('alergias')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Alergias.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('cardiovascular')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Cardiovascular (CV).</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('respiratorio')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Respiratorio.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('NefroUrologico')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Nefro-urológico.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('Neurologico')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Respiratorio. (Números, emojis, etc)</p>";
            } else  if ($errors->has('Hematologicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Hematológicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('Ginecologicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Ginecológicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('Infectologicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Infectológicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('Endocrinologicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Endocrinológicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('Quirurgicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Quirúrgicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('Alergicos')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Alérgicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('SocioecEpide')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Socioeconómicos/Epidemiológicos.</strong> (Números, emojis, etc)</p>";
            } else if ($errors->has('AntecedentesHeredo')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Antecedentes heredofamiliares.</strong> (Números, emojis, etc)</p>";
            } 
            return response()->json(array('message'=>$message),422);
        }
        
        $consulta = Antecedentes::create([
            'id_paciente' => new \MongoDB\BSON\ObjectId($id_paciente) ,
            'cirugias_hospitalizaciones' => $cirugias_hospitalizaciones,
            'inmunizaciones' => $inmunizaciones,
            'antecedentesPerPatologicos' => $antecedentesPerPatologicos,
            'antecedentesNoPatologicos' => $antecedentesNoPatologicos,
            'antecedentesGinecoObs' => $antecedentesGinecoObs,
            'toxicomanias' => $toxicomanias,
            'alergias' => $alergias,
            'cardiovascular' => $cardiovascular,
            'respiratorio' => $respiratorio,
            'NefroUrologico' => $NefroUrologico,
            'Neurologico' => $Neurologico,
            'Hematologicos' => $Hematologicos,
            'Ginecologicos' => $Ginecologicos,
            'Infectologicos' => $Infectologicos,
            'Endocrinologicos' => $Endocrinologicos,
            'Quirurgicos' => $Quirurgicos,
            'Alergicos' => $Alergicos,
            'SocioecEpide' => $SocioecEpide,
            'AntecedentesHeredo' => $AntecedentesHeredo,
        ]);

        if($consulta){
            return response()->json(['msg'=>"<p>Los datos de la sección <strong>Antecedentes</strong> se han guardado correctamente.</p>",'consulta'=>$consulta]);   
        }else{
            return response()->json(['message' => 'Error'], 500);
        }
    }

     public function Actualizar($id_consulta, $request){

        //$consulta = Antecedentes::where('id_consulta',new ObjectId($id_consulta))->first();
        
        
        $validator = Validator::make($request->all(), [
            'cirugias_hospitalizaciones' => 'bail|nullable|max:255|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'inmunizaciones' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            //'antecedentesPerPatologicos' => 'bail|nullable|regex:/^[A-Z,Ñ,a-z,á,é,í,ó,ú,ñ, ,0-9,.,,,:,;]+$/',
            //'antecedentesNoPatologicos' => 'bail|nullable|regex:/^[A-Z,Ñ,a-z,á,é,í,ó,ú,ñ, ,0-9,.,,,:,;]+$/',
            'antecedentesNoPatologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'antecedentesGinecoObs' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
           // 'toxicomanias' => 'bail|nullable|regex:/^[A-Z,Ñ,a-z,á,é,í,ó,ú,ñ, ,0-9,.,,,:,;]+$/',
            'alergias' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'cardiovascular' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'respiratorio' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'NefroUrologico' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Neurologico' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Hematologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Ginecologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Infectologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Endocrinologicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Quirurgicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'Alergicos' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'SocioecEpide' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
            'AntecedentesHeredo' => 'bail|nullable|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
        ]
    );

        if ($validator->fails()) {
            $errors = $validator->errors(); 
            if ($errors->has('cirugias_hospitalizaciones')) {
                $message="Registro no valido en cirugias y hospitalizaciones";
            } else if ($errors->has('inmunizaciones')) {
                $message="Registro no valido en inmunizaciones";
            }
            // else if ($errors->has('antecedentesPerPatologicos')) {
            //      $message="Registro no valido en Antecedentes personales patológicos ";
            // }
            else if ($errors->has('antecedentesNoPatologicos')) {
                $message="Registro no valido en Antecedentes personales no patológicos";
            }else if ($errors->has('antecedentesGinecoObs')) {
                $message="Registro no valido en Antecedentes gineco-obstétricos";
            } 
            // else if ($errors->has('toxicomanias')) {
            //      $message="Registro no valido en Toxicomanías";
            // }
             else if ($errors->has('alergias')) {
                $message="Registro no valido en Alergias";
            } else if ($errors->has('cardiovascular')) {
                $message="Registro no valido en Cardiovascular (CV)";
            } else if ($errors->has('respiratorio')) {
                $message="Registro no valido en Respiratorio";
            } else if ($errors->has('NefroUrologico')) {
                $message="Registro no valido en Nefro-urológico";
            } else if ($errors->has('Neurologico')) {
                $message="Registro no valido en Respiratorio";
            } else  if ($errors->has('Hematologicos')) {
                $message="Registro no valido en Hematológicos";
            } else if ($errors->has('Ginecologicos')) {
                $message="Registro no valido en Ginecológicos";
            } else if ($errors->has('Infectologicos')) {
                $message="Registro no valido en Infectológicos";
            } else if ($errors->has('Endocrinologicos')) {
                $message="Registro no valido en Endocrinológicos";
            } else if ($errors->has('Quirurgicos')) {
                $message="Registro no valido en Quirúrgicos";
            } else if ($errors->has('Alergicos')) {
                $message="Registro no valido en Alérgicos";
            } else if ($errors->has('SocioecEpide')) {
                $message="Registro no valido en Socioeconómicos/Epidemiológicos";
            } else if ($errors->has('AntecedentesHeredo')) {
                $message="Registro no valido en Antecedentes heredofamiliares";
            } 
            return response()->json(array('message'=>$message),422);
        }

        try{            
            $antecedentes = Antecedentes::where('id_paciente',new ObjectId($id_consulta))->first();                        

            if($antecedentes){
                $antecedentes->cirugias_hospitalizaciones = $request->cirugias_hospitalizaciones;
                $antecedentes->inmunizaciones = $request->inmunizaciones;
                $antecedentes->antecedentesPerPatologicos = $request->antecedentesPerPatologicos;
                $antecedentes->antecedentesNoPatologicos = $request->antecedentesNoPatologicos;
                $antecedentes->antecedentesGinecoObs = $request->antecedentesGinecoObs;
                $antecedentes->toxicomanias = $request->toxicomanias;
                $antecedentes->alergias = $request->alergias;
                $antecedentes->cardiovascular = $request->cardiovascular;
                $antecedentes->respiratorio = $request->respiratorio;
                $antecedentes->NefroUrologico = $request->NefroUrologico;
                $antecedentes->Neurologico = $request->Neurologico;
                $antecedentes->Hematologicos = $request->Hematologicos;
                $antecedentes->Ginecologicos = $request->Ginecologicos;
                $antecedentes->Infectologicos = $request->Infectologicos;
                $antecedentes->Endocrinologicos = $request->Endocrinologicos;
                $antecedentes->Quirurgicos = $request->Quirurgicos;
                $antecedentes->Alergicos = $request->Alergicos;
                $antecedentes->SocioecEpide = $request->SocioecEpide;
                $antecedentes->AntecedentesHeredo = $request->AntecedentesHeredo;
                $antecedentes->save();
                
            }else{
                $request->merge(['id_paciente'=>$id_consulta]);
                $this->Agregar($request);
            }
            return response()->json(['msg'=>"La información del paciente ha sido actualizada correctamente."]); 
        }catch(\Throwable $e){
            return response()->json(['message'=>"Ha ocurrido un error al actualizar la informacion.".$e->getMessage()],500); 
        }
        
    }
}