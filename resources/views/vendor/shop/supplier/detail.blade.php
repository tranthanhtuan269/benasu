@extends('shop::base')

@section('aimeos_header')
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/shop/themes/default/aimeos.css?v=' . config( 'shop.version', 1 ) ) }}" />
    <?= $aiheader['locale/select'] ?? '' ?>
    <?= $aiheader['basket/mini'] ?? '' ?>
    <?= $aiheader['catalog/tree'] ?? '' ?>
    <?= $aiheader['catalog/search'] ?? '' ?>
    <?= $aiheader['supplier/detail'] ?? '' ?>
    <?= $aiheader['catalog/lists'] ?? '' ?>
@stop

@section('aimeos_head_basket')
    <?= $aibody['basket/mini'] ?? '' ?>
@stop

@section('aimeos_head_nav')
    <?= $aibody['catalog/tree'] ?? '' ?>
@stop

@section('aimeos_head_locale')
    <?= $aibody['locale/select'] ?? '' ?>
@stop

@section('aimeos_head_search')
    <?= $aibody['catalog/search'] ?? '' ?>
@stop

@section('aimeos_body')
<main class="main" id="supplier-page">
    <div class="container">
        <?= $aibody['supplier/detail'] ?? '' ?>
        <?= $aibody['catalog/lists'] ?? '' ?>
    </div>
</main>
@stop
