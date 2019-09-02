<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
	</head>
	<body>
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		<div class="row-fluid">
			
			<div class="span3">
				<p class="datep"><a class="btn btn-inverse btn-block" href="#"><strong id="datepicker_display"><?= libDateTime::$days[self::$lang]['today'] ?></strong></a></p>
				<? include(Conf::DIR_INCLUDES . 'leftmenu.php'); ?>
			</div>
			<div class="span9">
            	<div class="well">
					<h4>Novosti
						<a class="btn font-normal pull-right form-toggle" href="#" data-id="form-new-announcement" data-text="Dodaj objavu" data-active="Zatvori formu">
							<i class="icon-plus-sign"></i>
						Dodaj objavu</a>
					</h4>
					
					<? include('app/widgets/forms/announcement-new.php'); ?>
					
					<br/>
					<div id="pagination-content"></div>
					<div class="pagination margin-bottom-5">
						<ul id="pagination"></ul>
					</div>
				</div>
			</div>
		</div>
<?

$footer = '
<script>

function Run(filter, date)
{
	$("#pagination").pagination(
	{
		dataType : "libSystemNews",
		filters : { filter : filter, date : date },
		perPage : 15
	});
}

$(document).ready(function()
{
	Run( $(".filter.active").attr("data-id"), $("#datepicker_display").text() );
	
	$( ".filter.active" ).parent().find(".filter").click(function()
	{
		Run( $(".filter.active").attr("data-id"), $("#datepicker_display").text() ); return false;
	});
	
	/* Datepicker */
	
	$(".datep").change(function()
	{
		setTimeout(function()
		{
			Run( $( ".filter.active" ).attr("data-id"), $("#datepicker_display").text() );
		},
			200
		);
	});
	
	$(".datep").datepicker({ language: "' . $lang . '",format: "dd. MM yyyy." })
		.on("changeDate", function(ev){
			startDate = new Date(ev.date);
			$("#datepicker_display").text($(".datep").data("date"));
			$(".datep").datepicker("hide");
			$(".datep").change();
		}
	);
});
</script>';

include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>