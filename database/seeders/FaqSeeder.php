<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question' => 'How can I track my order?',
                'answer' => '<p>You can track your order by logging into your account and going to the "My Orders" section. Click on the order you want to track and you will see the real-time status of your delivery.</p><p>You will also receive email notifications at each step of the delivery process.</p>',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => '<p>We accept the following payment methods:</p><ul><li>Credit/Debit Cards (Visa, MasterCard, American Express)</li><li>PayPal</li><li>Bank Transfer</li><li>Cash on Delivery (available in some areas)</li></ul><p>All transactions are secured with SSL encryption.</p>',

                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How long does delivery take?',
                'answer' => '<p>Delivery times vary depending on your location:</p><ul><li><strong>Standard Delivery:</strong> 3-5 business days</li><li><strong>Express Delivery:</strong> 1-2 business days</li><li><strong>International:</strong> 7-14 business days</li></ul><p>Orders placed before 2 PM are processed the same day.</p>',
    
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What is your return policy?',
                'answer' => '<p>We offer a 30-day return policy from the date of delivery. Items must be unused, in original packaging, and with all tags attached.</p><p>To initiate a return, contact our customer service or use the return form in your account.</p><p>Refunds are processed within 5-7 business days after we receive the returned item.</p>',

                'order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'How do I create an account?',
                'answer' => '<p>Creating an account is easy and takes just a few minutes:</p><ol><li>Click on "Sign Up" in the top right corner</li><li>Enter your email address and create a password</li><li>Fill in your personal information</li><li>Verify your email address</li></ol><p>Having an account allows you to track orders, save addresses, and get exclusive offers.</p>',

                'order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Can I cancel or modify my order?',
                'answer' => '<p>Yes, you can cancel or modify your order within 1 hour of placing it. After this time, the order enters our fulfillment process and cannot be changed.</p><p>To cancel or modify, contact our customer service immediately at support@boldroots.com or call our hotline.</p>',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer international shipping?',
                'answer' => '<p>Yes, we ship to most countries worldwide. International shipping rates and delivery times vary by destination.</p><p>You can check if we ship to your country during checkout by entering your address.</p><p>Please note that international orders may be subject to customs fees and import duties.</p>',
    
                'order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'How can I contact customer support?',
                'answer' => '<p>Our customer support team is available to help you:</p><ul><li><strong>Email:</strong> support@boldroots.com (response within 24 hours)</li><li><strong>Phone:</strong> +1-800-BOLDROOTS (Monday-Friday, 9 AM-6 PM EST)</li><li><strong>Live Chat:</strong> Available on our website during business hours</li><li><strong>Contact Form:</strong> Available in the "Contact Us" section</li></ul>',

                'order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'Are my personal details secure?',
                'answer' => '<p>Absolutely. We take data security very seriously:</p><ul><li>All data is encrypted with SSL technology</li><li>We never store credit card information</li><li>We comply with GDPR and data protection regulations</li><li>Regular security audits and updates</li></ul><p>Your privacy is our priority and we never share your information with third parties without your consent.</p>',
    
                'order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer discounts for bulk orders?',
                'answer' => '<p>Yes, we offer competitive pricing for bulk orders:</p><ul><li>10+ items: 10% discount</li><li>25+ items: 15% discount</li><li>50+ items: 20% discount</li></ul><p>For larger orders or custom requirements, please contact our business team at business@boldroots.com for a personalized quote.</p>',

                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
