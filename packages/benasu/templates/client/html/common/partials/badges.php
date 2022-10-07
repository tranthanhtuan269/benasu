<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022
 */

$enc = $this->encoder();


?>
<div class="label-group">
	<span class="product-label label-hot"><?= $enc->html( $this->translate( 'client', 'New' ) ) ?></span>
	<span class="product-label label-sale"><?= $enc->html( $this->translate( 'client', 'Sale' ) ) ?></span>
</div>