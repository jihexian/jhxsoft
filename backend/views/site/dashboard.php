<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = '控制面板';
?>
<style type="text/css">

    .head{
        height: 100px;
        font-size: 13pt;
    }
    .head p{
        font-weight: bold;
    }
     .head img{
        height: 30px;
        width: 30px;
        margin-right: 10px;
    }
    font{
    font-size: 8pt;
    margin-left: 15px;
    font-weight: lighter;
    }
     .head th{
        text-align: center;
        text-align: left;
        padding-left: 2% !important;
    }
    .div_left{
        height: auto;
        width: 99%;
        margin-bottom: 5px;
        margin-top: 25px;
    }
    .div_left p{
        text-align: center;
        font-size: 15pt;
        color: 	#4169E1;
    }
    .div_left li{
        float: left;
        list-style: none;
        margin-left: 110px;
        margin-top: 40px;
        font-size: 10pt;
        font-weight: lighter;
    }
    .div_right1{
        height: 300px;
        width: 99%;
    }
    .div_right1 td{
        text-align: center;
    }
     .div_right1 p{
        margin-top: 20px;
        font-size: 15pt;
    }
    .div_right2{
        height: 400px;
        width: 99%;
        margin-top: 50px;
    }
    .order li{
        float: left;
        list-style: none;
        margin-left: 20px;
        margin-top: 10px;
    }
    .tableshop{
        margin-top: 10px;
    }
    .table{
        height: 100%;
    }
    .yuan{
    height: 10px;
    width: 10px;
    background-color: #FFA500;
    border-radius: 50%;
    float: left;
    margin-top: 3px;
    margin-right: 5px;
    }
    .tb_body table{
      width: 98%;
    }
</style>
<div class="site-index">

    <table class="table table-bordered">
      <thead>
        <tr class="head">
          <th scope="col"><p><img src="<?=Url::to(['/ioc/yinshou.png']) ?>"><?=$totleMoney?></p>今日订单总金额（元）</th>
          <th scope="col"><p><img src="<?=Url::to(['/ioc/product.png']) ?>"><?=$groundProduct ?></p>已商品发布（个）</th>
          <th scope="col"><p><img src="<?=Url::to(['/ioc/order.png']) ?>"><?= $countOrder ?></p>订单总数（条）</th>
          <th scope="col"><p><img src="<?=Url::to(['/ioc/hudong.png']) ?>"><?=$saleTotle ?></p>本月销量（笔）</th>
          <th scope="col" colspan="2" style="width: 40%;"><p><img src="<?=Url::to(['/ioc/oder_sucess2.png']) ?>"><?=$finshOrder ?></p>已完成交易</th>
        </tr>
      </thead>
      <tbody>
        <tr class="tb_body">
          <th scope="row" colspan="4" rowspan="3">
            <table>
                <tr>
                    <td>
                        <div class="div_left">
                            <div><div class="yuan"></div>店铺及商品提示<font>您需要关注的店铺信息以及待处理事项</font></div>
                            <div>
                                <ul>
                                    <li><p><?=$groundProduct ?></p>出售中</li>
                                    <!-- <li><p>0</p>仓库中</li>
                                    <li><p>0</p>待回复咨询</li> -->
                                    <li><p><?=$noProduct ?></p>库存预警</li>
                                </ul>
                            </div>
                        </div>
                   </td>
                </tr>

                <tr>
                    <td>
                        <div class="div_left">
                        <div><div class="yuan"></div>交易提示<font>您需要立即处理的交易订单</font></div>
                            <div>
                                <ul>
                                    <li><p><?=$noPayOder ?></p>待付款</li>
                                    <li><p><?=$noSenOrder?></p>待发货</li>
                                    <li><p><?=$senOrder ?></p>已发货</li>
                                    <li><p><?=$getOrder ?></p>已收货</li>
                                    <li><p><?=$finshOrder ?></p>已完成</li>
                                    <li><p><?=$closeOrder ?></p>已关闭</li>
                                    <li><p><?=$refunOrder ?></p>退款中</li>
                                </ul>
                            </div>
                        </div>
                   </td>
                </tr>
                <tr>
                    <td>
                        <div class="div_left" style="height: 60%;">
                          <div><div class="yuan"></div>订单总量统计</div>
                            <!--  <div class="order" style="float: left;">
                                <ul>
                                    <li>今日</li>
                                    <li>昨日</li>
                                    <li>本周</li>
                                    <li>本月</li>
                                </ul>
                            </div>  -->
                        </div>
                      </td>
                </tr>
                 <tr>
                    <td>
                     <div style="margin-top:0px; width:100%;height:368px;" id="echartmain"></div>
                    </td>
                </tr>
          </table>
          </th>

          <td rowspan="3">
              <div class="div_right1">
                <div><div class="yuan"></div>销售情况统计<font>按周期统计商家店铺的订单量和订单金额</font></div>
                <table class="table tableshop">
                    <tr>
                        <td colspan="2">
                            昨日销量
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <p><?=$ysterDayOrder ?></p>订单量（条）
                        </td>
                        <td>
                            <p><?=$yesterTotleMoney ?></p>订单金额（元）
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            本月销量
                        </td>
                    </tr> 
                     <tr>
                        <td>
                            <p><?=$nowMonthOrder ?></p>订单量（条）
                        </td>
                        <td>
                            <p><?=$monthTotleMoney ?></p>订单金额（元）
                        </td>
                    </tr>                    
                  </table>
                </div>
                <div class="div_right2">
                    <div><div class="yuan"></div>单品销售排名<font>商品销售量排行榜</font></div>
                    <div style="margin-top: 10px;">
                          <?php echo GridView::widget([
                              'dataProvider' => $product,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn',
                                        'header' => '排序',
                                        'headerOptions' => ['style' =>' text-align: center;'],
                                        'contentOptions' => ['style'=>' text-align: center;'],
                                    ],
                                    [   'attribute'=>'name',
                                        'headerOptions' => ['style' =>' text-align: center;'],
                                        'contentOptions' => ['style'=>' text-align: center;'],
                                    ],
                                    [   'attribute'=>'sale',
                                        'headerOptions' => ['style' =>' text-align: center;'],
                                        'contentOptions' => ['style'=>' text-align: center;'],
                                    ],
                                ],
                        ]);?>
                    </div>              
                </div>

          </td>
        </tr>
      </tbody>
    </table>
</div>

<?php $this->beginBlock('test') ?>
  window.onload = function (){
  var echartsArray=new Array();
       $.ajax({
            url: 'ordercount',
            type: 'post',
            data :{},
            beforeSend: function (xhr) {
            },
            success: function (res) {
                data = res.split(',');;
                for(var i=0;i<=data.length-1;i++){
                   echartsArray.push(data[i]);
                }
                 echart(echartsArray);
			}
      });
       
   }
      function echart(res){
      console.log(res);
       var myChart = echarts.init(document.getElementById('echartmain'));
          // 指定图表的配置项和数据
          var option = {
              //title: {
                 // text: '订单量统计（条）',
              //},
              tooltip: {},
              legend: {
                  data:['销量（条）']
              },
              xAxis: {
                  data: ["昨日","今日","本周","本月","本年"]
              },
              yAxis: {
              },
              series: [{
                  name: '订单量（条）',
                  type: 'bar',
                  barWidth: 50,//柱图宽度
                  data: res,
                  itemStyle: {        //上方显示数值
                    normal: {
                        label: {
                            show: true, //开启显示
                            position: 'top', //在上方显示
                            textStyle: { //数值样式
                                color: 'black',
                                fontSize: 16
                            }
                        }
                    }
            }
              }],
          };
          // 使用刚指定的配置项和数据显示图表。
          myChart.setOption(option);
      }
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
