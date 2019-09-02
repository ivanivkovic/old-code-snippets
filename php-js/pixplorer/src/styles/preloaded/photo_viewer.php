.fb_edge_comment_widget{
	z-index: 99999 !important;
}

#close{
	background-image: url(<?php echo Conf::$src['images'] ?>close.png);
	background-repeat: no-repeat;
	background-position: center center;
	width: 20px;
	height: 20px;
	float: right;
	cursor: pointer;
	margin: 2px 4px 0px 0px;
}


.popup2{
	position: fixed;
	top: 0; 
	left: 0;
	width: 100%;
	height: 100%;
	z-index: 99981;
	text-align: center;
	padding: 0;
}

#viewer_wrapper{
	position: relative;
	top: 50%;
	margin: 0 auto;
}


.l_elem{
	height: 100%;
	min-height: 619px;
}

#viewer_wrapper #left{ 
	float: left; 
	background-color: #111;
	text-align: right;
	height: 100%;
	text-align: center;
	overflow: hidden;
}

#viewer_wrapper #right{ 
	background-color: #ffffff;
	float: left;
	width: 330px;
	min-width: 220px;
	height: 100%;
	text-align: left;
	overflow-y: visible;
	overflow-x: visible;
}

#viewer_wrapper #bottom{
	height: 32px;
	position: absolute;
	bottom: 0;
	background-color: #121212;
	float: left;
	display: none;
	z-index: 99000;
}

#fav #counter{
	margin-left: 3px;
}

#bottom #fav{
	float: right;
	position: relative;
	top: 1px;
	right: 1px;
}

#fav li{
	height: 19px;
}

#fav #favoritesToggle{
	width: 20px;
	height: 15px;
}

#fav a{
	display: inline-block;
	background-repeat: no-repeat;
	cursor: pointer;
	margin: 0;
	float: left;
}

#fav .not_favorite{
	background-image: url(<?php echo Conf::$src['images'] ?>add_to_fav.png);
}

#fav .favorite{
	background-image: url(<?php echo Conf::$src['images'] ?>fav.png); 
}

.bottom_menu_right{
	float: right;
}

.bottom_menu_left{
	float: left;
}

#bottom .bottom_menu{
	margin: 7px;
	padding: 0;
}

.bottom_menu_right li{
	margin: 0px 0px 0px 8px;
}

.bottom_menu_left li{
	margin: 0px 8px 0px 0px;
}

.bottom_menu li{
	list-style: none;
	display: inline;
	padding: 2px 4px;
	float: left;
}

.option_hover a{
	color: white !important;
	text-decoration: underline;
}

.bottom_menu li a{
	color: #9f9f9f;
	font-size: 11px;
	font-weight: bold;
	text-shadow: none !important;
	cursor: pointer;
}

#viewer_wrapper #right{
	font-family: 'tahoma',lucida grande,verdana,arial,sans-serif;
	font-size: 12px;
	color: #666;
}

#left #image_container{
	width: 100%;
	height: 100%;
	position: relative;
}
#left #image{
	max-width: 100%;
	max-height: 100%;
	vertical-align: middle;
	display: inline;
}

#left table, #left tr, #left td, #left tbody{
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	border-spacing: 0;
	display: block;
}

#right #p_profile_pic{
	
}

#move{
	margin: 10px 0px 0px 10px;		
}

.border{
	margin: 10px 10px 10px 0px;
	border-bottom: 1px solid #ccc;
	width: 97%;
	height: 0;
}
.user img{
	width: 32px; 
	heigth: 32px; 	
}
.user .link{
	margin-left: 8px;
	font: 11px Tahoma;
	color: #666;
	letter-spacing: 1.25;
	cursor: pointer;
}

.user a:hover{
	text-decoration: underline;
}

.wiki {
	width: 97%;
}

.wiki span{
	margin-left: 10px;
	margin-top: 9px;
}

.description{
	text-align: left;
	width: 97%;
}

.wiki span, .description{
	font: 13px Tahoma;
	color: #666;
}

.wiki img, .wiki span{
	float: left;
}

.wiki span a{
	cursor: pointer;
}

.wiki span a:hover{
	text-decoration: underline;
}

.description .cleaner span{
	font-size: 11px !important;
	position: relative;
	bottom: 5px;
	word-wrap: break-word;
}

.description a{ font-size: 11px !important; }

/* LIKES */

.likes{
	margin-bottom: 5px;
	z-index: 99999;
	width: 110px;
	height: 209px;
}


.like{
	margin-bottom: 7px;
}


<?php /* COMMENTS */ ?>


.comment{
	margin: 0;
	width: 290px;
}

.my_comment{
	height: 42px;
}

.my_comment .user_pic, .comment .user_pic{
	width: 32px;
	height: 32px;
	display: block;
	background-image: url(<?php echo Conf::$src['images'] ?>no-user_32.png);
	margin: 0 6px 10px 0;
}

.user_pic img{
	width: 100%;
	height: 100%;
}

.comments{
	overflow: hidden;
}

.comment p{
	margin-left: 6px;
	display: inline;
}

.comment .content{
	float: left;
	width: 230px;
}

.my_comment input{
	width: 265px;
	border: 1px solid #ccc;
	height: 20px;
	padding-left: 5px;
}

.my_comment span{
	width: 260px;
}

.content span{
	font-size: 11px !important;
}

.com_close{
	cursor: pointer;
}

.user_link{ font-weight: bold !important; }
.user_link:hover{ text-decoration: underline; }

.login_to_comment{ 
	text-align: center;
	font-size: 13px !important; 
	font-weight: bold;
	padding-top: 5px; 
	position: relative; 
	top: -355px;
}

.login_to_comment a:hover{
	text-decoration: underline;
}

#comment_mask{ 
	height: 225px; 
	background-color: #ffffff; 
	position: relative; 
	top: -225px;
}

<?php /* TINY SCROLLBAR */ ?>
#scrollbar1 { width: 310px; margin: 0px 0px 0 0px; }

.viewport { width: 290px; margin-top: 0px; overflow: hidden; position: relative; }
.overview { list-style: none; position: absolute; left: 0; top: 0; padding: 0; margin: 0; }
.scrollbar{ background: transparent; position: relative; background-position: 0 0; margin-right: -2px; float: right; width: 15px; }
.track { background: transparent; height: 100%; width: 12px; position: relative; }
.thumb { background-color: #ccc; height: 12px; width: 6px; overflow: hidden; position: absolute; left: 6px; border-radius: 100px;}
.disable { display: none; }

<?php /* Arrows in viewer */ ?>

#arrows_container{
	height: 100%;
	position: absolute;
	top: 0;
}

#left_arrow_container, #right_arrow_container{
	position: relative;
	top: 0px;
	width: 120px;
	height: 100%;
	cursor: pointer;
	text-align: center;
	display: none;
}

#left_arrow_container{
	float: left;
}

#right_arrow_container{
	float: right;
}

.arrow_left, .arrow_right{
	width: 30px;
	height: 70px;
	position: relative;
	top: 50%;
	margin-top: -35px;
}

.arrow_left{
	background-image: url(<?php echo Conf::$src['images'] ?>arrow_prev.png);
	background-repeat: no-repeat;
	left: 25%;
}

.arrow_right{
	background-image: url(<?php echo Conf::$src['images'] ?>arrow_next.png);
	background-repeat: no-repeat;
	right: 25%;
}