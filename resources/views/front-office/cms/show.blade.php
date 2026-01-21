@extends('front-office.layouts.app')

@section('title', $page->title)

@section('styles')
<style>
    .cms-page-container {
        min-height: 100vh;
        padding: 120px 50px 50px;
        background: #ffffff;
    }

    .cms-page-content {
        max-width: 1200px;
        margin: 0 auto;
        background: #fff;
        padding: 40px;
        border-radius: 8px;
    }

    .cms-page-title {
        font-size: 36px;
        font-weight: bold;
        color: #111;
        margin-bottom: 30px;
        text-align: center;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .cms-page-body {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }

    .cms-page-body h1,
    .cms-page-body h2,
    .cms-page-body h3,
    .cms-page-body h4,
    .cms-page-body h5,
    .cms-page-body h6 {
        margin-top: 30px;
        margin-bottom: 15px;
        color: #111;
        font-weight: bold;
    }

    .cms-page-body h1 {
        font-size: 32px;
    }

    .cms-page-body h2 {
        font-size: 28px;
    }

    .cms-page-body h3 {
        font-size: 24px;
    }

    .cms-page-body p {
        margin-bottom: 15px;
    }

    .cms-page-body ul,
    .cms-page-body ol {
        margin-bottom: 15px;
        padding-left: 30px;
    }

    .cms-page-body li {
        margin-bottom: 8px;
    }

    .cms-page-body a {
        color: #ff0000;
        text-decoration: none;
        transition: all 0.3s;
    }

    .cms-page-body a:hover {
        text-decoration: underline;
    }

    .cms-page-body img {
        max-width: 100%;
        height: auto;
        margin: 20px 0;
        border-radius: 8px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }

    .back-link:hover {
        color: #ff0000;
    }

    .back-link i {
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .cms-page-container {
            padding: 100px 20px 30px;
        }

        .cms-page-content {
            padding: 20px;
        }

        .cms-page-title {
            font-size: 24px;
        }

        .cms-page-body {
            font-size: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="cms-page-container">
    <div class="cms-page-content">
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
        
        <h1 class="cms-page-title">{{ $page->title }}</h1>
        
        <div class="cms-page-body">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
