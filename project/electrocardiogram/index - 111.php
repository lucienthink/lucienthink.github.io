<html>
<head>
	<title>canvas</title>
	<meta charset="utf-8">
	<script src="jquery.js" type="text/javascript"></script>
</head>
<body>
<canvas id="ca" width="800" height="500">
</canvas>
<div class="aside">
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
	if($_REQUEST['user']){
?>
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
	<input type="button" id="stop" name="stop" value="Stop">
	<input type="button" id="back" name="back" value="Back">
	<input type="button" id="continue" name="continue" value="Continue">
</div>

<script type="text/javascript">
	var control = 1;
	var canvas = {
		//获取url中指定参数，es为正则表达式
		getarg: function (es) {
			var str=window.location.href;
			es.exec(str);
			r=RegExp.rightContext;
			r=r.split('&')[0]||r.split('#')[0];
			return r;
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
			var len = arr.length;
			Array.prototype.max = function(){   //最大值
			 return Math.max.apply({},this) 
			};
			var max = arr.max();
			for (var i = 0; i < len; i++) {
				arr[i] *= (500/max);
			};
			return arr;
		}
		,draw: function (){
			var canvas = document.getElementById('ca');
			var context = canvas.getContext('2d');
			
			var data = this.getData();
			if(!data) return false;
			var len = data.length;
			var n = 50;
			var eachLen = len>n?n:len;
			var j =0;
			//context.globalCompositeOperation = 'destination-over';

			setInterval(function(){
				context.fillStyle = "#EEEEFF";
				context.fillRect(0,0,800,600);
				context.beginPath();
				context.strokeStyle = '#000';
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
				if(j==0&&control==2) control = 0
			},100)	
		}
		,eventBind: function(){
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
		}
	}
	
	canvas.draw();
	canvas.eventBind();
</script>
</body>
</html>