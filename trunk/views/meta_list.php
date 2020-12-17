<table>
   <tbody>
   <?php

   foreach ( $item_vars as $key => $value ): ?>
      <tr>
      <td class="meta_key"><?= $key ?></td>
      <td class="meta_value">
		  <?php if ( is_array( $value ) ) {
			  $item_vars = $value;
			  include static::META_LIST_FILE;
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