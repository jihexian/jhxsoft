<?php

use yii\helpers\Html;
use yii\grid\GridView;
use GuzzleHttp\json_encode;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品统计';
$this->params['breadcrumbs'][] = $this->title;
?>
   
    <div class="box box-primary">
   		<div class="box box-success">
          
            <div class="box-body">
              <div style="text-align: center;margin:20px 0 50px;">
		        <?php $form = ActiveForm::begin([
		        'action' => ['product'],
		        'method' => 'get',
		        'options' => ['class' => 'form-inline'],
		        ]); ?>
		        <div class="form-group">
		        	<label style="font-weight: normal;">查询区间：</label>
		        	
		        </div>

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
							//'今日': [moment().startOf('day'), moment()],
							//'昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
							//'最近7日': [moment().subtract('days', 6), moment()],
							//'最近30日': [moment().subtract('days', 29), moment()],
							//'本月': [moment().startOf("month"),moment().endOf("month")],
							//'上个月': [moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]
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
            </div>
            <!-- /.box-body -->
         </div>
       
		
    </div>
     <div><label style="font-weight: normal;">商品排行榜</label></div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'id' => 'product-grid',
                'dataProvider' => $dataProvider,
               //'showFooter'=>true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn',
                        'header' => '排序',
                    ],
                    [   'attribute'=>'name',
                    ],
                    [   'attribute'=>'sale',
                    ],
                ],
            ]); ?>
        </div>
    </div>
