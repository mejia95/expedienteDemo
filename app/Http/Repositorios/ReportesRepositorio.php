<?php 

namespace App\Http\Repositorios;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use App\Models\DependenciasSS;
use Illuminate\Support\Collection;
use Log;
class ReportesRepositorio {

	private $PlantillaSUIVE = "Formato_SUIVE_1 2025.xlsx";

	private $ColumnasSUIVE3 = [
		['epi_clave' => '114', 'columna_asociada' => 22],
		['epi_clave' => '115', 'columna_asociada' => 23],
		['epi_clave' => '116', 'columna_asociada' => 24],
		['epi_clave' => '135', 'columna_asociada' => 25],
		['epi_clave' => '155', 'columna_asociada' => 26],
		['epi_clave' => '119', 'columna_asociada' => 27],
		['epi_clave' => '97', 'columna_asociada' => 28],
		['epi_clave' => '117', 'columna_asociada' => 29],
		['epi_clave' => '118', 'columna_asociada' => 30],
		['epi_clave' => '203', 'columna_asociada' => 31],
		['epi_clave' => '204', 'columna_asociada' => 32],
		['epi_clave' => '205', 'columna_asociada' => 33],
		['epi_clave' => '206', 'columna_asociada' => 34],
		['epi_clave' => '207', 'columna_asociada' => 35],
		['epi_clave' => '208', 'columna_asociada' => 36],
		['epi_clave' => '209', 'columna_asociada' => 37],
		['epi_clave' => '210', 'columna_asociada' => 38],
		['epi_clave' => '211', 'columna_asociada' => 39],
		['epi_clave' => '212', 'columna_asociada' => 40],
		['epi_clave' => '213', 'columna_asociada' => 41],
		['epi_clave' => '214', 'columna_asociada' => 42],
		['epi_clave' => '215', 'columna_asociada' => 43],
		['epi_clave' => '216', 'columna_asociada' => 44],
		['epi_clave' => '217', 'columna_asociada' => 45],
		['epi_clave' => '129', 'columna_asociada' => 46],
		['epi_clave' => '130', 'columna_asociada' => 47],
		['epi_clave' => '131', 'columna_asociada' => 48],
		['epi_clave' => '184', 'columna_asociada' => 49],
		['epi_clave' => '96', 'columna_asociada' => 50],
		['epi_clave' => '169', 'columna_asociada' => 57],
	    ['epi_clave' => '170', 'columna_asociada' => 58],
	    ['epi_clave' => '171', 'columna_asociada' => 59],
	    ['epi_clave' => '195', 'columna_asociada' => 60],
	    ['epi_clave' => '196', 'columna_asociada' => 61],
	    ['epi_clave' => '197', 'columna_asociada' => 62],
	    ['epi_clave' => '198', 'columna_asociada' => 63],
	    ['epi_clave' => '199', 'columna_asociada' => 64],
	    ['epi_clave' => '200', 'columna_asociada' => 65],
	    ['epi_clave' => '201', 'columna_asociada' => 66],
	    ['epi_clave' => '202', 'columna_asociada' => 67],
	    ['epi_clave' => '123', 'columna_asociada' => 68],
	    ['epi_clave' => '124', 'columna_asociada' => 69],
	    ['epi_clave' => '126', 'columna_asociada' => 70],
	    ['epi_clave' => '132', 'columna_asociada' => 71],
	    ['epi_clave' => '227', 'columna_asociada' => 72],
	    ['epi_clave' => '228', 'columna_asociada' => 73],
	    ['epi_clave' => '229', 'columna_asociada' => 74],
	    ['epi_clave' => '122', 'columna_asociada' => 75],
	    ['epi_clave' => '172', 'columna_asociada' => 76],
	    ['epi_clave' => '230', 'columna_asociada' => 77],
	    ['epi_clave' => '231', 'columna_asociada' => 78],
	    ['epi_clave' => '232', 'columna_asociada' => 79],
	    ['epi_clave' => '233', 'columna_asociada' => 80]
	]
	;

	private $ColumnasSUIVE2 = [
		['epi_clave' => '29', 'columna_asociada' => 22],
		['epi_clave' => '102', 'columna_asociada' => 23],
		['epi_clave' => '218', 'columna_asociada' => 24],
		['epi_clave' => '219', 'columna_asociada' => 25],
		['epi_clave' => '30', 'columna_asociada' => 26],
		['epi_clave' => '12', 'columna_asociada' => 27],
		['epi_clave' => '103', 'columna_asociada' => 28],
		['epi_clave' => '34', 'columna_asociada' => 29],
		['epi_clave' => '35', 'columna_asociada' => 30],
		['epi_clave' => '45', 'columna_asociada' => 31],
		['epi_clave' => '73', 'columna_asociada' => 32],
		['epi_clave' => '104', 'columna_asociada' => 33],
		['epi_clave' => '39', 'columna_asociada' => 34],
		['epi_clave' => '173', 'columna_asociada' => 35],
		['epi_clave' => '43', 'columna_asociada' => 36],
		['epi_clave' => '36', 'columna_asociada' => 37],
		['epi_clave' => '72', 'columna_asociada' => 38],
		['epi_clave' => '68', 'columna_asociada' => 39],
		['epi_clave' => '105', 'columna_asociada' => 40],
		['epi_clave' => '186', 'columna_asociada' => 41],
		['epi_clave' => '187', 'columna_asociada' => 42],
		['epi_clave' => '181', 'columna_asociada' => 43],
		['epi_clave' => '182', 'columna_asociada' => 44],
		['epi_clave' => '194', 'columna_asociada' => 45],
		['epi_clave' => '144', 'columna_asociada' => 46],
		['epi_clave' => '145', 'columna_asociada' => 47],
		['epi_clave' => '69', 'columna_asociada' => 48],
		['epi_clave' => '74', 'columna_asociada' => 49],
		['epi_clave' => '235', 'columna_asociada' => 50],
		['epi_clave' => '64', 'columna_asociada' => 51],
		['epi_clave' => '66', 'columna_asociada' => 52],
		['epi_clave' => '98', 'columna_asociada' => 53],
		['epi_clave' => '41', 'columna_asociada' => 60],
		['epi_clave' => '110', 'columna_asociada' => 61],
		['epi_clave' => '48', 'columna_asociada' => 62],
		['epi_clave' => '127', 'columna_asociada' => 63],
		['epi_clave' => '49', 'columna_asociada' => 64],
		['epi_clave' => '136', 'columna_asociada' => 65],
		['epi_clave' => '112', 'columna_asociada' => 66],
		['epi_clave' => '46', 'columna_asociada' => 67],
		['epi_clave' => '47', 'columna_asociada' => 68],
		['epi_clave' => '51', 'columna_asociada' => 69],
		['epi_clave' => '52', 'columna_asociada' => 70],
		['epi_clave' => '54', 'columna_asociada' => 71],
		['epi_clave' => '128', 'columna_asociada' => 72],
		['epi_clave' => '109', 'columna_asociada' => 73],
		['epi_clave' => '111', 'columna_asociada' => 74],
		['epi_clave' => '57', 'columna_asociada' => 75],
		['epi_clave' => '220', 'columna_asociada' => 76],
		['epi_clave' => '221', 'columna_asociada' => 77],
		['epi_clave' => '222', 'columna_asociada' => 78],
		['epi_clave' => '223', 'columna_asociada' => 79],
		['epi_clave' => '224', 'columna_asociada' => 80],
		['epi_clave' => '225', 'columna_asociada' => 81],
		['epi_clave' => '226', 'columna_asociada' => 82],
		['epi_clave' => '94', 'columna_asociada' => 83],
		['epi_clave' => '107', 'columna_asociada' => 84],
		['epi_clave' => '91', 'columna_asociada' => 85],
		['epi_clave' => '151', 'columna_asociada' => 86],
		['epi_clave' => '152', 'columna_asociada' => 87],
		['epi_clave' => '106', 'columna_asociada' => 88],
		['epi_clave' => '153', 'columna_asociada' => 89],
		['epi_clave' => '125', 'columna_asociada' => 90],
		['epi_clave' => '150', 'columna_asociada' => 91],
		['epi_clave' => '148', 'columna_asociada' => 92],
		['epi_clave' => '234', 'columna_asociada' => 93]
	];

	private $ColumnasSUIVE1 = [
	    ['epi_clave'=>'44', 'columna_asociada'=>22],
	    ['epi_clave'=>'85', 'columna_asociada'=>23],
	    ['epi_clave'=>'86', 'columna_asociada'=>24],
	    ['epi_clave'=>'82', 'columna_asociada'=>25],
	    ['epi_clave'=>'83', 'columna_asociada'=>26],
	    ['epi_clave'=>'87', 'columna_asociada'=>27],
	    ['epi_clave'=>'32', 'columna_asociada'=>28],
	    ['epi_clave'=>'42', 'columna_asociada'=>29],
	    ['epi_clave'=>'38', 'columna_asociada'=>30],
	    ['epi_clave'=>'100', 'columna_asociada'=>31],
	    ['epi_clave'=>'37', 'columna_asociada'=>32],
	    ['epi_clave'=>'137', 'columna_asociada'=>33],
	    ['epi_clave'=>'99', 'columna_asociada'=>34],
	    ['epi_clave'=>'75', 'columna_asociada'=>35],
	    ['epi_clave'=>'40', 'columna_asociada'=>36],
	    ['epi_clave'=>'101', 'columna_asociada'=>37],
	    ['epi_clave'=>'176', 'columna_asociada'=>38],
	    ['epi_clave'=>'90', 'columna_asociada'=>39],
	    ['epi_clave'=>'33', 'columna_asociada'=>40],
	    ['epi_clave'=>'01', 'columna_asociada'=>41],
	    ['epi_clave'=>'06', 'columna_asociada'=>42],
	    ['epi_clave'=>'05', 'columna_asociada'=>43],
	    ['epi_clave'=>'08', 'columna_asociada'=>44],
	    ['epi_clave'=>'09', 'columna_asociada'=>45],
	    ['epi_clave'=>'02', 'columna_asociada'=>46],
	    ['epi_clave'=>'07', 'columna_asociada'=>47],
	    ['epi_clave'=>'93', 'columna_asociada'=>48],
	    ['epi_clave'=>'04', 'columna_asociada'=>49],
	    ['epi_clave'=>'10', 'columna_asociada'=>50],
	    ['epi_clave'=>'14', 'columna_asociada'=>57],
	    ['epi_clave'=>'03', 'columna_asociada'=>58],
	    ['epi_clave'=>'177', 'columna_asociada'=>59],
	    ['epi_clave'=>'178', 'columna_asociada'=>60],
	    ['epi_clave'=>'19', 'columna_asociada'=>61],
	    ['epi_clave'=>'18', 'columna_asociada'=>62],
	    ['epi_clave'=>'15', 'columna_asociada'=>63],
	    ['epi_clave'=>'16', 'columna_asociada'=>64],
	    ['epi_clave'=>'17', 'columna_asociada'=>65],
	    ['epi_clave'=>'191', 'columna_asociada'=>66],
	    ['epi_clave'=>'92', 'columna_asociada'=>67],
	    ['epi_clave'=>'25', 'columna_asociada'=>68],
	    ['epi_clave'=>'23', 'columna_asociada'=>69],
	    ['epi_clave'=>'24', 'columna_asociada'=>70],
	    ['epi_clave'=>'21', 'columna_asociada'=>71],
	    ['epi_clave'=>'26', 'columna_asociada'=>72],
	    ['epi_clave'=>'22', 'columna_asociada'=>73],
	    ['epi_clave'=>'20', 'columna_asociada'=>74],
	    ['epi_clave'=>'179', 'columna_asociada'=>75],
	    ['epi_clave'=>'192', 'columna_asociada'=>76],
	    ['epi_clave'=>'27', 'columna_asociada'=>77],
	    ['epi_clave'=>'189', 'columna_asociada'=>78],
	    ['epi_clave'=>'89', 'columna_asociada'=>79],
	    ['epi_clave'=>'76', 'columna_asociada'=>80],
	    ['epi_clave'=>'28', 'columna_asociada'=>81],
	    ['epi_clave'=>'88', 'columna_asociada'=>82],
	    ['epi_clave'=>'77', 'columna_asociada'=>83],
	    ['epi_clave'=>'81', 'columna_asociada'=>84],
	    ['epi_clave'=>'504', 'columna_asociada'=>85],
	    ['epi_clave'=>'78', 'columna_asociada'=>86],
	    ['epi_clave'=>'175', 'columna_asociada'=>87],
	    ['epi_clave'=>'80', 'columna_asociada'=>88],
	    ['epi_clave'=>'146', 'columna_asociada'=>89],
	    ['epi_clave'=>'180', 'columna_asociada'=>90],
	    ['epi_clave'=>'183', 'columna_asociada'=>91],
	    ['epi_clave'=>'188', 'columna_asociada'=>92]
	];
	private $arregloSUIVE,$arregloSUIVE2,$arregloSUIVE3;
	private $coleccion,$pipeline,$tipo,$plantilla;
	public $informacion;
	public function __construct()
	{
		$this->plantilla =  null;
		$this->tipo = "xlsx";
		$this->informacion = [];
		$this->arregloSUIVE = [];
		$this->arregloSUIVE2 = [];
		$this->arregloSUIVE3 = [];
	}

	public function coleccion($coleccion,$consulta){
		$this->coleccion = $coleccion;
		$this->pipeline = $consulta;

		Log::info("Que traes en el pipeline");
		Log::info($this->pipeline);
		return $this;
	}
	
	public function obtenerInformacion(){
		$pipeline = $this->pipeline;
		$chunks = 500;
		$skip = 0;




		$this->informacion = DB::table($this->coleccion)->raw(function($collection)use($pipeline){
			return $collection->aggregate($pipeline);
		})->toArray();
		
		$objeto = [];
		foreach ($this->informacion as $valor) {
			$objeto ["$valor->_id"] = json_decode(json_encode($valor),true);
		}
		$this->informacion = $objeto;
		Log::info("Que resultados traes");
		Log::info($this->informacion);
		return $this;
	}




	/**
	 * Procesamiento de datos por pagina
	 * para Reporte SUIVE
	 * PAgina 1 del formato
	 */

	private function InformacionPorPaginaSUIVE($columnas){
		$datos = $this->informacion;
		$columnasSheet1 = $columnas;
		$arregloOrdenadoSUIVE = [];
		foreach ($columnasSheet1 as $columna) {
			if(isset($datos[$columna['epi_clave']])){
				$columnaDatos = $datos[$columna['epi_clave']];
				$columna_index = (string) $columna['epi_clave'];
				$arregloOrdenadoSUIVE[$columna_index] = [
					'menos1AH' => (string)$columnaDatos['menos1AH'],
	     			'menos1AM' => (string)$columnaDatos['menos1AM'],
	     			'Rango1a4H' => (string)$columnaDatos['Rango1a4H'],
	     			'Rango1a4M' => (string)$columnaDatos['Rango1a4M'],
	     			'Rango5a9H' => (string)$columnaDatos['Rango5a9H'],
	     			'Rango5a9M' => (string)$columnaDatos['Rango5a9M'],
	     			'Rango10a14H' => (string)$columnaDatos['Rango10a14H'],
	     			'Rango10a14M' => (string)$columnaDatos['Rango10a14M'],
	     			'Rango15a19H' => (string)$columnaDatos['Rango15a19H'],
	     			'Rango15a19M' => (string)$columnaDatos['Rango15a19M'],
	     			'Rango20a24H' => (string)$columnaDatos['Rango20a24H'],
	     			'Rango20a24M' => (string)$columnaDatos['Rango20a24M'],
	     			'Rango25a44H' => (string)$columnaDatos['Rango25a44H'],
	     			'Rango25a44M' => (string)$columnaDatos['Rango25a44M'],
	     			'Rango45a49H' => (string)$columnaDatos['Rango45a49H'],
	     			'Rango45a49M' => (string)$columnaDatos['Rango45a49M'],
	     			'Rango50a59H' => (string)$columnaDatos['Rango50a59H'],
	     			'Rango50a59M' => (string)$columnaDatos['Rango50a59M'],
	     			'Rango60a64H' => (string)$columnaDatos['Rango60a64H'],
	     			'Rango60a64M' => (string)$columnaDatos['Rango60a64M'],
	     			'Rango65masH' => (string)$columnaDatos['Rango65masH'],
	     			'Rango65masM' => (string)$columnaDatos['Rango65masM'],
	     			'IgnH' => (string)$columnaDatos['IgnH'],
	     			'IgnM' => (string)$columnaDatos['IgnM'],
	     			'TotalH' => (string)$columnaDatos['TotalH'],
	     			'TotalM' => (string)$columnaDatos['TotalM'],
	     			'Total' => (string)$columnaDatos['Total'],
				];
			}else{
				$columna_index = (string)$columna['epi_clave'];
				$arregloOrdenadoSUIVE[$columna_index] = [
					'menos1AH' => "0",
	     			'menos1AM' => "0",
	     			'Rango1a4H' => "0",
	     			'Rango1a4M' => "0",
	     			'Rango5a9H' => "0",
	     			'Rango5a9M' => "0",
	     			'Rango10a14H' => "0",
	     			'Rango10a14M' => "0",
	     			'Rango15a19H' => "0",
	     			'Rango15a19M' => "0",
	     			'Rango20a24H' => "0",
	     			'Rango20a24M' => "0",
	     			'Rango25a44H' => "0",
	     			'Rango25a44M' => "0",
	     			'Rango45a49H' => "0",
	     			'Rango45a49M' => "0",
	     			'Rango50a59H' => "0",
	     			'Rango50a59M' => "0",
	     			'Rango60a64H' => "0",
	     			'Rango60a64M' => "0",
	     			'Rango65masH' => "0",
	     			'Rango65masM' => "0",
	     			'IgnH' => "0",
	     			'IgnM' => "0",
	     			'TotalH' => "0",
	     			'TotalM' => "0",
	     			'Total' => "0",
				];
			}
		}
		return $arregloOrdenadoSUIVE;
	}

	
	public function FormatearSUIVE(){
		$datos = $this->informacion;
		$columnasSheet1 = $this->ColumnasSUIVE1;
		$arregloOrdenadoSUIVE = [];
		
		

		Log::info("Que traes en arreglo orfdenado");
		Log::info($this->InformacionPorPaginaSUIVE($this->ColumnasSUIVE1));
		$this->arregloSUIVE = $this->InformacionPorPaginaSUIVE($this->ColumnasSUIVE1);
		$this->arregloSUIVE2 = $this->InformacionPorPaginaSUIVE($this->ColumnasSUIVE2);
		$this->arregloSUIVE3 = $this->InformacionPorPaginaSUIVE($this->ColumnasSUIVE3);

		Log::info("Como queo arregloSUIVE");
		Log::info($this->arregloSUIVE);
		Log::info("Como queo arregloSUIVE2");
		Log::info($this->arregloSUIVE2);
		//array_push($this->arregloSUIVE, $this->InformacionPorPaginaSUIVE());
		//array_push($this->arregloSUIVE, $this->InformacionPorPaginaSUIVE());
		return $this;

	}
	public function ConPlantilla ($plantilla){
		$this->plantilla = $plantilla;
		return $this;
	}

	public function GenerarReporte(
		$nombreArchivo,
		$primerDiaReporte,
		$deReporte,
		$alDiaReporte,
		$deMesReporte,
		$delAnioReporte,
		$consultorios,
		$semanaInicio,$semanaCierre
		){
		$plantilla_file = file_exists(storage_path("app/public/PlantillasReportesXLSX/$this->PlantillaSUIVE")) ? storage_path("app/public/PlantillasReportesXLSX/$this->PlantillaSUIVE") : "ninguna";
		if($this->plantilla){
			
			$noSemana="";
			if($semanaInicio==$semanaCierre){
				$noSemana = $semanaInicio;
			}else{
				$noSemana = "$semanaInicio - $semanaCierre";
			}



			$plantilla_excel = IOFactory::load($plantilla_file);
			$sheet = $plantilla_excel->setActiveSheetIndex(0);
			$sheet1 = $plantilla_excel->getSheet(0);
			$sheet2 = $plantilla_excel->getSheet(1);
			$sheet3 = $plantilla_excel->getSheet(2);
			$fila = 22;

			$comunidadesTag = "";

			$comunidades = DependenciasSS::whereIn('_id',$consultorios)->get(['nombre']);
			Log::info($comunidades);
			if($comunidades){
				foreach($comunidades as $index => $comunidad){
					if($index<1){$comunidadesTag = $comunidad->nombre;}else{
						$comunidadesTag = "$comunidadesTag,   $comunidad->nombre";
					}
					
					/*Log::info("que indice tienes");
					Log::info($index);
					Log::info("que indice comunidadesTag");
					Log::info($comunidadesTag);*/
				}
			}


			//Setear columnas en las tres hojas
			/*$sheet1->getStyle('O13:AA13')->getFont()->setSize(24);
			$sheet1->getStyle('D15')->getFont()->setSize(24);
			$sheet1->getStyle('F22:AF50')->getFont()->setSize(24);
			$sheet1->getStyle('F57:AF92')->getFont()->setSize(24);*/
			$sheet1->setCellValue('O13',$primerDiaReporte);
			$sheet1->setCellValue('Q13',$deReporte);
			$sheet1->setCellValue('U13',$alDiaReporte);
			$sheet1->setCellValue('W13',$deMesReporte);
			$sheet1->setCellValue('AA13',$delAnioReporte);
			$sheet1->setCellValue('D15',$comunidadesTag);


			//Setear titulos y tamaño de la hoja 2
			/*$sheet2->getStyle('O13:AA13')->getFont()->setSize(24);
			$sheet2->getStyle('D15')->getFont()->setSize(24);
			$sheet2->getStyle('F22:AF53')->getFont()->setSize(24);
			$sheet2->getStyle('F60:AF93')->getFont()->setSize(24);*/

			$sheet2->setCellValue('O13',$primerDiaReporte);
			$sheet2->setCellValue('Q13',$deReporte);
			$sheet2->setCellValue('U13',$alDiaReporte);
			$sheet2->setCellValue('W13',$deMesReporte);
			$sheet2->setCellValue('AA13',$delAnioReporte);
			$sheet2->setCellValue('D15',$comunidadesTag);


			//Setear titulos y tamaño de la hoja 3
			/*$sheet3->getStyle('O13:AA13')->getFont()->setSize(24);
			$sheet3->getStyle('D15')->getFont()->setSize(24);


			$sheet3->getStyle('F22:AF50')->getFont()->setSize(24);
			$sheet3->getStyle('F57:AF80')->getFont()->setSize(24);*/

			$sheet3->setCellValue('O13',$primerDiaReporte);
			$sheet3->setCellValue('Q13',$deReporte);
			$sheet3->setCellValue('U13',$alDiaReporte);
			$sheet3->setCellValue('W13',$deMesReporte);
			$sheet3->setCellValue('AA13',$delAnioReporte);
			$sheet3->setCellValue('D15',$comunidadesTag);


			foreach($this->ColumnasSUIVE1 as $registro_por_columna){
				$indexFila = $registro_por_columna['columna_asociada'];
				$sheet1->fromArray($this->arregloSUIVE[$registro_por_columna['epi_clave']], null, "F$indexFila");
			}
			foreach($this->ColumnasSUIVE2 as $registro_por_columna){
				$indexFila = $registro_por_columna['columna_asociada'];
				$sheet2->fromArray($this->arregloSUIVE2[$registro_por_columna['epi_clave']], null, "F$indexFila");
			}
			foreach($this->ColumnasSUIVE3 as $registro_por_columna){
				$indexFila = $registro_por_columna['columna_asociada'];
				$sheet3->fromArray($this->arregloSUIVE3[$registro_por_columna['epi_clave']], null, "F$indexFila");
			}
			
			$tempFile = storage_path("app/public/$nombreArchivo.xlsx");
        	$writer = new Xlsx($plantilla_excel);
        	$writer->save($tempFile);
        	Log::info("Se ha creado el Reporte en la carpeta de storage");
		}

		return $this;
	}
}

