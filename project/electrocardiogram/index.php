<?php
session_start();
error_reporting(E_ALL & ~ E_NOTICE);
?>
<html>
<head>
	<title>心电监护系统</title>
	<meta charset="utf-8">
	<script src="./js/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link type="text/css" href="css/main.css" rel="stylesheet" />
</head>
<body>
<?php
	if($_REQUEST['username'] == 'root' && $_REQUEST['password'] == 'xindianjianhu'){
		$_SESSION['login'] = true;
	}
	elseif($_REQUEST['logout'] == 'sure'){
		$_SESSION['login'] = false;
	}
	if(!$_SESSION['login']){
?>
<div class="container-fluid">
	<div class="row span12">
		<div class="span3 offset4 hero-unit">
	      <h1>心电监护系统</h1>
	    </div>	    
	    <div class="span4 offset3">
	      <form action="index.php" method="post" class="form-horizontal">
	        <div class="control-group">
	          <label class="control-label" for="username">账号:</label>
	          <div class="controls">
	            <input class="span3" type="text" id="username" name="username" placeholder="Account">
	          </div>
	        </div>
	        <div class="control-group">
	          <label class="control-label" for="password">密码:</label>
	          <div class="controls">
	            <input class="span3" type="password" id="password" name="password" placeholder="Password">
	          </div>
	        </div>
	        <div class="control-group span4 offset1">
	            <input type="submit" id="login" class="btn btn-large btn-success span4" value="登录">
	        </div>
	      </form>
	    </div>
	</div>
    
</div>
<?php
	}
	else{
		
?>
<div class="container">
	<div class="top">
		<div class="logo"><a href="http://www.xidian.edu.cn"><img src="img/xidian.jpg" width="283" height="54" /></a></div>
	</div>
	

<canvas id="ca" width="800" height="320">
</canvas>
<div class="aside">
	<span>选择用户：</span>
	<select id='user'>
		<option>/</option>
<?php
	$rec = array();
   	$dir = './data/';
   	if(is_dir($dir))
   	{
    	if($dh = opendir($dir))
        {
	        while(($file = readdir($dh)) != false)
	        {
	            if(!preg_match("/\./",$file))
	            {
	                array_push($rec, "$file");
	            }
	        }
	        rsort($rec);
	        if($_REQUEST['user']){
	        	foreach($rec as $element)
		        {	
		        	if($_REQUEST['user'] == $element){
		        		echo '<option value="'.$element.'" selected>'.$element.'</option>';
		        	}else{
		        		echo '<option value="'.$element.'">'.$element.'</option>';
		        	}
		        }
	        }else{
	        	foreach($rec as $element)
		        {
		            echo '<option value="'.$element.'">'.$element.'</option>';
		        }
	        }
        
        }
  	}
?>
	</select>
<?php
	error_reporting(E_ALL & ~ E_NOTICE);
	if($_REQUEST['user']){
?>
	<span>选择数据：</span>
	<select id='data'>
		<option>/</option>	
<?php
	$rec_data = array();
   	$dir_data = './data/'.$_REQUEST['user'].'/';
   	if(is_dir($dir_data))
   	{
    	if($dh_data = opendir($dir_data))
        {
	        while(($file_data = readdir($dh_data)) != false)
	        {
	            if(preg_match("/\.txt/",$file_data))
	           {
	                array_push($rec_data, "$file_data");
	            }
	        }
	        rsort($rec_data);
	        if($_REQUEST['data']){
	        	foreach($rec_data as $element_data)
		        {	
		        	if($_REQUEST['data'] == $element_data){
		        		echo '<option value="'.$element_data.'" selected>'.$element_data.'</option>';
		        	}else{
		        		echo '<option value="'.$element_data.'">'.$element_data.'</option>';
		        	}
		        }
	        }else{
	        	foreach($rec_data as $element_data)
		        {
		            echo '<option value="'.$element_data.'">'.$element_data.'</option>';
		        }
	        }
        
        }
  	}
?>
	</select>
<?php
	}
?>
</div>
<div class="control">
	<span>控制：</span>
	<input type="button" id="stop" name="stop" value="暂停">
	<input type="button" id="back" name="back" value="回放">
	<input type="button" id="continue" name="continue" value="继续">
</div>
<div class="spped">
	<span>速度：</span>
	<input type="button" id="ss" name="ss" value="1/4倍">
	<input type="button" id="s" name="s" value="1/2倍">
	<input type="button" id="m" name="m" value="正常">
	<input type="button" id="l" name="l" value="2倍">
	<input type="button" id="xl" name="xl" value="4倍">
	<input type="button" id="xxl" name="xxl" value="10倍">
</div>
<div class="logout">
	<form action="index.php" method="post">
		<input type="hidden" name="logout" value="sure">
		<input type="submit" value="退出">
	</form>
</div>
<script type="text/javascript">
	var control = 1;
	var speed = 1;
	var canvas = {
		//获取url中指定参数，es为正则表达式
		getarg: function (es) {
			var str=window.location.href;
			es.exec(str);
			r=RegExp.rightContext;
			r=r.split('&')[0]||r.split('#')[0];
			return r;
		}
		//定时器
		,interval: function (fn,time){
			setTimeout(function(){
				fn();
				setTimeout(arguments.callee, time/speed);
			}, time)
		}
		,getData: function(){	
			var user = this.getarg(/user=/) 
			, data = this.getarg(/data=/) ;
			if(!(user&&data)) return false;
			var arr = [];
			$.ajaxSetup({async: false});
			$.get('data/'+user+'/'+data,function(back){
				arr = back.split(',');
			});
			/*var len = arr.length;
			Array.prototype.max = function(){   //最大值
			 return Math.max.apply({},this) 
			};
			var max = arr.max();
			for (var i = 0; i < len; i++) {
				arr[i] *= (500/max);
			};
			*/
			var Height = 320;
			var len = arr.length;
			for (var i = 0; i < len; i++) {
				arr[i] = Height-arr[i]+1;
			};
			return arr;
		}
		,draw: function (){
			var canvas = document.getElementById('ca');
			var context = canvas.getContext('2d');
			var data = this.getData();
			if(!data) return false;
			var len = data.length;
			var n = 300;
			var eachLen = len>n?n:len;
			var j =0;
			//context.globalCompositeOperation = 'destination-over';

			function foo(){
				context.fillStyle = "#000000";
				context.fillRect(0,0,800,600);
				context.beginPath();
				context.strokeStyle = '#00FF00';
				var x = 500/eachLen;
				for (var i = 1+j; i < eachLen+j; i++) {
					context.moveTo(x,data[i-1])
					x += 800/eachLen;
					context.lineTo(x,data[i]);
				};
				context.closePath();
				context.stroke();
				switch(control){
					case 1 :
						j++;
						break; //正常
					case 0 :
						break; //暂停
					case 2 :
						j--;
						break;	//回放
				}
				if(j>len) j = 0;
				if(j==0&&control==2) control = 0;
			}
			this.interval(foo,100);
		}
		,eventBind: function(){
			that = this;
			$('#user').bind('change',function(){
				window.location.href = 'index.php?user='+$('#user').val();
			})
			$('#data').bind('change',function(){
				window.location.href = 'index.php?user='+$('#user').val()+'&data='+$('#data').val();
			})
			$('#stop').bind('click',function(){
				control = 0;
			})
			$('#back').bind('click',function(){
				control = 2;
			})
			$('#continue').bind('click',function(){
				control = 1;
			})
			$('#ss').bind('click',function(){
				speed = 0.25;
			})
			$('#s').bind('click',function(){
				speed = 0.5;
			})
			$('#m').bind('click',function(){
				speed = 1;
			})
			$('#l').bind('click',function(){
				speed = 2;
			})
			$('#xl').bind('click',function(){
				speed = 4;
			})
			$('#xxl').bind('click',function(){
				speed = 10;
			})
		}
	}
	
	canvas.draw();
	canvas.eventBind();

</script>
</div>
<?php
	}
?>
</body>
</html>