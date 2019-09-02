{css blog|edit.css}

<form method="post" action="#">
<script>
	var CanEditArray = new Array();
	var CanPublishArray = new Array();
</script>
<!-- box -->
            <div class="box">
			
            	<h2>{_blog_new_edit}</h2>
				
				<div class="inner-box">
					
					<h3>{_author}</h3>
					<select class="filter" style="width: 300px;" name="blog_author" id="blog-edit-author">
						{@ $AuthorList as $Author}
						
						<option 
							{? isset($Data['authorid']) && $Data['authorid'] == $Author['authorid'] }
								{~ $authorFound = 1; }
								selected="selected"
							{/?}
			             
							value="{$Author['authorid']}">{$Author['name']}</option>
						
						{/@}
						
		            </select>
				</div>
					
				<br/>
				
				{? $CanChangeCategory }
					<div class="inner-box">
		                <h3>{_category}</h3>
		                <select class="filter" style="width: 300px;" name="blog_category" id="blog-edit-category">
		                	{@ $CategoryList as $Category }
								{? $Category['can_edit'] || $Category['can_publish'] }
			                		<option 
			                    	{? isset($Data['categoryid']) && $Data['categoryid'] == $Category['categoryid'] }
			                    		selected="selected"
			                    	{/?}
			                    	
			                    	value="{$Category['categoryid']}">{$Category['title']}</option>
			                    	<script>
			                    		CanEditArray[{$Category['categoryid']}] = {$Category['can_edit']};
			                    		CanPublishArray[{$Category['categoryid']}] = {$Category['can_publish']};
			                    	</script>
			                	{/?}
		                 	{/@}
						</select>
		            </div>
				<br/>
				
		        {??}
		        	<input type="hidden" id="blog-edit-category" value="{$Category['categoryid']}" />
		        {/?}
					
					
				{@lang}
					<div class="inner-box">
	                	<h3>{_title} {$LangName}</h3>
	                    <input type="text" class="text" name="texts[title][{$LangKey}]" value="{=$Data['text'][$LangKey]['title']}" />
	                </div>
				{/@}
				
				
				<div class="inner-box">
					<h3>{_head_image}</h3>
					<input class="ffcms-inline-addon" value="images,blog,{=$Data['blogid']},head,crocrop" />
				 </div>
				
				{@lang}
					<div class="inner-box">
	                	<h3>{_intro} {$LangName}</h3>
	                    <textarea name="texts[short_text][{$LangKey}]" >{=$Data['text'][$LangKey]['short_text']}</textarea>
	                </div>
				{/@}
				
				{@lang}
	                <div class="inner-box">
	                	<h3>{_text} {$LangName}</h3>
	                    <textarea class="visual-editor" name="texts[long_text][{$LangKey}]">{=$Data['text'][$LangKey]['long_text']}</textarea>
	                </div>
					<br/>
				{/@}
				
				{@lang}
					<div class="inner-box">
						<h3>{_keywords} {~ echo strtoupper('(' . $LangKey . ')') }</h3>
						<input class="ffcms-inline-addon" value="keywords,blog_{$LangKey},{=$Data['blogid']}"/>
					</div>
					<br/>
				{/@}
				
            </div>
            <!-- End box -->
		
		
			<!-- box -->
            <div class="box auto-collapse" id="image-catalog">
           	<script>
           		InitAddon('images', '#image-catalog', {datafrom: \'blog\', interid: {=$Data['blogid']}, preset: 'picker_thumb_big' });
           	</script>

                
            </div>
            <!-- End box -->
            
           
            
            

            

            
             <!-- box -->
            <div class="box">
            	<h2>{_dates}</h2>
            	
            	<!-- grid -->
                <div class="grid inner-box">
                	
                    <div class="grid_1_2">
                    
                    	<h3>{_pub_date}</h3>
                    	{_time}
                    	<input name="time_start" class="timepicker" type="text" value="{libDateTime::Date('H:i', $Data['publishtime'])}"/>
                        {_date}
                        <input name="date_start" class="datepicker" type="text" value="{libDateTime::Date('d.m.Y.', $Data['publishtime'])}"/>
                        
                    </div>
                    
                    <div class="grid_1_2 fr">
                    	{? $Data['unpublishtime'] != 0 }
                        	<h3>{_end_date}</h3>
                        	{_time}
                        	<input name="time_end" class="timepicker" type="text"  value="{libDateTime::Date('H:i', $Data['unpublishtime'])}" />
                       	 	Datum
                        	<input name="date_end" class="datepicker" type="text"  value="{libDateTime::Date('d.m.Y.', $Data['unpublishtime'])}" />
                        {??}
                        	<h3>{_end_date}</h3>
                        	{_time}
                        	<input name="time_end" class="timepicker" type="text"  value="" />
                       	 	{_date}
                        	<input name="date_end" class="datepicker" type="text"  value="" />
                        {/?}

                    </div>
                    
                </div>
                <!-- End grid -->
            </div>
            <!-- End box -->
			
			<!--div class="box">
			
				<h2>Postavke komentara</h2>
				
				<div class="inner-box comments">
					
					<div class="fl">
						<input type="checkbox"/>&nbsp;Omogući komentare
					</div>
					
					<div class="clear"></div>
					
					<div class="fl">
						<input type="checkbox"/>&nbsp;Omogući facebook komentare
					</div>
					<div class="fl">
						<input type="checkbox"/>&nbsp;Automatski odobri komentare
					</div>
					<div class="fl">
						<input type="checkbox"/>&nbsp;Omogući anonimne komentare
					</div>
					
				</div>
			</div-->
            
            
            <!-- plain box -->
            <div class="box">
                
                <h2>{_action}</h2>

                <a id="blog-edit-save" class="button2 fr submit" href="#blog_save=publis">{_save}</a>

            </div>

</form>
{js blog|edit.js}