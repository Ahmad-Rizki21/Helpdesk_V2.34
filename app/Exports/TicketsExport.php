<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua tiket dan sertakan relasi customer
        return Ticket::with('customer')->get()->map(function ($ticket) {
            return [
                $ticket->ticket_number,
                $ticket->service,
                $ticket->customer->composite_data, // Ambil composite_data dari relasi customer
                $ticket->problem_summary,
                $ticket->extra_description,
                $ticket->report_date,
                $ticket->status,
                $ticket->pending_clock,
                $ticket->closed_date,
            ];
        });
    }

    

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Lebar kolom untuk No Ticket
            'B' => 30, // Lebar kolom untuk Layanan
            'C' => 25, // Lebar kolom untuk Id Pelanggan
            'D' => 40, // Lebar kolom untuk Problem Summary
            'E' => 40, // Lebar kolom untuk Extra Description
            'F' => 20, // Lebar kolom untuk Report Date
            'G' => 15, // Lebar kolom untuk Status
            'H' => 20, // Lebar kolom untuk Pending Clock
            'I' => 20, // Lebar kolom untuk Closed Date
        ];
    }

    public function headings(): array
    {
        return [
            'No Ticket',
            'Layanan',
            'Id Pelanggan',
            'Problem Summary',
            'Extra Description',
            'Report Date',
            'Status',
            'Pending Clock',
            'Closed Date',
        ];
    }
}