.popup * {
	font-size: 12px;
}

.popup #search_criteria{
	margin-top: 85px;
}

.popup #edit_form{
	margin-top: 40px;
}

#edit_form textarea{
	width: 400px;
	height: 80px;
	resize: no-resize;	
}

.popup #upload_form{
	margin-top: 40px;
}

#world_select{
	display: inline;
}
.buttons{
	text-align: center;
}
.buttons button, .buttons input{
	display: inline;
	color: #666 !important;
	background-color: white;
	padding: 1px 15px 1px 15px;
	border: 1px solid #9f9f9f;
}

#background{
	position: fixed;
	top: -50px;
	left: -50px;
	width: 150%;
	height: 150%;
	background-color: #000;
	z-index: 99998;
	display: none;
}

.popup{
	position: fixed;
	left: 50%;
	top: 50%;
	background-color: #e7e7e7;
	z-index: 99999;
	-moz-box-shadow: 0 0 80px #000;
	-webkit-box-shadow: 0 0 80px #000;
	box-shadow: 0 0 80px #333;
	display: none;
	border-radius: 4px;
}

#background2{	
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: #222;
	z-index: 99980;
	display: none;
}

.popup > div{
	text-align: center;
	font-size: 14px;
}

.popup select{ width: 140px; margin-right: 6px; }

.popup #upload_form > form > span{
	/*font-weight: bold;*/
	color: #666 !important;
}

#upload_real_container{
	height: 0;
}

#upload_real{
	height: 80px;
	width: 70px;
	position: relative;
	top: -80px;
	cursor: pointer;
	opacity: 0;
}

#upload_fake{
	cursor: pointer;
}

#counter, #errors{
	margin: 10px;
}

.fb_dialog{
	position: absolute;
	z-index: 99982 !important;
}