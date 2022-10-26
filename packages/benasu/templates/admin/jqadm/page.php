<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2022
 */

$enc = $this->encoder();


/** admin/jsonadm/url/options/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/controller
 * @see admin/jsonadm/url/options/action
 * @see admin/jsonadm/url/options/config
 */

/** admin/jsonadm/url/options/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/target
 * @see admin/jsonadm/url/options/action
 * @see admin/jsonadm/url/options/config
 */

/** admin/jsonadm/url/options/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/target
 * @see admin/jsonadm/url/options/controller
 * @see admin/jsonadm/url/options/config
 */

/** admin/jsonadm/url/options/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  admin/jsonadm/url/options/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.04
 * @category Developer
 * @see admin/jsonadm/url/options/target
 * @see admin/jsonadm/url/options/controller
 * @see admin/jsonadm/url/options/action
 */


/** admin/jqadm/navbar
 * List of JQAdm client names shown in the navigation bar of the admin interface
 *
 * You can add, remove or reorder the links in the navigation bar by
 * setting a new list of client resource names.
 * In the configuration files of extensions, you should only add entries using
 * one of these lines:
 *
 *  'myclient' => 'myclient',
 *  'myclient-subclient' => 'myclient/subclient',
 *
 * The key of the new client must be unique in the extension configuration so
 * it's not overwritten by other extensions. Don't use slashes in keys (/)
 * because they are interpreted as keys of sub-arrays in the configuration.
 *
 * @param array List of resource client names
 * @since 2017.10
 * @category Developer
 * @see admin/jqadm/navbar-limit
 */
$navlist = map( $this->config( 'admin/jqadm/navbar', [] ) )->ksort();

foreach( $navlist as $key => $navitem )
{
	$name = is_array( $navitem ) ? ( $navitem['_'] ?? current( $navitem ) ) : $navitem;

	if( !$this->access( $this->config( 'admin/jqadm/resource/' . $name . '/groups', [] ) ) ) {
		$navlist->remove( $key );
	}
}


$resource = $this->param( 'resource', 'dashboard' );
$site = $this->param( 'site', 'default' );
$lang = $this->param( 'locale' );

$params = ['resource' => $resource, 'site' => $site];
$extParams = ['site' => $site];

if( $lang ) {
	$params['locale'] = $extParams['locale'] = $lang;
}


$pos = $navlist->pos( function( $item, $key ) use ( $resource ) {
	return is_array( $item ) ? in_array( $resource, $item ) : !strncmp( $resource, $item, strlen( $item ) );
} );
$before = $pos > 0 ? $navlist->slice( $pos - 1, 1 )->first() : null;
$before = is_array( $before ) ? $before['_'] ?? reset( $before ) : $before;
$after = $pos < count( $navlist ) ? $navlist->slice( $pos + 1, 1 )->first() : null;
$after = is_array( $after ) ? $after['_'] ?? reset( $after ) : $after;


?>
<div class="aimeos" lang="<?= $this->param( 'locale' ) ?>" data-url="<?= $enc->attr( $this->link( 'admin/jsonadm/url/options', array( 'site' => $site ) ) ) ?>">

	<nav class="main-sidebar">
		<div class="sidebar-wrapper">
			<ul class="sidebar-menu">
				<?php if( $this->access( $this->config( 'admin/jqadm/resource/site/groups', [] ) ) ) : ?>

					<li class="none"></li>
					<li class="treeview menuitem-site <?= $before === null ? 'before' : '' ?>">
						<a class="item-group" href="#">
							<i class="icon"></i>
							<span class="title"><?= $enc->html( $this->site()->label() ) ?></span>
						</a>
						<div class="tree-menu-wrapper">
							<div class="menu-header">
								<a href="#"><?= $enc->html( $this->translate( 'admin', 'Site' ) ) ?></a>
								<span class="close"></span>
							</div>
							<div class="menu-body vue" data-key="sidebar-sites">
								<site-tree
									v-bind:promise="Aimeos.options"
									current="<?= $enc->attr( $this->pageSiteItem->getId() ) ?>"
									parent="<?= $enc->attr( $this->pageSitePath->getParentId()->first( '0' ) ) ?>"
									placeholder="<?= $enc->attr( $this->translate( 'admin', 'Find site' ) ) ?>"
									url="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['site' => '_code_'] + $params ) ) ?>">
								</site-tree>
							</div>
						</div>
					</li>

				<?php else : ?>

					<li class="none <?= $before === null ? 'before' : '' ?>"></li>

				<?php endif ?>

				<?php foreach( $navlist as $nav => $navitem ) : ?>
					<?php if( is_array( $navitem ) ) : $nav = $navitem['_'] ?? current( $nav ) ?>

						<li class="treeview menuitem-<?= $enc->attr( $nav ) ?> <?= $nav === $before ? 'before' : '' ?> <?= in_array( $resource, $navitem ) !== false ? 'active' : '' ?> <?= $nav === $after ? 'after' : '' ?>">
							<span class="item-group">
								<i class="icon"></i>
								<span class="title"><?= $enc->attr( $this->translate( 'admin', $nav ) ) ?></span>
							</span>
							<div class="tree-menu-wrapper">
								<div class="menu-header">
									<a href="#"><?= $enc->html( $this->translate( 'admin', $nav ) ) ?></a>
									<span class="close"></span>
								</div>
								<ul class="tree-menu">

								<?php foreach( map( $navitem )->remove( '_' )->ksort() as $subresource ) : ?>
										<?php if( $this->access( $this->config( 'admin/jqadm/resource/' . $subresource . '/groups', [] ) ) ) : ?>
											<?php $key = $this->config( 'admin/jqadm/resource/' . $subresource . '/key', '' ) ?>

											<li class="menuitem-<?= str_replace( '/', '-', $subresource ) ?> <?= $subresource === $resource ? 'active' : '' ?>">
												<a class="item-group" href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['resource' => $subresource] + $params ) ) ?>"
													title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' ), $this->translate( 'admin', $subresource ), $key ) ) ?>"
													data-ctrlkey="<?= $enc->attr( strtolower( $key ) ) ?>">
													<i class="icon"></i>
													<span class="name"><?= $enc->html( $this->translate( 'admin', $subresource ) ) ?></span>
												</a>
											</li>

										<?php endif ?>
									<?php endforeach ?>
								</ul>
							</div>
						</li>

					<?php else : ?>
						<?php $key = $this->config( 'admin/jqadm/resource/' . $navitem . '/key' ) ?>

						<li class="menuitem-<?= $enc->attr( $navitem ) ?> <?= $navitem === $before ? 'before' : '' ?> <?= !strncmp( $resource, $navitem, strlen( $navitem ) ) ? 'active' : '' ?> <?= $navitem === $after ? 'after' : '' ?>">
							<a class="item-group" href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['resource' => $navitem] + $params ) ) ?>"
								title="<?= $enc->attr( sprintf( $this->translate( 'admin', '%1$s (Ctrl+Alt+%2$s)' ), $this->translate( 'admin', $navitem ), $key ) ) ?>"
								data-ctrlkey="<?= $enc->attr( strtolower( $key ) ) ?>">
								<i class="icon"></i>
								<span class="title"><?= $enc->html( $this->translate( 'admin', $navitem ) ) ?></span>
							</a>
						</li>

					<?php endif ?>
				<?php endforeach ?>

				<li class="treeview menuitem-language">
					<span class="item-group">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
							<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
							<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
						</svg>
						<a href="/admin/configs">
							<span class="title">Configs</span>
						</a>
					</span>
				</li>

				<li class="treeview menuitem-language">
					<span class="item-group">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
							<path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
							<path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
						</svg>
						<a href="/admin/blogs">
							<span class="title">Blog</span>
						</a>
					</span>
				</li>

				<li class="treeview menuitem-language">
					<span class="item-group">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
							<path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
							<path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
						</svg>
						<a href="/admin/pages">
							<span class="title">Pages</span>
						</a>
					</span>
				</li>

				<?php if( $this->access( $this->config( 'admin/jqadm/resource/language/groups', [] ) ) ) : ?>

					<li class="treeview menuitem-language <?= $after === null ? 'after' : '' ?>">
						<span class="item-group">
							<i class="icon"></i>
							<span class="title"><?= $enc->attr( $this->translate( 'language', $this->param( 'locale', $this->translate( 'admin', 'Language' ) ) ) ) ?></span>
						</span>
						<div class="tree-menu-wrapper">
							<div class="menu-header">
								<a href="#"><?= $enc->html( $this->translate( 'admin', 'Language' ) ) ?></a>
								<span class="close"></span>
							</div>
							<ul class="tree-menu">
								<?php foreach( $this->get( 'pageI18nList', [] ) as $langid ) : ?>
									<li class="menuitem-language-<?= $enc->attr( $langid ) ?>">
										<a href="<?= $enc->attr( $this->link( 'admin/jqadm/url/search', ['locale' => $langid] + $params ) ) ?>">
											<span class="name"><?= $enc->html( $this->translate( 'language', $langid ) ) ?> (<?= $langid ?>)</span>
										</a>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</li>

				<?php endif ?>

				<li class="none"></li>
			</ul>

		</div>
	</nav>

	<main class="main-content">
		<?= $this->partial( $this->config( 'admin/jqadm/partial/info', 'info' ), [
			'info' => array_merge( $this->get( 'pageInfo', [] ), $this->get( 'info', [] ) ),
			'error' => $this->get( 'errors', [] )
		] ) ?>

		<?= $this->block()->get( 'jqadm_content' ) ?>
	</main>

	<footer class="main-footer">
		<a href="https://github.com/aimeos/ai-admin-jqadm/issues" target="_blank">
			<?= $enc->html( $this->translate( 'admin', 'Bug or suggestion?' ) ) ?>
		</a>
	</footer>

	<?= $this->partial( $this->config( 'admin/jqadm/partial/confirm', 'confirm' ) ) ?>
	<?= $this->partial( $this->config( 'admin/jqadm/partial/problem', 'problem' ) ) ?>

</div>
