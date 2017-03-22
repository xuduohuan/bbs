<?php
defined('IN_IA') or exit('Access Denied');
class ArticleModule extends WeModule
{
    public $modulename = 'article'; //模块标识
    public $tablename = 'article_reply';
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if (!empty($rid)) {
            $sql_reply = "SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC";
            $reply = pdo_fetch($sql_reply, array(':rid' => $rid));
        }
        load()->func('tpl');
        include $this->template('form');
    }
    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        $weid = intval($_W['weid']);
        $data = array(
            'rid' => $rid,
            'weid' => $weid,
            'title' => trim($_GPC['title']),
            'description' => trim($_GPC['description']),
            'picture' => trim($_GPC['picture']),
            'dateline' => TIMESTAMP
        );
        
        if (strlen($data['title']) > 100) {
            message('活动名称必须小于100个字符！');
        }
        if (strlen($data['title']) == 0) {
            message('请输入名称！');
        }
        if (strlen($data['description']) > 1000) {
            message('活动简介必须小于1000个字符！');
        }
        if (strlen($data['description']) == 0) {
            message('请输入活动简介！');
        }
        if (empty($id)) {
            pdo_insert($this->tablename, $data);
            message('添加成功！', '', 'success');
        } else {
            pdo_update($this->tablename, $data, array('id' => $id));
            message('编辑成功！', '', 'success');
        }
    }
}
