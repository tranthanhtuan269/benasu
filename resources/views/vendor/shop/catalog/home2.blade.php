@extends('shop::base')

@section('aimeos_header')
    <?= $aiheader['basket/mini'] ?? '' ?>
    <?= $aiheader['catalog/tree'] ?? '' ?>
    <?= $aiheader['catalog/home'] ?? '' ?>
@stop

@section('aimeos_head_basket')
    <?= $aibody['basket/mini'] ?? '' ?>
@stop

@section('aimeos_head_nav')
    <?= $aibody['catalog/tree'] ?? '' ?>
@stop

@section('aimeos_body')
    <?= $aibody['catalog/home'] ?? '' ?>
    <?= $aibody['cms/page'] ?? '' ?>
@stop
