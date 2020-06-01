// subNav
$("#mulu-bt").click(function() {
    var mulu = $("#mulu-con");
    if (mulu.is(":hidden")) {
        mulu.show();
    } else {
        mulu.hide();
    }
});



// ajax 提交购物车
var before_request = 1; // 上一次请求是否已经有返回来, 有才可以进行下一次请求
function ajax_cart_list(){

	if(before_request == 0) // 上一次请求没回来 不进行下一次请求
	    return false;
	before_request = 0;
    $.ajax({
        type : "POST",
        url:"/cart/ajax-list",//+tab,
        data : $('#cart_list').serialize(),// 你的formid
        async:false,
        success: function(data){
            $("#cart_list").html('');
            $("#cart_list").append(data);
            
			before_request = 1;
        }
    });
}

/**
 * 购买商品数量加加减减
 * 购买数量 , 购物车id , 库存数量
 */
function switch_num(num,cart_id,store_count)
{
    var num2 = parseInt($("input[name='goods_num["+cart_id+"]']").val());
    num2 += num;
    if(num2 < 1) num2 = 1; // 保证购买数量不能少于 1
    if(num2 > store_count)
    {   $.alert("你只能买 "+store_count+" 件");
        num2 = store_count; // 保证购买数量不能多余库存数量
    }

    $("input[name='goods_num["+cart_id+"]']").val(num2);

    ajax_cart_list(); // ajax 更新商品价格 和数量
}

// ajax 删除购物车的商品

function del(id){
	
	  $(this).parents('.weui-cell').remove()
	  $.ajax({
	        type : "POST",
	        url:"",
	        data:{ids:id},
	        dataType:'json',
	        success: function(data){
	            if(data.status == 1)
	        	{
	            	ajax_cart_list(); //ajax 请求获取购物车列表	
	        	}               
	        }
	    });
	}





function chkAll_onclick() 
{
    //  取消
  if($("input[name^='check_all_buy']").prop('checked')){
	    $("input[name^='cart_select']").prop('checked',true);
	    $("input[name^='check_all_buy']").prop('checked',true)
    
    is_checked = false;
  }
  //全选
  else{
	  
	  $("input[name^='cart_select']").prop('checked',false);
	    $("input[name^='check_all_buy']").prop('checked',false)
    is_checked = true;
  }
  ajax_cart_list();
}


//点击结算
function selcart_submit()
{
   //获取中的商品数量
   var j=0;
   $('.goods-check').each(function(){
       //判断商品是否选中
	  if($(this).prop('checked'))//不是全选时
	  {
		 
	     j++
	  }
  });
   //判断是否有选择
   if (j>0)
   {
		window.location.href="/cart/confirm"
   }
   else
   {   
	   $.alert('请选择要结算的商品！');
	   return false;
  }
}

