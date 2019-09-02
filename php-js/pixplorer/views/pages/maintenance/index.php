<?php include(Conf::$dir['widgets']  . 'html_head.php'); ?>
	</head>
	<body id="<?php echo $this -> registry -> router -> page ?>">
	<div 
		style="
		position: absolute; 
		top: 50%; 
		left: 50%;
		margin-top: -325px; 
		margin-left: -300px; 
		width: 600px; 
		height: 650px; 
		text-align: center;"
	>
		<img alt="" 
			style="width: 600px; height: 600px;"
		src="<?php echo Conf::$src['images'] ?>development.png">
		<h1 style="margin-top: -70px;">
			<?php echo $this -> loadString('maintenance'); ?>
			<span id="num_base" style="margin: 0; font-size: inherit">1</span>:<span id="num2_base" style="margin: 0; font-size: inherit"></span><span id="counter" style="margin: 0; font-size: inherit">00</span>
		</h1>

<script>

$(document).ready(function(){
	setInterval(function(){
		var number = parseInt($('#counter').text());
		switch (number){
		
			default:
				if(number < 11){
					$('#num2_base').text(0);
				}
				$('#counter').text(number -1);
			break;
			
			case 0:
				$('#num_base').text(0);
				$('#num2_base').text('');
				$('#counter').text(59);
			break;
			
			case 1:
				window.location = location.href;
			break;
			
		}
	}, 1000);
});

</script>
		
	</div>
<?php
	include(Conf::$dir['widgets'] . 'html_footer.php');
?>