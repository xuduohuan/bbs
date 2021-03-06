<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/app_header', TEMPLATE_INCLUDEPATH)) : (include template('common/app_header', TEMPLATE_INCLUDEPATH));?>
<body>
    <div class="content">
        <div class="searchbar row bg_grey padding_h_15" style="padding-top:10px;">
            <div class="pull_left light_blue"><span class="bg_blue white circle block_inline block_20">&larr;</span> 回到上一页</div>
            <div class="pull_right label label_outlined label_blue">关注本论坛</div>
        </div>
        <div class="list">
            <div class="list_item article_body"></div>
            <div class="text_center bg_white">
                <div class="btn btn_blue white margin_v_10 padding_h_20 btn_award"><i class="iconfont icon-rmb1 font_20"></i>&ensp;打赏</div>
            </div>
            <div class="list_item">
                <div class="bg_grey">&ensp;<i class="iconfont icon-list1 font_20 light"></i>&ensp;相关阅读</div>
                <div class="article_list list"></div>
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
            <div class="list_item font_16">全部评论</div>
            <div class="reply_list"></div>
            <div class="flex bg_white padding_15 v_center">
                <div class="all_width border light_grey padding_5 btn_reply_input">我来说两句</div>
                <div class="flex font_12 dark_grey article_detail" style="width:200px;padding-left:5px;"></div>
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
    <script type="text/template" charset="utf-8" id='article_template'>
        <div class="font_20">{{=it.title}}</div>
        <div class="flex light margin_v_10">
            <div class="">{{=it.from}}&emsp;</div>
            <div class="">&emsp;{{=it.time}}&emsp;</div>
            <div class="flex_1 text_center"><i class="iconfont icon-eye"></i> {{=it.skin_num}}</div>
            <div class="flex_1 text_center"><i class="iconfont icon-good-copy"></i> {{=it.laud_num}}</div>
        </div>
        <div class="margin_b_10 post_content">{{=it.content}}</div>
    </script>
    <script type="text/template" charset="utf-8" id='list_template'>
        {{ for(var i=0;i<it.length;i++){ }}
            <div class="list_item flex v_center" style="padding:0.8em 0;">
                <div class="all_width">
                    <div class="">{{=it[i].title}}</div>
                    <div class="font_12 light">{{=it[i].time}}</div>
                </div>
                <div class="text_right" style="width:80px"><i class="iconfont icon-right"></i></div>
            </div>
        {{ } }}
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
        // 文章模版
        var evalArticle = doT.template($("#article_template").text());
        var evalList = doT.template($("#list_template").text());
        var evalNumber = doT.template($("#number_template").text());
        var evalDetail = doT.template($("#detail_template").text());
        var evalReply = doT.template($("#reply_template").text());
        var article={
            id:1,time:"2017-3-16",from:"众泰首席设计师",skin_num:2,laud_num:2,award_num:10,conment:10,
            title:"预售10.98万起 众泰SR9 11月11日将上市",
            content:'<p>日前，我们从众泰汽车官方获悉，众泰SR9将会在11月11日正式上市，届时将会公布其正式售价。在10月26日，众泰汽车曾公布了SR9的预售价区间为10.98-16.28万。</p><img src="<?php  echo $_W['attachurl'];?>image/a_1.jpg"><p>众泰SR9的长宽高分别为4744/1929/1647mm，轴距为2850mm。新车的前进气格栅采用大嘴式风格，搭配不规则的前大灯组造型。而在尾部设计上，新车设计较为圆润，整体风格于保时捷Macan非常相似。.98-16.28万。</p><img src="<?php  echo $_W['attachurl'];?>image/a_1.jpg">',
            list:[{id:1,time:"2017-3-20",title:"R3未批漳州段何以开工？"},{id:2,time:"2017-3-20",title:"厦漳城际轨道R3线先期工程长10km"},{id:3,time:"2017-3-20",title:"厦漳城际R3线公示 穿厦门南海域经"}],
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
        $(".article_body").html(evalArticle(article));
        $(".article_list").html(evalList(article.list))
        $(".award_number").html(evalNumber(article.award_num))
        $(".good_number").html(evalNumber(article.laud_num))
        $(".article_detail").html(evalDetail(article));
        $(".reply_list").html(evalReply(article.reply));
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