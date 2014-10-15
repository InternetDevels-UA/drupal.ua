<div class="node<?php if (!$status) { print ' node-unpublished'; } ?>">
  <?php if ($page == 0): ?>
    <h2 class="title"><a class="node-title" href="<?php print $node_url ?>"><?php print $title; ?></a></h2>
    <?php if ($title_prefix): ?><span class="node-title-prefix group-subtitle"><?php print $group_logo; ?><?php print $title_prefix . ', '; ?><?php print $group_description; ?></span><?php endif; ?>
  <?php endif; ?>

  <div class="content">
    <?php print $content; ?>
    <div class="clear-block"></div>
  </div>

  <div class="meta-links">
    <?php if ($submitted): ?>
      <div class="meta">
        <span class="submitted">
          <?php print $name; ?> / <span class="date"><?php print $date; ?></span>
        </span>
      </div>
    <?php endif; ?>
    <?php if ($links): ?>
      <div class="node-links">
        <?php  print $links; ?>
      </div>
    <?php endif; ?>
    <?php if (count($taxonomy)): ?>
      <div class="taxonomy-links"><?php print $terms; ?></div>
    <?php endif; ?>
    <div class="clear-block"></div>
  </div>

</div>
<div class="clear-block"></div>
