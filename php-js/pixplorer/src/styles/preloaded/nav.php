.navigation{
	width: 100%;
	padding: 0;
	margin: 0;
	position: fixed;
	z-index: 90000;

}

#navigation{
	background-image: url(<?php echo Conf::$src['images'] ?>header2.png);
	background-repeat: repeat-x;
	height: 125px;
}

#navigation2{
	background-image: url(<?php echo Conf::$src['images'] ?>header.png);
	background-repeat: repeat-x;
	height: 75px;
}

#logo{
	position: absolute;
	top: 6px;
	left: 50%;
	margin-left: -61px;
}

#logo img{
	border: 0;
}

.items{
	margin-left: 9px;
	float: left;
	width: 200px;
	text-align: left;
}

.items a:hover{
	text-decoration: none !important;
}

#nav_right{
	float: right;
	margin-right: 9px;
}

.items ul, #nav_right ul{
	margin: 0;
	padding: 0;
	position: relative;
	top: 9px !important;
	vertical-align: top;
}

.items li{
	margin-right: 6px;
	padding: 0;
	margin-left: 0;
}

#nav_right li{
	margin-left: 8px;
}


#nav_right li , .items li{
	width: 28px;
	cursor: pointer;
	display: inline;
}


#nav_right li a img, .items li a img{
	border: 0;
}

#search_box{
	border: 1px solid #ccc;
	display: inline;
	height: 18px;
	position: relative;
	top: 5px;
	padding-left: 22px;
	font-size: 13px;
	vertical-align: top;
	color: #999;
	width: 150px;
	background-image: url(<?php echo Conf::$src['images'] ?>toolbar_find.png);
	background-repeat: no-repeat;
	background-position: 1.2% 40%;
}

#social_image{
	height: 32px;
	width: 32px;
	vertical-align: top;
	z-index: 99999;
}

#navigation_headline{
	font-size: 14px;
	position: fixed;
	z-index: 90010;
	top: 16px;
	left: 50%;
	margin-left: -250px;
	width: 500px;
	text-align: center;
}

#navigation_headline .selected{
	color: #ff7d51;
	text-shadow: #fff 0 1px -1px;
}

#navigation_headline a{
	color: #777;
	cursor: pointer;
	font-size: inherit;
}

#header_txt{
	margin-top: 21px;
	color: white;
	font-family: 'tahoma',lucida grande,verdana,arial,sans-serif;
	font-size: 16px;
	text-align: center;
	text-shadow: none;
}

#header_txt a{
	color: white;
	font-family: 'tahoma',lucida grande,verdana,arial,sans-serif;
	font-size: 16px;
	text-align: center;
	text-shadow: none;
}

#header_txt a{
	color: white;
	text-decoration: underline;
	text-shadow: none;
}

#profile_icon{
	float: right;
	padding-right: 9px;
	padding-left: 9px;
	margin-right: 10px;
	height: 51px;
}

#profile_icon a{
	margin-top: 9px;
	display: block;
}

.profile_no_hover{
	background-color: transparent;
	border-left: 1px solid transparent;
	border-right: 1px solid transparent;
}

.profile_hover{
	background-color: #f5f5f5;
	border-left: 1px solid #9f9f9f;
	border-right: 1px solid #9f9f9f;
}

#profile_menu{
	position: absolute;
	top: 51px;
	right: 10px;
	min-width: 110px;
	heigth: 59px;
	background-color: #f5f5f5;
	border-bottom: 1px solid #9f9f9f;
	border-left: 1px solid #9f9f9f;
	border-right: 1px solid #9f9f9f;
}

#profile_menu ul{
	padding: 0;
	margin: 10px 0px 10px 0px;
}

#profile_menu li:hover{
	background-color: #eee;
}

#profile_menu ul a{
	font-size: 13px;
}

#profile_menu li{
	padding: 0px 10px 0px 10px;
	margin: 0px 0px 5px 0px;
	text-align: left;
	cursor: pointer;
}

#nFlag{
	position: absolute;
	right: 14px;
	top: 33px;
	background-color: #ff7d51;
	color: white;
	font-weight: bold;
	padding: 1px 3px;
	font-family: 'tahoma',lucida grande,verdana,arial,sans-serif;
	font-size: 10px;
	text-shadow: #777 1px 1px 2px;
}

#profile_menu li a span{
	font-weight: bold;
}