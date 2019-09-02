{js blog|author.js}
{css blog|author.css}
			<!-- grid -->
            <div class="grid">
                
                <div class="grid_1_2">
                    
                    <!-- box -->
                    <div class="box">
                    
                        <h2>{_author_list}</h2>
                        <h3>{_author_edit}:</h3>
                       	<a class="button2 fr clear" href="#" onclick="BlogAuthorNew(); return false;">{_author_new}</a>
                        <ul class="list clear" id="blog-author-list">
                        	{@ $AuthorList as $Author }
                        		<li><a id="blog-author-{$Author['authorid']}" href="#" onclick="BlogAuthorLoad({$Author['authorid']}); return false;">{$Author['name']}</a></li>
                        	{/@}
                        </ul>
                        <a class="button2 fr clear" href="#" onclick="BlogAuthorNew(); return false;">{_author_new}</a>
                    </div>
                    <!-- End box --> 
					
                </div>
                
                <div class="grid_1_2 fr">
                
                    <!-- box -->
                    <div class="box" id="blog-author-optiones">
                    	<h2>{_author_info}</h2>
                       	
						<div ></div>
						
						<div class="inner-box">
							<h3>{_author_image}</h3>
							
							<div id="addon-container"></div>
							
							<br/>
							<h3>{_fullname}</h3>
                            <input class="text" type="text" id="blog-author-name" />
							
							<br/>
							<h3>{_email}</h3>
							<input class="text" type="text" id="blog-email" />
							
							<br/>
							<h3>{_phone}</h3>
							<input type="text" class="text" id="blog-phone"/>
							
							{@lang}
								
								<br/>
								<h3>{_title} ({strtoupper($LangKey)})</h3>
								<input data-lang="{$LangKey}" class="text blog-title"/>
								
								<br/>
								<h3>{_role} ({strtoupper($LangKey)})</h3>
								<input data-lang="{$LangKey}" class="text blog-role"/>
								
							{/@}
							
                        </div>
						
                        <input type="hidden" id="blog-author-authorid" value="0" />
						
                        
                        <a class="button3 fr clear" href="#" onclick="BlogAuthorDelete(); return false;">{_delete}</a>
						<a class="button2 fr" href="#" onclick="BlogAuthorSave(); return false;">{_save}</a>
                        
                        <div id="blog-author-error" class="clear error-report">
						</div>
						
                    </div>
                    <!-- End box -->
                
                </div>

            </div>
            <!-- End grid -->