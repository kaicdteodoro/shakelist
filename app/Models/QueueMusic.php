<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueueMusic extends Model
{
    use HasFactory;

    protected $table = 'queue_music';

    protected $fillable = ['queue_id', 'reference_name', 'url', 'done'];

    protected $casts = [
        'queue_id' => 'int',
        'reference_name' => 'string',
        'url' => 'string',
        'done' => 'boolean'
    ];

    public const rules = [
        'reference_name' => 'required|string',
        'url' => 'required|string'
    ];

    public const rulesUpdate = [
        'reference_name' => 'string',
        'url' => 'string'
    ];

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class);
    }

    public function queryWithQueue(int $queue_id): Builder
    {
        return $this->query()
            ->whereHas(
                'queue',
                static function (Builder $has) use ($queue_id) {
                    return $has->where('id', $queue_id);
                }
            );
    }
}
