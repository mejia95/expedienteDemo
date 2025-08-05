<?php
namespace App\Http\Repositorios;
use Log;
use App\Models\ConsultaLaboratorioEstudios;
use App\Models\ConsultaElectrocardiogramaEstudios;
use App\Models\ConsultaRadiografiaEstudios;
use App\Models\ConsultaEstudios;
use MongoDB\BSON\ObjectId;
/**
 * 
 */
class Estudios 
{
	private $estudios_laboratorio,$estudios_electrocardiograma,$estudios_radiografia,$consulta,$otros_estudios,$estudios,$seccionGuardada, $bandera_copia;

	public function insertarEstudio($consulta,$estudio,$estudios_laboratorio,$estudios_electrocardiograma,$estudios_radiografia,$otros_estudios, $bandera_copia){
		$this->estudio = $estudio;
		$this->consulta = $consulta;
		$this->estudios_laboratorio = $estudios_laboratorio;
		$this->estudios_electrocardiograma = $estudios_electrocardiograma;
		$this->estudios_radiografia = $estudios_radiografia;		
		$this->otros_estudios = $otros_estudios;
		$this->seccionGuardada = 1;
		$ConsultaEstudios = ConsultaEstudios::where('consulta',new ObjectId($this->consulta))->update(['otros'=>$this->otros_estudios,'bandera_copia'=>$bandera_copia]);
		return $this;

	}


	public function RegistrarLaboratorio($archivo1 = null,$archivo2=null,$archivo3=null, $bandera_copia){
		$datos = $this->estudios_laboratorio;
		$datos_laboratorio = [];
		if($archivo1){
			$nombreArchivo =  $archivo1->getFilename().'-'.date("YmdH:1").'.'.$archivo1->getClientOriginalExtension();
			$tipo = $archivo1->getMimeType();
			$archivo1 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo1)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo1->getClientOriginalName())
			];
		}
		if($archivo2){
			$nombreArchivo =  $archivo2->getFilename().'-'.date("YmdH:1").'.'.$archivo2->getClientOriginalExtension();
			$tipo = $archivo2->getMimeType();
			$archivo2 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo2)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo2->getClientOriginalName())
			];
		}
		if($archivo3){
			$nombreArchivo =  $archivo3->getFilename().'-'.date("YmdH:1").'.'.$archivo3->getClientOriginalExtension();
			$tipo = $archivo3->getMimeType();
			$archivo3 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo3)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo3->getClientOriginalName())
			];
		}
		$consulta = $this->consulta;
		if($datos){
			$datos_laboratorio = [
				'link_laboratorio'=>array_key_exists('link_laboratorio',$datos) ? $datos['link_laboratorio']:null ,
				'archivoLab1'=>$archivo1 ,
				'archivoLab2'=>$archivo2,
				'archivoLab3'=>$archivo3,
				'hematocrito'=>array_key_exists('hematocrito',$datos) ? (float)$datos['hematocrito']:null,
				'leucocitos'=>array_key_exists('leucocitos',$datos) ? (float)$datos['leucocitos']:null,
				'linfocitos'=>array_key_exists('linfocitos',$datos) ? (float)$datos['linfocitos']:null,
				'monocitos'=>array_key_exists('monocitos',$datos) ? (float)$datos['monocitos']:null,
				'volumen_corpuscular'=>array_key_exists('volumen_corpuscular',$datos) ? (float)$datos['volumen_corpuscular']:null,
				'plaquetas'=>array_key_exists('plaquetas',$datos) ? (float)$datos['plaquetas']:null,
				'glucemia'=>array_key_exists('glucemia',$datos) ? (float)$datos['glucemia']:null,
				'urea'=>array_key_exists('urea',$datos) ? (float)$datos['urea']:null,
				'creatinina'=>array_key_exists('creatinina',$datos) ? (float)$datos['creatinina']:null,
				'sodio'=>array_key_exists('sodio',$datos) ? (float)$datos['sodio']:null,
				'potasio'=>array_key_exists('potasio',$datos) ? (float)$datos['potasio']:null,
				'cloro'=>array_key_exists('cloro',$datos) ? (float)$datos['cloro']:null,
				'got'=>array_key_exists('got',$datos) ? (float)$datos['got']:null,
				'gpt'=>array_key_exists('gpt',$datos) ? (float)$datos['gpt']:null,
				'fosfatasa'=>array_key_exists('fosfatasa',$datos) ? (float)$datos['fosfatasa']:null,
				'bilirrubina'=>array_key_exists('bilirrubina',$datos) ? (float)$datos['bilirrubina']:null,
				'coagulograma'=>array_key_exists('coagulograma',$datos) ? (float)$datos['coagulograma']:null,
				'ph'=>array_key_exists('ph',$datos) ? (float)$datos['ph']:null,
				'co2'=>array_key_exists('co2',$datos) ? (float)$datos['co2']:null,
				'hco3'=>array_key_exists('hco3',$datos) ? (float)$datos['hco3']:null,
				'po2'=>array_key_exists('po2',$datos) ? (float)$datos['po2']:null,
				'saturacion_oxigeno'=>array_key_exists('saturacion_oxigeno',$datos) ? (float)$datos['saturacion_oxigeno']:null,
				'anion'=>array_key_exists('anion',$datos) ? (float)$datos['anion']:null,
				'orina'=>array_key_exists('orina',$datos) ? (float)$datos['orina']:null,
				'glucosa'=>array_key_exists('glucosa',$datos) ? (float)$datos['glucosa']:null,
				'hemocultivo'=>array_key_exists('hemocultivo',$datos) ? (float)$datos['hemocultivo']:null,
				'urocultivo'=>array_key_exists('urocultivo',$datos) ? (float)$datos['urocultivo']:null,
				'descripcion_urocultivo'=>array_key_exists('descripcion_urocultivo',$datos) ? $datos['descripcion_urocultivo']:null,			
			];
		}
		$datos_laboratorio['guardada'] = $this->seccionGuardada;
		$datos_laboratorio['estudio'] = new ObjectId($this->estudio);
		$datos_laboratorio['bandera_copia'] = $bandera_copia;
		$laboratorio = ConsultaLaboratorioEstudios::where('estudio',new ObjectId($this->estudio))->first();
		if(!$laboratorio){
			$laboratorio = ConsultaLaboratorioEstudios::create($datos_laboratorio);
		}else{
			$laboratorio->update($datos_laboratorio);
		}
		return $this;
	}

	public function RegistrarElectrocardiograma($archivo1 = null,$archivo2=null,$archivo3=null, $bandera_copia){
		$datos = $this->estudios_electrocardiograma;
		$consulta = $this->consulta;
		$datos_electrocardiograma = array();
		if($archivo1){
			$nombreArchivo =  $archivo1->getFilename().'-'.date("YmdH:1").'.'.$archivo1->getClientOriginalExtension();
			$tipo = $archivo1->getMimeType();
			$archivo1 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo1)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo1->getClientOriginalName())
			];
		}
		if($archivo2){
			$nombreArchivo =  $archivo2->getFilename().'-'.date("YmdH:1").'.'.$archivo2->getClientOriginalExtension();
			$tipo = $archivo2->getMimeType();
			$archivo2 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo2)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo2->getClientOriginalName())
			];
		}
		if($archivo3){
			$nombreArchivo =  $archivo3->getFilename().'-'.date("YmdH:1").'.'.$archivo3->getClientOriginalExtension();
			$tipo = $archivo3->getMimeType();
			$archivo3 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo3)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo3->getClientOriginalName())
			];
		}
		
		if($datos){
			$datos_electrocardiograma = [
				'link_electro'=>array_key_exists('link_electro',$datos) ? $datos['link_electro']:null ,
				'archivoElect1' => $archivo1,
				'archivoElect2' => $archivo2,
				'archivoElect3' => $archivo3,
				'descripcion' => array_key_exists('descripcion',$datos) ? $datos['descripcion']:null,
				'ritmo' => array_key_exists('ritmo',$datos) ? $datos['ritmo']:null,
				'frecuencia_cardiaca' => array_key_exists('frecuencia_cardiaca',$datos) ? (int)$datos['frecuencia_cardiaca']:null,
				'eje' => array_key_exists('eje',$datos) ? (int)$datos['eje']:null,
				'duracionQRS' => array_key_exists('duracionQRS',$datos) ? (float)$datos['duracionQRS']:null,
				'ondaP' => array_key_exists('ondaP',$datos) ? $datos['ondaP']:null,
				'ondaT' => array_key_exists('ondaT',$datos) ? $datos['ondaT']:null,
				'segmento' => array_key_exists('segmento',$datos) ? $datos['segmento']:null,
				'intervaloPR' => array_key_exists('intervaloPR',$datos) ? (float)$datos['intervaloPR']:null,
				'intervaloQTC' => array_key_exists('intervaloQTC',$datos) ? (float)$datos['intervaloQTC']:null,
				'conclusion' => array_key_exists('conclusion',$datos) ? $datos['conclusion']:null
			];
		}
		$datos_electrocardiograma['guardada'] = $this->seccionGuardada;
		$datos_electrocardiograma['estudio'] = new ObjectId($this->estudio);
		$datos_electrocardiograma['bandera_copia'] = $bandera_copia;
		$electrocardiograma = ConsultaElectrocardiogramaEstudios::where('estudio',new ObjectId($this->estudio))->first();
		if(!$electrocardiograma){
			$electrocardiograma = ConsultaElectrocardiogramaEstudios::create($datos_electrocardiograma);
		}else{
			$electrocardiograma->update($datos_electrocardiograma);
		}
		Log::info("hola aqui guarda Electrocardiograma");
		return $this;
	}
	public function RegistrarRadiografia($archivo1 = null,$archivo2=null,$archivo3=null, $bandera_copia){
		$datos = $this->estudios_radiografia;
		$consulta = $this->consulta;
		$datos_radiografia = [];
		if($archivo1){
			$nombreArchivo =  $archivo1->getFilename().'-'.date("YmdH:1").'.'.$archivo1->getClientOriginalExtension();
			$tipo = $archivo1->getMimeType();
			$archivo1 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo1)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo1->getClientOriginalName())
			];
		}
		if($archivo2){
			$nombreArchivo =  $archivo2->getFilename().'-'.date("YmdH:1").'.'.$archivo2->getClientOriginalExtension();
			$tipo = $archivo2->getMimeType();
			$archivo2 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo2)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo2->getClientOriginalName())
			];
		}
		if($archivo3){
			$nombreArchivo =  $archivo3->getFilename().'-'.date("YmdH:1").'.'.$archivo3->getClientOriginalExtension();
			$tipo = $archivo3->getMimeType();
			$archivo3 = [
				'base64_archivo'=>$filename_base64=base64_encode(file_get_contents($archivo3)),
				'tipo_archivo'=>$tipo,
				'nombre_archivo'=>strtolower($archivo3->getClientOriginalName())
			];
		}
		if($datos){
			$datos_radiografia = [
				'link_radiografia'=>array_key_exists('link_radiografia',$datos) ? $datos['link_radiografia']:null ,
				'archivoRadio1' => $archivo1,
				'archivoRadio2' => $archivo2,
				'archivoRadio3' => $archivo3,
				'partes_blandas' => array_key_exists('partes_blandas',$datos) ? $datos['partes_blandas']:null,
				'partes_oseas' => array_key_exists('partes_oseas',$datos) ? $datos['partes_oseas']:null,
				'campos_pulmonares' => array_key_exists('campos_pulmonares',$datos) ? $datos['campos_pulmonares']:null,
				'silueta_cardiovascular' => array_key_exists('silueta_cardiovascular',$datos) ? $datos['silueta_cardiovascular']:null,
				'indice_cardiotoracico' => array_key_exists('indice_cardiotoracico',$datos) ? $datos['indice_cardiotoracico']:null,
				'conclusiones' => array_key_exists('conclusiones',$datos) ? $datos['conclusiones']:null
			];			
		}
		$datos_radiografia['guardada'] = $this->seccionGuardada;
		$datos_radiografia['estudio'] = new ObjectId($this->estudio);
		$datos_radiografia['bandera_copia'] = $bandera_copia;
		$radiografia = ConsultaRadiografiaEstudios::where('estudio',new ObjectId($this->estudio))->first();
		if(!$radiografia){
			$radiografia = ConsultaRadiografiaEstudios::create($datos_radiografia);
		}else{
			$radiografia->update($datos_radiografia);
		}

		Log::info("hola aqui guarda Radiografia");
		return $this;
	}
}