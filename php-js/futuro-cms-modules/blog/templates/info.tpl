<form method="post" action="#">

<!-- box -->
            <div class="box">
            	
            	<h2 class="lang-toggle">Info obavijesti</h2>
				
				<a href="#" class="icon plus1" onclick="AddNewInfo(); return false" title="Dodaj novu obavijest"></a> 
				<br class="clear" />
				<div id="info-obavijesti-list" style="margin-bottom: 40px;">
					{@ $InfoList as $Info }
					<div class="info-obavijest">

						<div class="inner-box">
		                	<h3>Obavijest <a href="#" class="icon del fl" title="Obriši obavijest" onclick="DeleteInfo(this); return false;"></a></h3>
		                    <textarea style="width: 500px; height: 60px;" name="info-obavijest[]">{=$Info['text']}</textarea>
	               		</div>
	               		
	               		<div class="inner-box" style="margin-top: -7px;">
							<h3>Datum objave</h3>
                       	 	<input style="width: 150px;" name="info-date[]" class="text datepicker" type="text" value="{libDateTime::Date('d.m.Y.', $Info['pubdate'])}"/>
						</div>
					
						<input type="hidden" name="info-infoid[]" value="{=$Info['infoid']}"/>
	               		
					</div>
					{/@}
					
				</div>
	
            </div>
            <!-- End box -->
		
		
			
            
            <!-- plain box -->
            <div class="plain-box">
                
               
           	<a class="button2 fr submit" href="#info_save=save">Spremi</a>
                
               
            </div>

</form>
{js blog|info.js}			