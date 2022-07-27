<?php

namespace App\Models;

use Database\Factories\ResourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    public const TYPE_HTML = 'html';
    public const TYPE_LINK = 'link';
    public const TYPE_PDF = 'pdf';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * @return ResourceFactory
     */
    protected static function newFactory(): ResourceFactory
    {
        return ResourceFactory::new();
    }

    /**
     * @return string[]
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_HTML,
            self::TYPE_LINK,
            self::TYPE_PDF,
        ];
    }
}
