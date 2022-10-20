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
                <li class="breadcrumb-item active" aria-current="page">Blog Post</li>
            </ol>
        </div><!-- End .container -->
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <article class="post single">
                    <div class="post-media">
                        <img src="/images/{{ $blog->image }}" alt="Post">
                    </div><!-- End .post-media -->

                    <div class="post-body">
                        <div class="post-date">
                            <span class="day">{{ $blog->created_at->format('d') }}</span>
                            <span class="month">{{ $blog->created_at->format('M') }}</span>
                        </div><!-- End .post-date -->

                        <h2 class="post-title">{{ $blog->title }}</h2>

                        <div class="post-meta">
                            <a href="#" class="hash-scroll">{{ $blog->comments()->count() }} Comments</a>
                        </div><!-- End .post-meta -->

                        <div class="post-content">
                            {!! $blog->content !!}
                        </div><!-- End .post-content -->

                        <div class="post-share">
                            <h3 class="d-flex align-items-center">
                                <i class="fas fa-share"></i>
                                Share this post
                            </h3>

                            <div class="social-icons">
                                <a href="#" class="social-icon social-facebook" target="_blank"
                                    title="Facebook">
                                    <i class="icon-facebook"></i>
                                </a>
                                <a href="#" class="social-icon social-twitter" target="_blank" title="Twitter">
                                    <i class="icon-twitter"></i>
                                </a>
                                <a href="#" class="social-icon social-linkedin" target="_blank"
                                    title="Linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="social-icon social-gplus" target="_blank" title="Google +">
                                    <i class="fab fa-google-plus-g"></i>
                                </a>
                                <a href="#" class="social-icon social-mail" target="_blank" title="Email">
                                    <i class="icon-mail-alt"></i>
                                </a>
                            </div><!-- End .social-icons -->
                        </div><!-- End .post-share -->

                        <div class="post-author">
                            <h3><i class="far fa-user"></i>Author</h3>

                            <figure>
                                <a href="#">
                                    <img src="/images/man.png" alt="author">
                                </a>
                            </figure>

                            <div class="author-content">
                                <h4><a href="#">John Doe</a></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod
                                    odio, gravida pellentesque urna varius vitae. Sed dui lorem, adipiscing in
                                    adipiscing et, interdum nec metus. Mauris ultricies, justo eu convallis
                                    placerat, felis enim ornare nisi, vitae mattis nulla ante id dui.</p>
                            </div><!-- End .author.content -->
                        </div><!-- End .post-author -->

                        @foreach($blog->comments as $comment)
                        <hr />

                        <div class="post-author">
                            <h3><i class="far fa-user"></i>User</h3>

                            <figure>
                                <a href="#">
                                    <img src="/images/man.png" alt="author">
                                </a>
                            </figure>

                            <div class="author-content">
                                <h4><a href="#">{{ $comment->username }}</a></h4>
                                <p>{{ $comment->description }}</p>
                            </div><!-- End .author.content -->
                        </div><!-- End .post-author -->

                        @endforeach
                        <hr />

                        <div class="comment-respond">
                            <h3>Leave a Reply</h3>

                            <form action="/comments" method="POST">
                                @csrf
                                <input name="blog_id" type="hidden" class="form-control" value="{{ $blog->id }}">
                                <p>Your email address will not be published. Required fields are marked *</p>

                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea name="description" cols="30" rows="1" class="form-control" required></textarea>
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>Name</label>
                                    <input name="username" type="text" class="form-control" required>
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>Email</label>
                                    <input name="useremail" type="email" class="form-control" required>
                                </div><!-- End .form-group -->

                                <div class="form-footer my-0">
                                    <button type="submit" class="btn btn-sm btn-primary">Post
                                        Comment</button>
                                </div><!-- End .form-footer -->
                            </form>
                        </div><!-- End .comment-respond -->
                    </div><!-- End .post-body -->
                </article><!-- End .post -->

                <hr class="mt-2 mb-1">

                <div class="related-posts">
                    <h4>Related <strong>Posts</strong></h4>

                    <div class="owl-carousel owl-theme related-posts-carousel" data-owl-options="{
                        'dots': false
                    }">
                        @foreach($relativeBlogs as $post)
                        <article class="post">
                            <div class="post-media zoom-effect">
                                <a href="/blogs/{{ $post->slug }}">
                                    <img src="/images/{{ $post->image }}" alt="Post">
                                </a>
                            </div><!-- End .post-media -->

                            <div class="post-body">
                                <div class="post-date">
                                    <span class="day">{{ $blog->created_at->format('d') }}</span>
                                    <span class="month">{{ $blog->created_at->format('M') }}</span>
                                </div><!-- End .post-date -->

                                <h2 class="post-title">
                                    <a href="single.html">Post Format - Image</a>
                                </h2>

                                <div class="post-content">
                                    <p>{!! $post->description !!}</p>

                                    <a href="/blogs/{{ $post->slug }}" class="read-more">Read more <i
                                            class="fas fa-angle-right"></i></a>
                                </div><!-- End .post-content -->
                            </div><!-- End .post-body -->
                        </article>
                        @endforeach
                    </div><!-- End .owl-carousel -->
                </div><!-- End .related-posts -->
            </div><!-- End .col-lg-9 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
</main><!-- End .main -->
@stop
