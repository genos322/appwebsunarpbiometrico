<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
                $sheet->getRowDimension(1)->setRowHeight(50); 
                $sheet->getRowDimension(3)->setRowHeight(50);
                $sheet->mergeCells('A1:U1');
                $sheet->autoSize(true);
                $sheet->getStyle('A3:U3')->getAlignment()->setWrapText(true);//ajustar texto
                $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIAS');
                // Centrar horizontalmente
                $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIA DE LA ZONA REGISTRAL Nº XIV - SEDE AYACUCHO');
                $sheet->setCellValue('A2', 'Tipo de contrato');
                $sheet->setCellValue('A3', 'USUARIO');
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
                $sheet->setCellValue('Q3', 'DESCUENTO TARDANZA');
                $sheet->setCellValue('R3', 'SOBRETIEMPO');
                $sheet->setCellValue('S3', 'OBSERVACIÓN');
                $sheet->setCellValue('T3', 'ENCARGATURA DE APOYO');
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
                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A3:I3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('K3:L3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('N3:O3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('Q3:R3')->applyFromArray($styleArrayHeadBlue);
                $sheet->getStyle('J3')->applyFromArray($styleArrayHeadRed);
                $sheet->getStyle('M3')->applyFromArray($styleArrayHeadRed);
                $sheet->getStyle('P3')->applyFromArray($styleArrayHeadRed);
                $sheet->getStyle('S3:T3')->applyFromArray($styleArrayYellow);
                
                // Crear otra hoja
                $additionalSheet = new Worksheet(null, 'Otra hoja');
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
