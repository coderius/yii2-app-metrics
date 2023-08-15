<?php

use backend\helpers\DateRangeHelper;
use common\models\User;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;

/* @var int $idBuyer */
/* @var string $dateStart */
/* @var string $dateEnd */

$this->registerJsFile( 'https://code.highcharts.com/highcharts.js', [ 'depends' => [ yii\web\JqueryAsset::class ] ] );
$this->registerJsFile( 'https://code.highcharts.com/highcharts-more.js', [ 'depends' => [ yii\web\JqueryAsset::class ] ] );

$graphUrl = Url::toRoute( [ 'graph/geos-by-buyers' ] );
$graphOneBuyerUrl = Url::toRoute( [ 'graph/geos-by-one-buyer' ] );
$js = '

   function makeGraphGeosByBuyers(date_start, date_end){
       
        var idBuyer = "' . $idBuyer . '";

        var url =  "' . $graphUrl . '?date_start=" + date_start + "&date_end=" + date_end;
        if( idBuyer != "" ){
            url = "' . $graphOneBuyerUrl . '?date_start=" + date_start + "&date_end=" + date_end + "&id_buyer=" + idBuyer;
        } 
        $.ajax({
                url: url,
                cache: false,
                success: function(html){
                    $("#js-dynamic-graph-geos-by-buyers").html( html );
                }
        });
   }
   
   makeGraphGeosByBuyers("' . $dateStart . '","' . $dateEnd . '");
';

$this->registerJs( $js );

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <?= DateRangePicker::widget( [
                    'name'                => 'dateGeosByBuyers',
                    'startAttribute'      => 'from_date_geos_by_buyers',
                    'endAttribute'        => 'to_date_geos_by_buyers',
                    'value'               => $dateStart . ' - ' . $dateEnd,
                    'presetDropdown'      => true,
                    'convertFormat'       => true,
                    'readonly'            => true,
                    'includeMonthsFilter' => true,
                    'pluginEvents'        => [
                        'apply.daterangepicker' => "function(ev, picker) { 
                            makeGraphGeosByBuyers(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'))
                        }",
                    ],
                    'pluginOptions'       => [
                        'locale' => [ 'format' => 'Y-m-d' ],
                        'ranges' => DateRangeHelper::getRanges( [
                            DateRangeHelper::YESTERDAY,
                            DateRangeHelper::TODAY,
                            DateRangeHelper::CURRENT_WEEK,
                            DateRangeHelper::CURRENT_CALENDAR_MONTH,
                            DateRangeHelper::PREVIOUS_CALENDAR_MONTH,
                        ] ),
                    ],
                    'options'             => [ 'placeholder' => Yii::t( 'messages', 'Select range...' ), 'id' => 'dateRangeGeosByBuyers' ],
                ] ); ?>
            </div>
        </div>

        <div id="js-dynamic-graph-geos-by-buyers" class="dynamic-graph"></div>
    </div><!-- /.card-body -->
</div>
<!-- /.card -->

<?php if ( in_array( Yii::$app->params[ 'userRole' ], [ User::ROLE_CHIEF, User::ROLE_CHIEFTEAM, User::ROLE_AFF ] ) ): ?>
    <div class="modal fade" id="leadsForGeoactivity" tabindex="-1" aria-labelledby="leadsForGeoactivityLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" style="min-width: 80%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="leadsForGeoactivityLabel"><?= Yii::t( 'messages', 'Geo' ) ?>: <span id="geoTitle"></span>
                    </h5>
                    <button type="button" class="close" aria-label="Close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-2 ml-2 mr-2">
                    <div class="card-body table-responsive p-0 m-0">
                        <table class="table" style="font-size: 10px">
                            <thead>
                            <?php if ( Yii::$app->mobileDetect->isMobile() ): ?>
                                <tr>
                                    <th style="width: 50px"></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Data' ) ?></th>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th style="width: 50px"></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Id' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Email' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Office' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Buyer' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Offer' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Date Created' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Sent at' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Status' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Purchase' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Date deposit' ) ?></th>
                                    <th class="table__top-label"><?= Yii::t( 'messages', 'Alien id' ) ?></th>
                                </tr>
                            <?php endif; ?>
                            </thead>
                            <tbody class="js-leads-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


