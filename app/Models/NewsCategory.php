<?php

namespace App\Models;

use Titan\Models\TitanCMSModel;
use Bpocallaghan\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NewsCategory
 * @mixin \Eloquent
 */
class NewsCategory extends TitanCMSModel
{
    use SoftDeletes, HasSlug;

    protected $table = 'news_categories';

    protected $guarded = ['id'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'name' => 'required|min:3:max:255',
    ];

    /**
     * Get all the rows as an array (ready for dropdowns)
     *
     * @return array
     */
    public static function getAllList()
    {
        return self::orderBy('name')->get()->pluck('name', 'id')->toArray();
    }

    /**
     * Get the articles
     */
    public function news()
    {
        return $this->hasMany(News::class, 'category_id', 'id');
    }
}