<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('seto', array('op' => 'display'))?>">管理</a></li>
	<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>>
	<?php  if($_GPC['ps'] == 's') { ?>
	<a href="<?php  echo $this->createWebUrl('seto', array('op' => 'post','id'=>$id, 'ps'=>'s'))?>">添加栏目（子级）</a>
	<?php  } else if(($_GPC['ps'] == 'c')) { ?>
	<a href="<?php  echo $this->createWebUrl('seto', array('op' => 'post','sid'=>$sid))?>">编辑栏目（子级）</a>
	<?php  } else { ?>
	<?php  if(empty($id)) { ?>
	<a href="<?php  echo $this->createWebUrl('seto', array('op' => 'post'))?>">添加页面（父级）</a>
	<?php  } else { ?>
	<a href="<?php  echo $this->createWebUrl('seto', array('op' => 'post','id'=>$id))?>">编辑页面（父级）</a>
	<?php  } ?>
	<?php  } ?>
	</li>
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
		<div class="panel-heading">页面&栏目列表</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:10px;">序号</th>
						<th style="width:20px;">标题</th>
						<th style="width:20px;">自定义轮播</th>
						<th style="width:20px;">分类</th>
						<th style="width:20px;">是否显示</th>
						<th style="width:30px;">创建时间</th>
						<th style="width:90px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>

				<?php  if(is_array($arr)) { foreach($arr as $item) { ?>
				<tr style="background-color: #a6e1ec">
					<td><?php  echo $item['id'];?></td>
					<td><?php  echo $item['title'];?></td>
					<td><?php  if($item['banner']) { ?><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['banner'];?>" width="60px"><?php  } ?></td>
					<td>页面（父级）</td>
					<td><?php  if($item['status'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 详情编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'url', 'id'=>$item['id']))?>"><i class="fa fa-mail-forward"></i>链接 </a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'post', 'id'=>$item['id'],'ps'=>s))?>"><i class="fa fa-plus-circle"></i> 添加</a>
					</td>
				</tr>
				<?php  if(is_array($item['sons'])) { foreach($item['sons'] as $k => $row) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><?php  echo $row['title'];?></td>
					<td></td>
					<td>栏目</td>
					<td><?php  if($row['status'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$row['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'post', 'id'=>$row['id'],'ps'=>c))?>"><i class="fa fa-edit"></i> 详情编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('seto',array('op'=>'delete', 'id'=>$row['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
					</td>
				</tr>
				<?php  } } ?>
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
				<?php  if(!isset($_GPC['ps'])) { ?>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">自定义轮播</label>
					<div class="col-md-2 col-lg-5">
						<?php  echo tpl_form_field_multi_image('banner',unserialize($info['banner']));?>
					</div>
				</div>
				<?php  } ?>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">是否显示</label>
					<div class="col-md-2 col-lg-1">
						<select class="form-control" style="margin-right:15px;" name="status">
							<option value="0" <?php  if($info['status']=='0') { ?>selected<?php  } ?>>显示</option>
							<option value="1" <?php  if($info['status']=='1') { ?>selected<?php  } ?>>不显示</option>
						</select>
					</div>
				</div>
			</div>

			<div class='form-group text-center'>
				<input class="btn btn-primary" type="submit" name="sub">
				<?php  if($_GPC['ps'] == 's') { ?>
				<input type="hidden" name="id" value="<?php  echo $id;?>" />
				<input type="hidden" name="ps" value="s" />
				<?php  } else if(($_GPC['ps'] == 'c')) { ?>
				<input type="hidden" name="id" value="<?php  echo $id;?>" />
				<input type="hidden" name="ps" value="c" />
				<?php  } else { ?>
				<input type="hidden" name="id" value="<?php  echo $id;?>" />
				<?php  } ?>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>

		</div>
	</form>
</div>
<?php  } else if($op=='url') { ?>
<div class="form-group">
	<label class="col-md-2 col-lg-1 control-label">页面链接</label>
	<div class="col-md-2 col-lg-5">
		<input type="text" class="form-control" value="<?php  echo $url;?>" readonly>
	</div>
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

<script>
    $(document).ready(function(e) {
        try {
            var pages = $("#pages").msDropdown({on:{change:function(data, ui) {
                var val = data.value;
                if(val!="")
                    window.location = val;
            }}}).data("dd");

            var pagename = document.location.pathname.toString();
            pagename = pagename.split("/");
            pages.setIndexByValue(pagename[pagename.length-1]);
            $("#ver").html(msBeautify.version.msDropdown);
        } catch(e) {
            //console.log(e);
        }

        $("#ver").html(msBeautify.version.msDropdown);

        //convert
        $("select").msDropdown({roundedBorder:false});
        createByJson();
    });
    function showValue(h) {
        console.log(h.name, h.value);
    }
</script>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























