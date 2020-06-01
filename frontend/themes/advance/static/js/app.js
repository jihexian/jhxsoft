$(function(){
	/*商品盒子的高度*/
	// resize_goods_box();
 //    function resize_goods_box(){
 //    	var window = $(document).width();
	//     var height = window/3+'px'
	//     $(".item-pic").css({"height":height});
 //    }
    $(".a_cancel_order").click(function(){
    	var that = $(this);
    	var text = that.text();
    	that.text('正在取消...');
    	var url = that.data('href');
    	$.confirm("您确定取消订单吗？", function() {
		  window.location.href = url;
		}, function() {
		  that.text(text);
		});
    })
    $(".a_del_order").click(function(){
    	var that = $(this);
    	var text = that.text();
    	that.text('正在删除...');
    	var url = that.data('href');
    	$.confirm("您确定删除订单吗？", function() {
		  window.location.href = url;
		}, function() {
		  that.text(text);
		});
    })
    $(".a_refund_order").click(function(){
    	var that = $(this);
    	var text = that.text();
    	that.text('正在申请...');
    	var url = that.data('href');
    	$.confirm("您确定申请退款吗？", function() {
		  window.location.href = url;
		}, function() {
		  that.text(text);
		});
    })
    $(".a_logout").click(function(){
    	var that = $(this);
    	var text = that.text();
    	that.text('正在退出...');
    	var url = that.data('href');
    	$.confirm("您确定退出登录吗？", function() {
		  window.location.href = url;
		}, function() {
		  that.text(text);
		});
    })
   
})