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
                // Aquí puedes agregar tus manipulaciones de la hoja de cálculo, como fusionar celdas, agregar estilos, etc.
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', 'Reporte de asistencias');
                $sheet->setCellValue('A2', 'Tipo de contrato');

                // Agregar tus propios datos
                foreach ($this->data as $rowIndex => $rowData) {
                    foreach ($rowData as $columnIndex => $value) {
                        // Ajusta la fila y columna según tu estructura de datos
                        $sheet->setCellValueByColumnAndRow($columnIndex + 1, $rowIndex + 3, $value);
                    }
                }

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
