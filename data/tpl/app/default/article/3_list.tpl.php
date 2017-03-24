<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/app_header', TEMPLATE_INCLUDEPATH)) : (include template('common/app_header', TEMPLATE_INCLUDEPATH));?>
<body>
    <div class="content margin_b_foot">
        <div class="searchbar flex v_center bg_blue">
            <div class="searchbar_input circle_15 all_width">
                <i class="icon icon_search"></i>
                <input type="search" placeholder="请输入搜索内容">
            </div>
            <i class="searchbar_right icon icon_my white font_18"></i>
            <div class="badge user_num"></div>
        </div>
        <div class="swiper-container banner_container">
            <div class="swiper-wrapper banner_wrap"></div>
            <div class="swiper-pagination text_right padding_h_15"></div>
        </div>
        <div class="tab relative">
            <div class="tab_item active">文章分类1</div>
            <div class="tab_item">文章分类2</div>
            <div class="tab_item">文章分类3</div>
        </div>
        <div>
            <ul class="list article_list"></ul>
        </div>
        <footer class="bar bar_tab">
            <div class="bar_tab_item active">
                <i class="iconfont icon-home font_34"></i>
                <div class="bar_tab_label lineheight_2">首页</div>
            </div>
            <div class="bar_tab_item">
                <i class="iconfont icon-post font_34"></i>
                <div class="bar_tab_label lineheight_2">发帖</div>
            </div>
            <div class="bar_tab_item">
                <div class="dot"></div>
                <i class="iconfont icon-user font_34"></i>
                <div class="bar_tab_label lineheight_2">我的</div>
            </div>
        </footer>
    </div>
    <script type="text/template" charset="utf-8" id='swipe_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <div class="swiper-slide">
                <img src="{{=it[i]}}">
            </div>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='article_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <li class="list_item">
                <div class="flex v_center">
                    <div class="all_width">
                        <div class="line_ellipsis font_16" style="height:45px;">{{=it[i].posttext}}</div>
                        <div class="flex h_justify font_12">
                            <div class="" style="line-height:30px">2017-3-20&emsp;</div>
                            <div class=""><i class="iconfont icon-eye"></i> {{=it[i].award_num}}</div>
                            <div class=""><i class="iconfont icon-conment1"></i> {{=it[i].comment}}</div>
                            <div class=""><i class="iconfont icon-good-copy"></i> {{=it[i].laud_num}}</div>
                        </div>
                    </div>
                    <div class="media_img" style="width:160px;padding-left: 0.6em;padding-right: 0;">
                        <img class="circle_5" src="{{=it[i].postimg}}"/>
                    </div>
                </div>
            </li>
        {{ } }}
    </script>
    <script type="text/javascript">
        // 轮播模版
        var swipes = ["image/img1.jpg","image/img2.jpg","image/img3.jpg","image/img4.jpg","image/img5.jpg"];
        var evalSwipe = doT.template($("#swipe_template").text());
        $(".banner_wrap").html(evalSwipe(swipes));
        // 文章列表模版
        var evalArticle = doT.template($("#article_template").text());
//        var articles=[{id:1,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10},{id:2,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10},{id:3,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10}];
        var articles = <?php  echo $arr_json;?>;
        console.log(articles);
        $(".article_list").html(evalArticle(articles));
        window.onload = function(){
            new Swiper('.banner_container', {
                // prevButton:'.swiper_prev',
                // nextButton:'.swiper_next',
                pagination : '.swiper-pagination',
            })
        }
        // 个人中心消息提醒数目
        $(".user_num").html("2")
    </script>
</body>
</html>