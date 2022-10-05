<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2022
 */

$enc = $this->encoder();
$pos = 0;

/** client/html/catalog/home/imageset-sizes
 * Size hints for loading the appropriate catalog home image sizes
 *
 * Modern browsers can load images of different sizes depending on their viewport
 * size. This is also known as serving "responsive images" because on small
 * smartphone screens, only small images are loaded while full width images are
 * loaded on large desktop screens.
 *
 * A responsive image contains additional "srcset" and "sizes" attributes:
 *
 *  <img src="img.jpg"
 *  	srcset="img-small.jpg 240w, img-large.jpg 720w"
 *  	sizes="(max-width: 320px) 240px, 720px"
 *  >
 *
 * The images and their width in the "srcset" attribute are automatically added
 * based on the sizes of the generated preview images. The value of the "sizes"
 * attribute can't be determined by Aimeos because it depends on the used frontend
 * theme and the size of the images defined in the CSS file. This config setting
 * adds the required value for the "sizes" attribute.
 *
 * It's value consists of one or more comma separated rules with
 * - an optional CSS media query for the view port size
 * - the (max) width the image will be displayed within this viewport size
 *
 * Rules without a media query are independent of the view port size and must be
 * always at last because the rules are evaluated from left to right and the first
 * matching rule is used.
 *
 * The above example tells the browser:
 * - Up to 320px view port width use img-small.jpg
 * - Above 320px view port width use img-large.jpg
 *
 * For more information about the "sizes" attribute of the "img" HTML tag read:
 * {@link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/img#attr-sizes}
 *
 * @param string HTML image "sizes" attribute
 * @since 2021.04
 * @see client/html/common/imageset-sizes
 */

$lazy = false;

?>

<div class="home-slider slide-animate owl-carousel owl-theme show-nav-hover nav-big mb-2 text-uppercase" data-owl-options="{
        'loop': false
    }">
	<?php if( isset( $this->homeTree ) ) : ?>
		<?php 
			foreach( $this->homeTree->getChildren() as $child ) : 
				if( !( $mediaItems = $child->getRefItems( 'media', 'stage', 'default' ) )->isEmpty() ) :
					foreach( $mediaItems as $mediaItem ) :
		?>
				<div class="home-slide home-slide1 banner">
					<img class="slide-bg"  loading="<?= $lazy ? 'lazy' : '' ?>"
						src="<?= $enc->attr( $this->content( $mediaItem->getPreview( true ), $mediaItem->getFileSystem() ) ) ?>"
						srcset="<?= $enc->attr( $this->imageset( $mediaItem->getPreviews(), $mediaItem->getFileSystem() ) ) ?>"
						alt="<?= $enc->attr( $mediaItem->getProperties( 'name' )->first() ) ?>" width="1903" height="499" alt="slider image">
					<div class="container d-flex align-items-center">
						<div class="banner-layer appear-animate" data-animation-name="fadeInUpShorter">
							<?php 
								$con = 0;
								foreach( $child->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>
								<?php
									switch($con) {
										case 0: echo '<h4 class="text-transform-none text-white m-b-3">' . $textItem->getContent() . '</h4>'; break;
										case 1: echo '<h2 class="text-transform-none text-white mb-0">' . $textItem->getContent() . '</h2>'; break;
										case 2: echo '<h3 class="m-b-3 text-white">' . $textItem->getContent() . '</h3>'; break;
										default: break;
									}
									$con++;
								?>
							<?php endforeach ?>
							<div class="btn btn-dark btn-lg"><?= $enc->html( $this->translate( 'client', 'Take a look' ) ) ?></div>
						</div>
						<!-- End .banner-layer -->
					</div>
				</div>
				<!-- End .home-slide -->
				<?php endforeach ?>
			<?php endif  ?>
		<?php endforeach ?>
	<?php endif ?>

	<div class="home-slide home-slide2 banner banner-md-vw">
		<img class="slide-bg" style="background-color: #ccc;" width="1903" height="499" src="/assets/images/demoes/demo4/slider/slide-2.jpg" alt="slider image">
		<div class="container d-flex align-items-center">
			<div class="banner-layer d-flex justify-content-center appear-animate" data-animation-name="fadeInUpShorter">
				<div class="mx-auto">
					<h4 class="m-b-1">Extra</h4>
					<h3 class="m-b-2">20% off</h3>
					<h3 class="mb-2 heading-border">Accessories</h3>
					<h2 class="text-transform-none m-b-4">Summer Sale</h2>
					<a href="category.html" class="btn btn-block btn-dark">Shop All Sale</a>
				</div>
			</div>
			<!-- End .banner-layer -->
		</div>
	</div>
	<!-- End .home-slide -->

	<div class="home-slide home-slide2 banner banner-md-vw">
		<img class="slide-bg" style="background-color: #ccc;" width="1903" height="499" src="/assets/images/demoes/demo4/slider/slide-2.jpg" alt="slider image">
		<div class="container d-flex align-items-center">
			<div class="banner-layer d-flex justify-content-center appear-animate" data-animation-name="fadeInUpShorter">
				<div class="mx-auto">
					<h4 class="m-b-1">Extra2</h4>
					<h3 class="m-b-2">202% off</h3>
					<h3 class="mb-2 heading-border">Accessories 2</h3>
					<h2 class="text-transform-none m-b-4">Summer Sale 2</h2>
					<a href="category.html" class="btn btn-block btn-dark">Shop All Sale 2</a>
				</div>
			</div>
			<!-- End .banner-layer -->
		</div>
	</div>
	<!-- End .home-slide -->
</div>
<!-- End .home-slider -->