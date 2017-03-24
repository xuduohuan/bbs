<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('post', array('op' => 'display'))?>">管理</a></li>
	<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('post', array('op' => 'post'))?>">添加</a></li>
</ul>
<?php  if($op=='display') { ?>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form id="form_search" action="" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">帖子内容</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="title" type="text" value="<?php  echo $title;?>" placeholder="内容关键字">
				</div>
				<label class="col-md-2 col-lg-1 control-label">所属话题</label>
				<div class="col-md-2 col-lg-2">
					<select class="form-control" style="margin-right:15px;" name="topic" id="topic">
						<option value=""></option>
						<?php  if(is_array($topic)) { foreach($topic as $row) { ?>
						<option value="<?php  echo $row['id'];?>" <?php  if($to==$row['id']) { ?>selected<?php  } ?>><?php  echo $row['title'];?></option>
						<?php  } } ?>
					</select>
				</div>
				<label class="col-md-2 col-lg-1 control-label">发帖用户</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="name" type="text" value="<?php  echo $name;?>" placeholder="用户姓名">
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
		<div class="panel-heading">帖子列表【帖子数量：<?php  echo $post_num;?>】</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:20px;">所属话题</th>
						<th style="width:10px;">发帖用户</th>
						<th style="width:50px;">帖子内容</th>
						<th style="width:10px;">浏览量</th>
						<th style="width:20px;">是否显示</th>
						<th style="width:30px;">发帖时间</th>
						<th style="width:90px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><?php  echo $item['tt'];?></td>
					<td><?php  echo $item['nickname'];?></td>
					<td><?php  echo $item['content'];?></td>
					<td><?php  echo $item['click'];?></td>
					<td><?php  if($item['status'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('post',array('op'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 详情编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('post',array('op'=>'see', 'id'=>$item['id']))?>"><i class="fa fa-bar-chart"></i> 查看用户关注</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('post',array('op'=>'banner', 'id'=>$item['id']))?>"><i class="fa fa-plus-circle"></i> 自定义轮播</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('post',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
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
	<form action="<?php  echo $this->createWebUrl('post',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">所属话题</label>
					<div class="col-md-2 col-lg-2">
						<select class="form-control" style="margin-right:15px;" name="topic" id="topic">
							<?php  if(is_array($topic)) { foreach($topic as $row) { ?>
							<option value="<?php  echo $row['id'];?>" <?php  if($info['tid']==$row['id']) { ?>selected<?php  } ?>><?php  echo $row['title'];?></option>
							<?php  } } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">帖子内容</label>
					<div class="col-md-2 col-lg-7">
						<input type="text" name="content" id="content"  class="form-control" value="<?php  echo $info['content'];?>">
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
<?php  } else if($op=='banner') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('post',array('op'=>'banner'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<?php  if(is_array($arr)) { foreach($arr as $k => $row) { ?>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">自定义轮播<?php  echo $k+1?></label>
					<div class="col-md-2 col-lg-3">
						<?php  echo tpl_form_field_image("ban$k",$row['ban']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">轮播<?php  echo $k+1?>对应链接</label>
					<div class="col-md-2 col-lg-4">
						<input type="text" name="burl<?php  echo $k;?>" id="burl"  class="form-control" value="<?php  echo $row['url'];?>">
					</div>
				</div>
				<?php  } } ?>
				<div class='form-group text-center'>
					<input class="btn btn-primary" type="submit" name="sub">
					<input type="hidden" name="id" value="<?php  echo $id;?>" />
					<input type="hidden" name="num" value="<?php  echo $num;?>" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</div>
	</form>
</div>
<?php  } else if($op=='see') { ?>
<div class="main">
	<div class="panel panel-info">
		<div class="panel-heading topop">收藏记录</div>
		<div class="panel-body ele">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<tbirthdayad class="navbar-inner">
						<tr>
							<th style="width:5px;">序号</th>
							<th style="width:20px;">用户昵称</th>
							<th style="width:20px;">用户头像</th>
							<th style="width:20px;">收藏时间</th>
							<th style="width:50px;">操作</th>
						</tr>
					</tbirthdayad>
					<tbody>
					<?php  if(is_array($coll)) { foreach($coll as $k => $item) { ?>
					<tr>
						<td><?php  echo $k+1?></td>
						<td><?php  echo $item['nickname'];?></td>
						<td><?php  echo $item['avatar'];?></td>
						<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
						<td>
							<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'see_del','wi'=>'coll','id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
						</td>
					</tr>
					<?php  } } ?>
					</tbody>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				</table>
			</div>
			<?php  echo $pager;?>
		</div>
	</div>

	<div class="panel panel-danger">
		<div class="panel-heading topop">点赞记录</div>
		<div class="panel-body ele">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<tbirthdayad class="navbar-inner">
						<tr>
							<th style="width:5px;">序号</th>
							<th style="width:20px;">用户昵称</th>
							<th style="width:20px;">用户头像</th>
							<th style="width:20px;">点赞时间</th>
							<th style="width:50px;">操作</th>
						</tr>
					</tbirthdayad>
					<tbody>
					<?php  if(is_array($zan)) { foreach($zan as $k => $item) { ?>
					<tr>
						<td><?php  echo $k+1?></td>
						<td><?php  echo $item['nickname'];?></td>
						<td><?php  echo $item['avatar'];?></td>
						<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
						<td>
							<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'see_del','wi'=>'zan','id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
						</td>
					</tr>
					<?php  } } ?>
					</tbody>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				</table>
			</div>
			<?php  echo $pager_z;?>
		</div>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading topop">评论记录</div>
		<div class="panel-body ele">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<tbirthdayad class="navbar-inner">
						<tr>
							<th style="width:5px;">序号</th>
							<th style="width:20px;">用户昵称</th>
							<th style="width:20px;">用户头像</th>
							<th style="width:60px;">评论</th>
							<th style="width:20px;">点赞时间</th>
							<th style="width:50px;">操作</th>
						</tr>
					</tbirthdayad>
					<tbody>
					<?php  if(is_array($com)) { foreach($com as $k => $item) { ?>
					<tr>
						<td><?php  echo $k+1?></td>
						<td><?php  echo $item['nickname'];?></td>
						<td><?php  echo $item['avatar'];?></td>
						<td><?php  echo $item['content'];?></td>
						<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
						<td>
							<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'see_del','wi'=>'com','id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
						</td>
					</tr>
					<?php  } } ?>
					</tbody>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				</table>
			</div>
			<?php  echo $pager_m;?>
		</div>
	</div>

	<div class="panel panel-warning topop">
		<div class="panel-heading topop">打赏记录</div>
		<div class="panel-body ele">

		</div>
	</div>
</div>
<?php  } ?>
<script>

    //    var level=<?php  echo json_encode($group)?>;
    //    if($("select[name='se']").val()){
    //        setlevel(parseInt($("select[name='se']").val()-1));
    //    }
    //    $("select[name='se']").change(function(){
    //        setlevel(parseInt($(this).val()-1));
    //    })
    //    function setlevel(i){
    //        var h = "";
    //        $.each(level_arr[i],function(k,v){
    //            h += '<option value="'+v.id+'">'+v.name+'</option>';
    //        })
    //        $("select[name='to']").html(h);
    //    }


    $(".topop").click(function(){
        $(this).next(".ele").slideToggle()
    })

    $("#btn_search").click(function(){
        var title = $("input[name='title']").val();
        var name = $("input[name='name']").val();
        var to = $("select[name='topic']").val();
        var str = '';
        if(title){
            str += "&title="+encodeURI(title);
        }
        if(name){
            str += "&name="+encodeURI(name);
        }
        if(to){
            str += "&to="+to;
        }

        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('post',array('op'=>'display'))?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























