<?php

/**
 * @file
 * widget.tpl.php
 *
 * stackoverflow widget theme for Vote Up/Down
 */
?>
<div class="stackoverflow-widget vud-widget-stackoverflow" id="<?php print $id; ?>">




    <?php if ($show_up_as_link): ?>
      <a href="<?php print $link_up; ?>" rel="nofollow" class="<?php print $link_class_up; ?>">+
    <?php endif; ?>
    <?php if ($show_up_as_link): ?>
      </a>
    <?php endif; ?>


  <div class="stackoverflow-score">
    <span class="stackoverflow-current-score"><?php print $unsigned_points; ?></span>

  </div>


    <?php if ($show_down_as_link): ?>
      <a href="<?php print $link_down; ?>" rel="nofollow" class="<?php print $link_class_down; ?>">-
    <?php endif; ?>
    <?php if ($show_down_as_link): ?>
      </a>
    <?php endif; ?>


</div>



