@extends('shop::base')

@section('aimeos_styles')
@stop

@section('aimeos_scripts')
@stop

@section('aimeos_nav')
@stop

@section('aimeos_stage')
@stop

@section('aimeos_header')
    <title>{{ __( 'Favorite') }}</title>
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/shop/themes/default/aimeos.css?v=' . config( 'shop.version', 1 ) ) }}" />
    <?= $aiheader['account/favorite'] ?>
    <?= $aiheader['account/history'] ?? '' ?>
    <?= $aiheader['account/review'] ?? '' ?>
@stop

@section('aimeos_head')
@stop

@section('aimeos_aside')
@stop

@section('aimeos_body')
<main class="main">
	<div class="container">
        <?= $aibody['account/favorite'] ?>
        <?= $aibody['account/history'] ?? '' ?>
        <?= $aibody['account/review'] ?? '' ?>
    </div>
</main>
@stop