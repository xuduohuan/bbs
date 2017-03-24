<?php

include 'plugin/common.func.php';

defined('IN_IA') or exit('Access Denied');
class ArticleModuleSite extends WeModuleSite {
    //模块标识
    public $modulename = 'article';//模块名

    public function __construct()
    {
        global $_W;

    }

    public function doWebgo()
    {
        header('Location: ./index.php?c=home&a=welcome&do=ext&m=post');
    }

    //文章所属组——页面、栏目管理
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

            $list = pdo_fetchAll("select * from ".tablename('forum_section')." where weid=:weid and del=:del $where order by `id` LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':del'=>0]);

            if($list){
                foreach ($list as $v){
                    if($v['stype'] == 1){
                        $arr[$v['id']]['id'] = $v['id'];
                        $arr[$v['id']]['title'] = $v['title'];
                        $arr[$v['id']]['status'] = $v['status'];
                        $arr[$v['id']]['del'] = $v['del'];
                        $arr[$v['id']]['sort'] = $v['sort'];
                        $arr[$v['id']]['time'] = $v['time'];
                        $arr[$v['id']]['banner'] = unserialize($v['banner'])[0];
                        $arr[$v['id']]['sons'] = pdo_fetchall('select id,title,status,time from '.tablename('forum_section').' where weid=:weid and del=:del and stype=:sty and sid=:sid ',[':weid'=>$weid,':del'=>0,':sty'=>2,':sid'=>$v['id']]);
                    }
                }
            }
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_section')." where weid=$weid and del=0 $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
            $id = intval($_GPC['id']);

            if(checksubmit('sub')){
                $data=[
                    'weid'=>$weid,
                    'title'=>trim($_GPC['title']),
                    'status'=>$_GPC['status'],
                    'time'=>TIMESTAMP
                ];

                if($_GPC['ps'] == 's'){//添加子级
                    $data['stype'] = '2';
                    $data['sid'] = intval($_GPC['id']);
                    $res = pdo_insert('forum_section',$data);
                    $msg='新增成功';
                }elseif ($_GPC['ps'] == 'c'){//编辑子级
                    $res = pdo_update('forum_section',['title'=>trim($_GPC['title']),'status'=>$_GPC['status']],['id'=>intval($_GPC['id'])]);
                    $msg='更新成功';
                }else{//添加父级
                    $gpc_id = intval($_GPC['id']);//从隐藏域中获取的
                    $data['stype'] = '1';
                    $data['sid'] = 0;

                    if(!empty($gpc_id)){
                        $res = pdo_update('forum_section',['title'=>trim($_GPC['title']),'status'=>$_GPC['status'],'banner'=>$_GPC['banner']],['id'=>$gpc_id]);
                        $msg='更新成功';
                    }else{
                        $data['banner'] = serialize($_GPC['banner']);
                        $res = pdo_insert('forum_section',$data);
                        $insertid = pdo_insertid();//新增成功后，生成链接
                        $msg='新增成功';
                    }
                }

                if($res){
                    message($msg, $this->createWebUrl('seto'),'success');
                }
            }elseif ((!isset($_GPC['ps']) and !empty($id)) or ($_GPC['ps'] == 'c' and !empty($id))){
                $info = pdo_fetch('select * from '.tablename('forum_section').' where id=:id',[':id'=>$id]);
            }
        }else if($op == 'url'){
            $id=intval($_GPC['id']);
            $url = $this->createMobileUrl('list',['pid'=>$id]);
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_section',['del'=>1],['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('seto'),'success');
            }
        }
        include $this->template('seto');
    }


    //图片组管理
    public function doWebPics()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 5;
            $where='';
            if (!empty($_GPC['name'])) {
                $name=$_GPC['name'];
                $where .= " AND name like '%{$name}%'";
            }
            $list = pdo_fetchAll("select * from ".tablename('forum_pics')." where weid=:weid and del=:del $where order by id LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':del'=>0]);
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_pics')." where weid=$weid and del=0 $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
            $id=intval($_GPC['id']);
            if(checksubmit('sub')){
                $data=[
                    'weid'=>$weid,
                    'url'=>trim($_GPC['url']),
                    'pics'=>$_GPC['pics'],
                    'status'=>$_GPC['status'],
                    'use'=>$_GPC['use'],
                    'time'=>TIMESTAMP
                ];

                if(!empty($id)){
                    $res = pdo_update('forum_pics',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_pics',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('pics'),'success');
                }
            }elseif (!empty($id)){
                $info = pdo_fetch('select * from '.tablename('forum_pics').' where id=:id',[':id'=>$id]);
            }
        }elseif($op == 'quit'){//取消首页自定义设置
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_pics',['use'=>'0'],['id'=>$id]);
            if($res){
                message('取消成功', $this->createWebUrl('pics'),'success');
            }
        }elseif($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_pics',['del'=>1],['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('pics'),'success');
            }
        }elseif($op == 'deleteall'){
            $ids = $_GPC['ids'];
            $res = pdo_query("UPDATE ims_forum_pics SET del = '1' WHERE id in ($ids)");
            if($res){
                message(['msg'=>'删除成功','error'=>0],'','ajax');
            }
        }
        include $this->template('pics');
    }


    //用户管理
    public function doWebUser()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $label = pdo_fetchall('select * from '.tablename('forum_label').' order by sort');
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
            if (!empty($_GPC['name'])) {
                $name=$_GPC['name'];
                $where .= " AND name like '%{$name}%'";
            }
            if (!empty($_GPC['v'])) {
                $v=$_GPC['v'];
                $where .= " AND v = '$v' ";
            }

            $list = pdo_fetchAll("select * from ".tablename('forum_user')." where weid=:weid $where order by id LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid]);
            $user_num = count($list);
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_user')." where weid=$weid $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op == 'behavior'){//用户行为记录
            $uid = intval($_GPC['id']);

            //收藏（文章）
            $coll = pdo_fetchall('select * from '.tablename('forum_coll').' c
            left join '.tablename('forum_article').' a on a.id = c.cid
            where c.weid=:weid and c.uid=:uid order by c.time DESC',[':weid'=>$weid,':uid'=>$uid]);
            //点赞（文章和帖子）
            $zan = pdo_fetchall('(select z.*,a.id as cid,a.title from '.tablename('forum_zan').' z left join '.tablename('forum_article').' a on a.id = z.cid where z.weid=:weid and z.uid=:uid and z.type = 1)
            union (select z.*,p.id as cid,p.content from '.tablename('forum_zan').' z left join '.tablename('forum_post').' p on p.id = z.cid where z.weid=:weid and z.uid=:uid and z.type = 2) order by time DESC ',[':weid'=>$weid,':uid'=>$uid]);
            //评论（文章和帖子）
            $com = pdo_fetchall('(select c.*,a.id as cid,a.title from '.tablename('forum_com').' c left join '.tablename('forum_article').' a on a.id = c.cid where c.weid=:weid and c.uid=:uid and c.type = 1) 
            union (select c.*,p.id as cid,p.content from '.tablename('forum_com').' c left join '.tablename('forum_post').' p on p.id = c.cid where c.weid=:weid and c.uid=:uid and c.type = 2) order by time DESC',[':weid'=>$weid,':uid'=>$uid]);
            //打赏（文章和帖子）
//            $awa = pdo_fetchall('select c.*,a.id as cid,a.title from '.tablename('forum_com').' c left join '.tablename('forum_article').' a on a.id = c.cid where c.weid=:weid and c.uid=:uid and c.type = 1
//            union select c.*,p.id as cid,p.content from '.tablename('forum_com').' c left join '.tablename('forum_post').' p on p.id = c.cid where c.weid=:weid and c.uid=:uid and c.type = 2 ',[':weid'=>$weid,':uid'=>$uid]);
            $arr =  array_merge($coll,$zan,$com);
            $arr_limit = array_slice($arr,0,10);//取多少记录
            if($arr_limit){
                foreach ($arr_limit as $v){
                    $time[] = $v['time'];
                }
                array_multisort($time,SORT_DESC,SORT_NUMERIC,$arr_limit);//对合并之后的数组以时间倒序排列
            }
//            print_r($com);
        } elseif ($op == 'addv'){//加标签
            $id=intval($_GPC['id']);
            $label = pdo_fetchall('select * from '.tablename('forum_label').' order by sort');
            if(checksubmit('sub')){
                $v = idtouserinfo($id,'v');
                if(!empty($v)){
                    $msg='标签更新成功';
                }else{
                    $msg='标签添加成功';
                }
                $res = pdo_update('forum_user',['v'=>$_GPC['v']],['id'=>$id]);
                if($res){
                    message($msg, $this->createWebUrl('user'),'success');
                }
            }elseif (!empty($id)){
                $info = pdo_fetch('select * from '.tablename('forum_pics').' where id=:id',[':id'=>$id]);
            }
        } elseif($op == 'label'){
            $oop = empty($_GPC['oop']) ? 'display' : $_GPC['oop'];
            if($oop == 'display'){
                $label = pdo_fetchAll("select * from ".tablename('forum_label')." order by sort ");
            }elseif ($oop == 'post'){
                $id=intval($_GPC['id']);
                if(checksubmit('sub')){
                    $la=[
                        'label'=>trim($_GPC['label']),
                        'sort'=>$_GPC['sort']
                    ];

                    if(!empty($id)){
                        $res = pdo_update('forum_label',$la,['id'=>$id]);
                        $msg='更新成功';
                    }else{
                        $res = pdo_insert('forum_label',$la);
                        $msg='新增成功';
                    }
                    if($res){
                        message($msg, $this->createWebUrl('user',['op'=>'label']),'success');
                    }
                }elseif (!empty($id)){
                    $history = pdo_fetch('select * from '.tablename('forum_label').' where id=:id',[':id'=>$id]);
                }
            }elseif ($oop == 'del'){
                $id=intval($_GPC['id']);
                $res = pdo_delete('forum_label',['id'=>$id]);
                if($res){
                    message('删除成功', $this->createWebUrl('user',['op'=>'label']),'success');
                }
            }
        } elseif($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_user',['black'=>1],['id'=>$id]);
            if($res){
                message('禁止成功', $this->createWebUrl('user'),'success');
            }
        } else if($op == 'quitdelete'){
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_user',['black'=>0],['id'=>$id]);
            if($res){
                message('取消成功', $this->createWebUrl('user'),'success');
            }
        }

        include $this->template('user');
    }


    //文章收藏管理
    public function doWebColl()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
            if (!empty($_GPC['name'])) {
                $name=$_GPC['name'];
                $where .= " AND u.nickname like '%{$name}%'";
            }
            if (!empty($_GPC['title'])) {
                $title=$_GPC['title'];
                $where .= " AND a.title like '%{$title}%'";
            }
            $list = pdo_fetchAll("select c.id,c.uid,c.lz,c.time,a.title,a.click,u.nickname,u.avatar from ".tablename('forum_coll')." c
            left join ".tablename('forum_article')." a on a.id = c.cid 
            left join ".tablename('forum_user')." u on u.id = c.uid 
            where c.weid=:weid and c.type=:type $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':type'=>1]);
//print_r($list);
            $total = pdo_fetchcolumn('select COUNT(c.id) from '.tablename('forum_coll')." c 
            left join ".tablename('forum_article')." a on a.id = c.cid 
            left join ".tablename('forum_user')." u on u.id = c.uid 
            where c.weid=$weid and c.type=1 $where ");
            $pager = pagination($total, $pindex, $psize);
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_coll',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('coll'),'success');
            }
        }

        include $this->template('coll');
    }

    //文章点赞管理
    public function doWebZan()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
            if (!empty($_GPC['name'])) {
                $name=$_GPC['name'];
                $where .= " AND u.nickname like '%{$name}%'";
            }
            if (!empty($_GPC['title'])) {
                $title=$_GPC['title'];
                $where .= " AND a.title like '%{$title}%'";
            }
            $list = pdo_fetchAll("select c.id,c.uid,c.lz,c.time,a.title,a.click,u.nickname,u.avatar from ".tablename('forum_zan')." c
            left join ".tablename('forum_article')." a on a.id = c.cid 
            left join ".tablename('forum_user')." u on u.id = c.uid 
            where c.weid=:weid and c.type=:type $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':type'=>1]);
//print_r($list);
            $total = pdo_fetchcolumn('select COUNT(c.id) from '.tablename('forum_zan')." c 
            left join ".tablename('forum_article')." a on a.id = c.cid 
            left join ".tablename('forum_user')." u on u.id = c.uid 
            where c.weid=$weid and c.type=1 $where ");
            $pager = pagination($total, $pindex, $psize);
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_zan',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('coll'),'success');
            }
        }

        include $this->template('zan');
    }


    //文章评论管理
    public function doWebcom()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
            if (!empty($_GPC['name'])) {
                $name=$_GPC['name'];
                $where .= " AND u.nickname like '%{$name}%'";
            }
            if (!empty($_GPC['title'])) {
                $title=$_GPC['title'];
                $where .= " AND a.title like '%{$title}%'";
            }
            if (!empty($_GPC['from'])) {
                $from=$_GPC['from'];
                $where .= " AND a.from like '%{$from}%'";
            }
            $list = pdo_fetchAll("select c.id,c.uid,c.cid,c.lz,c.time,a.title,se.title as st,to.title as tt,c.content,a.from,a.click,u.nickname,u.avatar from ".tablename('forum_com')." c
            left join ".tablename('forum_article')." a on a.id = c.cid 
            left join ".tablename('forum_section')." se on se.id = a.sid 
            left join ".tablename('forum_section')." `to` on to.id = a.tid 
            left join ".tablename('forum_user')." u on u.id = c.uid 
            where c.weid=:weid and c.type=:type $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':type'=>1]);
//print_r($list);
            $scan_num = count($list);
            $total = pdo_fetchcolumn('select COUNT(c.id) from '.tablename('forum_com')." c 
            left join ".tablename('forum_article')." a on a.id = c.cid 
            left join ".tablename('forum_section')." se on se.id = a.sid 
            left join ".tablename('forum_section')." `to` on to.id = a.tid 
            left join ".tablename('forum_user')." u on u.id = c.uid 
            where c.weid=$weid and c.type=1 $where ");
            $pager = pagination($total, $pindex, $psize);
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_zan',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('com'),'success');
            }
        }else if($op == 'com_delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_com',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('com'),'success');
            }
        }
        include $this->template('com');
    }

    //文章管理
    public function doWebArticle()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            //页面
            $section = pdo_fetchall('select id,title from '.tablename('forum_section').' where weid=:weid and stype=:sty and del=:del and status=:sta order by sort,time',[':weid'=>$weid,':sty'=>1,':del'=>0,':sta'=>0]);
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where='';
            if (!empty($_GPC['title'])) {
                $title = urldecode($_GPC['title']);
                $where .= " AND a.title like '%{$title}%'";
            }
            if (!empty($_GPC['se'])) {
                $se = $_GPC['se'];
                $where .= " AND se.id = $se";
            }

            $list = pdo_fetchAll("select a.*,se.title as st,t.title as tt from ".tablename('forum_article')." a 
            left join ".tablename('forum_section')." se on se.id = a.sid
            left join ".tablename('forum_section')." t on t.id = a.tid
            where a.weid=:weid and a.del=:del $where order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':del'=>0]);

            $total = pdo_fetchcolumn('select COUNT(a.id) from '.tablename('forum_article')." a 
            left join ".tablename('forum_section')." se on se.id = a.sid
            left join ".tablename('forum_section')." t on t.id = a.tid
            where a.weid=$weid and a.del=0 $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
            $id=intval($_GPC['id']);
            $ss = pdo_fetchall('select id,title,sid from '.tablename('forum_section').' where weid=:weid and del=:del and status=:sta order by sort ',[':weid'=>$weid,':del'=>0,':sta'=>0]);
            if($ss){
                $arr = [];
                foreach ($ss as $v){
                    if($v['sid'] != 0){
                        $p = pdo_fetch('select id,title from '.tablename('forum_section').' where id=:id',[':id'=>$v['sid']]);
                        $arr[$v['sid']]['name'] = $p['title'];
                        $arr[$v['sid']]['data'][$v['id']] = $v['title'];
                    }
                }
            }

            $arr_json = json_encode($arr);

            if(checksubmit('sub')){
                $data=[
                    'weid'=>$weid,
                    'sid'=>$_GPC['se'],
                    'tid'=>$_GPC['to'],
                    'title'=>trim($_GPC['title']),
                    'from'=>trim($_GPC['from']),
                    'content'=>htmlspecialchars_decode(trim($_GPC['content'])),
                    'pic'=>$_GPC['pic'],
                    'status'=>$_GPC['status'],
                    'time'=>TIMESTAMP
                ];

                if(!empty($id)){
                    $res = pdo_update('forum_article',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_article',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('article'),'success');
                }
            }elseif (!empty($id)){
                $info = pdo_fetch('select * from '.tablename('forum_article').' where id=:id',[':id'=>$id]);
            }
        }else if($op == 'see'){//查看用户对此文章打赏，评论，赞，收藏的信息
            $id=intval($_GPC['id']);
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;

            //收藏记录
            $coll = pdo_fetchall("select u.openid,c.id,c.time from ".tablename('forum_coll')." c
            left join ".tablename('forum_user')." u on u.id = c.uid where c.weid=:weid and c.type=:type and c.cid=:cid order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':type'=>1,':cid'=>$id]);

            $total = pdo_fetchcolumn('select COUNT(c.id) from '.tablename('forum_coll')." c
            left join ".tablename('forum_user')." u on u.id = c.uid where c.weid = $weid and c.type = 1 and c.cid = $id ");
            $pager = pagination($total, $pindex, $psize);

            if($coll){
                foreach ($coll as $v){
                    $this->user_info($v['openid']);
                }
            }

            //点赞记录
            $zan = pdo_fetchall("select u.openid,z.id,z.time from ".tablename('forum_zan')." z
            left join ".tablename('forum_user')." u on u.id = z.uid where z.weid=:weid and z.type=:type and z.cid=:cid order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':type'=>1,':cid'=>$id]);

            $total_z = pdo_fetchcolumn('select COUNT(z.id) from '.tablename('forum_zan')." z
            left join ".tablename('forum_user')." u on u.id = z.uid where z.weid = $weid and z.type = 1 and z.cid = $id ");
            $pager_z = pagination($total_z, $pindex, $psize);

            if($zan){
                foreach ($zan as $v){
                    $this->user_info($v['openid']);
                }
            }

            //评论记录
            $com = pdo_fetchall("select u.openid,m.id,m.content,m.time from ".tablename('forum_com')." m
            left join ".tablename('forum_user')." u on u.id = m.uid where m.weid=:weid and m.type=:type and m.cid=:cid order by time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid,':type'=>1,':cid'=>$id]);

            $total_m = pdo_fetchcolumn('select COUNT(m.id) from '.tablename('forum_com')." m
            left join ".tablename('forum_user')." u on u.id = m.uid where m.weid = $weid and m.type = 1 and m.cid = $id ");
            $pager_m = pagination($total_m, $pindex, $psize);

            if($com){
                foreach ($com as $v){
                    $this->user_info($v['openid']);
                }
            }
        }else if($op == 'magnet'){//设置文章相关阅读
            $id=intval($_GPC['id']);
            //该文章信息
            $info = pdo_fetch('select a.title,a.pic,a.content,a.click,a.time,a.`from`,se.title as st,to.title as tt from '.tablename('forum_article').' a 
            left join '.tablename('forum_section').' se on se.id = a.sid left join '.tablename('forum_section').' `to` on to.id = a.tid where a.id=:id',[':id'=>$id]);
            //可设为相关文章的列表
            $arc = pdo_fetchall('select a.id,se.title as st,to.title as tt,a.title,a.pic,a.click,a.time,a.`from` from '.tablename('forum_article').' a 
            left join '.tablename('forum_section').' se on se.id = a.sid
            left join '.tablename('forum_section').' `to` on to.id = a.tid
            where a.weid=:weid and a.del=:del and a.status=:sta and a.id!=:id order by time DESC',[':weid'=>$weid,':id'=>$id,':del'=>0,':sta'=>0]);
//            print_r($arc);
            if(checksubmit('sub')){
                $sel = $_GPC['sel'];
                if($sel){
                    $str = implode(',',$sel);
                    $res =pdo_update('forum_article',['magnet'=>$str],['id'=>$id]);
                    if($res){
                        message('设置成功', $this->createWebUrl('article',['op'=>'magnet','id'=>$id]),'success');
                    }
                }
            }elseif (!empty($id)){
                $sel_info = pdo_fetch('select magnet from '.tablename('forum_article').' where id=:id',[':id'=>$id]);
                $sel_arr = explode(',',$sel_info['magnet']);
            }
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_article',['del'=>1],['id'=>$id]);
            if($res){//同时删除此文章下对应的赞，收藏等记录
//                pdo_delete('forum_coll',['weid'=>$weid,'type'=>1,'cid'=>$id]);
//                pdo_delete('forum_zan',['weid'=>$weid,'type'=>1,'cid'=>$id]);
//                pdo_delete('forum_com',['weid'=>$weid,'type'=>1,'cid'=>$id]);
//                pdo_delete('forum_reward',['weid'=>$weid,'type'=>1,'cid'=>$id]);
                message('删除成功', $this->createWebUrl('article'),'success');
            }
        }else if($op == 'see_del'){
            $id=intval($_GPC['id']);
            if($_GPC['wi'] == 'coll'){
                $res = pdo_delete('forum_coll',['id'=>$id]);
            }elseif ($_GPC['wi'] == 'zan'){
                $res = pdo_delete('forum_zan',['id'=>$id]);
            }elseif ($_GPC['wi'] == 'com'){
                $res = pdo_delete('forum_com',['id'=>$id]);
            }

            if($res){
                message('删除成功', $this->createWebUrl('article'),'success');
            }
        }
        include $this->template('article');
    }


    //图片组管理
    public function doWebrset()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 5;
            $list = pdo_fetchAll("select * from ".tablename('forum_reward_set')." where weid=:weid order by sort LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid]);
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_reward_set')." where weid=$weid");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
            $id=intval($_GPC['id']);
            if(checksubmit('sub')){
                $data=[
                    'weid'=>$weid,
                    'title'=>trim($_GPC['title']),
                    'score'=>trim($_GPC['score']),
                    'sort'=>trim($_GPC['sort']),
                    'status'=>$_GPC['status'],
                    'time'=>TIMESTAMP
                ];

                if(!empty($id)){
                    $res = pdo_update('forum_reward_set',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_reward_set',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('rset'),'success');
                }
            }elseif (!empty($id)){
                $info = pdo_fetch('select * from '.tablename('forum_reward_set').' where id=:id',[':id'=>$id]);
            }
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_reward_set',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('rset'),'success');
            }
        }
        include $this->template('rset');
    }


    //公告
    public function doWebnotice()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

        if ($op=='display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 5;
            $where='';
            if (!empty($_GPC['notice'])) {
                $notice=$_GPC['notice'];
                $where .= " notice like '%{$notice}%'";
            }
            $list = pdo_fetchAll("select * from ".tablename('forum_notice')." $where order by sort,time DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_notice')." $where ");
            $pager = pagination($total, $pindex, $psize);
        }elseif ($op=='post'){
            $id=intval($_GPC['id']);

            if(checksubmit('sub')){
                $data=[
                    'notice'=>trim($_GPC['notice']),
                    'status'=>$_GPC['status'],
                    'sort'=>$_GPC['sort'],
                    'time'=>TIMESTAMP
                ];

                if(!empty($id)){
                    $res = pdo_update('forum_notice',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_notice',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('notice'),'success');
                }
            }elseif (!empty($id)){
                $info = pdo_fetch('select * from '.tablename('forum_notice').' where id=:id',[':id'=>$id]);
            }
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_update('forum_notice',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('notice'),'success');
            }
        }
        include $this->template('gg');
    }


    //首页自定义
    public function doWebindex()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = empty($_GPC['op']) ? 'display' : $_GPC['op'];




        include $this->template('diy');
    }


    //首页自定义按钮管理
    public function doWebindexdiy()
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

            $list = pdo_fetchAll("select * from ".tablename('forum_diybutton')." where weid=:weid $where order by sort,time LIMIT " . ($pindex - 1) * $psize . ',' . $psize,[':weid'=>$weid]);
            $total = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_diybutton')." where weid=$weid $where ");
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
                    $res = pdo_update('forum_diybutton',$data,['id'=>$id]);
                    $msg='更新成功';
                }else{
                    $res = pdo_insert('forum_diybutton',$data);
                    $msg='新增成功';
                }
                if($res){
                    message($msg, $this->createWebUrl('indexdiy'),'success');
                }
            }else{
                $info = pdo_fetch('select * from '.tablename('forum_diybutton').' where id=:id',[':id'=>$id]);
            }
        }else if($op == 'delete'){
            $id=intval($_GPC['id']);
            $res = pdo_delete('forum_diybutton',['id'=>$id]);
            if($res){
                message('删除成功', $this->createWebUrl('indexdiy'),'success');
            }
        }
        include $this->template('diy');
    }
    //***********************************************************************************************

    //首页
    public function doMobileindex()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $op = $_GPC['op'];
        $openid = $_W['openid'];
        $uid = openidtoinfo($openid);
        //自定义按钮
        $diybutton = pdo_fetchall('select title,url,css from '.tablename('forum_diybutton').' where weid=:weid and sel=:sel order by sort,time',[':weid'=>$weid,':sel'=>0]);
//        print_r($diybutton);
        $diybutton_json = json_encode($diybutton);
        //首页图片（轮播，帖子插图，文章插图）
        $pic_b['banner'] = pdo_fetchall('select pics,url from '.tablename('forum_pics').' where weid=:weid and del=:del and status=:sta and `use`=:use order by time DESC',[':weid'=>$weid,':del'=>0,':sta'=>0,':use'=>'b']);
        $pic_a['article'] = pdo_fetch('select pics,url from '.tablename('forum_pics').' where weid=:weid and del=:del and status=:sta and `use`=:use order by time DESC',[':weid'=>$weid,':del'=>0,':sta'=>0,':use'=>'a']);
        $pic_p['post'] = pdo_fetch('select pics,url from '.tablename('forum_pics').' where weid=:weid and del=:del and status=:sta and `use`=:use order by time DESC',[':weid'=>$weid,':del'=>0,':sta'=>0,':use'=>'p']);
        $pics = array_merge($pic_b,$pic_a,$pic_p);
        $pics_json = json_encode($pics);
        //公告
        $notice = pdo_fetchall('select notice from '.tablename('forum_notice').' where status=:status order by sort ASC,time DESC limit 3',[':status'=>0]);
        $notice_json = json_encode($notice);
        //浏览量最大的前4个帖子
        $post = pdo_fetchall('select p.*,u.v,u.nickname,u.avatar from '.tablename('forum_post').' p 
        left join '.tablename('forum_user').' u on u.id = p.uid
        where p.weid=:weid and p.del=:del and p.status=:sta order by p.click DESC,p.time DESC limit 4',[':weid'=>$weid,':del'=>0,':sta'=>0]);
        foreach ($post as &$v){
            $v['time'] = $this->time_tran($v['time']);
            $v['zan'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_zan').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$v['id']]);
            $v['rew'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_reward').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$v['id']]);
            $v['com'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_com').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>2,':cid'=>$v['id']]);
        }

//        print_r($post);
        $post_json = json_encode($post);
        //文章
        $arc = pdo_fetchall('select id,title,click,pic,time from '.tablename('forum_article').' where weid=:weid and del=:del and status=:sta order by click DESC,time DESC limit 4',[':weid'=>$weid,':del'=>0,':sta'=>0]);
        foreach ($arc as &$val){
            $val['time'] = $this->time_tran($val['time']);
            $val['pic'] = $_W['attachurl'].$val['pic'];
            $val['zan'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_zan').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>1,':cid'=>$val['id']]);
            $val['com'] = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_com').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>1,':cid'=>$val['id']]);
        }
        $arc_json = json_encode($arc);

        if($op == 'zan'){
            $type = $_GPC['type'];//1是文章，2是帖子
            $cid = $_GPC['cid'];
            $res = pdo_insert('forum_zan',['weid'=>$weid,'uid'=>$uid,'type'=>$type,'cid'=>$cid,'time'=>TIMESTAMP]);
            if($res){
                message(['error'=>0],'','ajax');
            }
        }
        if($op == 'com'){
            $type = $_GPC['type'];//1是文章，2是帖子
            $cid = $_GPC['cid'];
            $content = $_GPC['content'];
            $towho = $type == '2' ? $cid : 0;
            $res = pdo_insert('forum_com',['weid'=>$weid,'uid'=>$uid,'type'=>$type,'cid'=>$cid,'content'=>$content,'towho'=>$towho,'time'=>TIMESTAMP]);
            if($res){
                message(['error'=>0],'','ajax');
            }
        }
        if($op == 'rew'){
            $type = $_GPC['type'];//1是文章，2是帖子
            $cid = $_GPC['cid'];
            $rid = $_GPC['rid'];
            $res = pdo_insert('forum_reward',['weid'=>$weid,'uid'=>$uid,'type'=>$type,'cid'=>$cid,'rid'=>$rid,'time'=>TIMESTAMP]);
            if($res){
                message(['error'=>0],'','ajax');
            }
        }
        include $this->template('index');
    }

    //个人中心
    public function doMobilemyhome()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $openid = $_W['openid'];
        //个人信息
        $info = pdo_fetch('select id,score,nickname,avatar from '.tablename('forum_user').' where weid=:weid and openid=:oid',[':weid'=>$weid,':oid'=>$openid]);
        //我的发帖

        include $this->template('myhome');
    }


    //文章列表
    public function doMobilelist()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];

        $seto = pdo_fetchall('select * from '.tablename('forum_section').' where weid=:weid and del=:del and status=:sta',[':weid'=>$weid,':del'=>0,':sta'=>0]);
        if($seto){
            foreach ($seto as $v){
                if($v['stype'] == 1){
                    $arr[$v['id']]['sons'] = pdo_fetchall('select id,title from '.tablename('forum_section').' where weid=:weid and del=:del and status=:sta and stype=:sty and sid=:sid order by sort',[':weid'=>$weid,':del'=>0,':sta'=>0,':sty'=>2,':sid'=>$v['id']]);
                    if(empty($arr[$v['id']]['sons'])){
                        unset($arr[$v['id']]);//如果页面（父级）下面没有栏目（子级）
                        continue;//跳出本次循环
                    }else{//遍历每个栏目，然后把对应页面栏目的文章取出
                        foreach ($arr[$v['id']]['sons'] as $key=>$val){
                            $corra = pdo_fetchall("select title,DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d') as time,click,pic from ".tablename('forum_article').' where weid=:weid and del=:del and status=:sta and sid=:sid and tid=:tid order by click DESC,sort limit 4',[':weid'=>$weid,':del'=>0,':sta'=>0,':sid'=>$v['id'],':tid'=>$val['id']]);
                            if($corra){
                                $arr[$v['id']]['sons'][$key]['ac'] = $corra;
                            }
                        }
                    }
                    $arr[$v['id']]['id'] = $v['id'];
                    $arr[$v['id']]['title'] = $v['title'];
                    $arr[$v['id']]['banner'] = $v['banner'];
                }
            }
        }
//        print_r($arr);
        $arr_json = json_encode($arr);
//        $list = pdo_fetchall("select a.title,se.title as st,to.title as tt,DATE_FORMAT(FROM_UNIXTIME(a.time),'%Y-%m-%d') as time,a.pic from ".tablename('forum_article').' a
//        left join '.tablename('forum_section').' se on se.id = a.sid
//        left join '.tablename('forum_section').' `to` on to.id = a.tid
//        where a.weid=:weid and a.del=:del and a.status=:sta and se.del=:del and to.del=:del order by a.sort,a.click DESC,a.time DESC',[':weid'=>$weid,':del'=>0,':sta'=>0]);
//        if($list){
//            foreach ($list as $v){
////                Array
////                (
////                    [title] => 钢铁是怎样炼成的
////                    [st] => 页面1
////                [tt] => 栏目一
////                [time] => 2017-03-18
////    [pic] => images/3/2017/03/A88mdjDXWDdB3NXhd82W14bO2vddND.jpg
////)
//            }
//        }


        print_r($arr);

        include $this->template('list');
    }


    //文章详情
    public function doMobiledetail()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $id = intval($_GPC['id']);//文章id
        if(empty($id)){//如果文章id不存在
            return false;
        }
        pdo_query("update ims_forum_article set click = click + 1 where id = $id");
        $info = pdo_fetch('select id,sid,tid,title,`from`,content,click,time from '.tablename('forum_article').' where id=:id',[':id'=>$id]);
        if($info){
            $zan_num = pdo_fetchcolumn('select COUNT(id) from '.tablename('forum_zan').' where weid=:weid and type=:ty and cid=:cid',[':weid'=>$weid,':ty'=>1,':cid'=>$id]);
            $info['time'] = date('Y-m-d',$info['time']);
            $info['zan'] = $zan_num;
            //最新评论
            $com = pdo_fetchall('select c.id,c.time,c.content,u.nickname,u.avatar from '.tablename('forum_com').' c
            left join '.tablename('forum_user').' u on u.id = c.uid
            where c.weid=:weid and c.type=:ty and c.cid=:cid',[':weid'=>$weid,':ty'=>1,':cid'=>$id]);

            if($com){
                foreach ($com as &$v){
                    $v['time'] = $this->time_tran($v['time']);
                }
            }
            //相关阅读
            $mag = pdo_fetch('select magnet from '.tablename('forum_article').' where id=:id',[':id'=>$id]);
            if($mag['magnet']){
                $magnet = pdo_fetchall("select id,title,DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d') as time from ".tablename('forum_article').' where id in ('.$mag['magnet'].')' );
            }else{//如没有设置，默认推荐浏览量最高的前4个
                $magnet = pdo_fetchall("select id,title,DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d') as time from ".tablename('forum_article').' where weid=:weid and id!=:id and del=:del and status=:sta order by click DESC,time DESC limit 4',[':weid'=>$weid,':id'=>$id,':del'=>0,':sta'=>0]);
            }
//            print_r($info);
//            print_r($com);
            $magnet_json = json_encode($magnet);
            $info_json = json_encode($info);
            $com_json = json_encode($com);
        }




        include $this->template('a_detail');
    }



    function time_tran($show_time){
        $now_time = TIMESTAMP;
        $dur = $now_time - $show_time;
        if($dur < 0){
            return date('m-d H:i',$show_time);
        }else{
            if($dur < 60){
                return $dur.'秒前';
            }else{
                if($dur < 3600){
                    return floor($dur/60).'分钟前';
                }else{
                    if($dur < 86400){
                        return floor($dur/3600).'小时前';
                    }else{
                        if($dur < 259200){//3天内
                            return floor($dur/86400).'天前';
                        }else{
                            return date('m-d H:i',$show_time);;
                        }
                    }
                }
            }
        }
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

