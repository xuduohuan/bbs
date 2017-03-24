<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('com', array('op' => 'display'))?>">管理</a></li>
	<!--<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('coll', array('op' => 'post'))?>">添加</a></li>-->
</ul>
<?php  if($op=='display') { ?>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form id="form_search" action="" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">姓名</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="name" type="text" value="<?php  echo $name;?>" placeholder="用户姓名">
				</div>
				<label class="col-md-2 col-lg-1 control-label">标题</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="title" type="text" value="<?php  echo $title;?>" placeholder="文章标题">
				</div>
				<label class="col-md-2 col-lg-1 control-label">来源</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="from" type="text" value="<?php  echo $from;?>" placeholder="文章来源">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-lg-offset-1 col-md-2 col-lg-1">
					<button id="btn_search" type="submit" class="btn btn-success"><i class="fa fa-search"></i> 搜索</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</form>
	</div>
</div>
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">评论列表【评论量：<?php  echo $scan_num;?>】</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:10px;">用户</th>
						<th style="width:10px;">头像</th>
						<th style="width:40px;">文章标题</th>
						<th style="width:10px;">文章页面</th>
						<th style="width:10px;">所属栏目</th>
						<th style="width:20px;">文章来源</th>
						<th style="width:10px;">浏览量</th>
						<th style="width:40px;">评论内容</th>
						<th style="width:40px;">评论时间</th>
						<th style="width:30px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><?php  echo $item['nickname'];?></td>
					<td><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['pics'];?>" width="60px"></td>
					<td><a href="<?php  echo $this->createWebUrl('article',['op'=>'post','id'=>$item['cid']])?>"><?php  echo $item['title'];?></a></td>
					<td><?php  echo $item['st'];?></td>
					<td><?php  echo $item['tt'];?></td>
					<td><?php  echo $item['from'];?></td>
					<td><?php  echo $item['click'];?></td>
					<td><?php  echo $item['content'];?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('com',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</table>
		</div>
	</div>
	<?php  echo $pager;?>
</div>
<?php  } else if($op=='post') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('pics',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">添加链接</label>
					<div class="col-md-2 col-lg-5">
						<input type="text" name="url" id="url"  class="form-control" value="<?php  echo $info['url'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-lg-1 control-label">照片</label>
					<div class="upload_img col-xs-8" style="line-height:0.5;">
						<?php  echo tpl_form_field_image('pics',$info['pics']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">是否显示</label>
					<div class="col-md-2 col-lg-1">
						<select class="form-control" style="margin-right:15px;" name="status">
							<option value="0" <?php  if($info['status']=='0') { ?>selected<?php  } ?>>显示</option>
							<option value="1" <?php  if($info['status']=='1') { ?>selected<?php  } ?>>不显示</option>
						</select>
					</div>
				</div>
				<div class='form-group text-center'>
					<input class="btn btn-primary" type="submit" name="sub">
					<input type="hidden" name="id" value="<?php  echo $id;?>" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</div>
	</form>
</div>
<?php  } ?>
<script>
    $("#btn_search").click(function(){
        var name = $("input[name='name']").val();
        var title = $("input[name='title']").val();
        var from = $("input[name='from']").val();
        var str = '';
        if(name){
            str += "&name="+name;
        }
        if(title){
            str += "&title="+title;
        }
        if(from){
            str += "&from="+from;
        }
        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('com',array('op'=>'display'))?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























