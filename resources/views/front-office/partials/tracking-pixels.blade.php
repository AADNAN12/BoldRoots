{{-- Tracking Pixels - Google Analytics, Facebook Pixel, TikTok Pixel --}}
@php
    $googleAnalyticsId = \App\Models\SiteSetting::get('google_analytics_id');
    $facebookPixelId = \App\Models\SiteSetting::get('facebook_pixel_id');
    $tiktokPixelId = \App\Models\SiteSetting::get('tiktok_pixel_id');
@endphp

{{-- Google Analytics 4 / Universal Analytics --}}
@if($googleAnalyticsId)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $googleAnalyticsId }}');
    </script>
@endif

{{-- Facebook (Meta) Pixel --}}
@if($facebookPixelId)
    <!-- Meta Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $facebookPixelId }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" 
             src="https://www.facebook.com/tr?id={{ $facebookPixelId }}&ev=PageView&noscript=1"/>
    </noscript>
@endif

{{-- TikTok Pixel --}}
@if($tiktokPixelId)
    <!-- TikTok Pixel Code -->
    <script>
        !function (w, d, t) {
            w.TiktokAnalyticsObject=t;
            var ttq=w[t]=w[t]||[];
            ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],
            ttq.setAndDefer=function(t,t){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
            for(var e=0;e<ttq.methods.length;e++)ttq.setAndDefer(t,ttq.methods[e]);
            ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";
            ttq._i=ttq._i||{},ttq._i[e]=n,n.cn=[],t._u=i,
            t.setAndDefer(ttq,"initialize"),ttq.initialize.call(ttq,e,n)};
            ttq.load('{{ $tiktokPixelId }}');
            ttq.page();
        }(window, document, 'ttq');
    </script>
@endif
