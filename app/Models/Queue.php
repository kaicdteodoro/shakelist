<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Queue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'queues';

    protected $fillable = ['user_id', 'name', 'closing_date'];

    protected $casts = ['user_id' => 'int', 'name' => 'string', 'closing_date' => 'date'];

    protected $dates = ['closing_date'];

    public const rules = ['name' => 'required|string', 'closing_date' => 'date'];

    public const rulesUpdate = ['name' => 'string', 'closing_date' => 'nullable|date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function musics(): HasMany
    {
        return $this->hasMany(QueueMusic::class);
    }

    public function queryWithUser(): Builder
    {
        return $this->query()
            ->whereHas(
                'user',
                static function (Builder $has) {
                    return $has->where('id', auth()->id());
                }
            );
    }

    public function nextOrder()
    {
        return $this->musics()->max('order') + 1;
    }
}
