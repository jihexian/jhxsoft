<?php
use yii\helpers\Url;
?>
    <header class="top-fixed">
        <div class="weui-flex top-box">
            <div onclick="window.open('<?=url::to(['/member/index'])?>','_self');">
                <i class="iconfont icon-fanhui"></i>
            </div>
            <div class="weui-flex__item">快递信息</div>
            <div>
                <i class="iconfont icon-mulu" id="mulu-bt"></i>
            </div>
        </div>
    </header>
    <?=$this->render('../layouts/cart_menu')?>
    <div class="wrap" style="margin-top: 1rem;">
        <div class="main">
            <div class="view_logistics">
                <div class="bgfff pd20 weui-flex item-hd">
                    <div class="weui-flex__item">
                        <p class="fs28 lh48">快递单号：<?=$data['LogisticCode']?></p>
                        <p class="fs28 lh48">快递公司：<?=$shipping_name?></p>
                        <!--  <p class="fs28 lh48">承运人电话：68868</p>-->
                    </div>
<!--                     <div class="">
                        <a href="" class="cr333 p12 bre block fs28 br5">我要催单</a>
                    </div> -->
                </div>
                <ul class="item-bd">
                <?php foreach ($data['Traces'] as $v):?>
                    <li>
                        <p><?=$v['AcceptStation']?></p>
                        <?=$v['AcceptTime']?>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>


 