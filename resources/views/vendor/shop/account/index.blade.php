@extends('shop::base')

@section('aimeos_header')
    <title>{{ __( 'Profile') }}</title>
    <link type="text/css" rel="stylesheet" href="{{ asset('vendor/shop/themes/default/aimeos.css?v=' . config( 'shop.version', 1 ) ) }}" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $aiheader['locale/select'] ?? '' ?>
    <?= $aiheader['basket/mini'] ?? '' ?>
    <?= $aiheader['account/profile'] ?? '' ?>
    <?= $aiheader['account/review'] ?? '' ?>
    <?= $aiheader['account/subscription'] ?? '' ?>
    <?= $aiheader['account/history'] ?? '' ?>
    <?= $aiheader['account/favorite'] ?? '' ?>
    <?= $aiheader['account/watch'] ?? '' ?>
    <?= $aiheader['catalog/search'] ?? '' ?>
    <?= $aiheader['catalog/session'] ?? '' ?>
    <?= $aiheader['catalog/tree'] ?? '' ?>
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
<main class="main">
	<div class="container">
        <?= $aibody['account/profile'] ?? '' ?>
        <?= $aibody['account/review'] ?? '' ?>
        <?= $aibody['account/subscription'] ?? '' ?>
        <?= $aibody['account/history'] ?? '' ?>
        <?= $aibody['account/favorite'] ?? '' ?>
        <?= $aibody['account/watch'] ?? '' ?>

        <section class="aimeos account-refer" data-jsonurl="http://myshop.test/jsonapi">
            <div class="container-xxl">
                <div class="account-profile-address">
                <h1 class="header">Referrer</h1>
                <form method="POST" action="/profile">
                    <input class="csrf-token" type="hidden" name="_token" value="jznOeyu4sF4MdBjWM8q0NwlxNiHOJ3z7GSiMvGYE">
                    <div class="row">
                        <div class="billing col-md-12">
                            <div class="form-list">
                                <div class="form-item form-group row user-presenter">
                                    <label class="col-md-4" for="user-presenter">Your Referrer code: </label>
                                    <div class="col-md-8">
                                    <input class="form-control" type="text" id="user-presenter" value="{{ url('/') }}/referal={{ \Auth::user()->refer_code }}" placeholder="Referrer code" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </section>
    </div>
</main>
@stop

@section('aimeos_aside')
    <?= $aibody['catalog/session'] ?>
@stop
