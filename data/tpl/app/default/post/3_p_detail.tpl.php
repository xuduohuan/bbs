<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/app_header', TEMPLATE_INCLUDEPATH)) : (include template('common/app_header', TEMPLATE_INCLUDEPATH));?>
<body>
    <div class="content">
        <div class="searchbar row bg_white margin_b_10 padding_h_15" style="padding-top:10px;">
            <div class="pull_left light_blue"><span class="bg_blue white circle block_inline block_20">&larr;</span> 回到上一页</div>
            <div class="pull_right label label_outlined label_blue">关注本论坛</div>
        </div>
        <div class="swiper-container banner_container">
            <div class="swiper-wrapper banner_wrap"></div>
            <div class="swiper-pagination text_right padding_h_15"></div>
        </div>
        <div class="post list">
            <div class="list_item post_body"></div>
            <div class="text_center bg_white">
                <div class="btn btn_blue white margin_v_10 padding_h_20 btn_award"><i class="iconfont icon-rmb1 font_20"></i>&ensp;打赏</div>
            </div>
            <div class="list_item text_center">
                <div class="flex">
                    <div class="text_center" style="width:30px;"><i class="iconfont icon-rmb1 font_18 _yellow"></i></div>
                    <div class="row all_width award_number"></div>
                </div>
            </div>
            <div class="list_item flex margin_b_10">
                <div class="text_center" style="width:30px;"><i class="iconfont icon-good-copy font_18"></i></div>
                <div class="row all_width good_number"></div>
            </div>
            <div class="padding_15 bg_white reply_input_block hide">
                <textarea class="all_width border_grey padding_5 reply_input" rows="5" placeholder="回复帖子"></textarea>
                <div class="flex center">
                    <div class="light font_18">
                        <i class="iconfont icon-tupian1"></i>&ensp;<i class="iconfont icon-biaoqing"></i>
                    </div>
                    <div class="font_12">
                        <div class="btn border_grey quit_reply">取消</div>
                        <div class="btn btn_blue border_blue white">确定</div>
                    </div>
                </div>
            </div>
            <div class="list_item font_16">全部评论<div class="pull_right label label_outlined label_blue font_12 circle_10">只看楼主</div></div>
            <div class="reply_list"></div>
            <div class="flex bg_white padding_15 v_center">
                <div class="all_width border light_grey padding_5 btn_reply_input">我来说两句</div>
                <div class="flex font_12 dark_grey post_detail" style="width:200px;padding-left:5px;"></div>
            </div>
        </div>
    </div>
    <div class="absolute all text_center bg_white award_page hide">
        <div class="award_bg"></div>
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
    <script type="text/template" charset="utf-8" id='swipe_template'>
        {{for(var i=0;i<it.length;i++){ }}
            <div class="swiper-slide">
                <img src="{{=it[i]}}">
            </div>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='post_template'>
        <div class="flex margin_b_10">
            <div class="media_img">
                <img src="{{=it.headimg}}"/>
            </div>
            <div class="all_width block">
                <div class="list_title dark_blue">{{=it.name}} <span class="label bg_blue white font_12 circle_10">{{=it.label}}</span><div class="pull_right label label_blue font_12 circle_10" style="margin-top:3px;">+关注他</div></div>
                <div class="font_12 light">发表于{{=it.time}}前&emsp;浏览 {{=it.skin_num}}</div>
            </div>
        </div>
        <div class="margin_b_10 post_content">{{=it.content}}</div>
    </script>
    <script type="text/template" charset="utf-8" id='detail_template'>
        <div class=""><i class="iconfont icon-conment1 light_blue"></i> {{=it.conment}} </div>
        <div class=""><i class="iconfont icon-good-copy"></i> {{=it.laud_num}} </div>
        <div class=""><i class="iconfont icon-share light_blue"></i> {{=it.award_num}} </div>
    </script>
    <script type="text/template" charset="utf-8" id='number_template'>
        {{ for(var i=0;i<it;i++){ }}
        <div class="col_2" style="padding:2px">
            <img src="<?php  echo $_W['attachurl'];?>image/demo3.png">
        </div>
        {{ } }}
    </script>
    <script type="text/template" charset="utf-8" id='reply_template'>
        {{ for(var i=0;i<it.length;i++){ }}
        <div class="list_item reply_item">
            <div class="flex margin_b_10">
                <div class="media_img" style="width:4em;">
                    <img src="{{=it[i].headimg}}"/>
                </div>
                <div class="all_width block">
                    <div class="list_title dark_blue">{{=it[i].name}} <span class="label label_blue font_12 circle_10">{{=it[i].label}}</span></div>
                    <div class="font_12 light">发表于{{=it[i].time}}前&emsp;{{=i+1}}楼</div>
                    <div class="conment_item" style="border-bottom:1px solid #eee;">
                        <div class="reply_content padding_v_10" style="">{{=it[i].content}}</div>
                        <div class="text_right font_12 dark_grey btn_conment_input"><i class="iconfont icon-conment1"></i>评</div>
                        <div class="bg_white conment_input_block margin_b_10 hide">
                            <textarea class="all_width border_grey padding_5 conment_input" rows="3" placeholder="发表你的评论吧"></textarea>
                            <div class="flex center">
                                <div class="light font_18">
                                    <i class="iconfont icon-tupian1"></i>&ensp;<i class="iconfont icon-biaoqing"></i>
                                </div>
                                <div class="font_12">
                                    <div class="btn border_grey quit_conment">取消</div>
                                    <div class="btn btn_blue border_blue white">确定</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="conment_list">
                        {{ for(var j=0;j<it[i].conment.length;j++){ }}
                        <div class="conment_item" style="border-bottom:1px solid #eee;">
                            <div class="reply_content padding_v_10">
                                <span class="reply_name dark_blue font_16">{{=it[i].conment[j].name}}</span>
                                <span class="font_12 light_grey">&emsp;{{=it[i].conment[j].time}}</span>
                                <div class="">{{=it[i].conment[j].content}}</div>
                            </div>
                        </div>
                        {{ } }}
                    </div>
                </div>
            </div>
        </div>
        {{ } }}
    </script>
    <script type="text/javascript">
        // 轮播模版
        var swipes = ["<?php  echo $_W['attachurl'];?>image/bg1.jpg","<?php  echo $_W['attachurl'];?>image/bg2.jpg","<?php  echo $_W['attachurl'];?>image/bg3.jpg","<?php  echo $_W['attachurl'];?>image/bg4.jpg","<?php  echo $_W['attachurl'];?>image/bg5.jpg"];
        var evalSwipe = doT.template($("#swipe_template").text());
        $(".banner_wrap").html(evalSwipe(swipes));
        window.onload = function(){
            new Swiper('.banner_container', {
                pagination : '.swiper-pagination',
            })
        }
        // 帖子模版
        var evalPost = doT.template($("#post_template").text());
        var evalNumber = doT.template($("#number_template").text());
        var evalDetail = doT.template($("#detail_template").text());
        var evalReply = doT.template($("#reply_template").text());
        var post={
            id:1,headimg:"<?php  echo $_W['attachurl'];?>image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",skin_num:2,
            content:'<p>2016年依然是非常忙碌奋斗的一年，每天都在学习与进步。不过而大多数人不一样，学习的重心依然是CSS基础知识，事无巨细和深入到底两个方向，其实基本上都没什么实用价值的东西，兴趣所在而已。并没有过多关注上层建筑，一些流行事物也就看看而已；没有参加任何论坛会议之类，没有任何的社交和抛头露面，坚持分享和知识传播，无论是公司内还是对外；也几乎没有花精力在开源项目上，无论是自己的还是协助贡献。以保证有限的精力能够专注在1~2件事情上。依旧奋斗在项目一线，尽量避免面试，人员管理这些琐碎的事情，目前对当领导不感兴趣，我只是个打杂的，所以，小伙伴写邮件提问与交流的时候，不要叫我领导，我不是；也不要叫我大神，更不是。</p><img src="<?php  echo $_W['attachurl'];?>image/img5.jpg">',
            laud_num:2,award_num:8,conment:10,
            reply:[
                {
                    id:2,headimg:"<?php  echo $_W['attachurl'];?>image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",content:"啦啦啦，不错呀",
                    conment:[
                        {id:3,name:"迟勇军",time:"1小时",content:"啦啦啦，不错呀"}
                    ]
                },
                {
                    id:4,headimg:"<?php  echo $_W['attachurl'];?>image/demo1.png",name:"迟勇军",label:"用户标签",time:"1小时",content:"啦啦啦，不错呀",
                    conment:[
                        {id:5,name:"迟勇军",time:"1小时",content:"啦啦啦，不错呀"},
                        {id:6,name:"迟勇军",time:"1小时",content:'<img src="<?php  echo $_W['attachurl'];?>image/banner0.png">'}
                    ]
                }
            ]
        };
        $(".post_body").html(evalPost(post));
        $(".award_number").html(evalNumber(post.award_num))
        $(".good_number").html(evalNumber(post.laud_num))
        $(".post_detail").html(evalDetail(post));
        $(".reply_list").html(evalReply(post.reply));
        // 赞赏
        $(".btn_award").click(function(){
            $("body").scrollTop(0).addClass("row");
            $('.award_page').show()
        })
        $(".border_red").click(function(){
            $(".border_red").removeClass("cur")
            $(this).addClass("cur")
            $("body").removeClass("row");
            $('.award_page').fadeOut()
        })
        $('.award_page').on('touchmove', function(event) {
            event.preventDefault();
        });
        // 回复
        $(".btn_reply_input").click(function(){
            $(".reply_input_block").show()
            $(".reply_input").focus()
        })
        $(".quit_reply").click(function(){
            $(".reply_input_block").hide()
        })
        $(".btn_conment_input").click(function(){
            $(".conment_input_block").hide()
            $(this).next(".conment_input_block").show().find(".conment_input").focus()
        })
        $(".quit_conment").click(function(){
            $(".conment_input_block").hide()
        })
    </script>
</body>
</html>