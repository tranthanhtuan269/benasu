@extends('shop::base_cart')

@section('aimeos_header')
    <title>{{ __( 'Basket') }}</title>
    <?= $aiheader['locale/select'] ?? '' ?>
    <?= $aiheader['catalog/search'] ?? '' ?>
    <?= $aiheader['catalog/tree'] ?? '' ?>
    <?= $aiheader['basket/bulk'] ?? '' ?>
    <?= $aiheader['basket/standard'] ?? '' ?>
    <?= $aiheader['basket/related'] ?? '' ?>
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
<main class="main" id="basket-page">
	<div class="container">
        <?= $aibody['basket/standard'] ?? '' ?>
        <?= $aibody['basket/related'] ?? '' ?>
        <?= $aibody['basket/bulk'] ?? '' ?>
    </div>
</main>
@stop
