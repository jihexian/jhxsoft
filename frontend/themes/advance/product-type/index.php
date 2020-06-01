<?php

?>

<style type="text/css">
    .top-search{padding: .2rem .3rem .2rem;}
    .search-btn-wrap input{height: .48rem;}
    .right-main ul li a{text-align: center;}
</style>
<section class="top-fixed">
            <div class="flex-box flex-between top-search" style="background-color: #efeff4">
                <div class="search-btn-wrap flex-grow-1 pointer">
                    <i class="ico-search"></i>
                    <input type="text" class="" placeholder="搜索您需要的产品">
                </div>
            </div>
            </section>
            <aside class="left-fixed tab_menu">
                <ul>
                    <?php foreach($types as $v): ?>
                     <li <?= Yii::$app->request->get('type_id',1) == $v['type_id']? 'class="menu_on"' :''?>><?= $v['type_name']?></li>
                    <?php endforeach; ?>
                   
                </ul>
            </aside>
            <section class="right-main tab_box">
                    
                    <?php foreach($types as $v): ?>
                    
                    <div <?= Yii::$app->request->get('type_id',1) == $v['type_id']? 'class="mgb2"':'class="mgb2 hide"' ?>>
                    <?php if(!empty($v['sons'])):?>
                        <?php foreach($v['sons'] as $child): ?>                   
                            <h2><?=$child['type_name']?></h2>                        
                            <ul class="clearfix">
                                <?php if(!empty($child['sons'])):?>
                                    <?php foreach($child['sons'] as $son): ?>
                                        <li>
                                            <a href="<?= \yii\helpers\Url::to(['product/index','type_id'=>$son['type_id']])?>">
                                                <div class="category-img">
                                                    <img src="<?=$son['image']?>">
                                                </div>
                                                <p><?=$son['type_name']?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        <?php endforeach; ?>
                    <?php else:?>
                    <h2><?=$v['type_name']?></h2>                        
                        <ul class="clearfix">
                            
                            <li>
                                <a href="<?= \yii\helpers\Url::to(['product/index','type_id'=>$v['type_id']])?>">
                                <img src="<?=$v['image']?>">
                                <p><?=$v['type_name']?></p>
                            </a>
                            </li>                            
                            
                        </ul>
                    <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                  
        </section>
<?php
$this->registerJs(<<<JS
  $(function() {
        var li = $(".tab_menu ul li");
        li.click(function() {
            $(this).addClass("menu_on").siblings().removeClass("menu_on");
            var index = li.index(this);
            $(".tab_box > div").eq(index).show().siblings().hide();
        });
    })
    $('.ico-search').click(function(){  
        name=$('.search-input').val();
        window.location.href="/product/index?name="+name; 
    });
JS
);
?>