<table>
   <tbody>
   <?php
   foreach ( $metadata_list as $meta_key => $meta_value ): ?>
      <tr class="depth_<?= $metadata_list->get_depth() ?>">
         <td class="meta_key"><?= $meta_key ?></td>
         <td class="meta_value"><?= $meta_value  ?></td>
      </tr><?php
   endforeach;
   ?>
   </tbody>
</table>