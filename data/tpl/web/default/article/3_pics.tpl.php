<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('pics', array('op' => 'display'))?>">管理</a></li>
	<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('pics', array('op' => 'post'))?>">添加</a></li>
</ul>
<?php  if($op=='display') { ?>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form id="form_search" action="" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">姓名</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="name" type="text" value="<?php  echo $name;?>" placeholder="姓名">
				</div>
				<label class="col-md-2 col-lg-1 control-label">专属码</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="onlycode" type="text" value="<?php  echo $onlycode;?>" placeholder="专属码">
				</div>
				<label class="col-md-2 col-lg-1 control-label">手机号</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="tel" type="text" value="<?php  echo $tel;?>" placeholder="手机号">
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
		<div class="panel-heading">图片组列表</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;"><input type="button" value="反选" style="color: #606060;background:#ffffff;border: solid 1px #b7b7b7;margin-left: -15px" onclick="javascript:CheckedRev();" /></th>
						<th style="width:5px;">序号</th>
						<th style="width:30px;">图片</th>
						<th style="width:60px;">链接</th>
						<th style="width:30px;">当前使用</th>
						<th style="width:20px;">是否显示</th>
						<th style="width:40px;">创建时间</th>
						<th style="width:100px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><input name="sel[]" type="checkbox" value="<?php  echo $item['id'];?>" /></td>
					<td><?php  echo $k+1?></td>
					<td><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['pics'];?>" width="60px"></td>
					<td><?php  if($item['url']) { ?><a href="<?php  echo $item['url'];?>"><?php  echo $item['url'];?></a><?php  } else { ?>无<?php  } ?></td>
					<td><?php  if($item['use']=='0') { ?>无<?php  } else if(($item['use']=='b')) { ?>自定义轮播<?php  } else if(($item['use']=='p')) { ?>帖子自定义插图<?php  } else if(($item['use']=='a')) { ?>文章自定义插图<?php  } ?></td>
					<td><?php  if($item['status'] == 0) { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('pics',array('op'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 编辑</a>
						<?php  if($item['use'] != '0') { ?><a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('pics',array('op'=>'quit', 'id'=>$item['id']))?>"><i class="fa fa-circle-o"></i> 取消首页展示</a><?php  } ?>						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('pics',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
				<tr>
					<td colspan="7">
						<input type="button" class="btn btn-primary" name="deleteall" onclick="deleteall()" value="批量删除" />
					</td>
				</tr>
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
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">首页展示类型</label>
					<div class="col-md-2 col-lg-2">
						<select class="form-control" style="margin-right:15px;" name="use">
							<option value="b" <?php  if($info['use']=='b') { ?>selected<?php  } ?>>自定义轮播</option>
							<option value="p" <?php  if($info['use']=='p') { ?>selected<?php  } ?>>帖子-自定义插图</option>
							<option value="a" <?php  if($info['use']=='a') { ?>selected<?php  } ?>>文章-自定义插图</option>
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
    function CheckedRev(){
        var arr = $(':checkbox');
        for(var i=0;i<arr.length;i++){
            arr[i].checked = ! arr[i].checked;
        }
    }

    $("#btn_search").click(function(){
        var name = $("input[name='name']").val();
        var onlycode = $("input[name='onlycode']").val();
        var tel = $("input[name='tel']").val();
        var str = '';
        if(name){
            str += "&name="+name;
        }
        if(onlycode){
            str += "&code="+onlycode;
        }
        if(tel){
            str += "&tel="+tel;
        }
        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('pics',array('op'=>'display'))?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }

    function deleteall() {
        var arr_v = new Array();
        $("input[name='sel[]']:checked").each(function(){
            arr_v.push($(this).val());
        });
        var check = arr_v.join(',');
        if (check .length == 0){
            alert("请选择要删除的记录!");return false;
        } else{
            if( confirm("确认要删除选择的记录?")){
                $.post("<?php  echo $this->createWebUrl('pics',array('op'=>'deleteall'))?>", {ids:check},function(data){
                    if (data.message.error == 0)
                    {
                        alert(data.message.msg);
                        location.reload();
                    } else {
                        alert(data.message.msg);
                    }
                },'json');
            }
        }
    };
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























