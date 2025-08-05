<?php

namespace App\Http\Repositorios;
use App\Models\ConsultaExploracion;
use App\Models\ConsultaExploracionPiel;
use App\Models\ConsultaExploracionCabezaCuello;
use App\Models\ConsultaExploracionOftalmologico;
use App\Models\ConsultaExploracionRespiratorio;
use App\Models\ConsultaExploracionCardiovascular;
use App\Models\ConsultaExploracionAbdomen;
use App\Models\ConsultaExploracionNeurologico;
use App\Models\ConsultaExploracionGinecoObstetrico;
use Log;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Decimal128;
class ExploracionesyConsulta
{
	private $consulta,
			$tension_sistolica,
            $tension_diastolica,
            $frecuencia_cardiaca,
            $frecuencia_respiratoria,
            $temperatura,
            $glucosa_sanguinea,
            $glucosa_unidad,
            $satuoxigeno,
            $peso,
            $peso_unidad,
            $altura,
            $altura_unidad,
            $imc,
            $imc_unidad,
            $motivo_consulta,
            $piel,
            $cabeza,
            $oftalmologico,
            $cuello,
            $respiratorio,
            $cardiovascular,
            $abdomen,
            $neurologico;
	public function setConsulta($consulta)
	{
		$this->consulta = $consulta;
		return $this;
	}

	public function RegistrarConsultaGral($tension_sistolica,
            $tension_diastolica,
            $frecuencia_cardiaca,
            $frecuencia_respiratoria,
            $temperatura,
            $peso,
            $peso_unidad,
            $altura,
            $altura_unidad,
            $imc,
            $imc_unidad,
            $glucosa_sanguinea,
            $glucosa_unidad,
            $satuoxigeno,
            $motivo_consulta,
			$copia)
	{		
		$consulta = $this->consulta;
		$Exploracion = ConsultaExploracion::where('consulta',new ObjectId($consulta))->first();
	
		if(!$Exploracion){
			$NuevaExploracion = ConsultaExploracion::create([
				'tension_sistolica'=>$tension_sistolica,
	            'tension_diastolica'=>$tension_diastolica,
	            'frecuencia_cardiaca'=>$frecuencia_cardiaca,
	            'frecuencia_respiratoria'=>$frecuencia_respiratoria,
	            'temperatura'=>$temperatura,
	            'peso'=>new Decimal128($peso),
	            'peso_unidad'=>$peso_unidad,
	            'altura'=>new Decimal128($altura),
	            'altura_unidad'=>$altura_unidad,
	            'imc'=>$imc,
	            'imc_unidad'=>$imc_unidad,
                //?Nuevos campos agregados
                'glucosa_sanguinea' => $glucosa_sanguinea,
                'glucosa_unidad' => $glucosa_unidad,
                'satuoxigeno' => $satuoxigeno,
                //////////////////////
	            'motivo_consulta'=>$motivo_consulta,
	            'consulta'=>new ObjectId($consulta),
				'bandera_copia' => $copia,
			]);
		}else{
			$Exploracion->update(['tension_sistolica'=>$tension_sistolica,
	            'tension_diastolica'=>$tension_diastolica,
	            'frecuencia_cardiaca'=>$frecuencia_cardiaca,
	            'frecuencia_respiratoria'=>$frecuencia_respiratoria,
	            'temperatura'=>$temperatura,
	            'peso'=>new Decimal128($peso),
	            'peso_unidad'=>$peso_unidad,
	            'altura'=>new Decimal128($altura),
	            'altura_unidad'=>$altura_unidad,
	            'imc'=>$imc,
	            'imc_unidad'=>$imc_unidad,
                'glucosa_sanguinea' => $glucosa_sanguinea,
                'glucosa_unidad' => $glucosa_unidad,
                'satuoxigeno' => $satuoxigeno,
	            'motivo_consulta'=>$motivo_consulta,
				'bandera_copia' => $copia,
			]);
		}

		Log::info("Se han insertado los datos generales de la consulta");
		return $this;
	}

	public function RegistrarPiel($datos_piel,$copia){
		$consulta = $this->consulta;
		$InformacionPiel = [];

		if($datos_piel){
			$InformacionPiel = $datos_piel;
		}
		$InformacionPiel['consulta']=new ObjectId($consulta);
		$InformacionPiel['bandera_copia']= $copia;
		$ExploracionPiel = ConsultaExploracionPiel::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionPiel){
			//$ExploracionPiel = ConsultaExploracionPiel::create($InformacionPiel);
			$ExploracionPiel = ConsultaExploracionPiel::create($InformacionPiel);
		}else{
			//$ExploracionPiel->update($InformacionPiel);
			$ExploracionPiel->update($InformacionPiel);
		}
		Log::info("Se ha insertado correctamente la informacion de la piel");

		return $this;
	}

	public function RegistrarCabezaYCuello($datos_cabeza_cuello, $copia){
		$consulta = $this->consulta;
		$InformacionCabezaCuello = [];
		if($datos_cabeza_cuello){
			$InformacionCabezaCuello = $datos_cabeza_cuello;
		}
		$InformacionCabezaCuello['consulta']=new ObjectId($consulta);
		$InformacionCabezaCuello['bandera_copia']= $copia;

		$ExploracionCabezaCuello = ConsultaExploracionCabezaCuello::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionCabezaCuello){
			$ExploracionCabezaCuello = ConsultaExploracionCabezaCuello::create($InformacionCabezaCuello);
		}else{
			$ExploracionCabezaCuello->update($InformacionCabezaCuello);
		}
		Log::info("Se ha insertado correctamente la informacion de cabeza-cuello");

		return $this;
	}
	public function RegistrarOftalmologico($datos_oftalmologico, $copia){
		$consulta = $this->consulta;
		$InformacionOftalmologico = [];
		if($datos_oftalmologico){
			$InformacionOftalmologico = $datos_oftalmologico;
		}
		$InformacionOftalmologico['consulta']=new ObjectId($consulta);
		$InformacionOftalmologico['bandera_copia']= $copia;

		$ExploracionOftalmologico = ConsultaExploracionOftalmologico::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionOftalmologico){
			$ExploracionOftalmologico = ConsultaExploracionOftalmologico::create($InformacionOftalmologico);
		}else{
			$ExploracionOftalmologico->update($InformacionOftalmologico);
		}
		Log::info("Se ha insertado correctamente la informacion de oftalmologico");

		return $this;
	}
	public function RegistrarRespiratorio($datos_respiratorio, $copia){
		$consulta = $this->consulta;
		$InformacionRespiratorio = [];
		if($datos_respiratorio){
			$InformacionRespiratorio = $datos_respiratorio;
		}
		$InformacionRespiratorio['consulta']=new ObjectId($consulta);
		$InformacionRespiratorio['bandera_copia']= $copia;

		$ExploracionRespiratorio = ConsultaExploracionRespiratorio::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionRespiratorio){
			$ExploracionRespiratorio = ConsultaExploracionRespiratorio::create($InformacionRespiratorio);
		}else{
			$ExploracionRespiratorio->update($InformacionRespiratorio);
		}
		Log::info("Se ha insertado correctamente la informacion de respiratorio");

		return $this;
	}
	public function RegistrarCardiovascular($datos_cardiovascular, $copia){
		$consulta = $this->consulta;
		$InformacionCardiovascular = [];
		if($datos_cardiovascular){
			$InformacionCardiovascular = $datos_cardiovascular;
		}
		$InformacionCardiovascular['consulta']=new ObjectId($consulta);
		$InformacionCardiovascular['bandera_copia']= $copia;

		$ExploracionCardiovascular = ConsultaExploracionCardiovascular::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionCardiovascular){
			$ExploracionCardiovascular = ConsultaExploracionCardiovascular::create($InformacionCardiovascular);
		}else{
			$ExploracionCardiovascular->update($InformacionCardiovascular);
		}
		Log::info("Se ha insertado correctamente la informacion de cardiovascular");

		return $this;
	}
	public function RegistrarAbdomen($datos_abdomen, $copia){
		$consulta = $this->consulta;
		$InformacionAbdomen = [];
		if($datos_abdomen){
			$InformacionAbdomen = $datos_abdomen;
		}
		$InformacionAbdomen['consulta']=new ObjectId($consulta);
		$InformacionAbdomen['bandera_copia']= $copia;

		$ExploracionAbdomen = ConsultaExploracionAbdomen::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionAbdomen){
			$ExploracionAbdomen = ConsultaExploracionAbdomen::create($InformacionAbdomen);
		}else{
			$ExploracionAbdomen->update($InformacionAbdomen);
		}
		Log::info("Se ha insertado correctamente la informacion de abdomen");

		return $this;
	}
	public function RegistrarNeurologico($datos_neurologico, $copia){
		$consulta = $this->consulta;
		$InformacionNeurologico = [];
		if($datos_neurologico){
			$InformacionNeurologico = $datos_neurologico;
		}
		$InformacionNeurologico['consulta']=new ObjectId($consulta);
		$InformacionNeurologico['bandera_copia']= $copia;

		$ExploracionNeurologico = ConsultaExploracionNeurologico::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionNeurologico){
			$ExploracionNeurologico = ConsultaExploracionNeurologico::create($InformacionNeurologico);
		}else{
			$ExploracionNeurologico->update($InformacionNeurologico);
		}
		Log::info("Se ha insertado correctamente la informacion de neurologico");

		return $this;
	}
	public function RegistrarGinecoObstetrico($datos_ginecoobstetrico, $copia){
		$consulta = $this->consulta;
		$InformacionGinecoObstetrico = [];
		if($datos_ginecoobstetrico){
			$InformacionGinecoObstetrico = $datos_ginecoobstetrico;
		}
		$InformacionGinecoObstetrico['consulta']=new ObjectId($consulta);
		$InformacionGinecoObstetrico['bandera_copia']= $copia;

		$ExploracionGinecoObstetrico = ConsultaExploracionGinecoObstetrico::where('consulta',new ObjectId($consulta))->first();
		if(!$ExploracionGinecoObstetrico){
			$ExploracionGinecoObstetrico = ConsultaExploracionGinecoObstetrico::create($InformacionGinecoObstetrico);
		}else{
			$ExploracionGinecoObstetrico->update($InformacionGinecoObstetrico);
		}
		Log::info("Se ha insertado correctamente la informacion de GinecoObstetrico");

		return $this;
	}

}
