<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "farmer_education".
 *
 * @property integer $id
 * @property integer $education_id
 * @property integer $state_id
 * @property integer $year
 * @property integer $quarter
 * @property double $param
 * @property string $note
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Education $education
 * @property State $state
 */
class FarmerEducation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'farmer_education';
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
            [['education_id', 'state_id', 'year', 'quarter'], 'required'],
            [['education_id', 'state_id', 'year', 'quarter', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['param'], 'number'],
            [['note'], 'string', 'max' => 255],
            [['education_id', 'state_id', 'year', 'quarter'], 'unique', 'targetAttribute' => ['education_id', 'state_id', 'year', 'quarter'], 'message' => 'The combination of Education ID, State ID, Year and Quarter has already been taken.'],
            [['education_id'], 'exist', 'skipOnError' => true, 'targetClass' => Education::className(), 'targetAttribute' => ['education_id' => 'id']],
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
            'education_id' => Yii::t('app', 'Pendidikan'),
            'state_id' => Yii::t('app', 'Kecamatan'),
            'year' => Yii::t('app', 'Tahun'),
            'quarter' => Yii::t('app', 'Triwulan'),
            'param' => Yii::t('app', 'Param'),
            'note' => Yii::t('app', 'Keterangan'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(Education::className(), ['id' => 'education_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }
}
