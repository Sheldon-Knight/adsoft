<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory,SoftDeletes;

    public const ACTIVE = 'Active';

    public const SENT = 'Send';

    public const PAID = 'Paid';

    public const VOID = 'Void';

    public const UNPAID = 'Paid';

    public const OVERDUE = 'Overdue';

    public const WRITE_OFF = 'Write Off';

    public const DRAFT = 'Draft';

    public const APPROVED = 'Approved';

    public const PENDING = 'Pending';

    public const REJECTED = 'Rejected';

    protected $fillable = [
        'invoice_date',
        'invoice_number',
        'eft_uploads',
        'invoice_due_date',
        'invoice_status',
        'invoice_total',
        'invoice_subtotal',
        'invoice_tax',
        'invoice_discount',
        'items',
        'user_id',
        'client_id',
        'is_quote',
    ];

    public static function invoiceStatuses()
    {
        return [
            self::SENT => self::SENT,
            self::PAID => self::PAID,
            self::VOID => self::VOID,
            self::UNPAID => self::UNPAID,
            self::OVERDUE => self::OVERDUE,
            self::WRITE_OFF => self::WRITE_OFF,
            self::DRAFT => self::DRAFT,
        ];
    }

    public static function QuoteStatuses()
    {
        return [
            self::APPROVED => self::APPROVED,
            self::PENDING => self::PENDING,
            self::REJECTED => self::REJECTED,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'items' => 'array',
        'eft_uploads' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'invoice_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
