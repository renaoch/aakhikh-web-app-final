<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-email', function () {
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome to the Newsletter</title>
    </head>
    <body style="margin:0; padding:0; background-color:#f6f1eb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
        <div style="width:100%; background-color:#f6f1eb; padding:40px 20px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:640px; margin:0 auto; background:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.08);">
                
                <tr>
                    <td style="background:linear-gradient(135deg, #0f766e 0%, #134e4a 100%); padding:48px 40px; text-align:center;">
                        <div style="font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#ccfbf1; margin-bottom:14px;">
                            Premium Newsletter
                        </div>
                        <h1 style="margin:0; font-size:36px; line-height:1.2; color:#ffffff; font-weight:700;">
                            Welcome to the inner circle
                        </h1>
                        <p style="margin:16px 0 0; font-size:16px; line-height:1.7; color:#d1fae5;">
                            You are officially subscribed and ready to receive thoughtful updates, curated insights, and exclusive drops.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:40px;">
                        <p style="margin:0 0 18px; font-size:16px; line-height:1.8; color:#374151;">
                            Hey Prem,
                        </p>

                        <p style="margin:0 0 18px; font-size:16px; line-height:1.8; color:#374151;">
                            Thanks for joining the <strong>Renao Chetri Newsletter</strong>. This is where I share premium updates, useful ideas, behind-the-scenes notes, and carefully selected content worth your time.
                        </p>

                        <p style="margin:0 0 18px; font-size:16px; line-height:1.8; color:#374151;">
                            Expect a clean mix of product updates, project insights, web and design inspiration, and occasional exclusive announcements before they go public.
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:30px 0; background:#f9fafb; border:1px solid #e5e7eb; border-radius:16px;">
                            <tr>
                                <td style="padding:24px;">
                                    <h2 style="margin:0 0 14px; font-size:18px; color:#111827;">
                                        What you’ll receive
                                    </h2>
                                    <p style="margin:0 0 10px; font-size:15px; line-height:1.8; color:#4b5563;">
                                        • Premium newsletter updates
                                    </p>
                                    <p style="margin:0 0 10px; font-size:15px; line-height:1.8; color:#4b5563;">
                                        • Early product and project announcements
                                    </p>
                                    <p style="margin:0 0 10px; font-size:15px; line-height:1.8; color:#4b5563;">
                                        • Curated ideas on design, development, and growth
                                    </p>
                                    <p style="margin:0; font-size:15px; line-height:1.8; color:#4b5563;">
                                        • Occasional exclusive resources and launch notes
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 28px; font-size:16px; line-height:1.8; color:#374151;">
                            I’m glad you’re here. The first premium issue will land in your inbox soon.
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="border-radius:999px; background-color:#0f766e;">
                                    <a href="https://renaochetri.me" target="_blank" rel="noopener noreferrer" style="display:inline-block; padding:14px 28px; font-size:15px; font-weight:600; color:#ffffff; text-decoration:none;">
                                        Visit Website
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:32px 0 8px; font-size:16px; line-height:1.8; color:#374151;">
                            — Renao Chetri
                        </p>
                        <p style="margin:0; font-size:14px; line-height:1.7; color:#6b7280;">
                            Building thoughtful digital experiences, products, and updates worth opening.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:24px 40px; background:#fafaf9; border-top:1px solid #e5e7eb; text-align:center;">
                        <p style="margin:0 0 8px; font-size:13px; color:#6b7280; line-height:1.7;">
                            You’re receiving this because you subscribed to the newsletter at renaochetri.me
                        </p>
                        <p style="margin:0; font-size:12px; color:#9ca3af; line-height:1.7;">
                            newsletter@renaochetri.me
                        </p>
                    </td>
                </tr>

            </table>
        </div>
    </body>
    </html>
    ';

    Mail::html($html, function ($message) {
        $message->to('chetri.prem999@gmail.com')
                ->subject('Welcome to the Premium Newsletter')
                ->from(
                    env('MAIL_FROM_ADDRESS', 'newsletter@renaochetri.me'),
                    env('MAIL_FROM_NAME', 'Renao Chetri')
                );
    });

    return 'Premium welcome email sent successfully';
});