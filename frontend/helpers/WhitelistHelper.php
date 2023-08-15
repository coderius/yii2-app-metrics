<?php


namespace frontend\helpers;


use common\helpers\IpHelper;
use common\models\Whitelist;
use Yii;
use yii\helpers\ArrayHelper;

class WhitelistHelper
{

    /**
     *
     * @return bool
     */
    public static function isCurrentIpPermitted()
    {
        $userIpList = Whitelist::find()->where( [ 'id_user' => Yii::$app->user->id ] )->asArray()->all();

        $userIpList = ArrayHelper::getColumn( $userIpList, 'ip' );
        if ( !empty( $userIpList ) && !in_array( IpHelper::getUserIp(), $userIpList ) ) {
            return false;
        }

        return true;
    }

}