@extends('front-office.layouts.app')

@section('title', 'FAQ - Questions Fréquentes')

@section('styles')
    <style>
        .menu-toggle {
            color: #fff !important;
        }
        .header-right a {
            color: #fff!important;
        }

        .header-right a:hover {
            color: #ff0000 !important;
        }
        #cartToggle{
            color: #fff !important; 
        }
        .faq-hero {
            background: linear-gradient(135deg, #000000 0%, #1a0000 100%);
            padding: 80px 0;
            margin-top:40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .faq-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255, 0, 0, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .faq-hero-content {
            position: relative;
            z-index: 10;
        }

        .faq-hero h1 {
            font-size: 3rem;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
        }

        .faq-hero p {
            font-size: 1.2rem;
            color: #cccccc;
            max-width: 600px;
            margin: 0 auto;
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 20px;
        }


        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            border-color: rgba(255, 0, 0, 0.5);
            background: rgba(255, 255, 255, 0.08);
        }

        .faq-question {
            padding: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #dc3545;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            color: #ff0000;
        }

        .faq-icon {
            transition: transform 0.3s ease;
            color: #ff0000;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
        }

        .faq-answer-content {
            padding: 0 20px 20px;
            color: #212529;
            line-height: 1.6;
        }

        .faq-answer-content p {
            margin-bottom: 15px;
        }

        .faq-answer-content p:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .faq-hero h1 {
                font-size: 2rem;
            }

            .faq-hero p {
                font-size: 1rem;
            }

            .faq-categories {
                justify-content: flex-start;
            }

            .faq-question {
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- FAQ Hero Section -->
    <section class="faq-hero">
        <div class="faq-hero-content">
            <h1>FAQ</h1>
            <p>Questions Fréquemment Posées</p>
        </div>
    </section>

    <!-- FAQ Content -->
    <div class="faq-container">

        <!-- FAQ List -->
        <div class="faq-list">
            @forelse($faqs as $faq)
                <div class="faq-item" data-faq-id="{{ $faq->id }}">
                    <div class="faq-question" onclick="toggleFaq({{ $faq->id }})">
                        <span>{{ $faq->question }}</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; color: #999999;">
                    <i class="fas fa-question-circle" style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
                    <p>Aucune FAQ disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleFaq(id) {
            const faqItem = document.querySelector(`[data-faq-id="${id}"]`);
            const allFaqItems = document.querySelectorAll('.faq-item');
            
            // Close all other FAQ items
            allFaqItems.forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                }
            });
            
            // Toggle current FAQ item
            faqItem.classList.toggle('active');
        }

        // Open FAQ item if hash is present in URL
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash && hash.startsWith('#faq-')) {
                const faqId = hash.replace('#faq-', '');
                const faqItem = document.querySelector(`[data-faq-id="${faqId}"]`);
                if (faqItem) {
                    faqItem.classList.add('active');
                    faqItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>
@endsection