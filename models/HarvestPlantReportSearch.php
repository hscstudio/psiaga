<?php

namespace app\models;

use Yii;
use app\models\HarvestPlant;

/**
 * HarvestPlantReportSearch represents the model behind the search form about `app\models\HarvestPlant`.
 */
class HarvestPlantReportSearch extends HarvestPlant
{
    public $yearStart, $yearEnd, $quarterStart, $quarterEnd, $typePlantIds, $stateIds;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yearStart', 'yearEnd', 'quarterStart', 'quarterEnd', 'typePlantIds', 'stateIds'], 'safe'],
        ];
    }
}
