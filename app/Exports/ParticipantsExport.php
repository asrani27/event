<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ParticipantsExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnFormatting, ShouldAutoSize, WithEvents
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        $participants = $this->event->participants()->select([
            'nip',
            'nama',
            'jabatan',
            'skpd',
            'status_kehadiran'
        ])->get();

        return $participants->map(function ($participant, $index) {
            return [
                'no' => $index + 1,
                'nip' => "'" . (string) $participant->nip, // Tambah petik di depan untuk force text di Excel
                'nama' => $participant->nama,
                'jabatan' => $participant->jabatan,
                'skpd' => $participant->skpd,
                'status_kehadiran' => $this->formatStatus($participant->status_kehadiran)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'NAMA',
            'JABATAN',
            'SKPD',
            'STATUS KEHADIRAN'
        ];
    }

    public function title(): string
    {
        return $this->event->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E8F4FD'
                    ]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '@', // Format kolom NIP sebagai text untuk mencegah pembulatan
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Insert rows at top for event information
                $sheet->insertNewRowBefore(1, 6);

                // Event Information
                $sheet->setCellValue('A1', 'INFORMASI EVENT');
                $sheet->setCellValue('A2', 'Judul: ' . $this->event->title);
                $sheet->setCellValue('A3', 'Deskripsi: ' . $this->event->description);
                $sheet->setCellValue('A4', 'Lokasi: ' . $this->event->location);
                $sheet->setCellValue('A5', 'Tanggal: ' . $this->event->date->format('d F Y'));
                $sheet->setCellValue('A6', 'Waktu: ' . $this->event->time->format('H:i') . ' WIB');

                // Style event information
                $sheet->getStyle('A1:A6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Special style for title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '2E3192'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F4FD']
                    ],
                ]);

                // Merge cells for better layout (updated to F column)
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->mergeCells('A4:F4');
                $sheet->mergeCells('A5:F5');
                $sheet->mergeCells('A6:F6');

                // Remove borders from event info section (clean look)
                $sheet->getStyle('A1:F6')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ],
                    ],
                ]);

                // Apply text format to NIP column to prevent scientific notation
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('B8:B' . $highestRow)->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                // Add black borders to participant data section (updated to F column)
                $sheet->getStyle('A8:F' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Black color
                        ],
                    ],
                ]);

                // Add summary section at bottom
                $summaryStartRow = $highestRow + 2;

                // Calculate statistics
                $participants = $this->event->participants;
                $totalParticipants = $participants->count();
                $hadirCount = $participants->where('status_kehadiran', 'hadir')->count();
                $tidakHadirCount = $participants->where('status_kehadiran', 'tidak_hadir')->count();
                $terdaftarCount = $participants->where('status_kehadiran', 'terdaftar')->count();

                // Add summary headers and data (updated to F column)
                $sheet->setCellValue('A' . $summaryStartRow, 'RINGKASAN KEHADIRAN');
                $sheet->setCellValue('A' . ($summaryStartRow + 1), 'Total Peserta: ' . $totalParticipants . ' orang');
                $sheet->setCellValue('A' . ($summaryStartRow + 2), 'Hadir: ' . $hadirCount . ' orang');
                $sheet->setCellValue('A' . ($summaryStartRow + 3), 'Tidak Hadir: ' . $tidakHadirCount . ' orang');
                $sheet->setCellValue('A' . ($summaryStartRow + 4), 'Terdaftar: ' . $terdaftarCount . ' orang');

                // Style summary section (except title row) - updated to F column
                $sheet->getStyle('A' . ($summaryStartRow + 1) . ':F' . ($summaryStartRow + 4))->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Style summary title (line 13 equivalent) - no bold, no special styling - updated to F column
                $sheet->getStyle('A' . $summaryStartRow . ':F' . $summaryStartRow)->applyFromArray([
                    'font' => [
                        'bold' => false,
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Merge cells for summary (updated to F column)
                $sheet->mergeCells('A' . $summaryStartRow . ':F' . $summaryStartRow);
                $sheet->mergeCells('A' . ($summaryStartRow + 1) . ':F' . ($summaryStartRow + 1));
                $sheet->mergeCells('A' . ($summaryStartRow + 2) . ':F' . ($summaryStartRow + 2));
                $sheet->mergeCells('A' . ($summaryStartRow + 3) . ':F' . ($summaryStartRow + 3));
                $sheet->mergeCells('A' . ($summaryStartRow + 4) . ':F' . ($summaryStartRow + 4));
            },
        ];
    }

    private function formatStatus($status)
    {
        $statusMap = [
            'terdaftar' => 'Terdaftar',
            'hadir' => 'Hadir',
            'tidak_hadir' => 'Tidak Hadir'
        ];

        return $statusMap[$status] ?? $status;
    }
}
