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

    public function __construct(array $data)
    {
        $this->data = $data;
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
                $firstData = Carbon::instance(Date::excelToDateTimeObject($this->data[1][3]));
                $monthDays = Carbon::createFromDate($firstData->format('Y'), $firstData->format('m'), 1);//instancia con el primer dia del mes
                $totalDays = $monthDays->daysInMonth;
                $feriados = [];
                $count=0;
                $lastDate = 0;//fecha para el tema de los 4 marcados
                $lastDate2 = 1;
                $allDni = [];
                $lastDni = $this->data[1][2];
                $rowIndexesByDNI = [];
                $data = '';
                $indice = [];
                $flag = 0;
                $sumaTotal = Carbon::createFromTime(0, 0, 0);
                $almuerzoInicio = null;
                $almuerzoFin = null;
                $extraInicio = null;
                $extraFin = null;
                $dni9 = Config::get('app.dni_list_horario_9');
                $dniList9 = array_map(function($item) {//obtneindo solo el dni
                    return explode('-', $item)[0];
                }, $dni9);

                $dniJefe = Config::get('app.dni_list_jefe');
                $dniListJefe = array_map(function($item) {
                    return explode('-', $item)[0];
                }, $dniJefe);

                foreach (range(1, $totalDays) as $day) {
                    $currentDate = Carbon::createFromDate($firstData->year, $firstData->month, $day);
                    $dayName = $currentDate->format('l'); // Obtener el nombre del día en inglés                
                    if ($dayName === 'Saturday' || $dayName === 'Sunday') {
                        $feriados[] = $day; // Almacenar el día en el array si es sábado o domingo
                    }
                }
                foreach ($this->data as $rowIndex => $rowData) {
                    if ($rowIndex !== 0) {
                        $fullName = explode(' ', $rowData[1]);
                        $apellidoPaterno = $fullName[0];
                        $apellidoMaterno = $fullName[1];
                        $nombres = $fullName[2];
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
                        if(!in_array($dni,$dniList9) && !in_array($dni,$dniListJefe))//para todos lo trabajadores ordinarios
                        {
                            if ($horaEntrada->lessThanOrEqualTo($horaLimite)) {
                                $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // Se coloca 0 en caso de que no haya tardanza
                            } else {
                                $tardanza = $horaEntrada->diff($horaLimite)->format('%H:%I:%S');
                                $sheet->setCellValue('J' . $sheet->getHighestRow(), $tardanza); // 1° tardanza
                            }
                        }
                        else{
                            $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // Para el caso d eque sea jefe
                        }
                        if(in_array($dni,$dniList9)){//para todos lo trabjadores que tengan una entrada de las 9
                            if ($horaEntrada->lessThanOrEqualTo($horaLimite2)) {
                                $sheet->setCellValue('J' . $sheet->getHighestRow(), 0); // Se coloca 0 en caso de que no haya tardanza
                            } else {
                                $tardanza = $horaEntrada->diff($horaLimite2)->format('%H:%I:%S');
                                $sheet->setCellValue('J' . $sheet->getHighestRow(), $tardanza); // 1° tardanza
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
                        }
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
                        // Estética

                        $sheet->autoSize(true);
                    }
                }
                //sumar el total de tardanza acumulada
                foreach($indice as $key => $data)
                {
                    $datos = $sheet->getCell('O'.$data)->getValue();
                    $sum = Carbon::createFromFormat('H:i:s', $datos);
                    if($sheet->getCell('A'.$data)->getValue() == $sheet->getCell('A'.($data+1))->getValue())
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
                    else
                    {
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
                foreach ($this->data as $rowIndex => $rowData) {
                    foreach ($rowData as $columnIndex => $value) {
                        $additionalSheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 1, $value);
                    }
                }
            },
        ];
    }
}
