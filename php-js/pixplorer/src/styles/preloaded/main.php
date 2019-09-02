body{
	margin: 0;
	padding: 0;
	text-align: center;
	background-color: #f7f7f7;
	background-image: url(<?php echo Conf::$src['images'] ?>image_background.png);
	background-repeat: repeat;
	overflow-x: hidden;
}

*{
	font-family: "tahoma",lucida grande,verdana,arial,sans-serif;
	font-size: 11px;
	color: #666;
	text-shadow: white 0 1px 0;
}

*::selection, *::-moz-selection {
	background: #e5e5e5;
}

h4, h5, h6{
	font-weight: 300;
	padding: 0;
	margin: 0;
}

img{ border: 0; }

.element{
	/*background-color: white;
	border: 1px solid #dadada;*/
	/*-moz-box-shadow: 0 0 7px #333;
	-webkit-box-shadow: 0 0 7px#333;
	box-shadow: 0 0 7px #333; */
}

a{ text-decoration: none; }

.data{ display: none; }

li{ list-style: none; }

.hidden{
	position: absolute;
	left: -10000px;
	width: 0;
	padding: 0;
	margin: 0;
}

#warning, #warning a{
	font-size: 14px;
}
#warning{
	position: relative;
	top: 85px;
	border: 1px solid #A9A9A9;
	width: 98.5% !important;
	padding: 10px 0px;
	color: #666;
	background-color: #e7e7e7;
	margin: 0 auto;
}

#warning2{
	position: relative;
	top: 290px;
	border: 1px solid #A9A9A9;
	width: 98.5% !important;
	padding: 10px 0px;
	color: #444;
	background-color: #ccc;
	margin: 0 auto;
}

#wrapper{
	clear: both;
	width: 98%;
	position: relative;
	left: 1%;
	top: 90px;
}

.cleaner{
	height: 0px;
	clear: both;
}

.fl{ float: left; }
.fr{ float: right; }

.pic_link{
	text-transform: none;
	cursor: pointer !important; 
}
.pointer{ cursor: pointer !important;  }
.pic_link:hover{ text-transform: none; }

img{ border: 0; }

.spacer10{
	display: block;
	height: 10px;
	clear: both;
}

.spacer20{
	display: block;
	height: 20px;
	clear: both;
}

.spacer30{
	display: block;
	height: 30px;
	clear: both;
}

.no_decoration{ text-decoration: none !important; cursor: text !important; }
.underline{ text-decoration: underline !important; }
.image_link{ cursor: pointer; border: 0; }
.user_pic{ border: none !important; }

.select_disable{
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.display_none{ display: none; }

.display_block( display: block; )

.display_inline( display: inline; )

.display_inline-block( display: inline-block; )

.button{ border: 1px solid #A9A9A9; background-color: white; } 
.button:hover{ border: 1px solid #ff7d51; color:  #ff7d51 !important; cursor: pointer; }