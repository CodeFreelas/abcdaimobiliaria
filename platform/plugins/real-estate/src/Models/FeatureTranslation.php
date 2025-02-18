<?php

namespace Srapid\RealEstate\Models;

use Srapid\Base\Models\BaseModel;

class FeatureTranslation extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 're_features_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        're_features_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
