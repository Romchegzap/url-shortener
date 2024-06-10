<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $original_url
 * @property string $short_hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Url newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Url newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Url query()
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereOriginalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereShortHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereUpdatedAt($value)
 * @property int $original_url_hash
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereOriginalUrlHash($value)
 * @mixin \Eloquent
 */
class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'original_url_hash',
        'short_hash'
    ];
}
