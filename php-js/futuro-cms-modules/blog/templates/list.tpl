<!-- box -->
<div class="box">
            
      <h2>Popis blogova</h2>
       		<a class="button2 fr" href="{FFConf::GetUrl('fly')}#!/blog/edit">{_news_add}</a>         
      
      <div class="inner-box">
	      <select style="width: 200px;" class="filter" id="blog-list-categoryid">
	      	<option value="0">{_category_select}</option>
	      	{@ $CategoryList as $Category }
	      		<option value="{$Category['categoryid']}">{$Category['title']}</option>
	      	{/@}
	      </select>
		  <select style="width: 200px;" class="filter" id="blog-list-authorid">
	      	<option value="0">{_author_select}</option>
	      	{@ $AuthorList as $Author }
	      		<option value="{$Author['authorid']}">{$Author['name']}</option>
	      	{/@}
	      </select>
	  </div>
      
      <div class="table-roundup clear" width="width: 97%;">
			<table width="100%" class="table-new">
	           <thead>
		         <tr>
		             <td width="3%"><a href="#" onclick="BlogListOrderBy('blogid'); return false;">ID</a></td>
		             <td width="24%">{_title}</td>
		             <td width="10%">{_category}</td>
		             <td width="10%">{_author}</td>
		             <td width="10%">Broj komentara</td>
		             <td width="10%"><a href="#" onclick="BlogListOrderBy('publishtime'); return false;">{_pub_date}</a></td>
		             <td width="5%">{_action}</td>
		             <td width="5%">{_status}</td>
		         </tr>
		       </thead>
		       <tbody id="blog-list-table">
					
		       </tbody>
		     </table>
	   </div>
              
              
      <ul class="paginator" id="blog-list-paginator"> </ul>          
</div>
<!-- End box -->

{js blog|list.js}