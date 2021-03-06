<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/app_header', TEMPLATE_INCLUDEPATH)) : (include template('common/app_header', TEMPLATE_INCLUDEPATH));?>
<body>
    <div class="content">
        <div class="searchbar flex v_center bg_blue">
            <div class="searchbar_input circle_15 all_width">
                <i class="icon icon_search"></i>
                <input type="search" placeholder="请输入搜索内容">
            </div>
            <i class="searchbar_right icon icon_my white font_18"></i>
            <div class="badge user_num"></div>
        </div>
        <div class="theme_list relative text_center bg_white">
            <div class="swiper-container lineheight_3">
                <div class="swiper-wrapper"></div>
            </div>
            <div class="absolute swiper_prev white bg_grey"><i class="iconfont icon-left"></i></div>
            <div class="absolute swiper_next white bg_grey"><i class="iconfont icon-right"></i></div>
        </div>
        <section>
            <ul class="list"></ul>
        </section>
    </div>
    <div class="absolute all text_center bg_white award_page hide">
        <div class="font_18 award_word">谢谢各位的赞赏</div>
        <div class="flex flex_wrap lineheight_3 light_red award_block">
            <div class="border_red col_3 circle_5">1分</div>
            <div class="border_red col_3 circle_5">5分</div>
            <div class="border_red col_3 circle_5">10分</div>
            <div class="border_red col_3 circle_5">20分</div>
            <div class="border_red col_3 circle_5">30分</div>
            <div class="border_red col_3 circle_5">100分</div>
        </div>
        <div class="font_18 light_blue">其他金额</div>
    <div>
    <script type="text/template" charset="utf-8" id='theme_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <div class="swiper-slide">
                <div class="theme_icon bg_blue"><i class="icon icon_mobile"></i></div>{{=it[i]}}
            </div>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='list_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <li class="list_item">
                <div class="flex margin_b_10">
                    <div class="media_img">
                        <img src="{{=it[i].avatar}}"/>
                    </div>
                    <div class="all_width block">
                        <div class="list_title dark_blue">{{=it[i].nickname}} <span class="label label_blue font_12 circle_10">{{=it[i].v}}</span></div>
                        <div class="font_12 _light">发表于{{=it[i].time}}&emsp;浏览 {{=it[i].click}}</div>
                    </div>
                </div>
                <div class="flex v_center bg_default margin_b_10">
                    <div class="media_img" style="width:200px;">
                        <img src="{{=it[i].postimg}}"/>
                    </div>
                    <div class="all_width line_ellipsis" style="height:42px;">{{=it[i].content}}</div>
                </div>
                <div class="flex h_justify font_12">
                    <div class=""><i class="iconfont icon-good-copy dark_blue"></i> 点赞：{{=it[i].zan}}</div>
                    <div class=""><i class="iconfont icon-dollar dark_blue"></i> 打赏：{{=it[i].rew}}</div>
                    <div class=""><i class="iconfont icon-conment1 dark_blue"></i> 评论：{{=it[i].com}}</div>
                </div>
            </li>
        {{ } }}
    </script>
    <script type="text/javascript">
        // 话题模版
        var evalTheme = doT.template($("#theme_template").text());
        var theme = ["话题1","话题2","话题3","话题4","话题5","话题6","话题7","话题8","话题9","话题10"];
        $(".swiper-wrapper").html(evalTheme(theme));
        window.onload = function(){
            var mySwiper = new Swiper('.swiper-container', {
                slidesPerView : 4,
                prevButton:'.swiper_prev',
                nextButton:'.swiper_next',
            })
        }
        // 帖子列表模版
        var evalList = doT.template($("#list_template").text());
//        var list=[{id:1,headimg:"image/demo1.png",nickname:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,postimg:"../image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",zan:2,rew:10,com:10},{id:2,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,postimg:"../image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",zan:2,rew:10,com:10},{id:3,headimg:"image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,postimg:"../image/demo.jpg",posttext:"如果谈及中国在技术领域的短板，那么大家可能想到的是发动机，其实还有一样：高级电子芯片！人们通常所说的CPU，所谓CPU即中央处理器，就是其代表产品，它可是为电子信息产品的心脏",zan:2,rew:10,com:10}];
        var list = <?php  echo $list_json;?>;
        console.log(<?php  echo $list_json;?>);
        $(".list").html(evalList(list));
        // 个人中心消息提醒数目
        $(".user_num").html("2")
        // 赞赏
        $(".border_red").click(function(){
            $(".border_red").removeClass("cur")
            $(this).addClass("cur")
        })
    </script>

</body>
</html>