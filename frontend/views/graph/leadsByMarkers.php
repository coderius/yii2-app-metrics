<?php
/* @var array $data_leads */
/* @var array $hint */
/* @var string $action */
/* @var string $dateFrom */
/* @var string $dateTo */
//dump( $data_leads );

use yii\helpers\Url;

$buyerActivityUrl = Url::to(['/buyer-activity/activity']);
if ($action == 'chief-leads') {
    $clickFunction = '
        var currentGeo = this.name;
        $( ".js-marker" ).val( currentGeo );
        $( "#filterForm" ).submit();
    
        hs.htmlExpand(null, {
            pageOrigin: {
                x: e.pageX || e.clientX,
                y: e.pageY || e.clientY 
            },
            headingText: this.series.name,
            maincontentText: Highcharts.dateFormat("%A, %b %e, %Y", this.x) + ":<br/> " + this.y + " sessions",
            width: 200
        });
    ';
} else {
    $clickFunction = '
        //e.preventDefault();
        $("#buyerActivityModal").modal("show");
        
        var marker = this.name;
        var dateFrom = "' . $dateFrom . '";
        var dateTo = "' . $dateTo . '";
        $.ajax({
            url: "' . $buyerActivityUrl . '?marker=" + marker + "&dateFrom=" + dateFrom + "&dateTo=" + dateTo,
            success: function (modalHtml) {
                $(".js-activity-buyers").html(modalHtml);
            }
        });
    ';
}

$graphTextColor = Yii::$app->params['graphStyles']['textColor'];
$gridLineColor = Yii::$app->params['graphStyles']['gridColor'];
$legendTextColor = '#A0AEC0';
$graphColor = '#FBB13C';

if( Yii::$app->session->has(Yii::$app->params['themeSessionName']) && Yii::$app->session->get(Yii::$app->params['themeSessionName']) == 'light'){
    $legendTextColor = '#000000';
}

$js = '

Highcharts.chart("container-markers", {
     chart: {
        type: "column",
        backgroundColor: false
    },
    credits: {
        enabled: false
    },
    title: {
        text: "'. Yii::t( 'messages', 'Buyers activity' ).'",
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
        allowDecimals: false,
        gridLineColor: "'.$gridLineColor.'",
        labels:{
            style: {
                color: "'.$graphTextColor.'"
            }
        },
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
           hint = '.json_encode($hint).'; 
           all = this.points[0].point.options.y;
           this.points[0].point.y = all + "; " + hint[this.points[0].key];
           newTooltip =   tooltip.defaultFormatter.call(this, tooltip); 
           //console.log( this ); 
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
                        ' . $clickFunction . '
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
            name: "'.Yii::t( 'messages', 'Created' ).'",
            type: "column",
            color: "'.$graphColor.'",
            data: '.json_encode($data_leads).'
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
';

$this->registerJs($js);

?>

<div id="container-markers" style="height: 300px; width: 100%;"></div>

