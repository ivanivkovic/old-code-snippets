{js blog|category.js}
{css blog|author.css}
			<!-- grid -->
            <div class="grid">
                
                <div class="grid_1_2">
                    
                    <!-- box -->
                    <div class="box">
                    
                        <h2>{_category_list}</h2>
                        <h3>{_category_edit}:</h3>
                       	<a class="button2 fr clear" href="#" onclick="BlogCategoryNew(); return false;">{_category_new}</a>
                        <ul class="list clear" id="blog-category-list">
                        	{@ $CategoryList as $Category }
                        		<li><a id="blog-category-{$Category['categoryid']}" href="#" onclick="BlogCategoryLoad({$Category['categoryid']}); return false;">{$Category['title']}</a></li>
                        	{/@}
                        </ul>
                        <a class="button2 fr clear" href="#" onclick="BlogCategoryNew(); return false;">{_category_new}</a>
                    </div>
                    <!-- End box --> 
					
                </div>
                
                <div class="grid_1_2 fr">
                
                    <!-- box -->
                    <div class="box" id="blog-category-optiones">
					
                    	<h2>{_category_info}</h2>
                       	
                        <div class="inner-box">
							{@lang}
								<h3>{_title} ({strtoupper($LangKey)}) </h3>
								<input data-lang="{$LangKey}" class="text blog-category-title" type="text" />
							{/@}
                        </div>
						
                        <input type="hidden" id="blog-category-categoryid" value="0" />
                        
                        
                        <a class="button3 fr clear" href="#" onclick="BlogCategoryDelete(); return false;">{_delete}</a>
						<a class="button2 fr" href="#" onclick="BlogCategorySave(); return false;">{_save}</a>
                        
                        
                        
                        <div id="blog-category-error" class="clear error-report">
						</div>
						
                    </div>
                    <!-- End box -->
                
                </div>

            </div>
            <!-- End grid -->