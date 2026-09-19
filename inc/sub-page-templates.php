<?php
/**
 * Page Templates: Gutenberg Content
 */

function lightshadestudioworks_render_wedding_celebration() {
    $img_dir = get_template_directory_uri() . '/assets/images/';
    $html = <<<EOD
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"top":"0px","bottom":"0px"},"blockGap":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"url":"{$img_dir}wedding-celebration.webp","id":616,"dimRatio":20,"overlayColor":"base-30","isUserOverlayColor":true,"sizeSlug":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><img class="wp-block-cover__image-background wp-image-616 size-full" alt="" src="{$img_dir}wedding-celebration.webp" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-base-30-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}}} -->
<h1 class="wp-block-heading has-text-align-center">Wedding celebrations</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)"><!-- wp:create-block/axis-folio {"uniqueId":"af-9716df5c","items":[{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}Placeholder_Image_1_.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}wedding-celebration7.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}wedding-celebration5.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}wedding-celebration4.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}wedding-celebration3.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}wedding-celebration2.webp","tags":"","linkUrl":"","openInNewTab":false}],"gridGap":4,"enableLoadMore":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
EOD;
    return $html;
}

function lightshadestudioworks_render_portrait_sessions() {
    $img_dir = get_template_directory_uri() . '/assets/images/';
    $html = <<<EOD
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"top":"0px","bottom":"0px"},"blockGap":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"url":"{$img_dir}image-54.webp","id":616,"dimRatio":20,"overlayColor":"base-30","isUserOverlayColor":true,"sizeSlug":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><img class="wp-block-cover__image-background wp-image-616 size-full" alt="" src="{$img_dir}image-54.webp" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-base-30-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}}} -->
<h1 class="wp-block-heading has-text-align-center">Portrait sessions</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)"><!-- wp:create-block/axis-folio {"uniqueId":"af-9716df5c","items":[{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}portrait-session4.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}portrait-session3.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}portrait-session2.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}portrait-session1.webp","tags":"","linkUrl":"","openInNewTab":false}],"columnsDesktop":4,"gridGap":4,"enableLoadMore":true,"postsPerPage":4} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
EOD;
    return $html;
}


function lightshadestudioworks_render_candid_moments() {
    $img_dir = get_template_directory_uri() . '/assets/images/';
    $html = <<<EOD
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"top":"0px","bottom":"0px"},"blockGap":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"url":"{$img_dir}fathers-day.webp","id":616,"dimRatio":20,"overlayColor":"base-30","isUserOverlayColor":true,"sizeSlug":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><img class="wp-block-cover__image-background wp-image-616 size-full" alt="" src="{$img_dir}fathers-day.webp" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-base-30-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}}} -->
<h1 class="wp-block-heading has-text-align-center">Candid moments</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)"><!-- wp:create-block/axis-folio {"uniqueId":"af-9716df5c","items":[{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments5.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments6.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments7.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments4.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments3.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments2.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}candid-moments1.webp","tags":"","linkUrl":"","openInNewTab":false}],"gridGap":4,"enableLoadMore":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
EOD;
return $html;
}
function lightshadestudioworks_render_real_estate_spaces() {
    $img_dir = get_template_directory_uri() . '/assets/images/';
    $html = <<<EOD
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"},"margin":{"top":"0px","bottom":"0px"},"blockGap":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:cover {"url":"{$img_dir}realestate-spaces.webp","id":616,"dimRatio":20,"overlayColor":"base-30","isUserOverlayColor":true,"sizeSlug":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><img class="wp-block-cover__image-background wp-image-616 size-full" alt="" src="{$img_dir}realestate-spaces.webp" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-base-30-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}}} -->
<h1 class="wp-block-heading has-text-align-center">Real estate spaces</h1>
<!-- /wp:heading --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)"><!-- wp:create-block/axis-folio {"uniqueId":"af-9716df5c","items":[{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}realestate-spaces4.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}realestate-spaces2.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}realestate-spaces1.webp","tags":"","linkUrl":"","openInNewTab":false},{"iconType":"arrow-right-alt2","iconList":"","title":"","description":"","url":"{$img_dir}realestate-spaces3.webp","tags":"","linkUrl":"","openInNewTab":false}],"columnsDesktop":4,"gridGap":4,"enableLoadMore":true,"postsPerPage":4} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
EOD;
return $html;
}