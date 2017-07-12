<?php

namespace App\Models;

use App\User;
use Titan\Models\TitanCMSModel;
use Bpocallaghan\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Titan\Models\Traits\ActiveTrait;

class Article extends TitanCMSModel
{
    use SoftDeletes, HasSlug, ActiveTrait;

    protected $table = 'articles';

    protected $guarded = ['id'];

    protected $dates = ['active_form', 'active_to'];

    /**
     * Validation rules for this model
     */
    static public $rules = [
        'title'       => 'required|min:3:max:255',
        'content'     => 'required|min:5:max:2000',
        'category_id' => 'required|exists:article_categories,id',
    ];

    /**
     * Get the summary text
     *
     * @return mixed
     */
    public function getSummaryAttribute()
    {
        if($this->attributes['summary']) {
            return $this->attributes['summary'];
        }

        return substr(strip_tags($this->attributes['content']), 0, 120);
    }

    /**
     * Get the createdBy
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the category
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id', 'id');
    }
}