<?php

# Make sure you sort these alphabetically for better search!

# Site logged-out strings.
$str['headline_txt'] 					= 'Share photos you love or just enjoy exploring. %1$sLogin with Facebook%2$s';
$str['login_to_fav'] 					= 'Login with Facebook to add this photo to favorites';
$str['login_to_com'] 					= 'Login with Facebook to leave a comment';

/* -----------------------------------------------------  Errors -------------------------------------------------- */
# Most common errors.
$str['maintenance']		 				= 'The site is currently updating. You will be redirected in ';
$str['err_default_error']		 		= 'We are experiencing issues with your request. Please refresh your page and try again. </br> </br>If the problem persists, please contact the <a href="mailto:' . DEV_TEAM_MAIL . '">Development Team</a>. Thank you for your patience.';
$str['err_alert_default_error']		 	= 'We are experiencing issues with your request. Please refresh your page and try again. If the problem persists, please contact the Development Team at ' . DEV_TEAM_MAIL . '. Thank you for your patience.';

# Photo uploader errors.
$str['err_try_again'] 					= '%1$sPlease try again.%2$s';

$str['err_category_no_exist'] 			= 'Your input category does not exist.';
$str['err_city_no_exist'] 				= 'Your input city does not exist.';
$str['err_incomplete'] 					= 'Please fill in all the data.';
$str['err_invalid_city'] 				= 'Your input city is invalid.';
$str['err_invalid_category'] 			= 'Your input category is invalid.';
$str['err_no_upl_files'] 				= 'There are no uploaded files to process. ' . $str['err_try_again'];
$str['err_subcat_no_exist'] 			= 'Subcategory does not exist.' . $str['err_try_again'];
$str['err_upload_fail'] 				= 'Upload Fail';
$str['err_wrong_file_format'] 			= 'Your image file format must be jpg, gif, or png.' . $str['err_try_again'];

# Ajax errors
$str['err_could_not_delete'] 			= 'Could not delete the photo.';
$str['err_could_not_upload'] 			= 'Could not post comment.';
$str['err_edit_failed'] 				= 'Changing photo info failed.';
$str['err_picture_not_exist'] 			= 'Photo does not exist.';
$str['err_picture_not_exist2'] 			= 'This photo does not exist or may have been deleted.';
$str['err_could_not_post_comment']		= 'Could not post comment!';
$str['err_pic_does_not_exist']			= 'This photo does not exist.';
$str['err_uploader_not_logged_in'] 		= 'You must be logged in to use the photo uploader.';
$str['err_you_dont_own'] 				= 'You do not own this photo.';
$str['err_you_are_not_logged_in'] 		= 'You are not logged in.';

# Search errors
$str['err_criteria_no_match'] 			= 'There are no results that match your search criteria.';
$str['err_post_criteria'] 				= 'Please post a search criteria.';
$str['err_specify_region'] 				= 'Please specify a region under union or select a country that is not a union.';

# Other errors
$str['err_already_logged_in'] 			= 'You are already logged in.';
$str['err_invalid_page'] 				= 'Invalid page';
$str['err_invalid_user_url']		 	= 'Invalid user URL.';
$str['err_no_upl_images'] 				= 'There are no images uploaded at the time.';
$str['err_page_not_exist'] 				= 'Sorry, the page you\'ve entered does not exist.';
$str['err_this_is_error_page'] 			= 'This is an error page.';
$str['err_you_not_posted_pics'] 		= 'This user hasn\'t posted any photos.';
$str['err_user_not_posted_pics'] 		= 'This user hasn\'t posted any photos.';
$str['err_user_does_not_exist'] 		= 'This user does not exist.';
$str['err_user_not_found']		 		= 'User Not Found';
$str['err_you_no_favorites']		 	= 'You haven\'t added any photos to favorites.';

# Popup strings
$str['are_sure_delete_photo']	 		= 'Are you sure you want to delete this photo?';
$str['are_sure_delete_comment']	 		= 'Are you sure you want to delete this comment?';
$str['popup_choose']	 				= 'Choose ';
$str['popup_choose_category'] 			= 'Choose Category';
$str['popup_choose_country'] 			= 'Choose Country';
$str['popup_choose_city'] 				= 'Choose Place or Nearest Location';
$str['popup_choose_region'] 			= 'Choose Region';
$str['popup_choose_subcategory'] 		= 'Choose Subcategory';
$str['popup_select_photos']	 			= 'Select Photos';
$str['popup_subcategory'] 				= 'Subcategory';
$str['popup_upload_headline']	 		= 'Select images from your computer (max 5mb, .jpg, .gif or .png)';
$str['popup_write_comment']	 			= 'Write a comment...';
$str['popup_you_have_selected']	 		= 'You have selected';

# Photo viewer / options

$str['option_go_to_album']	 			= 'Go to album';
$str['option_download']	 				= 'Download';

# Upload form validation
$str['uplform_file_format']				= 'Sorry, only image types listed above are allowed.';
$str['uplform_no_files']				= 'Please select files for upload.';
$str['uplform_select_category']			= 'Please, select a category.';
$str['uplform_select_subcategory']		= 'Please, select a subcategory.';
$str['uplform_select_location']			= 'Please select full location.';

# Navigation hover titles and texts.
$str['nav_auth_button'] 				= 'Log In / Sign Up';
$str['nav_fb_button'] 					= 'Login with Facebook';
$str['nav_back'] 						= 'Back';
$str['nav_cat'] 						= 'Search by Categories';
$str['nav_home'] 						= 'Home';
$str['nav_logo_home'] 					= SITE_NAME . ' Home';
$str['nav_profile'] 					= 'Profile';
$str['nav_search'] 						= 'Explore';
$str['nav_upl'] 						= 'Upload Photos';
$str['nav_wiki'] 						= 'Wikipedia Article';

# Footer
$str['footer_contact'] 					= 'Contact Us';
$str['footer_privacy'] 					= 'Privacy Policy';
$str['footer_feedback'] 				= 'Feedback';
$str['footer_terms'] 					= 'Terms of Service';


# Basic grammar additionals/conjuctions (in, by, from, and) - (maybe we implement multilingular support so... just so things don't get messy)
$str['conj_by'] 						= 'by';
$str['conj_from'] 						= 'from';
$str['conj_in'] 						= 'in';
$str['conj_my'] 						= 'my';


# Favorites related.
$str['add_to_fav']		 				= 'Add To Favorites';
$str['remove_from_fav']					= 'Remove From Favorites';
$str['user_favorite_photos']			= 'User Favorite Photos';
$str['my_favorite_photos']				= 'My Favorite Photos';
$str['favorite_photos']					= 'Favorite Photos';
$str['photo_favorited_times']			= 'Favorited %1$s times.';
$str['photo_search_wikipedia']			= 'Wikipedia Article';
$str['photo_search_booking']			= 'Search Hotels on Booking.com';

$str['daily_dose']						= '"Your daily dose of photos"';

# Profile menu
$str['profile_my_profile']				= 'My Profile';
$str['profile_my_favorites']			= 'My Favorites';
$str['profile_notifications']			= 'Notifications';
$str['profile_logout']					= 'Logout';

# Notifications
$str['notif_user_commented_on_your_photo'] 	= '<b>%1$s</b> - %2$s';
$str['notif_user_favorited_your_photo'] 	= '%1$s loves your photo%2$s.';

# Page notifications

$str['notifications_comments'] 			= 'Comments';
$str['notifications_favorited_photos'] 	= 'Favorited Photos';
$str['notifications_blog'] 				= SITE_NAME . ' Blog';

# General strings
$str['add_description'] 				= 'Add Description';
$str['albums'] 							= 'Albums';
$str['city']							= 'City';
$str['cities']							= 'Cities';
$str['categories']						= 'Categories';
$str['close'] 							= 'Close';
$str['country']							= 'Country';
$str['delete'] 							= 'Delete';
$str['share'] 							= 'Share on Facebook';
$str['description'] 					= 'Description';
$str['edit'] 							= 'Edit';
$str['explore'] 						= 'Explore';
$str['explore_enter']					= 'Explore the world. Enter.';
$str['favorites']						= 'Favorites';
$str['home'] 							= 'Home';
$str['photos_by']						= 'Photos by';
$str['photos_lower']					= 'photos';
$str['privacy_statement']				= 'Privacy Policy';
$str['region']							= 'Region';
$str['register']						= 'Register';
$str['report_photo']					= 'Report Photo';
$str['search_by_album']					= 'Search by Album';
$str['search_by_categories']			= 'Search by Categories';
$str['search_results']					= 'Search Results';
$str['submit']							= 'Submit';
$str['terms_of_service']				= 'Terms of Service';
$str['upload_success']					= 'Upload Success';

# Site titles

$str['title_notifications'] 			= 'Notifications';
$str['title_home'] 						= 'Home';

$str['pictures_does_not_exist_redirect'] 	= 'This photo does not exist. Go to %1$shome page%2$s. ';
$str['pictures_does_not_exist_redirect2'] 	= 'You will automatically be transfered in %1$s5%2$s seconds.';


$str['upload_result_description'] 			= 'Please add description (max ' . PHOTO_DESCRIPTION_LIMIT . ' characters). Thank You for sharing!';
$str['upload_result_facebook_publish'] 		= '%1$s has uploaded  %2$s pics in %3$s. Go check them out!';
$str['upload_images_chosen'] 				= 'images chosen.';

$str['terms'] = '


<h1>Terms and Conditions</h1>
<p>1. &nbsp;Acceptance The Use Of http://www.pixplorer.net Terms and Conditions</p>
<p>Your  access  to  and  use  of  http://www.pixplorer.net is  subject exclusively to these Terms and Conditions. You will not use the Website for any purpose that is unlawful or prohibited by these Terms and Conditions. By using  the  Website  you  are  fully  accepting  the  terms,  conditions  and disclaimers contained in this notice. If you do not accept these Terms and Conditions you must immediately stop using the Website.</p>

<p>2. &nbsp;Advice</p>
<p>The contents of http://www.pixplorer.net website do not constitute advice and should not be relied upon in making or refraining from making, any decision.</p>

<p>3. &nbsp;Change of Use</p>
<p>http://www.pixplorer.net reserves the right to:<br /> 4.1 &nbsp;change or remove (temporarily or permanently) the Website or any part of it without notice and you confirm that http://www.pixplorer.net shall not be liable to you for any such change or removal and.<br /> 4.2 &nbsp;change these Terms and Conditions at any time, and your continued use of the Website following any changes shall be deemed to be your acceptance of such change.</p>

<p>4. &nbsp;Links to Third Party Websites</p>
<p>http://www.pixplorer.net Website may include links to third party websites that are controlled and maintained by others. Any link to other websites is not an endorsement of such websites and you acknowledge and agree that we are not responsible for the content or availability of any such sites.</p>

<p>5. &nbsp;DoubleClick DART Cookie</p>
<p>.:: Google, as a third party vendor, uses cookies to serve ads on www.pixplorer.net.
.:: Google use of the DART cookie enables it to serve ads to users based on their visit to www.pixplorer.net and other sites on the Internet. 
.:: Users may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy at the following URL - http://www.google.com/privacy_ads.html 

Some of our advertising partners may use cookies and web beacons on our site. Our advertising partners include ....
Google Adsense
Amazon

These third-party ad servers or ad networks use technology to the advertisements and links that appear on www.pixplorer.net send directly to your browsers. They automatically receive your IP address when this occurs. Other technologies ( such as cookies, JavaScript, or Web Beacons ) may also be used by the third-party ad networks to measure the effectiveness of their advertisements and / or to personalize the advertising content that you see. 

www.pixplorer.net has no access to or control over these cookies that are used by third-party advertisers. 

You should consult the respective privacy policies of these third-party ad servers for more detailed information on their practices as well as for instructions about how to opt-out of certain practices. www.pixplorer.net privacy policy does not apply to, and we cannot control the activities of, such other advertisers or web sites. 

If you wish to disable cookies, you may do so through your individual browser options. More detailed information about cookie management with specific web browsers can be found at the browsers respective websites. </p>

<p>6. &nbsp;Copyright </p>
<p>6.1 &nbsp;All  copyright,  trade  marks  and  all  other  intellectual  property  rights  in  the Website and its content (including without limitation the Website design, text, graphics and all software and source codes connected with the Website) are owned by or   licensed to http://www.pixplorer.net or otherwise used by http://www.pixplorer.net as permitted by law.<br /> 6.2 &nbsp;In accessing the Website you agree that you will access the content solely for your personal, non-commercial use. None of the content may be downloaded, copied, reproduced, transmitted, stored, sold or distributed without the prior written consent of the copyright holder. This excludes the downloading, copying and/or printing of pages of the Website for personal, non-commercial home use only.</p>

<p>7. &nbsp;Disclaimers and Limitation of Liability </p>
<p>7.1 &nbsp;The Website is provided on an AS IS and AS AVAILABLE basis without any representation or endorsement made and without warranty of any kind whether express or implied, including but not limited to the implied warranties of satisfactory quality, fitness for a particular purpose, non-infringement, compatibility, security and accuracy.<br /> 7.2 &nbsp;To the extent permitted by law, http://www.pixplorer.net will not be liable for any indirect or consequential loss or damage whatever (including without limitation loss of business, opportunity, data, profits) arising out of or in connection with the use of the Website.<br /> 7.3 &nbsp;http://www.pixplorer.net makes no warranty that the functionality of the Website will be uninterrupted or error free, that defects will be corrected or that the Website or the server that makes it available are free of viruses or anything else which may be harmful or destructive.<br /> 7.4 &nbsp;Nothing in these Terms and Conditions shall be construed so as to exclude or limit the liability of http://www.pixplorer.net for death or personal injury as a result of the negligence of http://www.pixplorer.net or that of its employees or agents.</p>

<p>8. &nbsp;Indemnity</p>
<p>You agree to indemnify and hold http://www.pixplorer.net and its employees and agents harmless from and against all liabilities, legal fees, damages, losses, costs and other expenses in relation to any claims or actions brought against http://www.pixplorer.net arising out of any breach by you of these Terms and Conditions or other liabilities arising out of your use of this Website.</p>

<p>9. &nbsp;Severance</p>
<p>If any of these Terms and Conditions should be determined to be invalid, illegal or unenforceable for any reason by any court of competent jurisdiction then such Term or Condition shall be severed and the remaining Terms and Conditions shall survive and remain in full force and effect and continue to be binding and enforceable.</p>

<p>10. &nbsp;Governing Law</p>
<p>These Terms and Conditions shall be governed by and construed in accordance with the law of Croatia and you hereby submit to the exclusive jurisdiction of the Croatia courts.</p>

For any further information please email <a href=\'mailto:' . SITE_MAIL . '\'>webmaster</a>
';
$str['privacy'] = '<h1>
	Privacy Policy
</h1>

<p>
	Your privacy is very important to us. Accordingly, we have developed this Policy in order for you to understand how we collect, use, communicate and disclose and make use of personal information. The following outlines our privacy policy.
</p>

<ul>
	<li>
		Before or at the time of collecting personal information, we will identify the purposes for which information is being collected.
	</li>
	<li>
		We will collect and use of personal information solely with the objective of fulfilling those purposes specified by us and for other compatible purposes, unless we obtain the consent of the individual concerned or as required by law.		
	</li>
	<li>
		We will only retain personal information as long as necessary for the fulfillment of those purposes. 
	</li>
	<li>
		We will collect personal information by lawful and fair means and, where appropriate, with the knowledge or consent of the individual concerned. 
	</li>
	<li>
		Personal data should be relevant to the purposes for which it is to be used, and, to the extent necessary for those purposes, should be accurate, complete, and up-to-date. 
	</li>
	<li>
		We will protect personal information by reasonable security safeguards against loss or theft, as well as unauthorized access, disclosure, copying, use or modification.
	</li>
	<li>
		We will make readily available to customers information about our policies and practices relating to the management of personal information. 
	</li>
</ul>

<p>
	We are committed to conducting our business in accordance with these principles in order to ensure that the confidentiality of personal information is protected and maintained. 
</p>';

# Meta keywords.
