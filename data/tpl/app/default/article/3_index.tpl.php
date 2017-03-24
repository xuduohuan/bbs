<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/app_header', TEMPLATE_INCLUDEPATH)) : (include template('common/app_header', TEMPLATE_INCLUDEPATH));?>
<body>
    <div class="content margin_b_foot">
        <div class="swiper-container banner_container">
            <div class="swiper-wrapper banner_wrap"></div>
            <div class="swiper-pagination text_right padding_h_15"></div>
        </div>
        <div class="padding_h_15 relative bg_white text_center">
            <div class="swiper-container theme_container lineheight_3">
                <div class="swiper-wrapper theme_wrap"></div>
            </div>
            <div class="absolute swiper_prev white bg_grey"><i class="iconfont icon-left"></i></div>
            <div class="absolute swiper_next white bg_grey"><i class="iconfont icon-right"></i></div>
        </div>
        <div class="bg_white lineheight_2 margin_b_10 notice_wrap"></div>
        <div>
            <img src="image/img5.jpg">
        </div>
        <div>
            <ul class="list post_list"></ul>
        </div>
        <div>
            <img src="image/img3.jpg">
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
    <script type="text/template" charset="utf-8" id='notice_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <div class="flex v_center padding_h_15">
                <div class="" style="width:40px;">公告</div>
                <div class="flex all_width v_center">
                    <i class="iconfont icon-volume"></i>
                    <div class="marquee all_width"><p>{{=it[i]}}</p></div>
                </div>
            </div>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='theme_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <div class="swiper-slide">
                <div class="theme_icon bg_blue"><i class="icon icon_mobile"></i></div>{{=it[i]}}
            </div>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='post_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <li class="list_item">
                <div class="flex margin_b_10">
                    <div class="media_img">
                        <img src="{{=it[i].headimg}}"/>
                    </div>
                    <div class="all_width block">
                        <div class="list_title dark_blue">{{=it[i].name}} <span class="label label_blue font_12 circle_10">{{=it[i].label}}</span></div>
                        <div class="font_12 _light">发表于{{=it[i].time}}前&emsp;浏览 {{=it[i].skin_num}}</div>
                    </div>
                </div>
                <div class="flex v_center bg_default margin_b_10">
                    <div class="media_img" style="width:200px;">
                        <img src="{{=it[i].postimg}}"/>
                    </div>
                    <div class="all_width line_ellipsis" style="height:42px;">{{=it[i].posttext}}</div>
                </div>
                <div class="flex h_justify font_12">
                    <div class=""><i class="iconfont icon-good-copy dark_blue"></i> 点赞：{{=it[i].laud_num}}</div>
                    <div class=""><i class="iconfont icon-dollar dark_blue"></i> 打赏：{{=it[i].award_num}}</div>
                    <div class=""><i class="iconfont icon-conment1 dark_blue"></i> 评论：{{=it[i].comment}}</div>
                </div>
            </li>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='article_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <li class="list_item">
                <div class="flex v_center">
                    <div class="all_width">
                        <div class="line_ellipsis font_16" style="height:45px;">{{=it[i].posttext}}</div>
                        <div class="flex h_justify font_12">
                            <div class="" style="line-height:30px">{{=it[i].time}}&emsp;</div>
                            <div class=""><i class="iconfont icon-eye"></i> {{=it[i].skin_num}}</div>
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
        // 话题模版
        var theme = ["话题1","话题2","话题3","话题4","话题5","话题6","话题7","话题8","话题9","话题10"]
        var evalTheme = doT.template($("#theme_template").text());
        $(".theme_wrap").html(evalTheme(theme));
        window.onload = function(){
            new Swiper('.banner_container', {
                // prevButton:'.swiper_prev',
                // nextButton:'.swiper_next',
                pagination : '.swiper-pagination',
            })
            new Swiper('.theme_container', {
                slidesPerView : 4,
                // prevButton:'.swiper_prev',
                // nextButton:'.swiper_next',
            })
        }
        // 公告
        var notices = ["上市公司上市公司上市公司上市公司","上市公司上市公司上市公司上市公司","上市公司上市公司上市公司上市公司"];
        console.log(<?php  echo $notice_json;?>);
        var evalNotice = doT.template($("#notice_template").text());
        $(".notice_wrap").html(evalNotice(notices));
        // 帖子列表模版
        var evalPost = doT.template($("#post_template").text());
        console.log(<?php  echo $post_json;?>);
        var posts=[{id:1,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10},{id:2,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10},{id:3,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10}];
        $(".post_list").html(evalPost(posts));
        // 文章列表模版
        var evalArticle = doT.template($("#article_template").text());
        console.log(<?php  echo $arc_json;?>)
        var articles=[{id:1,headimg:"image/demo1.png",time:"2017-3-20",skin_num:2,postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10},{id:2,headimg:"image/demo1.png",time:"2017-3-20",skin_num:2,postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10},{id:3,headimg:"image/demo1.png",time:"2017-3-20",skin_num:2,postimg:"image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",laud_num:2,award_num:10,comment:10}];
        $(".article_list").html(evalArticle(articles));
    </script>
</body>
</html>