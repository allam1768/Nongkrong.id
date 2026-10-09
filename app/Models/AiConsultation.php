<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiConsultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'user_prompt',
        'ai_response',
        'recommended_ids',
        'is_converted',
    ];

    protected function casts(): array
    {
        return [
            'recommended_ids' => 'array',
            'is_converted' => 'boolean',
        ];
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
