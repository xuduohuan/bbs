<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>卡券详情</title>
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no,email=no">
    <link rel="stylesheet" type="text/css" href="/addons/article/template/mobile/css/lth.css">
    <link rel="stylesheet" type="text/css" href="/addons/article/template/mobile/css/iconfont.css">
</head>
<body>
<div class="container">
    <div class="detail"></div>
    <div class="cart"></div>
</div>
<!-- 遮罩 -->
<div class="backdrop page"></div>
<div class="toast block_center page"></div>
<!-- toast模版 -->
<script type="text/template" charset="utf-8" id='toast_template'>
    <div class="toast_block">
        <i class="iconfont icon-toast{{=it.icon}}"></i><br><span class="toast_text">{{=it.text}}</span>
    </div>
</script>
<script type="text/template" charset="utf-8" id='info_template'>
    <div class="detail_img">
        <img src="/attachment/{{=it.thumb}}">
        <div class="detail_cart color_red block_center circle"><i class="iconfont icon-jiarugouwuche" onclick="add_cart({{=it.id}})"></i></div>
    </div>
    <div class="margin_h_15">
        <p class="detail_title">{{=it.title}}
            {{ if(it.time != "0" || it.num == null ) { }}
            <span class="color_red pull_right">{{=it.price}}元/{{=it.time}}年</span>
            {{ }else { }}
            <span class="color_red pull_right">{{=it.price}}元/{{=it.num}}次</span>
            {{ } }}
        </p>
        <p class="detail_info margin_v_10">{{=it.detail}}</p>
    </div>
</script>
<!-- 购物车模版 -->
<script type="text/template" charset="utf-8" id='cart_template'>
    <div class="cart_list">
        <div class="cart_title">
            <span>购物车</span>
            <span class="pull_right" onclick="clearall()">清空全部</span>
        </div>
        {{for(i in it.data){ }}
        <div class="list">
            <div class="list_con pull_left">{{=it.data[i].title}}<span class="price color_red pull_right">￥{{=it.data[i].price}}</span></div>
            <div class="list_opr pull_left color_red block_center">
                <div class="opr_icon" onclick="add({{=i}})"><i class="iconfont icon-jia1"></i></div>
                <span class="number">{{=it.data[i].num}}</span>
                <div class="opr_icon" onclick="del({{=i}})"><i class="iconfont icon-jian"></i></div>
            </div>
        </div>
        {{ } }}
    </div>
    <div class="cart_opr">
        <div class="cart_icon block_center circle" onclick="toggle()"><i class="iconfont icon-gouwuche"></i><span class="cart_num block_center circle">{{=it.number}}</span></div>
        <div class="cart_total">共<span class="color_red">￥{{=it.total}}</span></div>
        <div class="cart_btn" onclick="go()">去付款</div>
    </div>
</script>
<script src="/addons/article/template/mobile/js/jquery.min.js"></script>
<script src="/addons/article/template/mobile/js/doT.min.js"></script>
<script src="/addons/article/template/mobile/js/base.js"></script>
<script type="text/javascript">
    // 卡券信息模版
    var evalInfo = doT.template($("#info_template").text());
    var data = <?php  echo $info_json;?>;
    $(".detail").html(evalInfo(data));
    // 购物车模版
    var evalCart = doT.template($("#cart_template").text());
    var cartnull = {data:{},total:0,number:0};
    var cart = JSON.parse(localStorage.getItem("cart")) || cartnull;
    $(".cart").html(evalCart(cart));
    // 切换购物车显示
    $(".backdrop").click(function(){
        toggle();
    })
    // 加入购物车
    function add_cart(n){
        _cart(n,data)
    }
    // 付款
    function go(){
        pay("<?php  echo $this->createMobileUrl('cards_list',array('op'=>cart))?>",function(res){
            toast("ok",res.message.msg);
            console.log(res);
            location.href = "<?php  echo $this->createMobileUrl('pay')?>"+"&oid="+res.message.orderid+"&total="+res.message.total;
        })
    }
</script>
</body>
</html>
