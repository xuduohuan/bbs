    <?php
    function auto_load_func($func)
    {
        require_once(IA_ROOT.'/addons/lth/plugin/'.$func.'.func'.'.php');
    }

    function auto_load_class($class)
    {
        require_once(IA_ROOT.'/addons/lth/plugin/'.$class.'.class'.'.php');
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

    //openid to userinfo  默认取出userid(uid)
    function openidtoinfo($openid,$type = 'id')
    {
        global $_W;
        $weid = $_W['uniacid'];
        if(!isset($openid)){
            return false;
        }
        $user=pdo_fetch('select '.$type.' from '.tablename('forum_user').' where weid=:weid and openid=:oid',array(':weid'=>$weid,':oid'=>$openid));
        if($user){
            return $user[$type];
        }else{
            return false;
        }
    }



    function idtouserinfo($userid,$type = 'nickname')
    {
        global $_W;
        if(!isset($userid)){
            return false;
        }

        $user=pdo_fetch('select '.$type.' from '.tablename('forum_user').' where id=:id',array(':id'=>$userid));
        if($user){
            return $user[$type];
        }else{
            return false;
        }
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
