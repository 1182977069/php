/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){
	
	//减少
	$(".reduce_num").click(function(){
        var _self = $(this);
        var goods_id = _self.parent().attr('goods_id');
        var goods_attr_ids = _self.parent().attr('goods_attr_ids');
        var amountEle = _self.parent().find(".amount");
        var amount = parseInt( _self.parent().find(".amount").val() );
        if(amount<=1){
            layer.msg('商品数量最少为1');
            return false;
        }

        var reduce=amount-1;
        var param={"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":reduce};
        $.get("/home/cart/changeCartNum",param,function(json){
            if(json.code==200){
                amountEle.val(reduce);
                //小计
                var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(reduce);
                _self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
                //总计金额
                var total = 0;
                $(".col5 span").each(function(){
                    total += parseFloat($(this).text());
                });

                $("#total").text(total.toFixed(2));
            }

        },'json');
	});

	//增加
	$(".add_num").click(function(){
        var _self = $(this);
        var goods_id = _self.parent().attr('goods_id');
        var goods_attr_ids = _self.parent().attr('goods_attr_ids');
        var amountEle = _self.parent().find(".amount");
        var amount = parseInt( _self.parent().find(".amount").val() );

        var add=amount+1;
        var param={"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":add};
        $.get("/home/cart/changeCartNum",param,function(json){
            if(json.code==200){
                amountEle.val(add);
                //小计
                var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(add);
                _self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
                //总计金额
                var total = 0;
                $(".col5 span").each(function(){
                    total += parseFloat($(this).text());
                });

                $("#total").text(total.toFixed(2));
            }

        },'json');
	});

	//直接输入
	$(".amount").blur(function(){
        var _self = $(this);
        var goods_id = _self.parent().attr('goods_id');
        var goods_attr_ids = _self.parent().attr('goods_attr_ids');
        var amount = _self.parent().find(".amount").val();
        var reg=/^\d+$/;
        if(!reg.test(amount)){
            layer.msg('商品数量格式非法',{"icon":0,'time':1500});
            _self.val(_self.attr('ori_amount'));
            return false;
        }
        if(amount<=1){
            layer.msg('商品数量最少为1');
            return false;
        }

        var param={"goods_id":goods_id,"goods_attr_ids":goods_attr_ids,"goods_number":amount};
        $.get("/home/cart/changeCartNum",param,function(json){
            if(json.code==200){
                //小计
                var subtotal = parseFloat(_self.parent().parent().find(".col3 span").text()) * parseInt(amount);
                _self.parent().parent().find(".col5 span").text(subtotal.toFixed(2));
                //总计金额
                var total = 0;
                $(".col5 span").each(function(){
                    total += parseFloat($(this).text());
                });

                $("#total").text(total.toFixed(2));
            }

        },'json');

	});

	$(".amount").focus(function(){
	    $(this).attr('ori_amount',$(this).val());
    });
});