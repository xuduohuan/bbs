<?php defined('IN_IA') or exit('Access Denied');?><script type="text/javascript" src="/addons/article/template/mobile/js/doT.min.js"></script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('article', array('op' => 'display'))?>">管理</a></li>
	<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('article', array('op' => 'post'))?>">添加</a></li>
</ul>
<?php  if($op=='display') { ?>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form id="form_search" action="" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">文章标题</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="title" type="text" value="<?php  echo $title;?>" placeholder="文章标题">
				</div>
				<label class="col-md-2 col-lg-1 control-label">所属页面</label>
				<div class="col-md-2 col-lg-2">
					<select class="form-control" style="margin-right:15px;" name="se">
						<option value=""></option>
						<?php  if(is_array($section)) { foreach($section as $row) { ?>
						<option value="<?php  echo $row['id'];?>" <?php  if($se == $row['id']) { ?>selected<?php  } ?>><?php  echo $row['title'];?></option>
						<?php  } } ?>
					</select>
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
		<div class="panel-heading">文章列表</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:10px;">序号</th>
						<th style="width:20px;">所属页面</th>
						<th style="width:20px;">所属栏目</th>
						<th style="width:40px;">文章标题</th>
						<th style="width:20px;">列表配图</th>
						<th style="width:20px;">文章来源</th>
						<th style="width:20px;">浏览量</th>
						<th style="width:20px;">是否显示</th>
						<th style="width:30px;">创建时间</th>
						<th style="width:90px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><a href="<?php  echo $this->createWebUrl('seto',array('op'=>'post', 'id'=>$item['sid']))?>"><?php  echo $item['st'];?></a></td>
					<td><?php  echo $item['tt'];?></td>
					<td><?php  echo $item['title'];?></td>
					<td><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['pic'];?>" width="60px"></td>
					<td><?php  echo $item['from'];?></td>
					<td><?php  echo $item['click'];?></td>
					<td><?php  if($item['status'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'see', 'id'=>$item['id']))?>"><i class="fa fa-bar-chart"></i> 查看用户关注</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'magnet', 'id'=>$item['id']))?>"><i class="fa fa-magnet"></i> 设置相关阅读</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('article',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
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
<?php  } else if($op=='magnet') { ?>
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">文章信息</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:20px;">所属页面</th>
						<th style="width:20px;">所属栏目</th>
						<th style="width:40px;">文章标题</th>
						<th style="width:20px;">列表配图</th>
						<th style="width:20px;">文章来源</th>
						<th style="width:20px;">浏览量</th>
						<th style="width:30px;">创建时间</th>
					</tr>
				</tbirthdayad>
				<tbody>

				<tr>
					<td><?php  echo $info['st'];?></td>
					<td><?php  echo $info['tt'];?></td>
					<td><?php  echo $info['title'];?></td>
					<td><img src="<?php  echo $_W['attachurl'];?><?php  echo $info['pic'];?>" width="60px"></td>
					<td><?php  echo $info['from'];?></td>
					<td><?php  echo $info['click'];?></td>
					<td><?php  echo date('Y-m-d H:i',$info['time'])?></td>
				</tr>
				</tbody>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</table>
		</div>
	</div>
</div>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('article',array('op'=>'magnet'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="clear" style="margin-bottom:10px;"></div>
		<div class="panel panel-default">
			<div class="panel-heading">文章列表【选择4篇文章作为相关阅读】</div>
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<tbirthdayad class="navbar-inner">
						<tr>
							<th style="width:5px;"></th>
							<th style="width:5px;">序号</th>
							<th style="width:20px;">所属页面</th>
							<th style="width:20px;">所属栏目</th>
							<th style="width:60px;">文章标题</th>
							<th style="width:10px;">列表配图</th>
							<th style="width:20px;">文章来源</th>
							<th style="width:5px;">浏览量</th>
							<th style="width:40px;">创建时间</th>
						</tr>
					</tbirthdayad>
					<tbody>
					<?php  if(is_array($arc)) { foreach($arc as $k => $info) { ?>
					<tr>
						<td><input name="sel[]" type="checkbox" value="<?php  echo $info['id'];?>" <?php  if(in_array($info['id'],$sel_arr)) { ?>checked="checked"<?php  } ?>/></td>
						<td><?php  echo $k+1?></td>
						<td><?php  echo $info['st'];?></td>
						<td><?php  echo $info['tt'];?></td>
						<td><?php  echo $info['title'];?></td>
						<td><img src="<?php  echo $_W['attachurl'];?><?php  echo $info['pic'];?>" width="60px"></td>
						<td><?php  echo $info['from'];?></td>
						<td><?php  echo $info['click'];?></td>
						<td><?php  echo date('Y-m-d H:i',$info['time'])?></td>
					</tr>
					<?php  } } ?>
					</tbody>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				</table>
			</div>
		</div>
		<div class='form-group text-center'>
			<input class="btn btn-primary" type="submit" name="sub">
			<input type="hidden" name="id" value="<?php  echo $id;?>" />
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</form>
</div>
<?php  } else if($op=='post') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('article',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group flex">
					<label class="col-md-2 col-lg-1 control-label">所属页面</label>
					<div class="col-md-2 col-lg-1 flex_1 flex">
						<select class="form-control level1" style="margin-right:15px;" name="se">

						</select>
					</div>
					<label class="col-md-2 col-lg-1 control-label">所属栏目</label>
					<div class="col-md-2 col-lg-1 flex_1 flex">
						<select class="form-control level2" style="margin-right:15px;" name="to">

						</select>
					</div>
				</div>

				<script type="text/template" charset="utf-8" id='list_template'>
					{{for (var i in it.data){ }}
					<option value="{{=i}}"{{ if(it.val==i){ }}selected{{ } }}>{{=it.data[i]}}</option>
					{{ } }}
				</script>

				<?php  if($id) { ?>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">浏览量</label>
					<div class="col-md-2 col-lg-2">
						<input type="text" name="click" id="click"  class="form-control" value="<?php  echo $info['click'];?>">
					</div>
				</div>
				<?php  } ?>

				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">文章标题</label>
					<div class="col-md-2 col-lg-4">
						<input type="text" name="title" id="title"  class="form-control" value="<?php  echo $info['title'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">文章来源</label>
					<div class="col-md-2 col-lg-4">
						<input type="text" name="from" id="from"  class="form-control" value="<?php  echo $info['from'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">文章内容</label>
					<div class="col-md-2 col-lg-7">
						<textarea style="height:600px;" class="form-control" name="content" id="content" cols="70"><?php  echo $info['content'];?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">列表配图</label>
					<div class="col-md-2 col-lg-7">
						<?php  echo tpl_form_field_image('pic',$info['pic']);?>
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
    $(".topop").click(function(){
        $(this).next(".ele").slideToggle()
    })

    $("#btn_search").click(function(){
        var title = $("input[name='title']").val();
        var se = $("select[name='se']").val();
        var str = '';
        if(title){
            str += "&title="+title;
        }
        if(se){
            str += "&se="+se;
        }
        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('article',array('op'=>'display'))?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }
</script>

<?php  if($op == 'post') { ?>
<script>
    //    var data1 = {
    //        "idA":{name:"一级A",data:{"idA1":"二级A1","idA2":"二级A2","idA3":"二级A3","idA4":"二级A4"}},
    //        "idB":{name:"一级B",data:{"idB1":"二级B1","idB2":"二级B2","idB3":"二级B3","idB4":"二级B4"}},
    //        "idC":{name:"一级C",data:{"idC1":"二级C1","idC2":"二级C2","idC3":"二级C3","idC4":"二级C4"}},
    //        "idD":{name:"一级D",data:{"idD1":"二级D1","idD2":"二级D2","idD3":"二级D3","idD4":"二级D4"}}
    //    };
    var data1 = <?php  echo $arr_json;?>;
    console.log(data1);

    var list1 = {val:'',data:{}};
    var list2 = {val:'',data:{}};
    // var list3 = {val:'',data:{}};
    // var initdata = {level1:"idA",level2:"idA3",level3:""};
    var initdata = {level1:"1",level2:""};
    var evalList = doT.template($("#list_template").text());
    for (var i in data1){
        list1.data[i] = data1[i].name
    }
    list1.val = initdata.level1||'';
    list2.val = initdata.level2||'';
    // list3.val = initdata.level3||'';
    list2.data = data1[initdata.level1].data;
    // list3.data = data2[initdata.level2].data;
    $(".level1").html(evalList(list1));
    $(".level2").html(evalList(list2));
    // $(".level3").html(evalList(list3));
    $(".level1").change(function(){
        list2.data = data1[$(this).val()].data;
        $(".level2").html(evalList(list2));
        // list3.data = data2[Object.keys(list2.data)[0]].data;
        // $(".level3").html(evalList(list3));
    })
    // $(".level2").change(function(){
    // 	list3.data = data2[$(this).val()].data;
    // 	$(".level3").html(evalList(list3));
    // })
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























