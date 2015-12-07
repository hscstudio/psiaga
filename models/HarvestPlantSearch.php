<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HarvestPlant;

/**
 * HarvestPlantSearch represents the model behind the search form about `app\models\HarvestPlant`.
 */
class HarvestPlantSearch extends HarvestPlant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plant_id', 'state_id', 'year', 'quarter', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['param1', 'param2', 'param3', 'param4', 'param5'], 'number'],
            [['note1', 'note2', 'note3', 'note4', 'note5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = HarvestPlant::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'plant_id' => $this->plant_id,
            'state_id' => $this->state_id,
            'year' => $this->year,
            'quarter' => $this->quarter,
            'param1' => $this->param1,
            'param2' => $this->param2,
            'param3' => $this->param3,
            'param4' => $this->param4,
            'param5' => $this->param5,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'note1', $this->note1])
            ->andFilterWhere(['like', 'note2', $this->note2])
            ->andFilterWhere(['like', 'note3', $this->note3])
            ->andFilterWhere(['like', 'note4', $this->note4])
            ->andFilterWhere(['like', 'note5', $this->note5]);

        return $dataProvider;
    }
}
