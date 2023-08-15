<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tb_teamlead_markers}}".
 *
 * @property int $id
 * @property int $id_teamlead
 * @property string $markers
 * @property string $type
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $teamlead
 */
class TeamleadMarkers extends ActiveRecord
{
	const TYPE_INHOUSE = 'inhouse';
	const TYPE_EXTERNAL = 'external';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tb_teamlead_markers}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave( $insert )
    {
        if ( $this->markers !== null ){
            $this->markers = implode( ', ', $this->markers );

            return parent::beforeSave( $insert );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_teamlead', 'markers', 'type'], 'required'],
            ['id_teamlead', 'integer'],
            ['id_teamlead', 'unique', 'message' => Yii::t( 'messages', 'Not unique Teamlead' ) ],
            [['markers', 'type'], 'safe'],
            //[['id_teamlead'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_teamlead' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t( 'messages', 'ID' ),
            'id_teamlead' => Yii::t( 'messages', 'Teamlead' ),
            'markers'     => Yii::t( 'messages', 'Buyers' ),
            'type'        => Yii::t( 'messages', 'Type' ),
            'created_at'  => Yii::t( 'messages', 'Created At' ),
            'updated_at'  => Yii::t( 'messages', 'Updated At' ),
        ];
    }

    /**
     * Gets query for [[Teamlead]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamlead()
    {
        return $this->hasOne( User::class, [ 'id' => 'id_teamlead' ] );
    }

    /**
     * Make array form string markers
     *
     * @return $this
     */
    public function properMarkers()
    {
        $this->markers = explode( ', ', $this->markers );
        return $this;
    }

}
