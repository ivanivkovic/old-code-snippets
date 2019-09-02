<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Ivan Ivković Test @ Degordian</title>

		<!-- Bootstrap core CSS -->
		<link href="/src/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>

	</head>
	<body>
	
	<div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
			<a href="/" class="navbar-brand">Home</a>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
        </div>
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="battles">Simulate battle <span class="caret"></span></a>
					<ul class="dropdown-menu" aria-labelledby="battles">
						<li><a href="?army1=50&army2=48">Attack 50 vs 48</a></li>
						<li><a href="?army1=100&army2=30">Attack 100 vs 30</a></li>
						<li><a href="?army1=100&army2=60">Attack 100 vs 60</a></li>
						<li><a href="?army1=100&army2=100">Attack 100 vs 100</a></li>
						<li><a href="?army1=100&army2=120">Attack 100 vs 120</a></li>
						<li><a href="?army1=100&army2=150">Attack 100 vs 150</a></li>
						<li><a href="?army1=150&army2=120">Attack 150 vs 120</a></li>
						<li><a href="?army1=200&army2=100">Attack 200 vs 100</a></li>
						<li><a href="?army1=200&army2=200">Attack 200 vs 200</a></li>
						<li><a href="?army1=200&army2=280">Attack 200 vs 280</a></li>
					</ul>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li><a href="?action=clear">Clear game history</a></li>
			</ul>

        </div>
      </div>
    </div>
	
	<div class="container" style="margin-top:70px">
	
	<section class="stats">
		<h5>JSON output:</h5>
		<div class="highlight"><pre><code class="json"><?php libJSON::flushCache() ?></code></pre></div>
	</section>
	
	
	<section id="listout" style="margin-top:50px">
	
	<?php

	foreach( $logsList as $logItem ):
	
		$color = $logItem['winner'] == 0 ? array('green', 'black') : array('black', 'green');
		
	?>
	
	<div class="well listout-item">
		<h2 class="text-center">
			<span style="color:<?php echo $color[0] ?>"><?php echo $logItem['army1Username'] ?> </span>
			vs. 
			<span style="color:<?php echo $color[1] ?>"><?php echo $logItem['army2Username'] ?></span>
		</h2>
		
		<table class="table">
			<thead>
				<th></th>
				<th><?php echo $logItem['army1Username'] ?> Army Units</th>
				<th><?php echo $logItem['army2Username'] ?> Army Units</th>
				<th>Interfeerence</th>
				<th>Interfeerence Victim</th>
				<th>Interfeerence Casualties</th>
			</thead>
			<tbody>
				
				<tr>
					<th>Start</th>
					<td><?php echo $logItem['army1Units'] ?></td>
					<td><?php echo $logItem['army2Units'] ?></td>
					<td></td>
				</tr>
				
				<?php
				
				$duration = 0;
				
				foreach( $logItem['events'] as $key => $event )
				{
					$duration += $event['duration'];
					
					?>
					
					<tr>
						<th><?php echo appCombatEvent::$events[$event['eventCode']]['eventName'] ?></th>
						<td><?php echo $event['army1Units']?></td>
						<td><?php echo $event['army2Units']?></td>
						<td><?php if( $event['interfeerenceCode'] != NULL ): echo appCombatEvent::$interfeerences[$event['interfeerenceCode']]['interfeerenceName']; endif; ?></td>
						<td><?php if( $event['interfeerenceCode'] != NULL ): echo $logItem['army' . ( $event['interfeerenceVictim'] + 1 ) . 'Username']; endif; ?></td>
						<td><?php if( $event['interfeerenceCode'] != NULL ): echo $event['interfeerenceCasualties']; endif; ?></td>
					</tr>
					
					<?php
				}
				?>
		
			</tbody>
		</table>
	
		<h3 class="text-center">Winner: <span style="color:green"><?php echo $logItem['army' . ( $logItem['winner'] + 1 ) . 'Username'] ?> </span></h3>
		<p class="text-right">Total duration: <?php echo gmdate("H:i:s", $duration) ?></p>
	
	</div>
	
	</br>
			
		<?php
	endforeach;

?>
			</section>
		</div>
		
		<script type="text/javascript" src="/src/bootstrap/js/jquery.js"></script>
		<script type="text/javascript" src="/src/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>