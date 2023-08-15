<?php

use backend\helpers\DateRangeHelper;
use kartik\daterange\DateRangePicker;


/* @var string $dateStart */
/* @var string $dateEnd */
/* @var string $date */
/* @var string $action */

$this->registerJsFile( 'https://code.highcharts.com/highcharts.js', [ 'depends' => [ yii\web\JqueryAsset::class ] ] );
$this->registerJsFile( 'https://code.highcharts.com/highcharts-more.js', [ 'depends' => [ yii\web\JqueryAsset::class ] ] );

$graphUrl = \yii\helpers\Url::toRoute(['/graph/leads-by-markers']);

$js = '

   function makeGraphMarkers(date_start_marker, date_end_marker){
   
        var action = "' . $action . '";

        $.ajax({
                url: "'.$graphUrl.'?date_start=" + date_start_marker + "&date_end=" + date_end_marker + "&action=" + action,
                cache: false,
                success: function(html){
                    $("#js-dynamic-graph-markers").html( html );
                }
        });
   }
   
   makeGraphMarkers("' . $dateStart . '","' . $dateEnd . '");
';

$this->registerJs( $js );

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <?= DateRangePicker::widget( [
                    'name'                => 'dateMarkers',
                    'startAttribute'      => 'from_date_marker',
                    'endAttribute'        => 'to_date_marker',
                    'value'               => $dateStart.' - '.$dateEnd,
                    'presetDropdown'      => true,
                    'convertFormat'       => true,
                    'readonly'            => true,
                    'includeMonthsFilter' => true,
                    'pluginEvents'        => [
                        'apply.daterangepicker' => "function(ev, picker) { 
                            makeGraphMarkers(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'))
                        }",
                    ],
                    'pluginOptions'       => [
                        'locale' => [ 'format' => 'Y-m-d' ],
                        'ranges' => DateRangeHelper::getRanges( [
                            DateRangeHelper::YESTERDAY,
                            DateRangeHelper::TODAY,
                            DateRangeHelper::CURRENT_WEEK,
                            DateRangeHelper::CURRENT_CALENDAR_MONTH,
                            DateRangeHelper::PREVIOUS_CALENDAR_MONTH
                        ]),
                    ],
                    'options'             => [ 'placeholder' => Yii::t( 'messages', 'Select range...' ), 'id' => 'dateRangeMarkers' ],
                ] ); ?>
            </div>
        </div>

        <div id="js-dynamic-graph-markers" class="dynamic-graph"></div>
    </div><!-- /.card-body -->
</div>
<!-- /.card -->



<div class="modal fade" id="buyerActivityModal" tabindex="-1" aria-labelledby="buyerActivityModal"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" style="min-width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <h5><?= Yii::t( 'messages', 'Buyer' ) ?></h5>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mt-2 ml-2 mr-2">
                <div class="card-body table-responsive p-0 m-0">
                    <div class="js-activity-buyers"></div>
                </div>
            </div>
        </div>
    </div>
</div>


