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
                $sheet->getRowDimension(1)->setRowHeight(30); 
                $sheet->getRowDimension(3)->setRowHeight(30);
                $sheet->mergeCells('A1:U1');
                $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIAS');
                // Centrar horizontalmente
                $sheet->setCellValue('A1', 'REPORTE DE ASISTENCIA DE LA ZONA REGISTRAL Nº XIV - SEDE AYACUCHO');
                $sheet->setCellValue('A2', 'Tipo de contrato');
                $sheet->setCellValue('A3', 'USUARIO');
                $sheet->setCellValue('B3', 'APELLIDO PARTERNO');
                $styleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'font' => 'Arial',
                        //para mayusculas
                        ''

                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $styleArray2 = [
                    'font' => [
                        'size' => 13,
                        'font' => 'Arial',//color de texto
                        'color' => ['argb' => 'ffffff'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '712daf'], // Color de fondo amarillo
                        // 'border' => [
                        //     'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        //     'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        //     'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        //     'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        // ],
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
                $sheet->getStyle('A1')->applyFromArray($styleArray);
                $sheet->getStyle('A3:J3')->applyFromArray($styleArray2);
                $sheet->getRowDimension(3)->setAutoSize(true);
                

                // Agregar tus propios datos
                // foreach ($this->data as $rowIndex => $rowData) {
                //     foreach ($rowData as $columnIndex => $value) {
                //         // Ajusta la fila y columna según tu estructura de datos
                //         $sheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 3, $value);
                //     }
                // }

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
