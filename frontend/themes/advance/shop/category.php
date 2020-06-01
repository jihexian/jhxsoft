<?php
?>
<style type="text/css">
    .top-search{padding: .2rem .3rem .2rem;}
</style>
        <section class="top-fixed">
            <div class="flex-box flex-between top-search" style="background-color: #efeff4">
                <div class="search-btn-wrap flex-grow-1 pointer">
                    <i class="ico-search"></i>
                    <input type="search" class="search" placeholder="搜索您需要的产品">
                </div>
            </div>
        </section>
            <aside class="left-fixed tab_menu">
                <ul>
                	<?php
                	foreach ($cats as $k=>$v):?>
                	<?php if(empty($v['sons'])):?>
                	<li><a href="<?= \yii\helpers\Url::to(['product/index','shop_id'=>$v['shop_id'],'cat_id'=>$v['category_id']])?>"><?=$v['cat_name']?></a>
                    </li>
                	<?php else:?>
                    <li  <?=$k==0? 'class="menu_on"' :''?>> 
                    <?=$v['cat_name']?></li>
                    <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </aside>
            <section class="right-main tab_box">
            <?php foreach($cats as $k=>$v): ?>
                <div <?= $k==0? 'class="mgb2"':'class="mgb2 hide"' ?>>
                        <?php if(empty($v['sons'][0]['sons'])):?><!-- 判断三级分类是否分空 -->
                         <div class="mgb2">
                            <ul class="clearfix">
                            <?php foreach ($v['sons'] as $k):?><!-- 没有三级分类 -->
                                <li>
                                    <a href="<?= \yii\helpers\Url::to(['product/index','shop_id'=>$k['shop_id'],'cat_id'=>$k['category_id']])?>">
                                    <img src="<?=$k['image']?>">
                                    <p><?=$k['cat_name']?></p>
                                	</a>
                                </li>
                              <?php endforeach;?>
                            </ul>
                        </div>
                        <?php else:?> <!-- 三级分类不为空 -->
                        <?php foreach ($v['sons'] as $k):?>
                        <div class="mgb2">
                            <h2><?=$k['cat_name']?></h2>
                            <ul class="clearfix">
                            <?php foreach ($k['sons'] as $a):?>
                                <li>
                                    <a href="<?= \yii\helpers\Url::to(['product/index','shop_id'=>$k['shop_id'],'cat_id'=>$a['category_id']])?>">
                                    <img src="<?=$a['image']?>">
                                    <p><?=$a['cat_name']?></p>
                                </a>
                                </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <?php endforeach;?>
                        <?php endif;?>
                </div>
                <?php endforeach;?>
        </section>
        <!-- 底部 -->
<?php
$this->registerJs(<<<JS
  $(function() {
        var li = $(".tab_menu ul li");
       	li.click(function() {
            $(this).addClass("menu_on").siblings().removeClass("menu_on");
            var index = li.index(this);
            $(".tab_box > div").eq(index).show().siblings().hide();
        });
        //搜索功能
        $(".ico-search").click(function(){
                name=$('.search').val();
                window.location.href="/product/index?name="+name; 
        })
        $('.search').keyup(function(evt){
            if (evt.keyCode == 13) {
                name=$('.search').val();
                window.location.href="/product/index?name="+name;
            }   
        });
    })
JS
);
?>
