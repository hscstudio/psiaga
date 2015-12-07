<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "harvest_plant".
 *
 * @property integer $id
 * @property integer $plant_id
 * @property integer $state_id
 * @property integer $year
 * @property integer $quarter
 * @property double $param1
 * @property double $param2
 * @property double $param3
 * @property double $param4
 * @property double $param5
 * @property string $note1
 * @property string $note2
 * @property string $note3
 * @property string $note4
 * @property string $note5
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Plant $plant
 * @property State $state
 */
class HarvestPlant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'harvest_plant';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'attributes' => [
                        \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_by','updated_by'],
                        \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id', 'state_id', 'year', 'quarter'], 'required'],
            [['plant_id', 'state_id', 'year', 'quarter', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['param1', 'param2', 'param3', 'param4', 'param5'], 'number'],
            [['note1', 'note2', 'note3', 'note4', 'note5'], 'string', 'max' => 255],
            [['plant_id', 'state_id', 'year', 'quarter'], 'unique', 'targetAttribute' => ['plant_id', 'state_id', 'year', 'quarter'], 'message' => 'The combination of Plant ID, State ID, Year and Quarter has already been taken.'],
            [['plant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plant::className(), 'targetAttribute' => ['plant_id' => 'id']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'plant_id' => Yii::t('app', 'Plant ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'year' => Yii::t('app', 'Year'),
            'quarter' => Yii::t('app', 'Quarter'),
            'param1' => Yii::t('app', 'Param1'),
            'param2' => Yii::t('app', 'Param2'),
            'param3' => Yii::t('app', 'Param3'),
            'param4' => Yii::t('app', 'Param4'),
            'param5' => Yii::t('app', 'Param5'),
            'note1' => Yii::t('app', 'Note1'),
            'note2' => Yii::t('app', 'Note2'),
            'note3' => Yii::t('app', 'Note3'),
            'note4' => Yii::t('app', 'Note4'),
            'note5' => Yii::t('app', 'Note5'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlant()
    {
        return $this->hasOne(Plant::className(), ['id' => 'plant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }
}
