<?php

use yii\helpers\Url;

/* @var array $data_geos */
/* @var string $dateStart */
/* @var string $dateEnd */
/* @var string|null $marker */


$graphTextColor = Yii::$app->params['graphStyles']['textColor'];
$gridLineColor = Yii::$app->params['graphStyles']['gridColor'];
$legendTextColor = '#A0AEC0';

if( Yii::$app->session->has(Yii::$app->params['themeSessionName']) && Yii::$app->session->get(Yii::$app->params['themeSessionName']) == 'light'){
    $legendTextColor = '#000000';
}

$js = '
var url       = "'. Url::to( [ 'lead/get-leads-for-geoactivity' ] ) .'";
var dateStart = "' . $dateStart . '";
var dateEnd   = "' . $dateEnd  . '";
var marker    = "' . $marker . '";

Highcharts.chart("container-geos-by-buyers", {
    chart: {
        type: "column",
        backgroundColor: false
    },
    credits: {
        enabled: false
    },
     title: {
            text: "'. Yii::t( 'messages', 'Geos activity (mining)' ).'",
            style: {
                color: "'.$graphTextColor.'",
                fontSize: "18px"
            }
     },
     xAxis: {
        type: "category",
        lineColor: "'.$gridLineColor.'",
            gridLineColor: "'.$gridLineColor.'",
             labels:{
                style: {
                    color: "'.$legendTextColor.'",
                    fontWeight: "bold"
                }
            }
    },
    yAxis: {
        title: false,
        gridLineColor: "'.$gridLineColor.'",
        labels:{
            style: {
                color: "'.$graphTextColor.'"
            }
        },
        allowDecimals: false,
    },
    legend: {
        verticalAlign: "top",
         itemStyle:{
                color: "'.$legendTextColor.'"
            }
    },
    
    tooltip: {
        shared: true,
        crosshairs: true,
        useHTML: true,
        headerFormat: "{point.key}<br/>",
        formatter: function (tooltip) {
           newTooltip =   tooltip.defaultFormatter.call(this, tooltip);   
           return newTooltip;
        },
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            }
        },
        column: {               
           borderColor: false,
           borderRadius: 5
        },
        series: {
            label: {
                connectorAllowed: false
            },
            cursor: "pointer",
            point: {
                events: {
                    click: function (e) {
                        $("#leadsForGeoactivity").modal("show");
                        var currentGeo = this.name;
                        $.ajax({
                            url: url,
                            method: "POST",
                            data: {geo: currentGeo, dateStart: dateStart, dateEnd: dateEnd, buyerMarker: marker},
                            success: function(response){
                                $(".js-leads-tbody").html( response );
                                $( "#geoTitle" ).html( currentGeo );
                            }
                        });
                    }
                }
            },
            marker: {
                lineWidth: 1
            }
        }
    },

    series: [
        {
            name: "'.Yii::t( 'messages', 'Geos' ).'",
            type: "column",
            data: '.json_encode($data_geos).',
        }
    ],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: "horizontal",
                    align: "center",
                    verticalAlign: "bottom"
                }
            }
        }]
    }   

});

 $( document ).on( "click", ".js-trash", function( e ) {
        e.preventDefault();
        var id = $( this ).data( "id" );
        $.ajax({
              url: "' . Url::to( [ 'lead/send-to-trash-one' ] ) . '",
              type: "post",
              data: { id: $( this ).data( "id" ) },
              success: function( response ) {
                  $(".js-tr-" + id).remove();
              }
        });
        
   } );
';

$this->registerJs($js);

?>

<div id="container-geos-by-buyers" style="height: 300px; width: 100%;"></div>
