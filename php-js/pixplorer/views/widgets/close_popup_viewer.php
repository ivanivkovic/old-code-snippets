<script>
$('.popup2, #arrows_container, #close').live('click', function(e){
	closePhotoViewerDynamic();
});

<?php // Used for closing the popup from any part of the script. ?>
function closePhotoViewerDynamic(){
	closePopupViewer();
}

</script>