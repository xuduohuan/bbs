<?php

include 'plugin/common.func.php';

defined('IN_IA') or exit('Access Denied');
    //根据openid获取用户微信基本信息
    public function user_info($openid){
        global $_W;
        if(!isset($openid)){
            $openid = $_W['fans']['from_user'];
        }
        $acc = WeAccount::create($this->acid);
        $access_token = $acc->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $output = http_request($url);
        if($output){
            $nickname=json_decode($output)->nickname;
            $avatar=json_decode($output)->headimgurl;
            return [$nickname,$avatar];
        }
    }





    //返回json格式化数据
    public static function returnMsg($responce=array())
    {
        echo json_encode($responce);
    }


    //取出用户信息
    public function getUserInfo($openid='',$uid=0)
    {
        global $_GPC,$_W;

        if($openid){
            return $userinfo = pdo_fetch('select * from '.tablename('forum_user').' where openid=:openid',[':openid'=>$_W['openid']]);   
        }else if($id){
            return $userinfo = pdo_fetch('select * from '.tablename('forum_user').' where id=:id',[':id'=>$id]);
        }
        return false;

    }


    //点赞
    public function doMobileZan()
    {
        global $_GPC,$_W;

        $uid = getUserInfo();
        $postid = $_GPC['postid'];
        $type = intval($_GPC['type']);      //打赏类型  2帖子1文章
        
        if($type && !in_array($type, [1,2])){
            self::returnMsg(['msg'=>'参数错误','status'=>1]);
        }

        $data = ['weid'=>$_W['weid'],'uid'=>$uid['id'],'type'=>$type,'cid'=>$postid,'time'=>time()];
        
        $ise = pdo_fetch('select 1 from '.tablename('forum_zan').' where uid=:uid and cid=:cid and type=:type',[':uid'=>$uid['uid'],'cid'=>$postid,':type'=>$type]);
        if($ise){
            pdo_insert('forum_zan',$data);
            echo json_decode(['msg'=>'点赞成功','status'=>0]);
        }else{
            echo json_decode(['msg'=>'已经赞过','status'=>1]);
        }  
    }


    //打赏
    public function doMobileShang()
    {
        global $_GPC,$_W;

        $uid = getUserInfo();
        $postid = $_GPC['postid'];
        $rid = intval($_GPC['rid']);
        $score = intval($_GPC['score']);
        $type = intval($_GPC['type']);      //打赏类型  2帖子1文章
        if($type && !in_array($type, [1,2])){
            self::returnMsg(['msg'=>'参数错误','status'=>1]);
        }

        if($_W['ispost'] && !empty($score) && !empty($rid)){
            elf::returnMsg(['msg'=>'参数错误','status'=>1]);
        }

        $data = ['weid'=>$_W['weid'],'uid'=>$uid['id'],'type'=>2,'cid'=>$postid,'time'=>time()];
        if($_GPC['rid']){       //档位
            $data['rid'] = intval($_GPC['rid']);
            $score = pdo_fetch('select score from '.tablename('forum_reward_set').' where id=:rid and status=0',[':rid'=>intval($_GPC['rid'])]);
            $data['score'] = $score['score'];
        }else{      //自定义
            $data['score'] = $score;
        }

        //减少用户对应积分
        $sql = 'update '.tablename('forum_user').' set score=score-:score where uid=:uid and weid=:weid';
        pdo_query($sql,[':score'=>$score,':uid'=>$uid['id'],':weid'=>$_W['weid']]);
        $isok = pdo_insert('forum_reward',$data);       //打赏记录
        $rewardid = pdo_insertid();
        if($isok){      //插入用户积分记录
            $data = ['weid'=>$weid,'uid'=>$uid['id'],'type'=>$type,'record_id'=>$rewardid,'time'=>time()];
            $isok = pdo_insert('forum_behavior',$rewardid);
        }

        if($isok){
            self::returnMsg(['msg'=>'打赏成功','status'=>0]);
        }else{
            self::returnMsg(['msg'=>'打赏失败','status'=>1]);
        } 
    }


    //回复帖子
    public function doMobileReplypost()
    {
        global $_GPC,$_W;
        $postid = intval($_GPC['postid']);          //帖子id
        $towho = intval($_GPC['towho']);            //回复的id

        $user = getUserInfo();
        $data = [
            'weid'=>$_W['weid'],
            'uid' => $user['id'],
            'type' => 1,
            'cid' => $postid,
            'time' => time(),
            'content' => $_GPC['content']
            ];

        if($towho){     //对帖子评论
            $data['towho'] = $towho;
        }

        $ise = pdo_fetch('select 1 from '.tablename('forum_com').' where uid=:uid and cid=:cid and towho=:towho');
        if($ise){
            pdo_insert('forum_com',$data);
            self::returnMsg(['msg'=>'评论成功','status'=>0]);
        }else{
            self::returnMsg(['msg'=>'已经评论过','status'=>1]);
        }

    }


    //搜索
    public function doMobileSearch()
    {
        global $_GPC,$_W;

        $search = $_GPC['search'];
        //搜索帖子
        $list['post'] = pdo_fetchAll('select * from '.tablename('forum_post')." where content like '%{$search}%'",[':content'=>$search]);
        //搜索回复
        $list['reply'] = pdo_fetchAll('select * from '.tablename('forum_com')." where content like '%{$search}%'",[':content'=>$search]);
        //搜索文章
        $list['article'] = pdo_fetchAll("select id,title,pic from ".tablename('forum_article')." where title like '%{$search}%'");
        if(empty($list)){
            $list = ['post'=>[],'reply'=>[],'article'=>[]];
        }
        self::returnMsg($list);

    }


    //只看楼主
    public function doMobileLooklz()
    {
        global $_GPC,$_W;


    }
class PostModuleSite extends WeModuleSite {
    //模块标识
    public $modulename = 'post';//模块名

    public function __construct()
    {
        global $_W;

    }

    public function doWebgo()
    {
        header('Location: ./index.php?c=home&a=welcome&do=ext&m=article');
    }

    //帖子管理
    public function doWebPost()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $topic = pdo_fetchall('select id,title from '.tablename('forum_topic').' where weid=:weid and sel=:sel order by sort,time DESC',[':weid'=>$weid,':sel'=>0]);
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
            if (!empty($_GPC['title'])) {
                $title = $_GPC['title'];
                $where .= " AND p.content like '%{$title}%'";
            }
            if (!empty($_GPC['name'])) {
                $name = $_GPC['name'];
                $where .= " AND u.nickname like '%{$name}%'";
            }
            if (!empty($_GPC['to'])) {
                $to = $_GPC['to'];
                $where .= " AND p.tid =$to";
            }
            $list = pdo_fetchAll("select p.*,t.title as tt,u.nickname,u.avatar from ".tablename('forum_post')." p 
            left join ".tablename('forum_topic')." t on t.id = p.tid
            left join ".tablename('forum_user')." u on u.id = p.uid
            where p.weid=:weid and p.del=:del $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':del'=>0]);

            $post_num = count($list);

            $total = pdo_fetchcolumn('select COUNT(p.id) from '.tablename('forum_post')." p
            left join ".tablename('forum_topic')." t on t.id = p.tid
            left join ".tablename('forum_user')." u on u.id = p.uid
            where p.weid=$weid and p.del=0 $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
            $id=intval($_GPC['id']);
            $topic = pdo_fetchall('select id,title from '.tablename('forum_topic').' where weid=:weid and sel=:sel order by sort,time DESC',[':weid'=>$weid,':sel'=>0]);
            $banner_info = pdo_fetchcolumn('select banner from '.tablename('forum_post').' where id=:id',[':id'=>$id]);

            if(checksubmit('sub')){
                $data=[
                    'weid'=>$weid,
                    'tid'=>$_GPC['topic'],
                    'content'=>htmlspecialchars_decode(trim($_GPC['content'])),
                    'banner'=>serialize($_GPC['banner']),
                    'url'=>$_GPC['burl'],
                    'status'=>$_GPC['status'],
                    'time'=>TIMESTAMP
                ];

                if(!empty($id)){
                    $res = pdo_update('forum_post',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_post',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('post'),'success');
                }
            }elseif (!empty($id)){
                $info = pdo_fetch('select * from '.tablename('forum_post').' where id=:id',[':id'=>$id]);
            }
        }elseif ($op=='banner'){//自定义轮播
            $id=intval($_GPC['id']);

            if(checksubmit('sub')){
                $num = $_GPC['num'];
                for ($i=0;$i<$num;$i++){
                    $bnarr[$i] = $_GPC["ban$i"];
                    $url[$i] = $_GPC["burl$i"];
                }

                if(!empty($id)){
                    $res = pdo_update('forum_post',['banner'=>serialize($bnarr),'url'=>serialize($url)],['id'=>$id]);
                }
                if($res){
                    message('更新成功', $this->createWebUrl('post'),'success');
                }
            }elseif (!empty($id)){
                $binfo = pdo_fetch('select banner,url from '.tablename('forum_post').' where id=:id',[':id'=>$id]);
                $bn = unserialize($binfo['banner']);
                $url = unserialize($binfo['url']);
                foreach ($bn as $k=>&$v){
                    $arr[$k]['ban'] = $v;
                    $arr[$k]['url'] = $url[$k];
                }
                $num = count($arr);
            }
        }
        include $this->template('post');
    }



    //帖子所属组——板块、话题管理
    public function doWebseto()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
//            if (!empty($_GPC['st'])) {
//                $st = $_GPC['st'];
//                $where .= " AND stype = $st ";
//            }

            $list = pdo_fetchAll("select * from ".tablename('forum_topic')." where weid=:weid $where order by sort,time LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid]);
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_topic')." where weid=$weid $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
//            $css = [];
            $id = intval($_GPC['id']);

            if(checksubmit('sub')){
                $data=[
                    'weid'=>$weid,
                    'title'=>trim($_GPC['title']),
                    'url'=>trim($_GPC['url']),
                    'sel'=>$_GPC['sel'],
                    'sort'=>$_GPC['sort'],
                    'time'=>TIMESTAMP
                ];

                if(!empty($id)){
                    $res = pdo_update('forum_topic',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_topic',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('seto'),'success');
                }
            }else{
                $info = pdo_fetch('select * from '.tablename('forum_topic').' where id=:id',[':id'=>$id]);
            }
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_topic',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('seto'),'success');
            }
        }
        include $this->template('seto');
    }


    //帖子列表
    public function doMobilepost()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $where = '';

        //话题选择
        $topic = pdo_fetchall('select * from '.tablename('forum_topic').' where weid=:weid and sel=:sel order by sort,time',[':weid'=>$weid,':sel'=>0]);

        $list = pdo_fetchAll("select p.*,t.title,u.nickname,u.v,u.avatar from ".tablename('forum_post')." p 
        left join ".tablename('forum_topic')." t on t.id = p.tid
        left join ".tablename('forum_user')." u on u.id = p.uid
        where p.weid=:weid and p.del=:del $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':del'=>0]);

//        $total = pdo_fetchcolumn('select COUNT(p.id) from '.tablename('forum_post')." p
//        left join ".tablename('forum_section')." se on se.id = p.sid
//        left join ".tablename('forum_section')." t on t.id = p.tid
//        left join ".tablename('forum_user')." u on u.id = p.uid
//        where p.weid=$weid and p.del=0 $where ");
//        $pager = pagination($total, $pindex, $psize);
        foreach ($list as &$v){
            $v['time']= time_tran($v['time']);
            $v['zan'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_zan').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$v['id']]);
            $v['rew'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_reward').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$v['id']]);
            $v['com'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_com').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$v['id']]);
        }
        $list_json = json_encode($list);
        include $this->template('list');
    }

    //帖子详情
    public function doMobiledetail()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $id = intval($_GPC['id']);//帖子id
        if(empty($id)){//如果帖子id不存在
            return false;
        }
        pdo_query("update ims_forum_post set click = click + 1 where id = $id");
        //帖子信息(包括自定义轮播)
        $info = pdo_fetch('select u.nickname,u.avatar,u.v,p.time,p.click,p.content,p.pics,p.banner,p.url from '.tablename('forum_post').' p
        left join '.tablename('forum_user').' u on u.id = p.uid
        where p.id=:id',[':id'=>$id]);

        if($info['banner']){
            $bn =  unserialize($info['banner']);
            $url =  unserialize($info['url']);
            foreach ($bn as $k=>$v){
                if(empty($v)){
                    unset($info['ban'][$k]);
                    continue;
                }
                $info['ban'][$k] = $v;
                $info['burl'][$k] = $url[$k];
            }
            unset($info['banner'],$info['url']);
        }

        $zan = pdo_fetchall('select u.avatar from '.tablename('forum_zan').' z
        left join '.tablename('forum_user').' u on u.id = z.uid
        where z.weid=:weid and z.type=:ty and z.cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$id]);

        $rew = pdo_fetchall('select u.avatar from '.tablename('forum_reward').' r
        left join '.tablename('forum_user').' u on u.id = r.uid
        where r.weid=:weid and r.type=:ty and r.cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$id]);
        if($info){
            $info['time'] = time_tran($info['time']);
            $info['zan'] = array_column($zan,'avatar');
            $info['rew'] = array_column($rew,'avatar');
            $info['zan_num'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_zan').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$id]);
            $info['com_num'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_com').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$id]);
            $info['rew_num'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_reward').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$id]);
        }
        $info_json = json_encode($info);
        //评论
        $comment = pdo_fetchall('select c.id,c.towho,c.lz,c.content,c.time,u.nickname,u.avatar,u.v from '.tablename('forum_com').' c 
        left join '.tablename('forum_user').' u on u.id = c.uid
        where c.weid=:weid and c.type=:ty and c.cid=:cid and towho=:tw order by time DESC',[':weid'=>$weid,':ty'=>2,':cid'=>$id,':tw'=>0]);
        foreach ($comment as $k=>$v){
            $com[$k]['id'] = $v['id'];
            $com[$k]['avatar'] = $v['avatar'];
            $com[$k]['nickname'] = $v['nickname'];
            $com[$k]['v'] = $v['v'];
            $com[$k]['content'] = $v['content'];
            $com[$k]['time'] = time_tran($v['time']);

            $cs = pdo_fetchall('select u.nickname,c.time,c.id,c.content from '.tablename('forum_com').' c 
            left join '.tablename('forum_user').' u on u.id = c.uid where c.weid=:weid and type=:ty and c.cid=:cid and c.towho=:tw order by c.time DESC',[':weid'=>$weid,':ty'=>2,':cid'=>$id,':tw'=>$v['id']]);
            foreach ($cs as $key=>&$val){
                $val['time'] = time_tran($val['time']);
                $com[$k]['comment'] = $cs;
            }
        }
        $com_json = json_encode($com);
        include $this->template('p_detail');
    }


    function checksubmit($var = 'submit', $allowget = false,$oid) {
        global $_W, $_GPC;
        if (empty($_GPC[$var])) {
            return FALSE;
        }
        if(defined('IN_SYS')) {
            if ($allowget || (($_W['ispost'] && !empty($_W['token']) && $_W['token'] == $_GPC['token']) && (empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
                return TRUE;
            }
        } else {
            if(empty($_W['isajax']) && empty($_SESSION['token'][$_GPC['token']])) {
                echo "<script>window.history.go(-1);</script>";
            } else {
                unset($_SESSION['token'][$_GPC['token']]);
            }
            return TRUE;
        }
        return FALSE;
    }


    //根据openid获取用户微信基本信息
    public function user_info($openid){
        global $_W;
        if(!isset($openid)){
            $openid = $_W['fans']['from_user'];
        }
        $acc = WeAccount::create($this->acid);
        $access_token = $acc->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $output = http_request($url);
        if($output){
            $nickname=json_decode($output)->nickname;
            $avatar=json_decode($output)->headimgurl;
            return [$nickname,$avatar];
        }
    }


    
    //根据openid获取用户微信基本信息
    public function user_info($openid){
        global $_W;
        if(!isset($openid)){
            $openid = $_W['fans']['from_user'];
        }
        $acc = WeAccount::create($this->acid);
        $access_token = $acc->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $output = http_request($url);
        if($output){
            $nickname=json_decode($output)->nickname;
            $avatar=json_decode($output)->headimgurl;
            return [$nickname,$avatar];
        }
    }





    //返回json格式化数据
    public static function returnMsg($responce=array())
    {
        echo json_encode($responce);
    }


    //取出用户信息
    public function getUserInfo($openid='',$uid=0)
    {
        global $_GPC,$_W;

        if($openid){
            return $userinfo = pdo_fetch('select * from '.tablename('forum_user').' where openid=:openid',[':openid'=>$_W['openid']]);   
        }else if($id){
            return $userinfo = pdo_fetch('select * from '.tablename('forum_user').' where id=:id',[':id'=>$id]);
        }
        return false;

    }


    //点赞
    public function doMobileZan()
    {
        global $_GPC,$_W;

        $uid = getUserInfo();
        $postid = $_GPC['postid'];
        $type = intval($_GPC['type']);      //打赏类型  2帖子1文章
        
        if($type && !in_array($type, [1,2])){
            self::returnMsg(['msg'=>'参数错误','status'=>1]);
        }

        $data = ['weid'=>$_W['weid'],'uid'=>$uid['id'],'type'=>$type,'cid'=>$postid,'time'=>time()];
        
        $ise = pdo_fetch('select 1 from '.tablename('forum_zan').' where uid=:uid and cid=:cid and type=:type',[':uid'=>$uid['uid'],'cid'=>$postid,':type'=>$type]);
        if($ise){
            pdo_insert('forum_zan',$data);
            echo json_decode(['msg'=>'点赞成功','status'=>0]);
        }else{
            echo json_decode(['msg'=>'已经赞过','status'=>1]);
        }  
    }


    //打赏
    public function doMobileShang()
    {
        global $_GPC,$_W;

        $uid = getUserInfo();
        $postid = $_GPC['postid'];
        $rid = intval($_GPC['rid']);
        $score = intval($_GPC['score']);
        $type = intval($_GPC['type']);      //打赏类型  2帖子1文章
        if($type && !in_array($type, [1,2])){
            self::returnMsg(['msg'=>'参数错误','status'=>1]);
        }

        if($_W['ispost'] && !empty($score) && !empty($rid)){
            elf::returnMsg(['msg'=>'参数错误','status'=>1]);
        }

        $data = ['weid'=>$_W['weid'],'uid'=>$uid['id'],'type'=>2,'cid'=>$postid,'time'=>time()];
        if($_GPC['rid']){       //档位
            $data['rid'] = intval($_GPC['rid']);
            $score = pdo_fetch('select score from '.tablename('forum_reward_set').' where id=:rid and status=0',[':rid'=>intval($_GPC['rid'])]);
            $data['score'] = $score['score'];
        }else{      //自定义
            $data['score'] = $score;
        }

        //减少用户对应积分
        $sql = 'update '.tablename('forum_user').' set score=score-:score where uid=:uid and weid=:weid';
        pdo_query($sql,[':score'=>$score,':uid'=>$uid['id'],':weid'=>$_W['weid']]);
        $isok = pdo_insert('forum_reward',$data);       //打赏记录
        $rewardid = pdo_insertid();
        if($isok){      //插入用户积分记录
            $data = ['weid'=>$weid,'uid'=>$uid['id'],'type'=>$type,'record_id'=>$rewardid,'time'=>time()];
            $isok = pdo_insert('forum_behavior',$rewardid);
        }

        if($isok){
            self::returnMsg(['msg'=>'打赏成功','status'=>0]);
        }else{
            self::returnMsg(['msg'=>'打赏失败','status'=>1]);
        } 
    }


    //回复帖子
    public function doMobileReplypost()
    {
        global $_GPC,$_W;
        $postid = intval($_GPC['postid']);          //帖子id
        $towho = intval($_GPC['towho']);            //回复的id

        $user = getUserInfo();
        $data = [
            'weid'=>$_W['weid'],
            'uid' => $user['id'],
            'type' => 1,
            'cid' => $postid,
            'time' => time(),
            'content' => $_GPC['content']
            ];

        if($towho){     //对帖子评论
            $data['towho'] = $towho;
        }

        $ise = pdo_fetch('select 1 from '.tablename('forum_com').' where uid=:uid and cid=:cid and towho=:towho');
        if($ise){
            pdo_insert('forum_com',$data);
            self::returnMsg(['msg'=>'评论成功','status'=>0]);
        }else{
            self::returnMsg(['msg'=>'已经评论过','status'=>1]);
        }

    }


    //搜索
    public function doMobileSearch()
    {
        global $_GPC,$_W;

        $search = $_GPC['search'];
        //搜索帖子
        $list['post'] = pdo_fetchAll('select * from '.tablename('forum_post')." where content like '%{$search}%'",[':content'=>$search]);
        //搜索回复
        $list['reply'] = pdo_fetchAll('select * from '.tablename('forum_com')." where content like '%{$search}%'",[':content'=>$search]);
        //搜索文章
        $list['article'] = pdo_fetchAll("select id,title,pic from ".tablename('forum_article')." where title like '%{$search}%'");
        if(empty($list)){
            $list = ['post'=>[],'reply'=>[],'article'=>[]];
        }
        self::returnMsg($list);

    }


    //只看楼主
    public function doMobileLooklz()
    {
        global $_GPC,$_W;


    }


}

