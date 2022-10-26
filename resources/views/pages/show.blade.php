@extends('shop::base')

@section('aimeos_header')
    <?= $aiheader['catalog/home'] ?? '' ?>
    <?= $aiheader['basket/mini'] ?? '' ?>
    <?= $aiheader['cms/page'] ?? '' ?>
@stop

@section('aimeos_head_basket')
    <?= $aibody['basket/mini'] ?? '' ?>
@stop

@section('aimeos_body')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->name }}</li>
            </ol>
        </div><!-- End .container -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <article class="post single">
                    <div class="post-body">
                        <h2 class="post-title mt-3 mb-2 text-center">{{ $page->name }}</h2>

                        <div class="post-content">
                            {!! $page->content !!}
                        </div><!-- End .post-content -->
                    </div><!-- End .post-body -->
                </article><!-- End .post -->
            </div><!-- End .col-lg-9 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
</main><!-- End .main -->
@stop
