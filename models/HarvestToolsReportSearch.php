<?php

namespace app\models;

use Yii;
use app\models\HarvestTools;

/**
 * HarvestToolsReportSearch represents the model behind the search form about `app\models\HarvestTools`.
 */
class HarvestToolsReportSearch extends HarvestTools
{
    public $yearStart, $yearEnd, $quarterStart, $quarterEnd, $typeToolsIds, $stateIds;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yearStart', 'yearEnd', 'quarterStart', 'quarterEnd', 'typeToolsIds', 'stateIds'], 'safe'],
        ];
    }
}
