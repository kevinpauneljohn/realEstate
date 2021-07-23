<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Sales extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'id',
        'reservation_date',
        'user_id',
        'lead_id',
        'project_id',
        'model_unit_id',
        'lot_area',
        'floor_area',
        'phase',
        'block',
        'lot',
        'total_contract_price',
        'discount',
        'processing_fee',
        'reservation_fee',
        'equity',
        'loanable_amount',
        'financing',
        'terms',
        'details',
        'commission_rate',
        'status',
        'template_id',
    ];

    protected static $logAttributes = [
        'id',
        'reservation_date',
        'user_id',
        'lead_id',
        'project_id',
        'model_unit_id',
        'lot_area',
        'floor_area',
        'phase',
        'block',
        'lot',
        'total_contract_price',
        'discount',
        'processing_fee',
        'reservation_fee',
        'equity',
        'loanable_amount',
        'financing',
        'terms',
        'details',
        'commission_rate',
        'status',
        'template_id',
    ];

    protected $dates = ['reservation_date'];
    protected $casts = [
        'date_of_payment' => 'array'
    ];

    public function getReservationDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function modelUnit()
    {
        return $this->belongsTo(ModelUnit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clientRequirements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClientRequirement::class);
    }

    public function paymentReminders()
    {
        return $this->hasMany(PaymentReminder::class);
    }

    public function getLocationAttribute()
    {
        return "Phase {$this->phase}, Block {$this->block}, Lot {$this->lot}";
    }
}
