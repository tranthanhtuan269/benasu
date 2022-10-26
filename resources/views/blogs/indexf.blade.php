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
                <li class="breadcrumb-item active" aria-current="page">Blog</li>
            </ol>
        </div><!-- End .container -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-section row">
                    @foreach($blogs as $blog)
                    <div class="col-md-6 col-lg-4">
                        <article class="post">
                            <div class="post-media">
                                <a href="/blogs/{{ $blog->slug }}">
                                    <img src="/images/{{ $blog->image }}" alt="Post" width="225" height="280">
                                </a>
                                <div class="post-date">
                                    <span class="day">{{ $blog->created_at->format('d') }}</span>
                                    <span class="month">{{ $blog->created_at->format('M') }}</span>
                                </div>
                            </div><!-- End .post-media -->

                            <div class="post-body">
                                <h2 class="post-title">
                                    <a href="/blogs/{{ $blog->slug }}">{{ $blog->title }}</a>
                                </h2>
                                <div class="post-content">
                                    {!! substr($blog->content, 0, 200) !!}
                                </div><!-- End .post-content -->
                                <a href="/blogs/{{ $blog->slug }}" class="post-comment">{{ $blog->comments()->count() }} Comments</a>
                            </div><!-- End .post-body -->
                        </article><!-- End .post -->
                    </div>
                    @endforeach
                </div>
            </div><!-- End .col-lg-9 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
</main>
@stop
