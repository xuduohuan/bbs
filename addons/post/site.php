<?php

include 'plugin/common.func.php';

defined('IN_IA') or exit('Access Denied');
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
            $list = pdo_fetchAll("select p.*,se.title as st,t.title as tt,u.nickname,u.avatar from ".tablename('forum_post')." p 
            left join ".tablename('forum_section')." se on se.id = p.sid
            left join ".tablename('forum_section')." t on t.id = p.tid
            left join ".tablename('forum_user')." u on u.id = p.uid
            where p.weid=:weid and p.del=:del $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':del'=>0]);

            $post_num = count($list);

            $total = pdo_fetchcolumn('select COUNT(p.id) from '.tablename('forum_post')." p
            left join ".tablename('forum_section')." se on se.id = p.sid
            left join ".tablename('forum_section')." t on t.id = p.tid
            left join ".tablename('forum_user')." u on u.id = p.uid
            where p.weid=$weid and p.del=0 $where ");
            $pager = pagination($total, $pindex, $psize);
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
        //自定义轮播
        //帖子信息
        $info = pdo_fetch('select u.nickname,u.avatar,u.v,p.time,p.click,p.content,p.pics from '.tablename('forum_post').' p
        left join '.tablename('forum_user').' u on u.id = p.uid
        where p.id=:id',[':id'=>$id]);

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
        $comment = pdo_fetchall('select c.id,c.lon,c.towho,c.lz,c.content,c.time,u.nickname,u.avatar,u.v from '.tablename('forum_com').' c 
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



    //获取模板id  模版消息 $flag=true下订单通知 $flag=false 订单完成通知
    public function postTmpMssage($openid,$template_id,$u,$querydata,$flag)
    {
        global $_W;
//        $access_token = $_W['account']['access_token']['token'];
        $acc = WeAccount::create($this->acid);
        $access_token = $acc->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        load()->func('communication');
        switch ($flag) {
            case 1:
                $data = array('touser'=>$openid,'template_id'=>$template_id,$u,'topcolor'=>"#FF0000",
                    'data'=>array(
                        'first'=>array('value'=>$querydata['first'],'color'=>"#173177"),
                        'keyword1'=>array('value'=>$querydata['keyword1'],'color'=>"#173177"),
                        'remark'=>array('value'=>$querydata['remark'],'color'=>"#173177"),
                    )
                );
                break;
            case 2:
                $data = array('touser'=>$openid,'template_id'=>$template_id,'url'=>$u,'topcolor'=>"#FF0000",
                    'data'=>array(
                        'first'=>array('value'=>$querydata['first'],'color'=>"#173177"),
                        'keyword1'=>array('value'=>$querydata['keyword1'],'color'=>"#173177"),
                        'keyword2'=>array('value'=>$querydata['keyword2'],'color'=>"#173177"),
                        'remark'=>array('value'=>$querydata['remark'],'color'=>"#173177"),
                    )
                );
                break;
            case 3:
                $data = array('touser'=>$openid,'template_id'=>$template_id,$u,'topcolor'=>"#FF0000",
                    'data'=>array(
                        'first'=>array('value'=>$querydata['first'],'color'=>"#173177"),
                        'keyword1'=>array('value'=>$querydata['keyword1'],'color'=>"#173177"),
                        'keyword2'=>array('value'=>$querydata['keyword2'],'color'=>"#173177"),
                        'keyword3'=>array('value'=>$querydata['keyword3'],'color'=>"#173177"),
                        'remark'=>array('value'=>$querydata['remark'],'color'=>"#173177"),
                    )
                );
                break;
            case 4:
                $data = array('touser'=>$openid,'template_id'=>$template_id,$u,'topcolor'=>"#FF0000",
                    'data'=>array(
                        'first'=>array('value'=>$querydata['first'],'color'=>"#173177"),
                        'keyword1'=>array('value'=>$querydata['keyword1'],'color'=>"#173177"),
                        'keyword2'=>array('value'=>$querydata['keyword2'],'color'=>"#173177"),
                        'keyword3'=>array('value'=>$querydata['keyword3'],'color'=>"#173177"),
                        'keyword4'=>array('value'=>$querydata['keyword4'],'color'=>"#173177"),
                        'remark'=>array('value'=>$querydata['remark'],'color'=>"#F5031F"),
                    )
                );
                break;
            case 5:
                $data = array('touser'=>$openid,'template_id'=>$template_id,$u,'topcolor'=>"#FF0000",
                    'data'=>array(
                        'first'=>array('value'=>$querydata['first'],'color'=>"#173177"),
                        'keyword1'=>array('value'=>$querydata['keyword1'],'color'=>"#173177"),
                        'keyword2'=>array('value'=>$querydata['keyword2'],'color'=>"#173177"),
                        'keyword3'=>array('value'=>$querydata['keyword3'],'color'=>"#173177"),
                        'keyword4'=>array('value'=>$querydata['keyword4'],'color'=>"#173177"),
                        'keyword5'=>array('value'=>$querydata['keyword5'],'color'=>"#173177"),
                        'remark'=>array('value'=>$querydata['remark'],'color'=>"#F5031F"),
                    )
                );
                break;
            default:
                break;
        }
        $data = json_encode($data);

        $res = http_request($url,$data);

        if(!is_bool($res)){
            return true;
        }else{
            return false;
        }
    }
}

