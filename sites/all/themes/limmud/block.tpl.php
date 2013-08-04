<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zila
 * Date: 8/4/13
 * Time: 1:16 PM
 * To change this template use File | Settings | File Templates.
 */

global $user;
if($block->subject == t('Navigation')) {
  $block->subject = check_plain($user->name);
}
?>
<div id="<?php print $block_html_id; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if ($block->subject): ?>
    <h2<?php print $title_attributes; ?>><?php print $block->subject ?></h2>
  <?php endif;?>
  <?php print render($title_suffix); ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php print $content ?>
  </div>
</div>
