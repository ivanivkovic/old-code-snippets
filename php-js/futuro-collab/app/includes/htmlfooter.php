            <hr />
            <footer>
                <p class="muted margin-bottom-20"><small>&copy; Futuro Internet Studio 2013.</small></p>
            </footer>
        </div>
		<!-- /container -->
		
		<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
        <script src="/src/js/bootstrap.js"></script>
        <script src="/src/js/bootstrap-datepicker.js"></script>
        <script src="/src/js/bootstrap-datepicker-locale/bootstrap-datepicker.<?= $lang ?>.js"></script>
		<script src="/src/js/poskok.loading.js" type="text/javascript"></script>
		<script src="/src/js/poskok.framework.js" type="text/javascript"></script>
		<script src="/src/js/poskok.hashtag.js" type="text/javascript"></script>
		<script src="/src/js/poskok.form.js" type="text/javascript"></script>
		<script src="/src/js/poskok.pagination.js" type="text/javascript"></script>
		<script src="/src/js/poskok.popup.js" type="text/javascript"></script>
		<script src="/src/js/poskok.prompt.js" type="text/javascript"></script>
		<script src="/src/js/poskok.load.js" type="text/javascript"></script>
		<? if( isset($footer) ): echo $footer; endif; ?>
    </body>
</html>