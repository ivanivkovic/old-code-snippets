<script>
$('.popup2, #image, #arrows_container, #close').live('click', function(e){
	closePhotoViewerDynamic();
});

<?php // Used for closing the photo viewer (popup or page) from any part of the script. ?>
function closePhotoViewerDynamic(){
	window.location = "<?php echo Conf::$page['previous_page'] ?>";
}
</script>