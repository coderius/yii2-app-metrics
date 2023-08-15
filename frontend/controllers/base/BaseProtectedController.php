<?php
namespace frontend\controllers\base;

use frontend\helpers\WhitelistHelper;

//use common\helpers\RaygunHelper;
use yii\web\Controller;

/**
 * Class BaseProtectedController
 * @package backend\controllers\base
 */
class BaseProtectedController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
    public function beforeAction( $action )
    {
        if ( !WhitelistHelper::isCurrentIpPermitted() ) {
            return $this->redirect( [ '/site/forbidden' ] );
        }

        //RaygunHelper::init();

        return parent::beforeAction( $action );
    }
}