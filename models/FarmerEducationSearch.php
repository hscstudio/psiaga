<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FarmerEducation;

/**
 * FarmerEducationSearch represents the model behind the search form about `app\models\FarmerEducation`.
 */
class FarmerEducationSearch extends FarmerEducation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'education_id', 'state_id', 'year', 'quarter', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['param'], 'number'],
            [['note'], 'safe'],
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
        $query = FarmerEducation::find();

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
            'education_id' => $this->education_id,
            'state_id' => $this->state_id,
            'year' => $this->year,
            'quarter' => $this->quarter,
            'param' => $this->param,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
