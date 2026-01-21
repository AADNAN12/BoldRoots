<style>
    /* Footer */
    .main-footer {
        background: #000;
        border-top: 1px solid rgba(255, 0, 0, 0.3);
        padding: 15px 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .main-footer a {
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        letter-spacing: 1px;
        transition: all 0.3s;
        position: relative;
    }

    .main-footer a:hover {
        color: #ff0000;
    }

    .main-footer a:not(:last-child)::after {
        content: '|';
        position: absolute;
        right: -18px;
        color: #666;
    }

    .footer-social {
        display: flex;
        gap: 15px;
        margin-left: 20px;
    }

    .footer-social a {
        font-size: 16px;
    }

    .footer-social a::after {
        content: none !important;
    }

    @media (max-width: 768px) {
        .main-footer {
            padding: 15px 20px;
            gap: 15px;
            font-size: 11px;
        }

        .main-footer a:not(:last-child)::after {
            right: -10px;
        }
    }
</style>

<!-- Footer -->
<footer class="main-footer">
    @php
        $cmsPages = \App\Models\CmsPage::active()->ordered()->get();
        $socialFacebook = \App\Models\SiteSetting::get('social_facebook');
        $socialInstagram = \App\Models\SiteSetting::get('social_instagram');
        $socialTwitter = \App\Models\SiteSetting::get('social_twitter');
        $socialYoutube = \App\Models\SiteSetting::get('social_youtube');
        $socialTiktok = \App\Models\SiteSetting::get('social_tiktok');
        $socialLinkedin = \App\Models\SiteSetting::get('social_linkedin');
    @endphp
    <a href="{{ route('contact')}}">Contact Us</a>
    @foreach($cmsPages as $page)
        <a href="{{ route('cms.show', $page->slug) }}">{{ $page->title }}</a>
    @endforeach
    
    <div class="footer-social">
        @if($socialFacebook)
            <a href="{{ $socialFacebook }}" target="_blank" rel="noopener noreferrer" title="Facebook">
                <i class="fab fa-facebook"></i>
            </a>
        @endif
        
        @if($socialInstagram)
            <a href="{{ $socialInstagram }}" target="_blank" rel="noopener noreferrer" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        @endif
        
        @if($socialTwitter)
            <a href="{{ $socialTwitter }}" target="_blank" rel="noopener noreferrer" title="Twitter">
                <i class="fab fa-twitter"></i>
            </a>
        @endif
        
        @if($socialYoutube)
            <a href="{{ $socialYoutube }}" target="_blank" rel="noopener noreferrer" title="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
        @endif
        
        @if($socialTiktok)
            <a href="{{ $socialTiktok }}" target="_blank" rel="noopener noreferrer" title="TikTok">
                <i class="fab fa-tiktok"></i>
            </a>
        @endif
        
        @if($socialLinkedin)
            <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener noreferrer" title="LinkedIn">
                <i class="fab fa-linkedin"></i>
            </a>
        @endif
    </div>
    <div class="footer-copyright">
        <span style="color:white;">&copy; {{ date('Y') }} BOLDROOTS. Tous droits réservés.</span>
    </div>
    
</footer>
