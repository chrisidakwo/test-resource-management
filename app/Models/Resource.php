<?php

namespace App\Models;

use Database\Factories\ResourceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    public const TYPE_HTML = 'html';
    public const TYPE_LINK = 'link';
    public const TYPE_PDF = 'pdf';

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return ResourceFactory
     */
    protected static function newFactory(): ResourceFactory
    {
        return ResourceFactory::new();
    }

    /**
     * Limit result to only resources that are of type: <code>self::TYPE_HTML</code>
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOnlyHtml(Builder $builder): Builder
    {
        return $builder->where('resources.type', self::TYPE_HTML);
    }

    /**
     * Limit result to only resources that are of type: <code>self::TYPE_LINK</code>
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOnlyLink(Builder $builder): Builder
    {
        return $builder->where('resources.type', self::TYPE_LINK);
    }

    /**
     * Limit result to only resources that are of type: <code>self::TYPE_PDF</code>
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeOnlyPdf(Builder $builder): Builder
    {
        return $builder->where('resources.type', self::TYPE_PDF);
    }
}
