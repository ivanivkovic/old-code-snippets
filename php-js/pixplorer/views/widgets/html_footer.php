	<?php if(isset($footer) && $footer !== false){ ?>
		<div id="footer">
			<div class="fl">
				<a class="hover_underline" href="mailto:<?php echo DEV_TEAM_MAIL ?>"><?php echo $this -> loadString('footer_feedback') ?></a>
			</div>
			<div class="fr">
				<a class="hover_underline" href="<?php echo Conf::$page['terms'] ?>"><?php echo $this -> loadString('footer_terms') ?></a>
				<a class="hover_underline" href="<?php echo Conf::$page['privacy'] ?>"><?php echo $this -> loadString('footer_privacy') ?></a>
				<a class="hover_underline" href="mailto:<?php echo SITE_MAIL ?>"><?php echo $this -> loadString('footer_contact') ?></a>
			</div>
		</div>
		<?php } ?>
		<?php if(isset($pinterest)){ ?>
			<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
		<?php } ?>
	</body>
</html>