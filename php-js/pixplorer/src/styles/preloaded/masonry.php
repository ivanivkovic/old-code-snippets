#index{
	text-align: center;
}

#container{
	margin: 0 auto;
	width: 100%;
	text-align: center;
}

.item h4{
	margin: 5px 0px 12px 0px;
}

.item h4, .item h5, .item h6{
	color: #666;
	font-size: 11px;
	text-align: right;
	word-wrap: break-word;
	width: 230px;
}

.item h6{
	position: absolute;
	bottom: 22px;
	height: 26px;
	width: 220px;
	right: 0px;
	font-size: 11px !important;
}

.item h5{
	position: absolute;
	bottom: 5px;
	right: 0px;
}

.item{
	float: left;
	margin: -3px 16px 35px 16px !important;
	padding: 0;
	width: 275px;
}

.item_stats span{
	font-size: 14px;
	font-weight: normal;
	text-shadow: 1px 1px 1px #333;
	color: white;
	margin: 6px 4px 0px 4px;
	display: block;
}

.item_stats_right span, .item_stats_right img{
	float: right;
}

.item_stats_left span, .item_stats_left img{
	float: left;
}

.item .item_stats_right{
	text-align: right;
	width: 100px;
	height: 20px;
	position: absolute;
	right: 10px;
	margin-top: 0px;
	display: none;
	z-index: 120;
}

.item .item_stats_left{
	text-align: left;
	width: 100px;
	height: 20px;
	position: absolute;
	left: 10px;
	margin-top: 0px;
	display: none;
	z-index: 120;
}

.item .item_dark_box{
	background-color: black;
	width: 100%;
	height: 50px;
	position: absolute;
	margin: 0;
	display: none;
	z-index: 110;
}

.item .item_link{
	border-bottom: 1px solid #A9A9A9;
	padding: 0;
	margin: 0;
	width: 275px;
	height: 275px;
	z-index: 100;
}

.item_link .img:hover{
	-moz-box-shadow: 0 0 5px #555;
	-webkit-box-shadow: 0 0 5px #666;
	box-shadow: 0 0 5px #666;
}

.item_link .img{
	margin-bottom: -1px;
	width: 275px;
	height: 275px;
	margin: 0;
	padding: 0;
	-moz-box-shadow: 0 0 5px #999;
	-webkit-box-shadow: 0 0 5px #999;
	box-shadow: 0 0 5px #999;
}

.bottom_part{
	border-bottom: 1px solid #A9A9A9;
	background-color: transparent;
}

.item .user_pic{
	margin: 5px 0px 5px 0px;
	width: 40px;
	height: 40px;
	display: block;
	background-image: url(<?php echo Conf::$src['images'] ?>no-user_38.png);
}

.item .user_pic img{
	width: 100%;
	height: 100%;
}

.item:hover{
}

.masonry,
.masonry .masonry-brick {
  -webkit-transition-duration: 0.3s;
     -moz-transition-duration: 0.3s;
      -ms-transition-duration: 0.3s;
       -o-transition-duration: 0.3s;
          transition-duration: 0.3s;
}

.masonry {
  -webkit-transition-property: width, height;
     -moz-transition-property: width, height;
      -ms-transition-property: width, height;
       -o-transition-property: width, height;
          transition-property: width, height;
}

.masonry .masonry-brick {
  -webkit-transition-property: left, right, top;
     -moz-transition-property: left, right, top;
      -ms-transition-property: left, right, top;
       -o-transition-property: left, right, top;
          transition-property: left, right, top;
}


.p_pic{
	float: left;
	width: 20px;
}

.item .item_link, .item .user_pic{
	display: block;
}

.profile_picture{
	width: 160px;
	max-height: 160px;
}

.item_link{ cursor: pointer; background-image: url(<?php echo Conf::$src['images'] ?>image_background.png); }

#profileBox .item_link{ cursor: default !important; }
#profileBox img{ cursor: default; }
#profileBox #profileImage{ cursor: pointer !important; }

.darkbox{
	background-color: #ff7d51;
	height: 86px;
	text-align: left;
	margin-bottom: 1px;
	/*position: relative;
	top: -16px;*/
	-moz-box-shadow: 0 0 5px #999;
	-webkit-box-shadow: 0 0 5px #999;
	box-shadow: 0 0 5px #999;
}

.darkbox h1{
	margin: 0;
	padding: 0;
	position: relative;
	top: 62px;
	left: 10px;
	font-weight: normal;
	font-size: 14px;
	color: white;
	text-shadow: 1px 1px 3px #333;
}

.notification:hover{
	background-color: #f5f5f5;
	cursor: pointer;
	opacity: 1;
}

.notification.new{
	opacity: 1;
}

.notification p{
	float: left;
	margin: 4px 0px 0px 6px;
	width: 160px;
	text-align: left;
}

.notification_thumb{
	height: 40px;
	width: 64px;
	display: inline-block;
	margin: 5px 0px 5px 0px;
}

.notification{
	border-color: #dfdfdf;
	opacity: 0.4;
}