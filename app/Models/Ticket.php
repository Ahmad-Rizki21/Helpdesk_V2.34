<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'service',
        'ticket_number',
        'customer_id',
        'report_date',
        'status',
        'pending_clock',
        'closed_date',
        'problem_summary',
        'extra_description',
        
        'title',
        'description',
        'sla_id', // Tambahkan kolom SLA
        // atribut lain yang ada
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sla()
    {
        return $this->belongsTo(SLA::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            // Mengisi kolom 'no' secara otomatis
            $lastTicket = self::orderBy('id', 'desc')->first();
            $ticket->no = $lastTicket ? $lastTicket->no + 1 : 1; // Menghasilkan nomor urut

            // Mengisi kolom 'ticket_number' secara otomatis
            $ticket->ticket_number = 'TFTTH-' . strtoupper(uniqid()); // Format otomatis
        });

        

        static::updating(function ($ticket) {
            if ($ticket->isDirty('status')) {
                if ($ticket->status === 'PENDING') {
                    $ticket->pending_clock = now();
                } elseif ($ticket->status === 'CLOSED') {
                    $ticket->closed_date = now();
                }
            }
        });
    }

    public function getPendingClockAttribute($value)
    {
        return $value ?? 'Belum ada Pending';
    }

    public function getClosedDateAttribute($value)
    {
        return $value ?? 'Belum ada Ticket Closed';
    }
}
