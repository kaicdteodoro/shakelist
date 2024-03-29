<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueueMusic extends Model
{
    use HasFactory;

    protected $table = 'queue_music';

    protected $fillable = ['queue_id', 'reference_name', 'url', 'order', 'done'];

    protected $casts = [
        'queue_id' => 'int',
        'reference_name' => 'string',
        'url' => 'string',
        'order' => 'int',
        'done' => 'boolean'
    ];

    public const rules = [
        'reference_name' => 'required|string',
        'url' => 'required|string',
        'done' => 'boolean',
        'order' => 'int',
    ];

    public const rulesUpdate = [
        'reference_name' => 'string',
        'done' => 'boolean',
        'url' => 'string',
        'order' => 'int',
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
