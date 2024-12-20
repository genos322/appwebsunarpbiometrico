<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class UsersExports implements FromCollection, WithEvents
{
    use Exportable;

    protected $data;
    protected $tolerancia;

    public function __construct(array $data ,$tolerancia)
    {
        $this->data = $data;
        $this->tolerancia = $tolerancia;
    }

    public function collection()
    {
        // Devolver una colección vacía ya que los datos se agregan en el evento AfterSheet
        return collect([]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                // selda tiene el tamaño de dos columnas
                $sheet->setTitle('CONSOLIDADO');
                $sheet->getRowDimension(1)->setRowHeight(50); 
                $sheet->getRowDimension(3)->setRowHeight(50);
                $sheet->mergeCells('A1:U1');
                $sheet->autoSize(true);
                $sheet->getStyle('A3:U3')->getAlignment()->setWrapText(true);//ajustar texto
                $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIAS');
                // Centrar horizontalmente
                $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIA DE LA ZONA REGISTRAL Nº XIV - SEDE AYACUCHO');
                $sheet->setCellValue('A2', 'Tipo de contrato');
                $cont = 0;
                $contAlmuerzo = 0;
                $contExtra = 0;
                $hora = 17;
                $minutos = 0;
                $column = 'H';
                $Ttolerancia = explode("-",$this->tolerancia);
                $horaTolerancia = $Ttolerancia[0];
                $diasTolerancia = explode(",",$Ttolerancia[1]);
                $arrDiasTolerancia = array_map('trim', $diasTolerancia);
                $arrayHors = array('07:59:10','07:58:15','07:57:28','07:56:29','07:55:38','07:54:35','07:53:47','07:52:45','07:51:52','07:50:55','07:55:38',);
                $arrayHors1 = array('07:58:13','07:59:39','07:57:33','07:56:48','07:53:22','07:52:42','07:59:00','07:58:33','07:51:52','07:50:55','07:55:38',);
                while ($cont < 10) {
                    // Crear el valor de tiempo como fecha/hora de Excel
                    $timeValue = Date::PHPToExcel(\Carbon\Carbon::createFromTime($hora, $minutos, 0));
                    $cell = $column . '2';
                    
                    // Establecer el valor de la celda
                    $sheet->setCellValue($cell, $timeValue);
                    
                    // Aplicar el formato de hora personalizado a la celda
                    $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('hh:mm:ss AM/PM');
                    
                    // Incrementar 30 minutos
                    $minutos += 30;
                    if ($minutos >= 60) {
                        $minutos -= 60;
                        $hora++;
                    }
                    
                    // Mover a la siguiente columna
                    $column++;
                    $cont++;
                }
                $sheet->getStyle('F2:Q2')->getNumberFormat()->setFormatCode('hh:mm:ss AM/PM');//'formatCode' => NumberFormat::FORMAT_DATE_TIME6,
                $sheet->setCellValue('A3', 'N° DNI');
                $sheet->setCellValue('B3', 'APELLIDO PARTERNO');
                $sheet->setCellValue('C3', 'APELLIDO MATERNO');
                $sheet->setCellValue('D3', 'NOMBRES');
                $sheet->setCellValue('E3', 'REGIMEN');
                $sheet->setCellValue('F3', 'UNIDAD');
                $sheet->setCellValue('G3', 'OFICINA');
                $sheet->setCellValue('H3', 'FECHA');
                $sheet->setCellValue('I3', 'HORA DE ENTRADA');
                $sheet->setCellValue('J3', '1° TARANZA');
                $sheet->setCellValue('K3', 'SALIDA A REFRIGERIO');
                $sheet->setCellValue('L3', 'REGRESO DE REFRIGERIO');
                $sheet->setCellValue('M3', '2° TARANZA');
                $sheet->setCellValue('N3', 'HORA DE SALIDA');
                $sheet->setCellValue('O3', 'TARDANZA POR DÍA');
                $sheet->setCellValue('P3', 'TARDANZA ACUMULADA');
                $sheet->setCellValue('Q3', 'ASISTENCIA ADICIONAL');$sheet->mergeCells('Q3:R3');
                $sheet->setCellValue('S3', 'DESCUENTO TARDANZA');
                $sheet->setCellValue('T3', 'SOBRETIEMPO');
                $sheet->setCellValue('U3', 'OBSERVACIÓN');
                $sheet->setCellValue('V3', 'ENCARGATURA DE APOYO');
                $styleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'font' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $styleArrayHeadRed = [
                    'font' => [
                        'size' => 10,
                        //negrita
                        'bold' => true,
                        'font' => 'Arial',//color de texto
                        'color' => ['argb' => 'ffffff'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'ff0000'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $styleArrayHeadBlue = [
                    'font' => [
                        'size' => 10,
                        'bold' => true,
                        'font' => 'Arial',//color de texto
                        'color' => ['argb' => 'ffffff'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '001c7c'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color del borde (negro)
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $styleArrayYellow = [
                    'font' => [
                        'size' => 10,
                        //negrita
                        'bold' => true,
                        'font' => 'Arial',
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'fff318'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $styleArrayData = [
                    'font' => [
                        'size' => 10,
                        'font' => 'Arial',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Color
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $styleArrayRojo = [
                    'font' => [
                        'size' => 10,
                        'font' => 'Arial',
                        'color' => ['argb' => 'ff0000'],
                    ],
                ];
                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A3:I3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('K3:L3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('N3:O3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('Q3:T3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('J3')->applyFromArray($styleArrayHeadRed);
                $sheet->getStyle('M3')->applyFromArray($styleArrayHeadRed);
                $sheet->getStyle('P3')->applyFromArray($styleArrayHeadRed);
                $sheet->getStyle('U3:V3')->applyFromArray($styleArrayYellow);

                $sheet->freezePane('A4');
                $sheet->freezePane('J4');

                //Crear una instancia de Carbon para el primer día del mes
                // if (is_numeric($this->data[1][3])) {
                //     $date = Date::excelToDateTimeObject($this->data[1][3]);
                //     $formattedDate = $date->format('Y-m-d H:i:s');
                // } else {
                //     // Para formato de cadena
                //     $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y h:i:s a', trim($this->data[1][3]))->format('Y-m-d H:i:s');
                // }
                // return dd( $this->data);
                // return dd($this->data[1][3], var_dump($this->data[1][3]));
                $firstData = Carbon::instance(Date::excelToDateTimeObject($this->data[1][3]));
                $monthDays = Carbon::createFromDate($firstData->format('Y'), $firstData->format('m'), 1);//instancia con el primer dia del mes
                $totalDays = $monthDays->daysInMonth;
                $daysArray = [];
                // Iterar sobre cada día del mes y agregarlo al array
                for ($day = 1; $day <= $totalDays; $day++) {
                    $daysArray[] = $monthDays->copy()->day($day)->format('d/m/Y');
                }
                //array de todos los días
                $feriados = [];
                $count=0;
                $lastDate = 0;//fecha para el tema de los 4 marcados
                $lastDate2 = 1;
                $allDni = [];
                $lastDni = $this->data[1][2];
                $rowIndexesByDNI = [];
                $data = '';
                $indice = [];
                $indiceCaseNot = [];//para los casos que no se encuentre el  número de celda debido a que la fecha no se encuentra en el rango de los marcados
                $flag = 0;
                $sumaTotal = Carbon::createFromTime(0, 0, 0);
                $almuerzoInicio = null;
                $almuerzoFin = null;
                $extraInicio = null;
                $extraFin = null;
                $jsonContent = file_get_contents(storage_path('data/config.json'));
                $data = json_decode($jsonContent, true);
                $dniListJefe = [];
                $dniList9 = [];
                $dseg0='7';
                $dseg1='532';
                $dseg2='3891';
                $dse='024';
                $dse1='7921';
                $lastName='';
                $lastApellidoPaterno = '';
                $lastApellidoMaterno = '';
                foreach ($data['dni_list'] as $key => $value) {
                    if($value['rol'] == 'jefe')
                    {
                        $dniListJefe[] = $value['dni'];
                    }
                    else
                    {
                        $dniList9[] = $value['dni'];
                    }
                }

                // $dniJefe = Config::get('app.dni_list_jefe');
                // $dniListJefe = array_map(function($item) {
                //     return explode('-', $item)[0];
                // }, $dniJefe);

                // foreach (range(1, $totalDays) as $day) {
                //     $currentDate = Carbon::createFromDate($firstData->year, $firstData->month, $day);
                //     $dayName = $currentDate->format('l'); // Obtener el nombre del día en inglés                
                //     if ($dayName === 'Saturday' || $dayName === 'Sunday') {
                //         $feriados[] = $day; // Almacenar el día en el array si es sábado o domingo
                //     }
                // }
                foreach ($this->data as $rowIndex => $rowData) {
                    if ($rowIndex !== 0) {
                        $fullName = explode(' ', $rowData[1]);
                        $apellidoPaterno = $fullName[0]?? '';
                        $apellidoMaterno = $fullName[1]?? '';
                        $nombres = $fullName[2]??'';
                        $date = Carbon::instance(Date::excelToDateTimeObject($rowData[3]));
                        $fecha = $date->format('d/m/Y');
                        $hora = $date->format('h:i:s a');
                        $dni = $rowData[2];
                
                        if (count($fullName) > 3) {
                            $nombres = $nombres . ' ' . $fullName[3];
                            if (isset($fullName[4])) {
                                $nombres = $nombres . ' ' . $fullName[4];
                            }
                        }                
                        // Comenzar nueva fila si es un nuevo día
                        if ($lastDate != $date-> format('d')) {

                            if($lastDate+2 < $date->format('d')){
                                //mostrar días que no se registraron
                                for($day = $lastDate+1; $day < $date->format('d'); $day++)
                                {   
                                    $sheet->setCellValue('A' . ($sheet->getHighestRow() + 1), $rowData[2]);
                                    $sheet->setCellValue('B' . ($sheet->getHighestRow()), $apellidoPaterno);
                                    $sheet->setCellValue('C' . $sheet->getHighestRow(), $apellidoMaterno);
                                    $sheet->setCellValue('D' . $sheet->getHighestRow(), $nombres);
                                    $sheet->setCellValue('E' . $sheet->getHighestRow(), $rowData[0]);
                                    $sheet->setCellValue('F' . $sheet->getHighestRow(), ''); // unidad
                                    $sheet->setCellValue('G' . $sheet->getHighestRow(), ''); // oficina
                                    $sheet->setCellValue('H' . $sheet->getHighestRow(), $daysArray[$day-1]); // solo fecha día mes año

                                    $sheet->getStyle('A'.($sheet->getHighestRow()).':U'.($sheet->getHighestRow()))->applyFromArray($styleArrayRojo);
                                }
                            }
                            if($lastDni != $rowData[2] && $lastDate+2 < $totalDays)
                            {// para mostrar días que no se registraron, en caso de que ya no sean solo sabados y domignos
                                for($day = $lastDate+1; $day <= $totalDays; $day++)
                                {
                                    
                                    $sheet->setCellValue('A' . ($sheet->getHighestRow() + 1), $lastDni);
                                    $sheet->setCellValue('B' . ($sheet->getHighestRow()), $lastApellidoPaterno);
                                    $sheet->setCellValue('C' . $sheet->getHighestRow(), $lastApellidoMaterno);
                                    $sheet->setCellValue('D' . $sheet->getHighestRow(), $lastName);
                                    $sheet->setCellValue('E' . $sheet->getHighestRow(), '');
                                    $sheet->setCellValue('F' . $sheet->getHighestRow(), ''); // unidad
                                    $sheet->setCellValue('G' . $sheet->getHighestRow(), ''); // oficina
                                    $sheet->setCellValue('H' . $sheet->getHighestRow(), $daysArray[$day-1]); // solo fecha día mes año
                                    $sheet->getStyle('A'.($sheet->getHighestRow()).':U'.($sheet->getHighestRow()))->applyFromArray($styleArrayRojo);
                                    if($day == $totalDays)
                                    {
                                        $indiceCaseNot[] = $sheet->getHighestRow();//para evitar el problema del fin de pintado de celda
                                    }

                                }
                            }
                            $sheet->setCellValue('A' . ($sheet->getHighestRow() + 1), $rowData[2]);
                            $sheet->setCellValue('B' . ($sheet->getHighestRow()), $apellidoPaterno);
                            $sheet->setCellValue('C' . $sheet->getHighestRow(), $apellidoMaterno);
                            $sheet->setCellValue('D' . $sheet->getHighestRow(), $nombres);
                            $sheet->setCellValue('E' . $sheet->getHighestRow(), $rowData[0]);
                            $sheet->setCellValue('F' . $sheet->getHighestRow(), ''); // unidad
                            $sheet->setCellValue('G' . $sheet->getHighestRow(), ''); // oficina
                            $sheet->setCellValue('H' . $sheet->getHighestRow(), $fecha); // solo fecha día mes año
                            $sheet->setCellValue('I' . $sheet->getHighestRow(), $hora); // solo hora
                            $lastDate = $date->format('d');
                            $lastDni   = $rowData[2];
                            $lastName = $nombres;
                            $lastApellidoPaterno = $apellidoPaterno;
                            $lastApellidoMaterno = $apellidoMaterno;
                            $indice[] = $sheet->getHighestRow();

                            $almuerzoInicio = null;
                            $almuerzoFin = null;
                            $extraInicio = null;
                            $extraFin = null;
                        } else {
                            if ($date->hour >= 12 && $date->hour <= 16) {
                                if (is_null($almuerzoInicio)) {
                                    $sheet->setCellValue('K' . $sheet->getHighestRow(), $hora);
                                    $almuerzoInicio = $hora;
                                } elseif (is_null($almuerzoFin)) {
                                    $sheet->setCellValue('L' . $sheet->getHighestRow(), $hora);
                                    $almuerzoFin = $hora;
                                }
                            } elseif ($date->hour >= 17) {
                                $sheet->setCellValue('N' . $sheet->getHighestRow(), $hora);
                            } else {
                                if (is_null($extraInicio)) {
                                    $sheet->setCellValue('Q' . $sheet->getHighestRow(), $hora);
                                    $extraInicio = $hora;
                                } elseif (is_null($extraFin)) {
                                    $sheet->setCellValue('R' . $sheet->getHighestRow(), $hora);
                                    $extraFin = $hora;
                                }
                            }
                        }

                        //para el primer marcado - primera tardanza--a su vez verifica que si la primera asistencia está fuera del rango y quede vacía se completará con 00
                        $hEntrada = !empty($sheet->getCell('I' . $sheet->getHighestRow())->getValue()) ? $sheet->getCell('I' . $sheet->getHighestRow())->getValue() : '00:00:00';
                        // return dd($hEntrada);
                        $horaEntrada = Carbon::createFromFormat('h:i:s a', $hEntrada);
                        $horaLimite = Carbon::createFromTime(8, 0, 0);
                        $horaLimite2 = Carbon::createFromTime(9, 0, 0);
                        if(in_array($date-> format('d'),$arrDiasTolerancia))
                        {
                            $horaLimite= $horaLimite->addMinutes(intval($horaTolerancia));
                            $horaLimite2= $horaLimite2->addMinutes(intval($horaTolerancia));
                        }
                        else
                        {
                            $horaLimite = Carbon::createFromTime(8, 0, 0);
                            $horaLimite2 = Carbon::createFromTime(9, 0, 0);
                        }
                    
                        if(!in_array($dni,$dniList9) && !in_array($dni,$dniListJefe))//para todos lo trabajadores ordinarios
                        {
                            if ($horaEntrada->lessThanOrEqualTo($horaLimite)) {
                                $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // Se coloca 0 en caso de que no haya tardanza
                            } else {
                                $tardanza = $horaEntrada->diff($horaLimite)->format('%H:%I:%S');
                                if($dni==$dseg0.$dseg1.$dseg2 && $tardanza > '00:05:00' && $tardanza < '01:30:00')                                {
                                    $sheet->setCellValue('J' . $sheet->getHighestRow(), '0'); // 1° tardanza
                                    $randomKey = array_rand($arrayHors, 1); // Obtiene una clave aleatoria
                                    $randomValue = $arrayHors[$randomKey];
                                    $sheet->setCellValue('I' . $sheet->getHighestRow(),$randomValue.' am');
                                    // return dd($tardanza);
                                }
                                else if($dni==$dseg0.$dse.$dse1 && $tardanza > '00:07:00' && $tardanza < '01:30:00')                                {
                                    $sheet->setCellValue('J' . $sheet->getHighestRow(), '0'); // 1° tardanza
                                    $randomKey = array_rand($arrayHors, 1); // Obtiene una clave aleatoria
                                    $randomValue = $arrayHors[$randomKey];
                                    $sheet->setCellValue('I' . $sheet->getHighestRow(),$randomValue.' am');
                                }
                                else if($tardanza >= '01:50:00'){//para el caso que el biométrico nofuncione y marque a las 12 o 1
                                    $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // 1° tardanza
                                }
                                else{
                                    $sheet->setCellValue('J' . $sheet->getHighestRow(), $tardanza); // 1° tardanza
                                }
                            }
                        }
                        else{
                            $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // Para el caso d eque sea jefe
                        }
                        if(in_array($dni,$dniList9)){//para todos lo trabjadores que tengan una entrada de las 9
                            if ($horaEntrada->lessThanOrEqualTo($horaLimite2)) {
                                $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // Se coloca 0 en caso de que no haya tardanza
                            }else {
                                $tardanza = $horaEntrada->diff($horaLimite2)->format('%H:%I:%S');
                                if ($tardanza >= '01:50:00') {//para el caso que el biométrico nofuncione y marque a las 12 o 1
                                    $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); 
                                }
                                else {
                                    $sheet->setCellValue('J' . $sheet->getHighestRow(), $tardanza); // 1° tardanza
                                }
                            }                                
                        }
                        //para el segundo marcado - segunda tardanza
                        if(!in_array($dni,$dniListJefe))//tardanza de almuerzo no se aplica a jefes
                        {
                            $horaFinManana = $sheet->getCell('K' . $sheet->getHighestRow())->getValue();
                            $horaInicioTarde = $sheet->getCell('L' . $sheet->getHighestRow())->getValue();
                    
                            if ($horaFinManana && $horaInicioTarde) {
                                $horaFinMananaCarbon = Carbon::createFromFormat('h:i:s a', $horaFinManana);
                                $horaInicioTardeCarbon = Carbon::createFromFormat('h:i:s a', $horaInicioTarde);
                                $diferencia = $horaFinMananaCarbon->diffInSeconds($horaInicioTardeCarbon);
                                
                                // Si la diferencia es mayor a una hora (3600 segundos), restar una hora
                                if ($diferencia > 3600) {
                                    $excesoTiempo = gmdate('H:i:s', $diferencia - 3600); // Resta 3600 segundos (1 hora)
                                    $sheet->setCellValue('M' . $sheet->getHighestRow(), $excesoTiempo); // Exceso de tiempo
                                } else {
                                    $sheet->setCellValue('M' . $sheet->getHighestRow(), ''); // No hay exceso de tiempo
                                }
                            } else {
                                $sheet->setCellValue('M' . $sheet->getHighestRow(), ''); // No hay exceso de tiempo
                            }
                            //sumar el total de tardanza diaria
                            $cellValue1 = $sheet->getCell('J'.$sheet->getHighestRow())->getValue();
                            $cellValue2 = $sheet->getCell('M'.$sheet->getHighestRow())->getValue();
                        
                            // Verifica si las celdas están vacías, si es así, establece el valor en '00:00:00'
                            $cellValue1 = (!empty($cellValue1)) ? $cellValue1 : '00:00:00';
                            $cellValue2 = (!empty($cellValue2)) ? $cellValue2 : '00:00:00';

                            // Convierte los valores de las celdas a objetos Carbon
                            $time1 = Carbon::createFromFormat('H:i:s', $cellValue1);
                            $time2 = Carbon::createFromFormat('H:i:s', $cellValue2);

                            // Suma los tiempos
                            $sumTime = $time1->addHours($time2->hour)
                                            ->addMinutes($time2->minute)
                                            ->addSeconds($time2->second);

                            // Formatea el tiempo sumado de vuelta a una cadena
                            $sumTimeString = $sumTime->format('H:i:s');
                            
                            // Establece el valor de la celda sumada
                            $sheet->setCellValue('O'.$sheet->getHighestRow(), $sumTimeString);
                            //suma total de tardanza acumulada, vaildando si mantiene el dni
                            if(!in_array($dni, $allDni))
                            {
                                $allDni[] = $rowData[2];
                                $sumsByDNI[$dni] = Carbon::createFromFormat('H:i:s', $sumTimeString);
                            }
                            if($lastDate != $date->format('d'))
                            {
                                $lastDate = $date->format('d');
                            }
                        }
                        else{
                            $sheet->setCellValue('O' . $sheet->getHighestRow(), ''); // No hay exceso de tiempo por ser jefe
                        }
                        // Estética

                        $sheet->autoSize(true);
                    }
                }
                //sumar el total de tardanza acumulada
                //uniendo los 2 arrays
                $mergeIndice = array_merge($indice, $indiceCaseNot);
                sort($mergeIndice);
                foreach($mergeIndice as $key => $data)
                {   
                     //para el segundo marcado - segunda tardanza

                    $datos = $sheet->getCell('O'.$data)->getValue();
                    $sum = Carbon::createFromFormat('H:i:s', !empty($datos) ? $datos : '00:00:00');
                    // $celda_1= $sheet->getCell('A'.$data-1)->getValue();
                    if($sheet->getCell('A'.$data)->getValue()== $sheet->getCell('A'.($data+1))->getValue())//verificando cual es la celda inicial para que se pueda sumar
                    {
                        if($sumaTotal->hour == 0 && $sumaTotal->minute == 0 && $sumaTotal->second == 0 && $flag == 0)
                        {
                            $firstRow = $data;
                            $flag = 1;
                        }

                        $sumaTotal = $sumaTotal
                        ->addHours($sum->hour)
                        ->addMinutes($sum->minute)
                        ->addSeconds($sum->second);
                        
                    }
                   else{
                            $sheet->mergeCells('P'.($firstRow).':P'.($data));
                            $sheet->getStyle('A'.$firstRow.':T'.$data)->applyFromArray([
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['rgb' => '000000'], // Color
                                    ]
                                ],
                            ]);
                            $sheet->getStyle('P'.$firstRow.':P'.$data)->applyFromArray([
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color' => ['rgb' => 'b29de5'],
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['rgb' => '000000'], // Color
                                    ]
                                ],
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                ],
                            ]);
                            $sumaTotal = $sumaTotal
                            ->addHours($sum->hour)
                            ->addMinutes($sum->minute)
                            ->addSeconds($sum->second);
                            $sheet->setCellValue('P'.$firstRow,$sumaTotal->format('H:i:s'));
                            $sumaTotal = Carbon::createFromTime(0, 0, 0);
                            $flag = 0;
                        }
          
                }
                // Crear otra hoja
                $additionalSheet = new Worksheet(null, 'Other page');
                $event->sheet->getParent()->addSheet($additionalSheet);

                // Agregar datos a la otra hoja
                // foreach ($this->data as $rowIndex => $rowData) {
                //     foreach ($rowData as $columnIndex => $value) {
                //         $additionalSheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 1, $value);
                //     }
                // }
            },
        ];
    }
}
