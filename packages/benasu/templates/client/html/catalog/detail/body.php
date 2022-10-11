<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2022
 */

/* Available data:
 * - detailProductItem : Product item incl. referenced items
 */


$enc = $this->encoder();

/** client/html/basket/require-stock
 * Customers can order products only if there are enough products in stock
 *
 * Checks that the requested product quantity is in stock before
 * the customer can add them to his basket and order them. If there
 * are not enough products available, the customer will get a notice.
 *
 * @param boolean True if products must be in stock, false if products can be sold without stock
 * @since 2014.03
 */
$reqstock = (int) $this->config( 'client/html/basket/require-stock', true );

/** client/html/catalog/detail/basket-add
 * Display the "add to basket" button for each suggested/bought-together product item
 *
 * Enables the button for adding products to the basket for the related products
 * in the basket. This works for all type of products, even for selection products
 * with product variants and product bundles. By default, also optional attributes
 * are displayed if they have been associated to a product.
 *
 * To fetch the variant articles of selection products too, add this setting to
 * your configuration:
 *
 * mshop/common/manager/maxdepth = 3
 *
 * @param boolean True to display the button, false to hide it
 * @since 2021.04
 * @see client/html/catalog/home/basket-add
 * @see client/html/catalog/lists/basket-add
 * @see client/html/catalog/product/basket-add
 * @see client/html/basket/related/basket-add
 */
?>
<?php if( isset( $this->detailProductItem )  && false) : ?>
	<div class="aimeos catalog-detail" itemscope itemtype="http://schema.org/Product" data-jsonurl="<?= $enc->attr( $this->link( 'client/jsonapi/url' ) ) ?>">
		<div class="container-xxl">

			<!-- catalog.detail.navigator -->
			<!-- navigator template added by client -->
			<!-- catalog.detail.navigator -->

			<article class="product row <?= $this->detailProductItem->getConfigValue( 'css-class' ) ?>"
				data-id="<?= $this->detailProductItem->getId() ?>" data-reqstock="<?= $reqstock ?>">

				<div class="col-sm-6">

					<?= $this->partial( $this->config( 'client/html/common/partials/badges', 'common/partials/badges' ) ) ?>

					<?= $this->partial(
						/** client/html/catalog/detail/partials/image
						 * Relative path to the detail image partial template file
						 *
						 * Partials are templates which are reused in other templates and generate
						 * reoccuring blocks filled with data from the assigned values. The image
						 * partial creates an HTML block for the catalog detail images.
						 *
						 * @param string Relative path to the template file
						 * @since 2017.01
						 */
						$this->config( 'client/html/catalog/detail/partials/image', 'catalog/detail/image' ),
						['mediaItems' => $this->get( 'detailMediaItems', map() ), 'params' => $this->param()]
					) ?>

				</div>

				<div class="col-sm-6">

					<div class="catalog-detail-basic">
						<?php if( !( $suppliers = $this->detailProductItem->getRefItems( 'supplier' ) )->isEmpty() ) : $name = $suppliers->getName()->first() ?>
							<p class="supplier">
								<a href="<?= $enc->attr( $this->link( 'client/html/supplier/detail/url', ['f_supid' => $suppliers->firstKey(), 's_name' => $name] ) ) ?>">
									<?= $enc->html( $name, $enc::TRUST ) ?>
								</a>
							</p>
						<?php elseif( $this->get( 'contextSite' ) !== 'default' ) : ?>
							<p class="site"><?= $enc->html( $this->get( 'contextSiteLabel' ) ) ?></p>
						<?php endif ?>

						<h1 class="name" itemprop="name"><?= $enc->html( $this->detailProductItem->getName(), $enc::TRUST ) ?></h1>

						<p class="code">
							<span class="name"><?= $enc->html( $this->translate( 'client', 'Article no.' ), $enc::TRUST ) ?>: </span>
							<span class="value" itemprop="sku"><?= $enc->html( $this->detailProductItem->getCode() ) ?></span>
						</p>

						<?php if( $this->detailProductItem->getRating() > 0 ) : ?>
							<div class="rating" itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
								<span class="stars"><?= str_repeat( '★', (int) round( $this->detailProductItem->getRating() ) ) ?></span>
								<span class="rating-value" itemprop="ratingValue"><?= $enc->html( $this->detailProductItem->getRating() ) ?></span>
								<span class="ratings" itemprop="reviewCount"><?= (int) $this->detailProductItem->getRatings() ?></span>
							</div>
						<?php endif ?>

						<?php foreach( $this->detailProductItem->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>
							<div class="short" itemprop="description"><?= $enc->html( $textItem->getContent(), $enc::TRUST ) ?></div>
						<?php endforeach ?>

					</div>


					<div class="catalog-detail-basket" itemscope itemprop="offers" itemtype="http://schema.org/Offer">

						<div class="price-list">
							<div class="articleitem price price-actual" data-prodid="<?= $enc->attr( $this->detailProductItem->getId() ) ?>">

								<?= $this->partial(
									$this->config( 'client/html/common/partials/price', 'common/partials/price' ),
									['prices' => $this->detailProductItem->getRefItems( 'price', null, 'default' )]
								) ?>

							</div>

							<?php if( $this->detailProductItem->getType() === 'select' ) : ?>
								<?php foreach( $this->detailProductItem->getRefItems( 'product', 'default', 'default' ) as $prodid => $product ) : ?>
									<?php if( !( $prices = $product->getRefItems( 'price', null, 'default' ) )->isEmpty() ) : ?>

										<div class="articleitem price" data-prodid="<?= $enc->attr( $prodid ) ?>">

											<?= $this->partial(
												$this->config( 'client/html/common/partials/price', 'common/partials/price' ),
												['prices' => $prices]
											) ?>

										</div>

									<?php endif ?>
								<?php endforeach ?>
							<?php endif ?>

						</div>


						<form class="basket" method="POST" action="<?= $enc->attr( $this->link( 'client/html/basket/standard/url' ) ) ?>">
							<!-- catalog.detail.csrf -->
							<?= $this->csrf()->formfield() ?>
							<!-- catalog.detail.csrf -->

							<?php if( $this->detailProductItem->getType() === 'select' ) : ?>

								<div class="catalog-detail-basket-selection">

									<?= $this->partial(
										/** client/html/common/partials/selection
										 * Relative path to the variant attribute partial template file
										 *
										 * Partials are templates which are reused in other templates and generate
										 * reoccuring blocks filled with data from the assigned values. The selection
										 * partial creates an HTML block for a list of variant product attributes
										 * assigned to a selection product a customer must select from.
										 *
										 * The partial template files are usually stored in the templates/partials/ folder
										 * of the core or the extensions. The configured path to the partial file must
										 * be relative to the templates/ folder, e.g. "common/partials/selection".
										 *
										 * @param string Relative path to the template file
										 * @since 2015.04
										 * @see client/html/common/partials/attribute
										 */
										$this->config( 'client/html/common/partials/selection', 'common/partials/selection' ),
										[
											'productItems' => $this->detailProductItem->getRefItems( 'product', null, 'default' ),
											'productItem' => $this->detailProductItem
										]
									) ?>

								</div>

							<?php elseif( $this->detailProductItem->getType() === 'group' ) : ?>

								<div class="catalog-detail-basket-selection">

									<?= $this->partial(
										/** client/html/catalog/detail/partials/group
										 * Relative path to the group product partial template file
										 *
										 * Partials are templates which are reused in other templates and generate
										 * reoccuring blocks filled with data from the assigned values. The group
										 * partial creates an HTML block for a list of sub-products assigned to a
										 * group product a customer can select from.
										 *
										 * @param string Relative path to the template file
										 * @since 2021.07
										 * @see client/html/common/partials/attribute
										 */
										$this->config( 'client/html/catalog/detail/partials/group', 'catalog/detail/group' ),
										[
											'productItems' => $this->detailProductItem->getRefItems( 'product', null, 'default' ),
											'productItem' => $this->detailProductItem
										]
									) ?>

								</div>

							<?php endif ?>

							<div class="catalog-detail-basket-attribute">

								<?= $this->partial(
									/** client/html/common/partials/attribute
									 * Relative path to the product attribute partial template file
									 *
									 * Partials are templates which are reused in other templates and generate
									 * reoccuring blocks filled with data from the assigned values. The attribute
									 * partial creates an HTML block for a list of optional product attributes a
									 * customer can select from.
									 *
									 * The partial template files are usually stored in the templates/partials/ folder
									 * of the core or the extensions. The configured path to the partial file must
									 * be relative to the templates/ folder, e.g. "partials/attribute.php".
									 *
									 * @param string Relative path to the template file
									 * @since 2016.01
									 * @see client/html/common/partials/selection
									 */
									$this->config( 'client/html/common/partials/attribute', 'common/partials/attribute' ),
									['productItem' => $this->detailProductItem]
								) ?>

							</div>


							<div class="stock-list">
								<div class="articleitem <?= !in_array( $this->detailProductItem->getType(), ['select', 'group'] ) ? 'stock-actual' : '' ?>"
									data-prodid="<?= $enc->attr( $this->detailProductItem->getId() ) ?>">
								</div>

								<?php foreach( $this->detailProductItem->getRefItems( 'product', null, 'default' ) as $articleId => $articleItem ) : ?>

									<div class="articleitem" data-prodid="<?= $enc->attr( $articleId ) ?>"></div>

								<?php endforeach ?>

							</div>


							<?php if( !$this->detailProductItem->getRefItems( 'price', 'default', 'default' )->empty() ) : ?>
								<div class="addbasket">
									<input type="hidden" value="add" name="<?= $enc->attr( $this->formparam( 'b_action' ) ) ?>">
									<input type="hidden"
										name="<?= $enc->attr( $this->formparam( ['b_prod', 0, 'prodid'] ) ) ?>"
										value="<?= $enc->attr( $this->detailProductItem->getId() ) ?>"
									>
									<div class="input-group">
										<?php if( $this->detailProductItem->getType() !== 'group' ) : ?>
											<input type="number" class="form-control input-lg" <?= !$this->detailProductItem->isAvailable() ? 'disabled' : '' ?>
												name="<?= $enc->attr( $this->formparam( ['b_prod', 0, 'quantity'] ) ) ?>"
												step="<?= $enc->attr( $this->detailProductItem->getScale() ) ?>"
												min="<?= $enc->attr( $this->detailProductItem->getScale() ) ?>" max="2147483647"
												value="<?= $enc->attr( $this->detailProductItem->getScale() ) ?>" required="required"
												title="<?= $enc->attr( $this->translate( 'client', 'Quantity' ) ) ?>"
											>
										<?php endif ?>
										<button class="btn btn-primary btn-lg btn-action" type="submit" value="" <?= !$this->detailProductItem->isAvailable() ? 'disabled' : '' ?>>
											<?= $enc->html( $this->translate( 'client', 'Add to basket' ), $enc::TRUST ) ?>
										</button>
									</div>
								</div>
							<?php endif ?>

						</form>

					</div>


					<div class="catalog-detail-actions">

						<?= $this->partial(
							/** client/html/catalog/partials/actions
							 * Relative path to the catalog actions partial template file
							 *
							 * Partials are templates which are reused in other templates and generate
							 * reoccuring blocks filled with data from the assigned values. The actions
							 * partial creates an HTML block for the product actions (pin, like and watch
							 * products).
							 *
							 * @param string Relative path to the template file
							 * @since 2017.04
							 */
							$this->config( 'client/html/catalog/partials/actions', 'catalog/actions' ),
							['productItem' => $this->detailProductItem]
						) ?>


						<?= $this->partial(
							/** client/html/catalog/partials/social
							 * Relative path to the social partial template file
							 *
							 * Partials are templates which are reused in other templates and generate
							 * reoccuring blocks filled with data from the assigned values. The social
							 * partial creates an HTML block for links to social platforms in the
							 * catalog components.
							 *
							 * @param string Relative path to the template file
							 * @since 2017.04
							 */
							$this->config( 'client/html/catalog/partials/social', 'catalog/social' ),
							['productItem' => $this->detailProductItem]
						) ?>

					</div>
				</div>

				<div class="col-sm-12">
					<div class="catalog-detail-additional content-block">
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">

								<?php if( !( $textItems = $this->detailProductItem->getRefItems( 'text', 'long' ) )->isEmpty() ) : ?>
									<a class="nav-link active" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button" role="tab" aria-controls="nav-description" aria-selected="true">
										<?= $enc->html( $this->translate( 'client', 'Description' ), $enc::TRUST ) ?>
									</a>
								<?php endif ?>

								<?php if( !$this->get( 'detailAttributeMap', map() )->isEmpty() || !$this->get( 'detailPropertyMap', map() )->isEmpty() ) : ?>
									<a class="nav-link nav-attribute" id="nav-attribute-tab" data-bs-toggle="tab" data-bs-target="#nav-attribute" type="button" role="tab" aria-controls="nav-attribute">
										<?= $enc->html( $this->translate( 'client', 'Characteristics' ), $enc::TRUST ) ?>
									</a>
								<?php endif ?>

								<?php if( !( $mediaItems = $this->detailProductItem->getRefItems( 'media', 'download' ) )->isEmpty() ) : ?>
									<a class="nav-link nav-characteristics" id="nav-characteristics-tab" data-bs-toggle="tab" data-bs-target="#nav-characteristics" type="button" role="tab" aria-controls="nav-characteristics">
										<?= $enc->html( $this->translate( 'client', 'Downloads' ), $enc::TRUST ) ?>
									</a>
								<?php endif ?>

								<a class="nav-link nav-review" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab" aria-controls="nav-review">
									<?= $enc->html( $this->translate( 'client', 'Reviews' ), $enc::TRUST ) ?>
									<span class="ratings"><?= $enc->html( $this->detailProductItem->getRatings() ) ?></span>
								</a>
							</div>
						</nav>

						<div class="tab-content" id="nav-tabContent">

							<div class="tab-pane fade show active" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab">

								<?php if( !( $textItems = $this->detailProductItem->getRefItems( 'text', 'long' ) )->isEmpty() ) : ?>

									<div class="block description">

										<?php foreach( $textItems as $textItem ) : ?>
											<div class="long item"><?= $enc->html( $textItem->getContent(), $enc::TRUST ) ?></div>
										<?php endforeach ?>

									</div>

								<?php endif ?>

							</div>

							<div class="tab-pane fade" id="nav-attribute" role="tabpanel" aria-labelledby="nav-attribute-tab">

								<?php if( !$this->get( 'detailAttributeMap', map() )->isEmpty() || !$this->get( 'detailPropertyMap', map() )->isEmpty() ) : ?>

									<div class="block attributes">
										<table class="attributes">
											<tbody>

												<?php foreach( $this->get( 'detailAttributeMap', map() ) as $type => $attrItems ) : ?>
													<?php foreach( $attrItems as $attrItem ) : ?>

														<tr class="item <?= ( $ids = $attrItem->get( 'parent' ) ) ? 'subproduct ' . map( $ids )->prefix( 'subproduct-' )->join( ' ' ) : '' ?>">
															<td class="name"><?= $enc->html( $this->translate( 'client/code', $type ), $enc::TRUST ) ?></td>
															<td class="value">
																<div class="media-list">

																	<?php foreach( $attrItem->getListItems( 'media', 'icon' ) as $listItem ) : ?>
																		<?php if( ( $refitem = $listItem->getRefItem() ) !== null ) : ?>
																			<?= $this->partial(
																				$this->config( 'client/html/common/partials/media', 'common/partials/media' ),
																				['item' => $refitem, 'boxAttributes' => ['class' => 'media-item']]
																			) ?>
																		<?php endif ?>
																	<?php endforeach ?>

																</div><!--
																--><span class="attr-name"><?= $enc->html( $attrItem->getName() ) ?></span>

																<?php foreach( $attrItem->getRefItems( 'text', 'short' ) as $textItem ) : ?>
																	<div class="attr-short"><?= $enc->html( $textItem->getContent() ) ?></div>
																<?php endforeach ?>

																<?php foreach( $attrItem->getRefItems( 'text', 'long' ) as $textItem ) : ?>
																	<div class="attr-long"><?= $enc->html( $textItem->getContent() ) ?></div>
																<?php endforeach ?>

															</td>
														</tr>

													<?php endforeach ?>
												<?php endforeach ?>

												<?php foreach( $this->get( 'detailPropertyMap', map() ) as $type => $propItems ) : ?>
													<?php foreach( $propItems as $propItem ) : ?>

														<tr class="item <?= ( $id = $propItem->get( 'parent' ) ) ? 'subproduct subproduct-' . $id : '' ?>">
															<td class="name"><?= $enc->html( $this->translate( 'client/code', $propItem->getType() ), $enc::TRUST ) ?></td>
															<td class="value"><?= $enc->html( $propItem->getValue() ) ?></td>
														</tr>

													<?php endforeach ?>
												<?php endforeach ?>

											</tbody>
										</table>
									</div>

								<?php endif ?>
							</div>

							<div class="tab-pane fade" id="nav-characteristics" role="tabpanel" aria-labelledby="nav-characteristics-tab">
								<?php if( !( $mediaItems = $this->detailProductItem->getRefItems( 'media', 'download' ) )->isEmpty() ) : ?>

									<ul class="block downloads">

										<?php foreach( $mediaItems as $id => $mediaItem ) : ?>

											<li class="item">
												<a href="<?= $this->content( $mediaItem->getUrl(), $mediaItem->getFileSystem() ) ?>"
													title="<?= $enc->attr( $mediaItem->getProperties( 'title' )->first( $mediaItem->getLabel() ) ) ?>">
													<img class="media-image"
														alt="<?= $enc->attr( $mediaItem->getProperties( 'title' )->first( $mediaItem->getLabel() ) ) ?>"
														src="<?= $enc->attr( $this->content( $mediaItem->getPreview(), $mediaItem->getFileSystem() ) ) ?>"
														srcset="<?= $enc->attr( $this->imageset( $mediaItem->getPreviews(), $mediaItem->getFileSystem() ) ) ?>"
													>
													<span class="media-name"><?= $enc->html( $mediaItem->getProperties( 'title' )->first( $mediaItem->getLabel() ) ) ?></span>
												</a>
											</li>

										<?php endforeach ?>

									</ul>

								<?php endif ?>
							</div>

							<div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab">
								<div class="reviews container-fluid block" data-productid="<?= $enc->attr( $this->detailProductItem->getId() ) ?>">
									<div class="row">
										<div class="col-md-4 rating-list">
											<div class="rating-numbers">
												<div class="rating-num"><?= number_format( $this->detailProductItem->getRating(), 1 ) ?>/5</div>
												<div class="rating-total"><?= $enc->html( sprintf( $this->translate( 'client', '%1$d review', '%1$d reviews', $this->detailProductItem->getRatings() ), $this->detailProductItem->getRatings() ) ) ?></div>
												<div class="rating-stars"><?= str_repeat( '★', (int) round( $this->detailProductItem->getRating() ) ) ?></div>
											</div>
											<table class="rating-dist" data-ratings="<?= $enc->attr( $this->detailProductItem->getRatings() ) ?>">
												<tr>
													<td class="rating-label"><label for="rating-5">★★★★★</label></td>
													<td class="rating-percent"><progress id="rating-5" value="0" max="100">0</progress></td>
												</tr>
												<tr>
													<td class="rating-label"><label for="rating-4">★★★★</label></td>
													<td class="rating-percent"><progress id="rating-4" value="0" max="100">0</progress></td>
												</tr>
												<tr>
													<td class="rating-label"><label for="rating-3">★★★</label></td>
													<td class="rating-percent"><progress id="rating-3" value="0" max="100">0</progress></td>
												</tr>
												<tr>
													<td class="rating-label"><label for="rating-2">★★</label></td>
													<td class="rating-percent"><progress id="rating-2" value="0" max="100">0</progress></td>
												</tr>
												<tr>
													<td class="rating-label"><label for="rating-1">★</label></td>
													<td class="rating-percent"><progress id="rating-1" value="0" max="100">0</progress></td>
												</tr>
											</table>
										</div>
										<div class="col-md-8 review-list">
											<div class="sort">
												<span><?= $enc->html( $this->translate( 'client', 'Sort by:' ), $enc::TRUST ) ?></span>
												<ul>
													<li>
														<a class="sort-option option-ctime active" href="<?= $enc->attr( $this->link( 'client/jsonapi/url', ['resource' => 'review', 'filter' => ['f_refid' => $this->detailProductItem->getId()], 'sort' => '-ctime'] ) ) ?>">
															<?= $enc->html( $this->translate( 'client', 'Latest' ), $enc::TRUST ) ?>
														</a>
													</li>
													<li>
														<a class="sort-option option-rating" href="<?= $enc->attr( $this->link( 'client/jsonapi/url', ['resource' => 'review', 'filter' => ['f_refid' => $this->detailProductItem->getId()], 'sort' => '-rating,-ctime'] ) ) ?>">
															<?= $enc->html( $this->translate( 'client', 'Rating' ), $enc::TRUST ) ?>
														</a>
													</li>
												</ul>
											</div>
											<div class="review-items">
												<div class="review-item prototype">
													<div class="review-name"></div>
													<div class="review-ctime"></div>
													<div class="review-rating">★</div>
													<div class="review-comment"></div>
													<div class="review-response">
														<div class="review-vendor"><?= $enc->html( $this->translate( 'client', 'Vendor response' ) ) ?></div>
													</div>
													<div class="review-show"><a href="#"><?= $enc->html( $this->translate( 'client', 'Show' ) ) ?></a></div><!--
												--></div>
											</div>
											<a class="btn btn-primary more" href="#"><?= $enc->html( $this->translate( 'client', 'More reviews' ), $enc::TRUST ) ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<?php if( $this->detailProductItem->getType() === 'bundle' && !( $products = $this->detailProductItem->getRefItems( 'product', null, 'default' ) )->isEmpty() ) : ?>

						<section class="catalog-detail-bundle content-block">
							<h2 class="header"><?= $this->translate( 'client', 'Bundled products' ) ?></h2>

							<?= $this->partial(
								$this->config( 'client/html/common/partials/products', 'common/partials/products' ),
								['products' => $products, 'itemprop' => 'isRelatedTo']
							) ?>

						</section>

					<?php endif ?>


					<?php if( !( $products = $this->detailProductItem->getRefItems( 'product', null, 'suggestion' ) )->isEmpty() ) : ?>

						<section class="catalog-detail-suggest content-block">
							<h2 class="header"><?= $this->translate( 'client', 'Suggested products' ) ?></h2>

							<?= $this->partial(
								$this->config( 'client/html/common/partials/products', 'common/partials/products' ), [
									'basket-add' => $this->config( 'client/html/catalog/detail/basket-add', false ),
									'products' => $products, 'itemprop' => 'isRelatedTo'
								] )
							?>

						</section>

					<?php endif ?>


					<?php if( !( $products = $this->detailProductItem->getRefItems( 'product', null, 'bought-together' ) )->isEmpty() ) : ?>

						<section class="catalog-detail-bought content-block">
							<h2 class="header"><?= $this->translate( 'client', 'Other customers also bought' ) ?></h2>

							<?= $this->partial(
								$this->config( 'client/html/common/partials/products', 'common/partials/products' ), [
									'basket-add' => $this->config( 'client/html/catalog/detail/basket-add', false ),
									'products' => $products, 'itemprop' => 'isRelatedTo'
								] )
							?>

						</section>

					<?php endif ?>

					<?php if( !( $supplierItems = $this->detailProductItem->getRefItems( 'supplier', null, 'default' ) )->isEmpty() ) : ?>
						<div class="catalog-detail-supplier content-block">

							<h2 class="header"><?= $this->translate( 'client', 'Supplier information' ) ?></h2>

							<?php foreach( $supplierItems as $supplierItem ) : ?>

								<div class="supplier-content">

									<?php if( ( $mediaItem = $supplierItem->getRefItems( 'media', 'default', 'default' )->first() ) !== null ) : ?>
										<div class="media-item">
											<img class="lazy-image"
												alt="<?= $enc->attr( $mediaItem->getProperties( 'title' )->first() ) ?>"
												data-src="<?= $enc->attr( $this->content( $mediaItem->getPreview(), $mediaItem->getFileSystem() ) ) ?>"
												data-srcset="<?= $enc->attr( $this->imageset( $mediaItem->getPreviews(), $mediaItem->getFileSystem() ) ) ?>"
												sizes="<?= $enc->attr( $this->config( 'client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw' ) ) ?>"
											>
										</div>
									<?php endif ?>

									<h3 class="supplier-name">
										<?= $enc->html( $supplierItem->getName(), $enc::TRUST ) ?>

										<?php if( ( $addrItem = $supplierItem->getAddressItems()->first() ) !== null ) : ?>
											<span class="supplier-address">(<?= $enc->html( $addrItem->getCity() ) ?>, <?= $enc->html( $addrItem->getCountryId() ) ?>)</span>
										<?php endif ?>
									</h3>

									<?php foreach( $supplierItem->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>
										<p class="supplier-short"><?= $enc->html( $textItem->getContent(), $enc::TRUST ) ?></p>
									<?php endforeach ?>

									<?php foreach( $supplierItem->getRefItems( 'text', 'long', 'default' ) as $textItem ) : ?>
										<p class="supplier-long"><?= $enc->html( $textItem->getContent(), $enc::TRUST ) ?></p>
									<?php endforeach ?>

								</div>

							<?php endforeach ?>

						</div>

					<?php endif ?>

				</div>
			</div>

		</article>
	</div>

<?php endif ?>

<?php
	// print_r($this->detailProductItem);die;
	$listMedia = $this->get( 'detailMediaItems', map() );
	foreach($listMedia as $media){
		// echo $enc->attr($this->content( $media->getPreview(), $media->getFileSystem() ));die;
	}
	$format = array(
		/// Price quantity format with quantity (%1$s)
		'quantity' => $this->translate( 'client', 'from %1$s' ),
		/// Price shipping format with shipping / payment cost value (%1$s) and currency (%2$s)
		'costs' => ( $this->get( 'costsItem', true ) ? $this->translate( 'client', '+ %1$s %2$s/item' ) : $this->translate( 'client', '%1$s %2$s' ) ),
		/// Rebate format with rebate value (%1$s) and currency (%2$s)
		'rebate' => $this->translate( 'client', '%1$s %2$s off' ),
		/// Rebate percent format with rebate percent value (%1$s)
		'rebate%' => $this->translate( 'client', '-%1$s%%' ),
	);
	$prices = $this->detailProductItem->getRefItems( 'price', null, 'default' );
	$price = $prices->getValue()->first();
	$rebate = $prices->getRebate()->first();
	$currency = substr($this->translate('currency', $prices->getCurrencyId()->first()), -1, 1);
?>

<main class="main">
	<div class="container">
		<nav aria-label="breadcrumb" class="breadcrumb-nav">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="/"><i class="icon-home"></i></a></li>
				<li class="breadcrumb-item"><a href="#">Products</a></li>
			</ol>
		</nav>

		<div class="product-single-container product-single-default">
			<div class="cart-message d-none">
				<strong class="single-cart-notice">“<?= $enc->html( $this->detailProductItem->getName(), $enc::TRUST ) ?>”</strong>
				<span>has been added to your cart.</span>
			</div>

			<div class="row">
				<div class="col-lg-5 col-md-6 product-single-gallery">
					<div class="product-slider-container">
						<div class="label-group">
							<div class="product-label label-hot">HOT</div>

							<div class="product-label label-sale">
								-16%
							</div>
						</div>

						<div class="product-single-carousel owl-carousel owl-theme show-nav-hover">
							<?php
								foreach($listMedia as $id => $media) :
							?>
								<div class="product-item">
									<?php echo $this->image( $media, '(min-width: 2000px) 1920px, (min-width: 500px) 960px, 100vw' ); ?>
								</div>
							<?php
								endforeach
							?>
						</div>
						<!-- End .product-single-carousel -->
						<span class="prod-full-screen">
							<i class="icon-plus"></i>
						</span>
					</div>

					<div class="prod-thumbnail owl-dots">
					<?php foreach( $listMedia as $mediaItem ) :  ?>

						<div class="owl-dot">
							<img class="item-thumb"
								src="<?= $enc->attr( $this->content( $mediaItem->getPreview(), $mediaItem->getFileSystem() ) ) ?>"
								alt="<?= $enc->attr( $this->translate( 'client', 'Product image' ) ) ?>"
								width="110" height="110"
							>
						</div>

					<?php endforeach ?>
					</div>
				</div><!-- End .product-single-gallery -->

				<div class="col-lg-7 col-md-6 product-single-details">
					<h1 class="product-title"><?= $enc->html( $this->detailProductItem->getName(), $enc::TRUST ) ?></h1>

					<div class="ratings-container">
						<div class="product-ratings">
							<span class="ratings" style="width:100%"></span><!-- End .ratings -->
							<span class="tooltiptext tooltip-top"></span>
						</div><!-- End .product-ratings -->

						<a href="#" class="rating-link">( 6 Reviews )</a>
					</div><!-- End .ratings-container -->

					<hr class="short-divider">

					<div class="price-box">
						<?php if($rebate > 0): ?>
							<span class="old-price"><?php echo $currency . $this->number($price, 2); ?></span>
							<span class="new-price"><?php echo $currency . $this->number(($price - $rebate), 2); ?></span>
						<?php else : ?>
							<span class="new-price"><?php echo $currency . $this->number(($price), 2); ?></span>
						<?php endif ?>
					</div><!-- End .price-box -->

					<div class="product-desc">
						<?php foreach( $this->detailProductItem->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>
							<div class="short" itemprop="description"><?= $enc->html( $textItem->getContent(), $enc::TRUST ) ?></div>
						<?php endforeach ?>
					</div><!-- End .product-desc -->

					<div class="product-action">
						<div class="product-single-qty">
							<input class="horizontal-quantity form-control" type="text">
						</div><!-- End .product-single-qty -->

						<a href="javascript:;" class="btn btn-dark add-cart mr-2" title="Add to Cart">Add to
							Cart</a>

						<a href="cart.html" class="btn btn-gray view-cart d-none">View cart</a>
					</div><!-- End .product-action -->

					<hr class="divider mb-0 mt-0">

					<div class="product-single-share mb-3">
						<label class="sr-only">Share:</label>

						<div class="social-icons mr-2">
							<a href="#" class="social-icon social-facebook icon-facebook" target="_blank"
								title="Facebook"></a>
							<a href="#" class="social-icon social-twitter icon-twitter" target="_blank"
								title="Twitter"></a>
							<a href="#" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank"
								title="Linkedin"></a>
							<a href="#" class="social-icon social-gplus fab fa-google-plus-g" target="_blank"
								title="Google +"></a>
							<a href="#" class="social-icon social-mail icon-mail-alt" target="_blank"
								title="Mail"></a>
						</div><!-- End .social-icons -->

						<a href="wishlist.html" class="btn-icon-wish add-wishlist" title="Add to Wishlist"><i
								class="icon-wishlist-2"></i><span>Add to
								Wishlist</span></a>
					</div><!-- End .product single-share -->
				</div><!-- End .product-single-details -->
			</div><!-- End .row -->
		</div><!-- End .product-single-container -->

		<div class="product-single-tabs catalog-detail-additional">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="product-tab-desc" data-toggle="tab"
						href="#product-desc-content" role="tab" aria-controls="product-desc-content"
						aria-selected="true">Description</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="product-tab-tags" data-toggle="tab" href="#product-tags-content"
						role="tab" aria-controls="product-tags-content" aria-selected="false">Additional
						Information</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="product-tab-reviews" data-toggle="tab"
						href="#product-reviews-content" role="tab" aria-controls="product-reviews-content"
						aria-selected="false">Reviews (1)</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="product-desc-content" role="tabpanel"
					aria-labelledby="product-tab-desc">
					<div class="product-desc-content">
						<?php if( !( $textItems = $this->detailProductItem->getRefItems( 'text', 'long' ) )->isEmpty() ) : ?>
							<?php foreach( $textItems as $textItem ) : ?>
								<div class="long item"><?= $enc->html( $textItem->getContent(), $enc::TRUST ) ?></div>
							<?php endforeach ?>
						<?php endif ?>
					</div><!-- End .product-desc-content -->
				</div><!-- End .tab-pane -->

				<div class="tab-pane fade" id="product-tags-content" role="tabpanel"
					aria-labelledby="product-tab-tags">

					<?php if( !$this->get( 'detailAttributeMap', map() )->isEmpty() || !$this->get( 'detailPropertyMap', map() )->isEmpty() ) : ?>

						<div class="block attributes">
							<table class="table table-striped mt-2">
								<tbody>

									<?php foreach( $this->get( 'detailAttributeMap', map() ) as $type => $attrItems ) : ?>
										<?php foreach( $attrItems as $attrItem ) : ?>

											<tr class="item <?= ( $ids = $attrItem->get( 'parent' ) ) ? 'subproduct ' . map( $ids )->prefix( 'subproduct-' )->join( ' ' ) : '' ?>">
												<td class="name"><?= $enc->html( $this->translate( 'client/code', $type ), $enc::TRUST ) ?></td>
												<td class="value">
													<div class="media-list">

														<?php foreach( $attrItem->getListItems( 'media', 'icon' ) as $listItem ) : ?>
															<?php if( ( $refitem = $listItem->getRefItem() ) !== null ) : ?>
																<?= $this->partial(
																	$this->config( 'client/html/common/partials/media', 'common/partials/media' ),
																	['item' => $refitem, 'boxAttributes' => ['class' => 'media-item']]
																) ?>
															<?php endif ?>
														<?php endforeach ?>

													</div><!--
													--><span class="attr-name"><?= $enc->html( $attrItem->getName() ) ?></span>

													<?php foreach( $attrItem->getRefItems( 'text', 'short' ) as $textItem ) : ?>
														<div class="attr-short"><?= $enc->html( $textItem->getContent() ) ?></div>
													<?php endforeach ?>

													<?php foreach( $attrItem->getRefItems( 'text', 'long' ) as $textItem ) : ?>
														<div class="attr-long"><?= $enc->html( $textItem->getContent() ) ?></div>
													<?php endforeach ?>

												</td>
											</tr>

										<?php endforeach ?>
									<?php endforeach ?>

									<?php foreach( $this->get( 'detailPropertyMap', map() ) as $type => $propItems ) : ?>
										<?php foreach( $propItems as $propItem ) : ?>

											<tr class="item <?= ( $id = $propItem->get( 'parent' ) ) ? 'subproduct subproduct-' . $id : '' ?>">
												<td class="name"><?= $enc->html( $this->translate( 'client/code', $propItem->getType() ), $enc::TRUST ) ?></td>
												<td class="value"><?= $enc->html( $propItem->getValue() ) ?></td>
											</tr>

										<?php endforeach ?>
									<?php endforeach ?>

								</tbody>
							</table>
						</div>

						<?php endif ?>
				</div><!-- End .tab-pane -->

				<div class="tab-pane fade" id="product-reviews-content" role="tabpanel"
					aria-labelledby="product-tab-reviews">
					<div class="reviews container-fluid block" data-productid="<?= $enc->attr( $this->detailProductItem->getId() ) ?>">
						<div class="row">
							<div class="col-md-4 rating-list">
								<div class="rating-numbers">
									<div class="rating-num"><?= number_format( $this->detailProductItem->getRating(), 1 ) ?>/5</div>
									<div class="rating-total"><?= $enc->html( sprintf( $this->translate( 'client', '%1$d review', '%1$d reviews', $this->detailProductItem->getRatings() ), $this->detailProductItem->getRatings() ) ) ?></div>
									<div class="rating-stars"><?= str_repeat( '★', (int) round( $this->detailProductItem->getRating() ) ) ?></div>
								</div>
								<table class="rating-dist" data-ratings="<?= $enc->attr( $this->detailProductItem->getRatings() ) ?>">
									<tr>
										<td class="rating-label"><label for="rating-5">★★★★★</label></td>
										<td class="rating-percent"><progress id="rating-5" value="0" max="100">0</progress></td>
									</tr>
									<tr>
										<td class="rating-label"><label for="rating-4">★★★★</label></td>
										<td class="rating-percent"><progress id="rating-4" value="0" max="100">0</progress></td>
									</tr>
									<tr>
										<td class="rating-label"><label for="rating-3">★★★</label></td>
										<td class="rating-percent"><progress id="rating-3" value="0" max="100">0</progress></td>
									</tr>
									<tr>
										<td class="rating-label"><label for="rating-2">★★</label></td>
										<td class="rating-percent"><progress id="rating-2" value="0" max="100">0</progress></td>
									</tr>
									<tr>
										<td class="rating-label"><label for="rating-1">★</label></td>
										<td class="rating-percent"><progress id="rating-1" value="0" max="100">0</progress></td>
									</tr>
								</table>
							</div>
							<div class="col-md-8 review-list">
								<div class="sort">
									<span><?= $enc->html( $this->translate( 'client', 'Sort by:' ), $enc::TRUST ) ?></span>
									<ul>
										<li>
											<a class="sort-option option-ctime active" href="<?= $enc->attr( $this->link( 'client/jsonapi/url', ['resource' => 'review', 'filter' => ['f_refid' => $this->detailProductItem->getId()], 'sort' => '-ctime'] ) ) ?>">
												<?= $enc->html( $this->translate( 'client', 'Latest' ), $enc::TRUST ) ?>
											</a>
										</li>
										<li>
											<a class="sort-option option-rating" href="<?= $enc->attr( $this->link( 'client/jsonapi/url', ['resource' => 'review', 'filter' => ['f_refid' => $this->detailProductItem->getId()], 'sort' => '-rating,-ctime'] ) ) ?>">
												<?= $enc->html( $this->translate( 'client', 'Rating' ), $enc::TRUST ) ?>
											</a>
										</li>
									</ul>
								</div>
								<div class="review-items">
									<div class="review-item prototype">
										<div class="review-name"></div>
										<div class="review-ctime"></div>
										<div class="review-rating">★</div>
										<div class="review-comment"></div>
										<div class="review-response">
											<div class="review-vendor"><?= $enc->html( $this->translate( 'client', 'Vendor response' ) ) ?></div>
										</div>
										<div class="review-show"><a href="#"><?= $enc->html( $this->translate( 'client', 'Show' ) ) ?></a></div><!--
									--></div>
								</div>
								<a class="btn btn-primary more" href="#"><?= $enc->html( $this->translate( 'client', 'More reviews' ), $enc::TRUST ) ?></a>
							</div>
						</div>
					</div><!-- End .product-reviews-content -->
				</div><!-- End .tab-pane -->
			</div><!-- End .tab-content -->
		</div><!-- End .product-single-tabs -->

		<div class="products-section pt-0">
			<h2 class="section-title">Related Products</h2>

			<div class="products-slider owl-carousel owl-theme dots-top dots-small">
				<div class="product-default">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/product-1.jpg" width="280" height="280"
								alt="product">
							<img src="/assets/images/products/product-1-2.jpg" width="280" height="280"
								alt="product">
						</a>
						<div class="label-group">
							<div class="product-label label-hot">HOT</div>
							<div class="product-label label-sale">-20%</div>
						</div>
					</figure>
					<div class="product-details">
						<div class="category-list">
							<a href="category.html" class="product-category">Category</a>
						</div>
						<h3 class="product-title">
							<a href="product.html">Ultimate 3D Bluetooth Speaker</a>
						</h3>
						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:80%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->
						<div class="price-box">
							<del class="old-price">$59.00</del>
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
						<div class="product-action">
							<a href="wishlist.html" title="Wishlist" class="btn-icon-wish"><i
									class="icon-heart"></i></a>
							<a href="product.html" class="btn-icon btn-add-cart"><i
									class="fa fa-arrow-right"></i><span>SELECT
									OPTIONS</span></a>
							<a href="ajax/product-quick-view.html" class="btn-quickview" title="Quick View"><i
									class="fas fa-external-link-alt"></i></a>
						</div>
					</div><!-- End .product-details -->
				</div>

				<div class="product-default">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/product-3.jpg" width="280" height="280"
								alt="product">
							<img src="/assets/images/products/product-3-2.jpg" width="280" height="280"
								alt="product">
						</a>
						<div class="label-group">
							<div class="product-label label-hot">HOT</div>
							<div class="product-label label-sale">-20%</div>
						</div>
					</figure>
					<div class="product-details">
						<div class="category-list">
							<a href="category.html" class="product-category">Category</a>
						</div>
						<h3 class="product-title">
							<a href="product.html">Circled Ultimate 3D Speaker</a>
						</h3>
						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:80%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->
						<div class="price-box">
							<del class="old-price">$59.00</del>
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
						<div class="product-action">
							<a href="wishlist.html" title="Wishlist" class="btn-icon-wish"><i
									class="icon-heart"></i></a>
							<a href="product.html" class="btn-icon btn-add-cart"><i
									class="fa fa-arrow-right"></i><span>SELECT
									OPTIONS</span></a>
							<a href="ajax/product-quick-view.html" class="btn-quickview" title="Quick View"><i
									class="fas fa-external-link-alt"></i></a>
						</div>
					</div><!-- End .product-details -->
				</div>

				<div class="product-default">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/product-7.jpg" width="280" height="280"
								alt="product">
							<img src="/assets/images/products/product-7-2.jpg" width="280" height="280"
								alt="product">
						</a>
						<div class="label-group">
							<div class="product-label label-hot">HOT</div>
							<div class="product-label label-sale">-20%</div>
						</div>
					</figure>
					<div class="product-details">
						<div class="category-list">
							<a href="category.html" class="product-category">Category</a>
						</div>
						<h3 class="product-title">
							<a href="product.html">Brown-Black Men Casual Glasses</a>
						</h3>
						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:80%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->
						<div class="price-box">
							<del class="old-price">$59.00</del>
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
						<div class="product-action">
							<a href="wishlist.html" title="Wishlist" class="btn-icon-wish"><i
									class="icon-heart"></i></a>
							<a href="product.html" class="btn-icon btn-add-cart"><i
									class="fa fa-arrow-right"></i><span>SELECT
									OPTIONS</span></a>
							<a href="ajax/product-quick-view.html" class="btn-quickview" title="Quick View"><i
									class="fas fa-external-link-alt"></i></a>
						</div>
					</div><!-- End .product-details -->
				</div>

				<div class="product-default">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/product-6.jpg" width="280" height="280"
								alt="product">
							<img src="/assets/images/products/product-6-2.jpg" width="280" height="280"
								alt="product">
						</a>
						<div class="label-group">
							<div class="product-label label-hot">HOT</div>
							<div class="product-label label-sale">-20%</div>
						</div>
					</figure>
					<div class="product-details">
						<div class="category-list">
							<a href="category.html" class="product-category">Category</a>
						</div>
						<h3 class="product-title">
							<a href="product.html">Men Black Gentle Belt</a>
						</h3>
						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:80%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->
						<div class="price-box">
							<del class="old-price">$59.00</del>
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
						<div class="product-action">
							<a href="wishlist.html" title="Wishlist" class="btn-icon-wish"><i
									class="icon-heart"></i></a>
							<a href="product.html" class="btn-icon btn-add-cart"><i
									class="fa fa-arrow-right"></i><span>SELECT
									OPTIONS</span></a>
							<a href="ajax/product-quick-view.html" class="btn-quickview" title="Quick View"><i
									class="fas fa-external-link-alt"></i></a>
						</div>
					</div><!-- End .product-details -->
				</div>

				<div class="product-default">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/product-4.jpg" width="280" height="280"
								alt="product">
							<img src="/assets/images/products/product-4-2.jpg" width="280" height="280"
								alt="product">
						</a>
						<div class="label-group">
							<div class="product-label label-hot">HOT</div>
							<div class="product-label label-sale">-20%</div>
						</div>
					</figure>
					<div class="product-details">
						<div class="category-list">
							<a href="category.html" class="product-category">Category</a>
						</div>
						<h3 class="product-title">
							<a href="product.html">Blue Backpack for the Young - S</a>
						</h3>
						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:80%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->
						<div class="price-box">
							<del class="old-price">$59.00</del>
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
						<div class="product-action">
							<a href="wishlist.html" title="Wishlist" class="btn-icon-wish"><i
									class="icon-heart"></i></a>
							<a href="product.html" class="btn-icon btn-add-cart"><i
									class="fa fa-arrow-right"></i><span>SELECT
									OPTIONS</span></a>
							<a href="ajax/product-quick-view.html" class="btn-quickview" title="Quick View"><i
									class="fas fa-external-link-alt"></i></a>
						</div>
					</div><!-- End .product-details -->
				</div>
			</div><!-- End .products-slider -->
		</div><!-- End .products-section -->

		<hr class="mt-0 m-b-5" />

		<div class="product-widgets-container row pb-2">
			<div class="col-lg-3 col-sm-6 pb-5 pb-md-0">
				<h4 class="section-sub-title">Featured Products</h4>
				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-1.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-1-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Ultimate 3D Bluetooth Speaker</a>
						</h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-2.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-2-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Brown Women Casual HandBag</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top">5.00</span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-3.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-3-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Circled Ultimate 3D Speaker</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>
			</div>

			<div class="col-lg-3 col-sm-6 pb-5 pb-md-0">
				<h4 class="section-sub-title">Best Selling Products</h4>
				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-4.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-4-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Blue Backpack for the Young - S</a>
						</h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top">5.00</span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-5.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-5-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Casual Spring Blue Shoes</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-6.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-6-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Men Black Gentle Belt</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top">5.00</span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>
			</div>

			<div class="col-lg-3 col-sm-6 pb-5 pb-md-0">
				<h4 class="section-sub-title">Latest Products</h4>
				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-7.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-7-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Men Black Sports Shoes</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-8.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-8-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Brown-Black Men Casual Glasses</a>
						</h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top">5.00</span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-9.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-9-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Black Men Casual Glasses</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>
			</div>

			<div class="col-lg-3 col-sm-6 pb-5 pb-md-0">
				<h4 class="section-sub-title">Top Rated Products</h4>
				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-10.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-10-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Basketball Sports Blue Shoes</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-11.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-11-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Men Sports Travel Bag</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top">5.00</span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>

				<div class="product-default left-details product-widget">
					<figure>
						<a href="product.html">
							<img src="/assets/images/products/small/product-12.jpg" width="74" height="74"
								alt="product">
							<img src="/assets/images/products/small/product-12-2.jpg" width="74" height="74"
								alt="product">
						</a>
					</figure>

					<div class="product-details">
						<h3 class="product-title"> <a href="product.html">Brown HandBag</a> </h3>

						<div class="ratings-container">
							<div class="product-ratings">
								<span class="ratings" style="width:100%"></span><!-- End .ratings -->
								<span class="tooltiptext tooltip-top"></span>
							</div><!-- End .product-ratings -->
						</div><!-- End .product-container -->

						<div class="price-box">
							<span class="product-price">$49.00</span>
						</div><!-- End .price-box -->
					</div><!-- End .product-details -->
				</div>
			</div>
		</div><!-- End .row -->
	</div><!-- End .container -->
</main><!-- End .main -->