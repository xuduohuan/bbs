<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/app_header', TEMPLATE_INCLUDEPATH)) : (include template('common/app_header', TEMPLATE_INCLUDEPATH));?>
<body>
    <div class="content">
        <div class="searchbar flex bg_white margin_b_10 padding_h_15" style="padding-top:10px;">
            <div class="light" style="width:20px;"><i class="iconfont icon-left"></i></div>
            <div class="all_width text_center font_16">我的用户中心</div>
        </div>
        <div class="flex user bg_white padding_15 margin_b_10"></div>
        <div class="nav_list list"></div>
    </div>
    <script type="text/template" charset="utf-8" id='user_template'>
        <div class="" style="width:70px;"><img src="{{=it.headimg}}"></div>
        <div class="all_width flex light v_center h_justify">
            <span>&ensp;{{=it.name}}</span>
            <span>完善个人资料<i class="iconfont icon-right"></i></span>
        </div>
    </script>
    <script type="text/template" charset="utf-8" id='nav_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <div class="list_item flex h_justify">
                <div class="flex"><div class="{{=it[i].color}} white text_center circle_5" style="width:24px;line-height:24px;"><i class="iconfont icon-{{=it[i].icon}}"></i></div>&ensp;{{=it[i].name}}</div>
                <div class="light"><i class="iconfont icon-right"></i></div>
            </div>
        {{ } }}
    </script>
    <script type="text/javascript">
        // 用户模版
        var evalUser = doT.template($("#user_template").text());
        var user = {headimg:"<?php  echo $_W['attachurl'];?>image/demo1.png",name:"elena"};
        $(".user").html(evalUser(user));
        // 导航模版
        var evalNav = doT.template($("#nav_template").text());
        var navs=[
            {id:1,icon:"list1",name:"我的发帖",number:"0",color:"bg_danger"},
            {id:2,icon:"conment",name:"我参与的",number:"0",color:"bg_warning"},
            {id:3,icon:"good-copy",name:"我的点赞",number:"0",color:"bg_warning"},
            {id:4,icon:"addconment",name:"我的关注",number:"0",color:"bg_green"},
            {id:5,icon:"dollar",name:"我的赏金",number:"0",color:"bg_primary"},
            {id:6,icon:"credit-copy",name:"我的积分",number:"0",color:"bg_blue"}
        ];
        $(".nav_list").html(evalNav(navs));
    </script>
</body>
</html>