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
    <?= $aibody['catalog/home'] ?? '' ?>
    <!-- End .home-slider -->

    <div class="container">
        <?= $aibody['cms/page'] ?? '' ?>
    </div>

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
