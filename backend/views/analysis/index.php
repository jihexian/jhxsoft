<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单统计';
$this->params['breadcrumbs'][] = $this->title;
?>
    
	<div class="box box-primary">
	    <div class="box-body">
	    
	    
	    	<div style="text-align: center;margin:20px 0 50px;">
		        <?php $form = ActiveForm::begin([
		        'action' => ['index'],
		        'method' => 'get',
		        'options' => ['class' => 'form-inline'],
		        ]); ?>
		        <!--<div class="form-group">
		        	<label style="font-weight: normal;">查询方式</label>
		        	<div class="input-group" style="margin-right: 18px;">
					<?php // echo Html::dropDownList('type', null, [1=>'按日',2=>'按月',3=>'按年'],['class'=>'form-control','options'=>[$params['type']=>['Selected'=>true]]]);?>
					</div>
		        </div>  -->
       
		  		<div class="input-group" style="width: 350px; margin-left: -5px;">
					<input type="text" class="form-control date-picker" id="dateTimeRange" value="" />
					<span class="input-group-addon">
						<i class="fa fa-calendar bigger-110"></i>
					</span>
					<input type="hidden" name="beginTime" id="beginTime" value="<?php echo $params['beginTime']?>" />
					<input type="hidden" name="endTime" id="endTime" value="<?php echo $params['endTime']?>" />
				</div>

				<a href="javascript:;" onclick="begin_end_time_clear();" style="margin: 0 35px 0 15px;">清除</a>
		
				<?php $this->beginBlock('datepicker') ?>  
				$(function() {
					$('#dateTimeRange').daterangepicker({		
						startDate:"<?php echo $params['beginTimeCn']?>",
						endDate:"<?php echo $params['endTimeCn']?>",		
						showDropdowns:true,
						linkedCalendars: false,
						applyClass : 'btn-sm btn-success',
						cancelClass : 'btn-sm btn-default',		
						locale: {
							applyLabel: '确认',
							cancelLabel: '取消',
							fromLabel : '起始时间',
							toLabel : '结束时间',
							customRangeLabel : '自定义',
							firstDay : 1
						},
						ranges : {
							//'最近1小时': [moment().subtract('hours',1), moment()],
						    '今日': [moment().startOf('day'), moment()],
						    '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
							//'最近7日': [moment().subtract('days', 6), moment()],
							//'最近30日': [moment().subtract('days', 29), moment()],
							'本月': [moment().startOf("month"),moment().endOf("month")],
							'上个月': [moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]
						},
						opens : 'right',	// 日期选择框的弹出位置
						separator : ' 至 ',
						showWeekNumbers : false,		// 是否显示第几周
				
						//timePicker: true,
						//timePickerIncrement : 10,	// 时间的增量，单位为分钟
						//timePicker12Hour : false,	// 是否使用12小时制来显示时间
				
						
						//maxDate : moment(),			// 最大时间
						format: 'YYYY-MM-DD',
					    
					}, function(start, end, label) { // 格式化日期显示框
						$('#beginTime').val(start.format('YYYY-MM-DD'));
						$('#endTime').val(end.format('YYYY-MM-DD'));
					}).next().on('click', function(){
						$(this).prev().focus();
					});
					
				<!-- 	$('#dateTimeRange').daterangepicker({startDate:"2013年1月8日",endDate:"2013年12月30日"},function(start, end){ -->
				<!--              $('#dateTimeRange input').val(start.toString('YYYY-MM-DD') + '-' + end.toString('YYYY-MM-DD')); -->
				<!--  	}); -->
				
				});
				
				/**
				 * 清除时间
				 */
				function begin_end_time_clear() {
					$('#dateTimeRange').val('');
					$('#beginTime').val('');
					$('#endTime').val('');
				}
				<?php $this->endBlock() ?>  
				<?php $this->registerJs($this->blocks['datepicker'], \yii\web\View::POS_END); ?>
				
		
		        <div class="form-group">
		            <?= Html::submitButton(Yii::t('common', '搜索'), ['class' => 'btn btn-primary btn-flat']) ?>
		        </div>
		
		        <?php ActiveForm::end(); ?>
	    	</div>
	 
	   		<div style="padding-top:40px;">
	            <div class="box-body">
	              <div class="chart">
	                <canvas id="barChart" style="height: 230px; width: 787px;" height="230" width="787"></canvas>
	              </div>
	            </div>
	         </div>
	       <canvas id="myChart" width="400" height="400"></canvas>
			<?php $this->beginBlock('specification') ?>  
			var ctx = document.getElementById("barChart").getContext('2d');
			var myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			        labels: <?php echo json_encode($result['labels'],true);?>,
			        datasets: [{
			            label: '销售总额',
			            data: <?php echo json_encode($result['orderAmount'],true);?>,
			            backgroundColor:'red',
			            borderColor:'red',
			            borderWidth: 1
			        },{
			            label: '订单总数',
			            data: <?php echo json_encode($result['orderCount'],true);?>,
			            backgroundColor:'green',
			            borderColor:'green',
			            borderWidth: 1
			        }]
			    },
			    options: {
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        },
			        "animation": {
				      "duration": 1,
				      "onComplete": function() {
				        	var chartInstance = this.chart,
				          	ctx = chartInstance.ctx;			
					        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
					        ctx.textAlign = 'center';
					        ctx.textBaseline = 'bottom';			
				        	this.data.datasets.forEach(function(dataset, i) {
					          var meta = chartInstance.controller.getDatasetMeta(i);
					          meta.data.forEach(function(bar, index) {
						          var data = dataset.data[index];
						          ctx.fillText(data, bar._model.x, bar._model.y - 5);
				          		});
				        	});
				     }
	    			},
			    }
			});
			<?php $this->endBlock() ?>  
			<?php $this->registerJs($this->blocks['specification'], \yii\web\View::POS_END); ?>
    	</div>
	</div>
	
	

