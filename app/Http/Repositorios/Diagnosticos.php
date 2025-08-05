<?php 
namespace App\Http\Repositorios;
use App\Models\ConsultaDiagnosticos;
use App\Models\DiagnosticosSindromaticos;
use App\Models\DiagnosticosEtiologicos;
use App\Models\DiagnosticosDiferenciales;
use App\Models\CatDiagnosticos;
use MongoDB\BSON\ObjectId;
use Log;
class Diagnosticos 
{
	private $consulta,
	$parametros_diagnostico,
	$diagnosticos_sindromaticos,
	$diagnosticos_etiologicos,
	$diagnosticos_diferenciales;

	public function DiagnosticoConsulta($consulta,$diagnosticos_sindromaticos,$diagnosticos_etiologicos,$diagnosticos_diferenciales){
		$this->consulta = $consulta;
		//$this->parametros_diagnostico=[];
		$this->diagnosticos_sindromaticos = $diagnosticos_sindromaticos;
		$this->diagnosticos_etiologicos = $diagnosticos_etiologicos;
		$this->diagnosticos_diferenciales = $diagnosticos_diferenciales;
		return $this;
	}

	public function Diagnostico(){
		$this->parametros_diagnostico = [
			'consulta'=>new ObjectId($this->consulta)
		];
		
		return $this;
	}
	public function Sindromaticos($consulta,$diagnosticos_sindromaticos,$bandera_copia){
		$documento_sindromaticos = [];

		$consulta_doc = DiagnosticosSindromaticos::where('consulta',new ObjectId($consulta))->get();
		if($consulta_doc){
			foreach($consulta_doc as $value){
				$delete = DiagnosticosSindromaticos::where('consulta',new ObjectId($consulta))->delete();
			}
		}
		if($diagnosticos_sindromaticos){
			foreach($diagnosticos_sindromaticos as $value){			
				$epi_clave = CatDiagnosticos::where('dId',$value)->first();

				$documento_sindromaticos['dId'] = $value;
				$documento_sindromaticos['clave_diagnostico'] = $epi_clave->EPI_CLAVE;
				$documento_sindromaticos['consulta']=new ObjectId($consulta);
				$documento_sindromaticos['bandera_copia']= $bandera_copia;
				
				$create = DiagnosticosSindromaticos::create($documento_sindromaticos);
			}
			return 1;
		}

		return 0;

	}
	public function Etiologicos($consulta,$diagnosticos_etiologicos, $bandera_copia){
		$documento_etiologicos = [];
	
		$consulta_doc = DiagnosticosEtiologicos::where('consulta',new ObjectId($consulta))->get();
		if($consulta_doc){
			foreach($consulta_doc as $value){
				$delete = DiagnosticosEtiologicos::where('consulta',new ObjectId($consulta))->delete();
			}
		}
		if($diagnosticos_etiologicos){
			foreach($diagnosticos_etiologicos as $value){
				$epi_clave = CatDiagnosticos::where('dId',$value)->first();

				$documento_etiologicos['dId'] = $value;
				$documento_etiologicos['clave_diagnostico'] = $epi_clave->EPI_CLAVE;
				$documento_etiologicos['consulta']=new ObjectId($consulta);
				$documento_etiologicos['bandera_copia']= $bandera_copia;
				$create = DiagnosticosEtiologicos::create($documento_etiologicos);
			}
			return 1;
		}
		return 0;
	}
	public function Diferenciales($consulta,$diagnosticos_diferenciales, $bandera_copia){
		$documento_diferenciales = [];

		$consulta_doc = DiagnosticosDiferenciales::where('consulta',new ObjectId($consulta))->get();
		if($consulta_doc){
			foreach($consulta_doc as $value){
				$delete = DiagnosticosDiferenciales::where('consulta',new ObjectId($consulta))->delete();
			}
		}

		if($diagnosticos_diferenciales){
			foreach($diagnosticos_diferenciales as $value){
				$epi_clave = CatDiagnosticos::where('dId',$value)->first();

				$documento_diferenciales['dId'] = $value;
				$documento_diferenciales['clave_diagnostico'] = $epi_clave->EPI_CLAVE;
				$documento_diferenciales['consulta']=new ObjectId($consulta);
				$documento_diferenciales['bandera_copia']= $bandera_copia;
				$create = DiagnosticosDiferenciales::create($documento_diferenciales);
			}
			return 1;
		}
		return 0;
		
	}
	public function guardar (){
		Log::info("Que traes en el array");
		Log::info($documento_sindromaticos);

		// $DiagnosticoConsulta = ConsultaDiagnosticos::where('consulta',new ObjectId($this->consulta))->first();
		// if(!$DiagnosticoConsulta){
		// 	Log::info("No existe el diagnostico");
		// 	//ConsultaDiagnosticos::insert($this->parametros_diagnostico);
		// }else{
		// 	Log::info("Si existe el diagnostico");
		// 	//$DiagnosticoConsulta->update($this->parametros_diagnostico);
		// }
		// return $this;
	}
}