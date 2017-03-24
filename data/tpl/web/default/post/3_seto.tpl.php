<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('seto', array('op' => 'display'))?>">管理</a></li>
	<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('seto', array('op' => 'post'))?>">添加话题</a></li>
</ul>
<?php  if($op=='display') { ?>
<!--<div class="panel panel-info">-->
	<!--<div class="panel-heading">筛选</div>-->
	<!--<div class="panel-body">-->
		<!--<form id="form_search" action="" method="post" class="form-horizontal" role="form">-->
			<!--<div class="form-group">-->
				<!--<label class="col-md-2 col-lg-1 control-label">分类</label>-->
				<!--<div class="col-md-2 col-lg-2">-->
					<!--<select class="form-control" style="margin-right:15px;" name="stype">-->
						<!--<option value="1" <?php  if($info['stype']== $_GPC['st']) { ?>selected<?php  } ?>>页面</option>-->
						<!--<option value="2" <?php  if($info['stype']== $_GPC['st']) { ?>selected<?php  } ?>>栏目</option>-->
					<!--</select>-->
				<!--</div>-->
			<!--</div>-->
			<!--<div class="form-group">-->
				<!--<div class="col-md-offset-2 col-lg-offset-1 col-md-2 col-lg-1">-->
					<!--<button id="btn_search" type="submit" class="btn btn-success"><i class="fa fa-search"></i> 搜索</button>-->
					<!--<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />-->
				<!--</div>-->
			<!--</div>-->
		<!--</form>-->
	<!--</div>-->
<!--</div>-->
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">话题列表</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:5px;">标题</th>
						<th style="width:70px;">链接</th>
						<th style="width:5px;">是否显示</th>
						<th style="width:5px;">排序</th>
						<th style="width:30px;">创建时间</th>
						<th style="width:30px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>

				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><?php  echo $item['title'];?></td>
					<td><?php  echo $item['url'];?></td>
					<td><?php  if($item['sel'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo $item['sort'];?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 详情编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
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
	<form action="<?php  echo $this->createWebUrl('seto',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">标题</label>
					<div class="col-md-2 col-lg-2">
						<input type="text" name="title" id="title"  class="form-control" value="<?php  echo $info['title'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">链接</label>
					<div class="col-md-2 col-lg-4">
						<input type="text" name="url" id="url"  class="form-control" value="<?php  echo $info['url'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">是否显示</label>
					<div class="col-md-2 col-lg-1">
						<select class="form-control" style="margin-right:15px;" name="sel">
							<option value="0" <?php  if($info['sel']=='0') { ?>selected<?php  } ?>>显示</option>
							<option value="1" <?php  if($info['sel']=='1') { ?>selected<?php  } ?>>不显示</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">排序</label>
					<div class="col-md-2 col-lg-1">
						<input type="text" name="sort" id="sort"  class="form-control" value="<?php  echo $info['sort'];?>">
					</div>【数字越大越靠后，不填即为0】
				</div>
			</div>

			<div class='form-group text-center'>
				<input class="btn btn-primary" type="submit" name="sub">
				<input type="hidden" name="id" value="<?php  echo $id;?>" />
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>
		</div>
	</form>
</div>
<?php  } ?>
<script>
    $("#btn_search").click(function(){
        var stype = $("select[name='stype']").val();
        var str = '';
        if(stype){
            str += "&st="+stype;
        }

        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('seto',['op'=>'display'])?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























