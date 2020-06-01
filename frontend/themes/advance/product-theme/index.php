<?php
use yii\helpers\Url;
?>
<style>
.floor-item{
background-color:#fff;
padding-top:15px;
}
.spe-product{
}
.spe-product-swiper
{
 display:block;
}
.spe-product li {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    width: 33.3333%;
    height: auto;
    float: left;
    position: relative;
}


.spe-pro-cont{

   padding-right:10px;

}
.swiper-container {
    margin-left: auto;
    margin-right: auto;
    position: relative;
    overflow: hidden;
    z-index: 1;
    width: 100%;
    height: 100%;
}
}
.swiper-slide, .swiper-wrapper {
    width: 100%;
    height: 100%;
    position: relative;
}
.small{

   /*  width: 2.5rem;*/
   height: 2.4rem; 
    margin: .26rem auto 0;
    overflow: hidden;
}
.small img{
	width: 100%;
	height: auto;
}
.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}
 .spe-product .txt h3 {
    font-size: .24rem;
    color: #333;
    height: .65rem;
    line-height: .32rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    font-weight:500;
}
.spe-product .txt p {
    font-size: .24rem;
    color: #ff5031;
}
 .spe-product .spe-more {
    width: 2.67rem;
    height: 2.67rem;
    margin: .29rem auto 0;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    border-radius: 4px;
}
 .spe-product .spe-more .spe-more-cont {
    margin-top: .68rem;
}
.spe-product .spe-more h4{
    font-size: .26rem;
    line-height: 2;
    font-weight:normal;
}
 .spe-product .spe-more h4 span {
    display: inline-block;
    border-bottom: 1px solid #d2d2d2;
    padding: 0 3px;
    vertical-align: top;
    color: #ff5031;
}
 .spe-product .spe-more p {
    font-size: .25rem;
    color: #aaa;
    line-height: 1.4;
}
</style>
      <div class="swiper-container index-ban">
            <div class="swiper-wrapper">
            	<?php foreach($carousels as $v): ?>
                <div class="swiper-slide">
                	<a href="<?= $v['url']?>">
                    <img src="<?= $v['image']?>" alt="" class="block">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
       
       <div class="floor-box">
      <?php foreach ($lists as $v):?>
       <div class="floor-item"> 
		<div class="big-img loadingImg">
			<span class="li-tag"></span>
			<a href="<?=Url::to(['product-theme/info','id'=>$v['id']])?>">
				<img class="" src="<?=$v['image']?>" style="transition: all 0.5s ease 0s; opacity: 1;">
			</a>
		</div>
		<div class="spe-product swiper-container" >
			<ul class="spe-product-list swiper-wrapper clearfix">
			
			<?php foreach ($v['products'] as $pv):?>
				<li class="spe-product-swiper swiper-slide">
					<div class="spe-pro-cont block">
					  <a href="<?=url::to(['product/detail','id'=>$pv['product_id']])?>">
						<div class="small">
							<img src="<?=$pv['image'][0]['thumbImg']?>" style="transition: all 0.5s ease 0s; opacity: 1;">
						</div>

						<div class="txt">
							<h3 class="title">【扶贫】<?=$pv['name']?></h3>
							<p class="price">
								<span class="spe-price">¥<?=$pv['max_price']?></span>
							</p>
						</div>
						</a>
					</div>
				</li>
		<?php endforeach;?>
		
				<li class="spe-product-swiper swiper-slide li-more">
					<div class="spe-more">
					<a href="<?=Url::to(['product-theme/info','id'=>$v['id']])?>">
						<div class="spe-more-cont">
							<h4><span>查看全部</span></h4>
							<p>see more</p>
						</div>
						</a>
						<!-- <span>查看全部<i class="icon iconfont">&#xe603;</i></span> -->
					</div>
				</li>
			</ul>
		</div>

     </div>
     <?php endforeach;?>

</div>
<?php

$this->registerJs(<<<JS
  
	/*分类的滚动*/
    var tabSwiper = new Swiper('.spe-product', {
        effect : 'left',
        speed : 500, 
        autoHeight: false,
        observer:true,
        slidesPerView : 3,//一行显示3个
        slidesPerGroup : 3,//3个一组
        onSlideChangeStart : function() {
            
        }
    });


	
JS
);
?>

