<?php
// $Id: page.tpl.php,v 1.1.2.2.4.2 2011/01/11 01:08:49 dvessel Exp $
?>
<div id="page" class="container-12 clearfix">

  <div id="site-header" class="grid-12 clearfix">
    <div id="branding" class="">
    <?php if ($linked_logo_img): ?>
      <span id="logo" class="grid-2 alpha"><?php print $linked_logo_img; ?></span>
    <?php endif; ?>
    </div>
		<div id="site-subheader" class="grid-10 omega clearfix">
      <?php if ($page['header']): ?>
    		<div id="header-region" class="region">
          <?php print render($page['header']); ?>
    		</div>
      <?php endif; ?>
  	</div>
  </div>


  <div id="main" class="column grid-10 push-2">
  	<?php if ($page['top_navigation']): ?>
    <div id="site-menu" class="region top-navigation-region">
      <?php print render($page['top_navigation']);?>
    </div>
  <?php endif; ?>
    <?php /* print $breadcrumb; */ ?>
    <?php if ($tabs): ?>
      <div class="tabs"><?php print render($tabs); ?></div>
    <?php endif; ?>
    <?php print $messages; ?>
    <?php print render($page['help']); ?>

    <div id="main-content" class="region <?php if (!drupal_is_front_page()): ?>prefix-1 suffix-1<?php endif; ?> clearfix">
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
      <h1 class="title" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>      
      <?php print render($page['content']); ?>
    </div>
		
		<div id="content-bottom-region" class="region prefix-1 suffix-1 clearfix">
      <?php print render($page['content_bottom']); ?>
    </div>
  </div>

<?php if ($page['sidebar_first']): ?>
  <div id="sidebar-left" class="column sidebar region grid-2 pull-10">
    <?php print render($page['sidebar_first']); ?>
  </div>
<?php endif; ?>


  <div id="footer" class="grid-12 clearfix">
    <?php if ($page['footer']): ?>
      <div id="footer-region" class="region">
        <?php print render($page['footer']); ?>
      </div>
    <?php endif; ?>
  </div>

</div>
