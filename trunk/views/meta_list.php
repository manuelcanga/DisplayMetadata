<?php
$depth = isset($depth)? $depth : 1;
?>
<table>
   <tbody>
   <?php
   foreach ( $item_vars as $key => $value ): ?>
   <tr class="depth_<?= $depth ?>">
      <td class="meta_key"><?= $key ?></td>
      <td class="meta_value">
		  <?php
		  /** @TODO ITERATOR */
		  $value = maybe_unserialize( $value );
		  if ( is_array( $value ) ) {
			  (function() use($depth, $value) {
				  $depth++;
				  $item_vars = $value;
				  ksort( $item_vars );
				  include static::META_LIST_FILE;
			  })();
		  } else {
			  echo is_null( $value ) ? 'NULL' : htmlentities( $value );
		  }
		  ?>
      </td>
      </tr><?php
   endforeach;
   ?>
   </tbody>
</table>