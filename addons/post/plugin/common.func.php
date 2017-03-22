    <?php
    function auto_load_func($func)
    {
        require_once(IA_ROOT.'/addons/lth/plugin/'.$func.'.func'.'.php');
    }

    function auto_load_class($class)
    {
        require_once(IA_ROOT.'/addons/lth/plugin/'.$class.'.class'.'.php');
    }


    function time_tran($show_time){
        $now_time = TIMESTAMP;
        $dur = $now_time - $show_time;
        if($dur < 0){
            return date('m-d H:i:s',$show_time);
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
                            return date('m-d H:i:s',$show_time);;
                        }
                    }
                }
            }
        }
    }



    function saveimage($file, $max_file_size = 5000000) {
        global $_W;
        //图片扩展名设置
        $uptypes = array('image/jpg', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/gif', 'image/bmp', 'image/x-png');

        $filename = $file["tmp_name"];
        $pinfo = pathinfo($file["name"]); //array
        $ftype = $pinfo['extension']; //文件扩展名
        if (!is_uploaded_file($file["tmp_name"])) {//是否存在文件
            echo "图片不存在!";
            exit;
        }
        //    $file = $_FILES["image1"];
        if ($max_file_size < $file["size"]) {//检查文件大小
            echo "文件太大!";
            exit;
        }
        if (!in_array($file["type"], $uptypes)) {//检查文件类型
            echo "文件类型不符!" . $file["type"];
            exit;
        }
        $path = "images/{$_W['uniacid']}/" . date('Y/m/');
        $img_folder=ATTACHMENT_ROOT . $path;
        if (!file_exists($img_folder)) {
            mkdir($img_folder);
        }
        $img = $img_folder.random(30).'.'.$ftype;

        if (file_exists($img) && $overwrite != true) {
            echo "同名文件已经存在了";
            exit;
        }
        if (!move_uploaded_file($filename, $img)) {
            echo "移动文件出错";
            exit;
        }
        return $img;
    }


    function  upload_clip($data){
        global $_W,$_GPC;
        load()->func('file');
        if(empty($data)){
            return false;
        }
        $base64_image_content = $data;
        //保存base64字符串为图片
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            //存储路径
            $path = "images/{$_W['uniacid']}/" . date('Y/m/d/');
            //创建文件夹
            mkdirs(ATTACHMENT_ROOT . '/' . $path);
            //创建文件名
            do {
                $imgurl=random(30).".".$type;
            } while(file_exists(ATTACHMENT_ROOT . '/' . $path . $imgurl));

            if (file_put_contents(ATTACHMENT_ROOT . '/' . $path.$imgurl, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                //            message(array('t'=>'success','imgurl'=>$path.$imgurl),'','ajax');
                return $path.$imgurl;
            }else{
                return false;
            }
        }
    }


    //根据openid，算出用户年龄
    function openidtoage($openid){
        global $_W;
        if(!isset($openid)){
            return false;
        }

        $info=pdo_fetch('select id,birthday,birthday_type from '.tablename('lth_vip').' where weid=:weid and openid=:oid',[':weid'=>$_W['uniacid'],':oid'=>$openid]);
        if(empty($info)){
            $info=pdo_fetch('select id,birthday,birthday_type from '.tablename('lth_barber').' where weid=:weid and openid=:oid',[':weid'=>$_W['uniacid'],':oid'=>$openid]);
        }

        if($info['birthday_type']=='新历'){//新历
            $age=age($info['birthday']);
        }elseif($info['birthday_type']=='农历'){//农历
            $arr=explode('-',$info['birthday']);
            $lunar =new Lunar();
            $solar=$lunar->convertLunarToSolar($arr[0],$arr[1],$arr[2]);//农历转公历
            $age=age($solar[0].'-'.$solar[1].'-'.$solar[2]);
        }
        return $age;
    }

//    //对象转数组
//    function object2array($object) {
//        if (is_object($object)) {
//            foreach ($object as $key => $value) {
//                $array[$key] = $value;
//            }
//        }
//        else {
//            $array = $object;
//        }
//
//        return $array;
//    }

    //根据生日算年龄
    function age($birthday){
        list($year,$month,$day) = explode("-",$birthday);
        $year_d = date("Y") - $year;
        $month_d = date("m") - $month;
        $day_d  = date("d") - $day;
        if ($day_d < 0 || $month_d < 0){
            $year_d--;
        }
        return $year_d;
    }

    //openid转成vip表id
    function openidtoid($openid)
    {
        global $_W;
        $weid = $_W['uniacid'];
        if(!isset($openid)){
            return false;
        }
        $user=pdo_fetch('select id from '.tablename('lth_vip').' where weid=:weid and openid=:oid',array(':weid'=>$weid,':oid'=>$openid));
        if($user){
            return $user['id'];
        }else{
            return false;
        }
    }


    function idtoopenid($userid)
    {
        global $_W;
        if(!isset($userid)){
            return false;
        }
        $user=pdo_fetch('select openid from '.tablename('lth_vip').' where id=:id',array(':id'=>$userid));
        if($user){
            return $user['openid'];
        }else{
            return false;
        }
    }

    //寻找客服人员，并平均分配预约订单
    function support_staff()
    {
        global $_W;
        $weid=$_W['uniacid'];
        $staffinfo=pdo_fetchall('select id,openid,name from '.tablename('lth_barber').' where weid=:weid and del=:del and role=:role',array(':weid'=>$weid,':del'=>0,':role'=>'客服'));
        $num=count($staffinfo);//客服人员数量
        $lastbook=pdo_fetch('select * from '.tablename('lth_bookrecord').' where weid=:weid order by `createtime` DESC',array(':weid'=>$weid));

        foreach ($staffinfo as $k=>$v){
            $staff[$k]=$v['id'];
        }
        $key=array_search($lastbook['id'],$staff)+1;
        if($key=$num){
            $key=0;
        }
        return $staffinfo[$key];
    }


    //返回数组维度
    function array_depth($array) {
        if(!is_array($array)) return 0;
        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    //把服务类型的数字转换成汉字（如1,3=>剪，染）
    function sernumtostr($num){
        if(!isset($num)){
            return false;
        }
        $arr = explode(',',$num);
        $num = count($arr);
        for ($i=0;$i<$num;$i++){
            $ti = pdo_fetch('select title from '.tablename('lth_servetype').' where id=:id',array(':id'=>$arr[$i]));
            if($i == $num-1){
                $str .= $ti['title'];
            }else{
                $str .= $ti['title'].',';
            }
        }
        return $str;
    }


    //把服务类别的字符串转化为服务名,$list里必须还有stype字段
    function type_trans($list){
        global $_W;
        $type=pdo_fetchall('select id,title from '.tablename('lth_servetype').' where weid=:weid and del=:del',array(':weid'=>$_W['uniacid'],':del'=>0));
        $di=array_depth($list);//数组维度
        if($di==2){
            //循环展示每个订单所选的服务
            foreach ($list as &$v){
                $v['client']=$v['name'];
                $v['address']=$v['province'].$v['city'].$v['area'].$v['address'];
                foreach ($type as $p){
                    $type_arr[$p['id']]=$p['title'];
                }
                $stype=explode(',',$v['stype']);//预约中的
                $num=count($stype);
                for ($i=0;$i<$num;$i++){
                    $v['serve'][]=$type_arr[$stype[$i]];
                }
            }
        }
        return json_encode($list);
    }


    function check_item($userid,$productid) {
        global $_W;
        $weid=$_W['weid'];
        if(!isset($userid) && !isset($productid)){
            return false;
        }
        $result=pdo_fetch('select b.* from '.tablename('lth_cart').' a left join '.tablename('lth_cartdetail').' b on a.id = b.cartid 
                where a.weid=:weid and a.vid=:vid and b.cid=:cid',array(':weid'=>$weid,':vid'=>$userid,':cid'=>$productid));
        //看该卡片在不在购物车中
        if(empty($result)){
            return false;
        }else{
            return $result['count'];//若找到，则返回该物品数量
        }
    }


    function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //添加新物品        用户id，物品id，数量
    function add_item($userid,$productid,$quantity) {
        global $_W;
        $weid=$_W['weid'];
        $qty = check_item($userid,$productid);//先检查该物品是不是已经在购物车中

        if($qty){
            $quantity += $qty; //若有，则在原有基础上增加数量
            $res=pdo_update('lth_cart',array('count'=>$quantity,'createdate'=>date('Y-m-d H:i:s')),array('weid'=>$weid,'vid'=>$userid,'cid'=>$productid));
        }else{
            $res=pdo_insert('lth_cart',array('weid'=>$weid,'createdate'=>date('Y-m-d H:i:s'),'vid'=>$userid,'cid'=>$productid,'count'=>$quantity));
        }
        if($res){
            return true;
        }
    }

    //删除物品
    function delete_item($userid,$productid) {
        global $_W;
        $weid=$_W['weid'];
        if(!isset($userid) && !isset($productid)){
            return false;
        }
        $res=pdo_delete('lth_cart',array('weid'=>$weid,'vid'=>$userid,'cid'=>$productid));
        if($res){
            return true;
        }
    }

    //清空购物车
    function clear_cart($userid) {
        global $_W;
        $weid=$_W['weid'];
        if(!isset($userid)){
            return false;
        }
        $res=pdo_delete('lth_cart',array('weid'=>$weid,'vid'=>$userid));
        if($res){
            return true;
        }
    }

    //车中物品总价
    function cart_total($userid) {
        global $_W;
        $weid=$_W['weid'];
        if(!isset($userid)){
            return false;
        }

        //先把车中所有物品取出
        $result=pdo_fetchall('select b.id,b.title,b.price,b.fprice,a.count from '.tablename('lth_cart').' a left join '.tablename('lth_cards').' b on a.cid=b.id
                where a.weid=:weid and a.vid=:vid',array(':weid'=>$weid,':vid'=>$userid));

        //如果物品数量>0个，则逐个判断价格并计算
        if(count($result) > 0){
            $openid=idtoopenid($userid);
            foreach ($result as $v){
                $info=pdo_fetch('select og.id from '.tablename('lth_order').' o 
                        left join '.tablename('lth_order_good').' og on og.orderid=o.id
                        where o.weid=:weid and o.openid=:oid and og.goodsid=:goodsid and o.status=:sta',array(':weid'=>$weid,':oid'=>$openid,':goodsid'=>$v['id'],':sta'=>1));

                if($v['fprice'] != 0 && $info['id']){//如果有首次购买的优惠价,同时客户确实是首次购买,那么按优惠价处理
                    $total+=$v['fprice']*$v['count'];
                }else{
                    $total+=$v['price']*$v['count'];
                }
            }
        }
        return $total; //返回总价
    }