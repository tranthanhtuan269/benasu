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
@php
    $blogs = \App\Models\Blog::orderBy('created_at', 'desc')->take(4)->get();
@endphp
<main class="main">
    <?= $aibody['catalog/home'] ?? '' ?>
    <!-- End .home-slider -->

    <div class="container">
        <?= $aibody['cms/page'] ?? '' ?>
    </div>

    <section class="blog-section pb-0">
        <div class="container">
            <h2 class="section-title heading-border border-0 appear-animate" data-animation-name="fadeInUp">
                Latest News</h2>

            <div class="owl-carousel owl-theme appear-animate" data-animation-name="fadeIn" data-owl-options="{
                'loop': false,
                'margin': 20,
                'autoHeight': true,
                'autoplay': false,
                'dots': false,
                'items': 2,
                'responsive': {
                    '0': {
                        'items': 1
                    },
                    '480': {
                        'items': 2
                    },
                    '576': {
                        'items': 3
                    },
                    '768': {
                        'items': 4
                    }
                }
            }">
                @foreach($blogs as $blog)
                    <article class="post">
                        <div class="post-media">
                            <a href="/blogs/{{ $blog->slug }}">
                                <img src="/images/{{ $blog->image }}" alt="Post" width="225" height="280">
                            </a>
                            <div class="post-date">
                                <span class="day">{{ $blog->created_at->format('d') }}</span>
                                <span class="month">{{ $blog->created_at->format('M') }}</span>
                            </div>
                        </div>
                        <!-- End .post-media -->

                        <div class="post-body">
                            <h2 class="post-title">
                                <a href="/blogs/{{ $blog->slug }}">{{ $blog->title }}</a>
                            </h2>
                            <div class="post-content">
                                {!! $blog->description !!}
                            </div>
                            <!-- End .post-content -->
                            <a href="/blogs/{{ $blog->slug }}" class="post-comment">{{ $blog->comments()->count() }} Comments</a>
                        </div>
                        <!-- End .post-body -->
                    </article>
                    <!-- End .post -->
                @endforeach
            </div>
        </div>
    </section>

    <section class="blog-section pb-0" id="sub_view_product">
        <div class="container">
            <div class="product-widgets-container row pb-2">
                <div id="sub_featured_products" class="col-lg-3 col-sm-6 pb-5 pb-md-0">
                    <h4 id="title_sub_featured_products" class="section-sub-title">Featured Products</h4>
                </div>

                <div id="best_selling_products" class="col-lg-3 col-sm-6 pb-5 pb-md-0">
                    <h4 class="section-sub-title">Best Selling Products</h4>
                </div>

                <div id="lastest_products" class="col-lg-3 col-sm-6 pb-5 pb-md-0">
                    <h4 class="section-sub-title">Latest Products</h4>
                </div>

                <div id="top_rated_products" class="col-lg-3 col-sm-6 pb-5 pb-md-0">
                    <h4 class="section-sub-title">Top Rated Products</h4>
                </div>
            </div>
            <!-- End .row -->
        </div>
    </section>
</main>
<!-- End .main -->
@stop
