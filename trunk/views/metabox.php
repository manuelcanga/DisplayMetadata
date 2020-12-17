<?php
	include static::ASSETS_FILE;
?>


<div id="trasweb-metadata-metabox">
<?php
if( !empty(static::HEADER_FILE) ) {
	include static::HEADER_FILE;
}
?>

	<div class="trasweb-metadata-metabox__content">
		<?php  include static::META_LIST_FILE;  ?>
	</div>

<?php
if( !empty(static::FOOTER_FILE) ) {
	include static::FOOTER_FILE;
}
?>
</div>
