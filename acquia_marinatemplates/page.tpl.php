<?php
// $Id: page.tpl.php,v 1.1.2.6 2009/05/13 09:26:06 jwolf Exp $
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>">

  <head>
    <title><?php print $head_title; ?></title>
    <?php print $head; ?>
    <?php print $styles; ?>
    <!--[if IE 7]>
      <link rel="stylesheet" href="<?php print $base_path . $directory; ?>/ie7-fixes.css" type="text/css">
    <![endif]-->
    <!--[if lte IE 6]>
      <link rel="stylesheet" href="<?php print $base_path . $directory; ?>/ie6-fixes.css" type="text/css">
    <![endif]-->
    <?php print $scripts; ?>

	<script language="JavaScript">
	<!--
	// ==============================================
	// Copyright 2003 by jsCode.com
	// Source: jsCode.com
	// Author: etLux
	// Free for all; but please leave in the header.
	// ==============================================

	// Set up the image files to be used.
	var theImages = new Array() // do not change this
	// To add more image files, continue with the
	// pattern below, adding to the array. Rememeber
	// to increment the theImages[x] index!

	theImages[0] = 'sites/default/files/images/featured/featured01.jpg'
	theImages[1] = 'sites/default/files/images/featured/featured02.jpg'
	theImages[2] = 'sites/default/files/images/featured/featured03.jpg'
	theImages[3] = 'sites/default/files/images/featured/featured04.jpg'
	theImages[4] = 'sites/default/files/images/featured/featured05.jpg'
	theImages[5] = 'sites/default/files/images/featured/featured06.jpg'
	theImages[6] = 'sites/default/files/images/featured/featured07.jpg'
	theImages[7] = 'sites/default/files/images/featured/featured08.jpg'
	theImages[8] = 'sites/default/files/images/featured/featured09.jpg'
	theImages[9] = 'sites/default/files/images/featured/featured10.jpg'
	theImages[10] = 'sites/default/files/images/featured/featured11.jpg'
	theImages[11] = 'sites/default/files/images/featured/featured12.jpg'
	theImages[12] = 'sites/default/files/images/featured/featured13.jpg'
	theImages[13] = 'sites/default/files/images/featured/featured14.jpg'

	// ======================================
	// do not change anything below this line
	// ======================================

	var j = 0
	var p = theImages.length;

	var preBuffer = new Array()
	for (i = 0; i < p; i++){
	   preBuffer[i] = new Image()
	   preBuffer[i].src = theImages[i]
	}

	var whichImage = Math.round(Math.random()*(p-1));
	function showImage(){
	document.write('<img src="'+theImages[whichImage]+'">');
	}

	//-->
	</script>

  </head>

  <body class="<?php print $body_classes; ?>">
    <div id="page" class="clearfix">

      <div id="header-wrapper">
        <div id="header" class="clearfix">
          
          <?php if ($search_box): ?>
          <div id="search-box">
            <?php print $search_box; ?>
          </div><!-- /search-box -->
          <?php endif; ?>
      
          <div id="header-first">
            <?php if ($logo): ?> 
            <div id="logo">
              <a href="<?php print $base_path ?>" title="<?php print t('Home') ?>"><img src="<?php print $logo ?>" alt="<?php print t('Home') ?>" /></a>
            </div>
            <?php endif; ?>
            <?php if ($site_name): ?>
            <h1><a href="<?php print $base_path ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a></h1>
            <?php endif; ?>
            <?php if ($site_slogan): ?>
            <span id="slogan"><?php print $site_slogan; ?></span>
            <?php endif; ?>
          </div><!-- /header-first -->
  
          <div id="header-middle">
            <?php if ($header_middle): ?>
            <?php print $header_middle; ?>
            <?php endif; ?>
          </div><!-- /header-middle -->
      
          <div id="header-last">
            <?php if ($header_last): ?>
            <?php print $header_last; ?>
            <?php endif; ?>
          </div><!-- /header-last -->
      
        </div><!-- /header -->
      </div><!-- /header-wrapper -->
      
      <div id="primary-menu-wrapper" class="clearfix">
        <?php if ($primary_links): ?>
        <div id="primary-menu">
          <?php print $primary_links_tree; ?>
        </div><!-- /primary_menu -->
        <?php endif; ?>
      </div><!-- /primary-menu-wrapper -->

      <div id="preface">
        <?php if ($preface_first || $preface_middle || $preface_last || $mission): ?>
        <div id="preface-wrapper" class="<?php print $prefaces; ?> clearfix">
          <?php if ($mission): ?>
          <div id="mission"> 
            <?php print $mission; ?>
          </div>
          <?php endif; ?>
        
          <?php if ($preface_first): ?>
          <div id="preface-first" class="column">
            <?php print $preface_first; ?>
          </div><!-- /preface-first -->
          <?php endif; ?>

          <?php if ($preface_middle): ?>
          <div id="preface-middle" class="column">
            <?php print $preface_middle; ?>
          </div><!-- /preface-middle -->
          <?php endif; ?>

          <?php if ($preface_last): ?>
          <div id="preface-last" class="column">
            <?php print $preface_last; ?>
          </div><!-- /preface-last -->
          <?php endif; ?>
        </div><!-- /preface-wrapper -->
        <?php endif; ?>
      </div><!-- /preface -->

      <div id="main-wrapper">
        <div id="main" class="clearfix">
          
          <?php if ($breadcrumb): ?>
          <div id="breadcrumb">
            <?php print $breadcrumb; ?>
          </div><!-- /breadcrumb -->
          <?php endif; ?>
        
          <?php if ($sidebar_first): ?>
          <div id="sidebar-first">
            <?php print $sidebar_first; ?>
          </div><!-- /sidebar-first -->
          <?php endif; ?>

		 <?php if ($sidebar_last): ?>
          <div id="sidebar-last">
            <?php print $sidebar_last; ?>
          </div><!-- /sidebar-last -->
          <?php endif; ?>

          <div id="content-wrapper">

            <?php if ($messages): ?>
              <?php print $messages; ?>
            <?php endif; ?>

            <?php if ($content_top): ?>
            <div id="content-top">
              <?php print $content_top; ?>
            </div><!-- /content-top -->
            <?php endif; ?>
            
            <div id="content">
              <?php if ($tabs): ?>
              <div id="content-tabs">
                <?php print $tabs; ?>
              </div>
              <?php endif; ?>

		<?php/*	<?php if (($sidebar_first) && ($sidebar_last)) : ?>
                <?php if ($sidebar_last): ?>
                <div id="sidebar-last">
                  <?php print $sidebar_last; ?>
                </div><!-- /sidebar_last -->
                <?php endif; ?>
              <?php endif; ?> */?>

              <div id="content-inner">
                
              <?php if ($help): ?>
                <div id="help">
                  <?php //print $help; ?>
                </div>
              <?php endif; ?>
                
                <?php if ($title): ?>
                <h1 class="title"><?php print $title; ?></h1>
                <?php endif; ?>
                <div id="content-content">
                  <?php print $content; ?>
                </div>
              </div><!-- /content-inner -->
            </div><!-- /content -->

            <?php if ($content_bottom): ?>
            <div id="content-bottom">
              <?php print $content_bottom; ?>
            </div><!-- /content-bottom -->
            <?php endif; ?>
          </div><!-- /content-wrapper -->
          
       <?php/*   <?php if ((!$sidebar_first) && ($sidebar_last)) : ?>
            <?php if ($sidebar_last): ?>
            <div id="sidebar-last">
              <?php print $sidebar_last; ?>
            </div><!-- /sidebar_last -->
            <?php endif; ?>
          <?php endif; ?> */?>

          <?php if ($postscript_first || $postscript_middle || $postscript_last): ?>
          <div id="postscript-wrapper" class="<?php print $postscripts; ?> clearfix">
            <?php if ($postscript_first): ?>
            <div id="postscript-first" class="column">
              <?php print $postscript_first; ?>
            </div><!-- /postscript-first -->
            <?php endif; ?>

            <?php if ($postscript_middle): ?>
            <div id="postscript-middle" class="column">
              <?php print $postscript_middle; ?>
            </div><!-- /postscript-middle -->
            <?php endif; ?>

            <?php if ($postscript_last): ?>
            <div id="postscript-last" class="column">
              <?php print $postscript_last; ?>
            </div><!-- /postscript-last -->
            <?php endif; ?>
          </div><!-- /postscript-wrapper -->
          <?php endif; ?>
          </div><!-- /main -->
      </div><!-- /main-wrapper -->
          <?php print $feed_icons; ?>
			<!-- footer -->
          <?php if ($footer_top || $footer || $footer_message): ?>
          <div id="footer" class="clearfix">
            <?php if ($footer_top): ?>
            <?php print $footer_top; ?>
            <?php endif; ?>
            <?php if ($footer): ?>
            <?php print $footer; ?>
            <?php endif; ?>
            <?php if ($footer_message): ?>
            <?php print $footer_message; ?>
            <?php endif; ?>
          </div><!-- /footer -->
          <?php endif; ?> 
    </div><!-- /page -->
    <?php print $closure; ?>
  </body>
</html>
