<?php

namespace app\models;

use Yii;
use app\models\FarmerEducation;

/**
 * HarvestPlantReportSearch represents the model behind the search form about `app\models\HarvestPlant`.
 */
class FarmerEducationReportSearch extends FarmerEducation
{
    public $yearStart, $yearEnd, $quarterStart, $quarterEnd, $stateIds;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yearStart', 'yearEnd', 'quarterStart', 'quarterEnd', 'stateIds'], 'safe'],
        ];
    }
}
