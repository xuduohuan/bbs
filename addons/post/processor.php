<?php
defined('IN_IA') or exit('Access Denied');
class PostModuleProcessor extends WeModuleProcessor {
    public function isNeedInitContext() {
        return 0;
    }
    public function respond() {
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('wjb_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));

        if($rid){
                $news = array();
                $news[] = array(
                    'title'=>$reply['title'],
                    'description'=>$reply['description'],
                    'picurl'=>!strexists($row['picture'], 'http://') ? $_W['attachurl'] . $row['picture'] : $row['picture'],
                    'url' => $this->createMobileUrl('index')
                    );
                return $this->respNews($news);
        }else{
            return $this->respText('活动已被删除');
        }
    }
    public function isNeedSaveContext() {
        return false;
    }
    
       
}
