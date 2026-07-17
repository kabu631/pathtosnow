<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailerException;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MailService
{
    private function mailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = config('mail.mailers.smtp.host', 'smtp.gmail.com');
        $mail->SMTPAuth   = true;
        $mail->Username   = config('mail.mailers.smtp.username', '');
        $mail->Password   = config('mail.mailers.smtp.password', '');
        $mail->SMTPSecure = config('mail.mailers.smtp.encryption', PHPMailer::ENCRYPTION_STARTTLS);
        $mail->Port       = (int) config('mail.mailers.smtp.port', 587);
        $mail->setFrom(
            config('mail.from.address', 'hello@pathtosnow.com'),
            config('mail.from.name', 'PathToSnow Nepal')
        );
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        return $mail;
    }

    public function send(string $to, string $toName, string $subject, string $htmlBody): bool
    {
        try {
            $mail = $this->mailer();
            $mail->addAddress($to, $toName);
            $mail->Subject = $subject;
            $mail->Body    = $this->wrap($subject, $htmlBody);
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody));
            return $mail->send();
        } catch (MailerException $e) {
            Log::error('PHPMailer error: ' . $e->getMessage());
            return false;
        } catch (\Throwable $e) {
            Log::error('Mail send failed: ' . $e->getMessage());
            return false;
        }
    }

    // ── Specific email types ───────────────────────────────────────────────

    public function sendAdminBookingAlert(\App\Models\Booking $booking): bool
    {
        $adminEmail = config('mail.from.address', 'hello@pathtosnow.com');
        $adminName  = config('mail.from.name', 'PathToSnow');

        $isQuote  = (bool) $booking->is_quotation;
        $ref      = $booking->booking_reference;
        $customer = $booking->customer_name;
        $email    = $booking->customer_email;
        $phone    = $booking->customer_phone ?? '—';
        $package  = $booking->package?->name ?? $booking->custom_package_name ?? 'Custom Package';
        $date     = $booking->travel_date;
        $people   = $booking->group_size;
        $total    = $isQuote ? 'Quote request (TBD)' : '$' . number_format($booking->total_price, 2);
        $type     = $isQuote ? '📋 New Quotation Request' : '🎉 New Booking';
        $subject  = $isQuote ? "New Quote Request — {$ref}" : "New Booking — {$ref}";
        $adminUrl = url("/admin/bookings/{$booking->id}");

        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>{$type}</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                A new " . ($isQuote ? 'quotation request' : 'booking') . " has been submitted. Review and respond promptly.
            </p>
            <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin:0 0 24px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Reference</td>
                        <td style='padding:6px 0;color:#0f172a;font-weight:700;font-size:14px;text-align:right;font-family:monospace;'>{$ref}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Customer</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$customer}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Email</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$email}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Phone</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$phone}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Package</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$package}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Travel Date</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$date}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Group Size</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$people} people</td></tr>
                    <tr style='border-top:1px solid #bbf7d0;'>
                        <td style='padding:12px 0 6px;color:#064e3b;font-weight:700;font-size:15px;'>Amount</td>
                        <td style='padding:12px 0 6px;color:#064e3b;font-weight:800;font-size:18px;text-align:right;'>{$total}</td></tr>
                </table>
            </div>
            " . $this->btn($adminUrl, 'View in Admin Panel');

        return $this->send($adminEmail, $adminName, $subject, $body);
    }

    public function sendAdminContactAlert(\App\Models\ContactMessage $message): bool
    {
        $adminEmail  = config('mail.from.address', 'hello@pathtosnow.com');
        $adminName   = config('mail.from.name', 'PathToSnow');
        $name        = $message->name;
        $email       = $message->email;
        $phone       = $message->phone ?? '—';
        $subject     = $message->subject;
        $msgHtml     = nl2br(htmlspecialchars($message->message));
        $adminUrl    = url("/admin/contact/{$message->id}");

        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>📬 New Contact Message</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                A visitor has submitted a contact form message. Please reply within 24 hours.
            </p>
            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin:0 0 16px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>From</td>
                        <td style='padding:6px 0;color:#0f172a;font-weight:600;font-size:14px;text-align:right;'>{$name}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Email</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$email}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Phone</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$phone}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Subject</td>
                        <td style='padding:6px 0;color:#0f172a;font-weight:600;font-size:14px;text-align:right;'>{$subject}</td></tr>
                </table>
            </div>
            <div style='background:#f0fdf4;border-left:4px solid #059669;padding:16px 20px;border-radius:0 12px 12px 0;margin:0 0 24px;'>
                <p style='color:#94a3b8;font-size:12px;margin:0 0 8px;text-transform:uppercase;letter-spacing:0.05em;'>Message</p>
                <p style='color:#0f172a;line-height:1.8;margin:0;font-size:15px;'>{$msgHtml}</p>
            </div>
            " . $this->btn($adminUrl, 'Reply in Admin Panel');

        return $this->send($adminEmail, $adminName, "New Contact: {$subject}", $body);
    }

    public function sendWelcome(User $user): bool
    {
        $name = $user->name;
        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>Welcome to PathToSnow, {$name}! 🏔️</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                Your account has been created successfully. You can now book Himalayan treks, wildlife safaris, cultural tours, and more — all with verified local guides.
            </p>
            <p style='color:#475569;line-height:1.7;margin:0 0 24px;'>Here's what you can do next:</p>
            <ul style='color:#475569;line-height:2;margin:0 0 24px;padding-left:20px;'>
                <li>Browse and book <strong>50+ curated packages</strong></li>
                <li>Track all your bookings from your dashboard</li>
                <li>Shop trekking gear with member discounts</li>
            </ul>
            " . $this->btn(url('/packages'), 'Browse Packages') . "
            <p style='color:#94a3b8;font-size:13px;margin:24px 0 0;'>
                If you didn't create this account, please <a href='mailto:" . config('mail.from.address') . "' style='color:#059669;'>contact us</a> immediately.
            </p>";

        return $this->send($user->email, $name, 'Welcome to PathToSnow Nepal! 🏔️', $body);
    }

    public function sendPasswordReset(User $user, string $resetUrl): bool
    {
        $name = $user->name;
        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>Reset Your Password</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                Hi <strong>{$name}</strong>, we received a request to reset the password for your PathToSnow account.
            </p>
            <p style='color:#475569;line-height:1.7;margin:0 0 24px;'>
                Click the button below to choose a new password. This link expires in <strong>60 minutes</strong>.
            </p>
            " . $this->btn($resetUrl, 'Reset My Password') . "
            <p style='color:#94a3b8;font-size:13px;margin:24px 0 0;'>
                If you did not request a password reset, you can safely ignore this email. Your password will not be changed.
            </p>";

        return $this->send($user->email, $name, 'Reset your PathToSnow password', $body);
    }

    public function sendBookingConfirmation(\App\Models\Booking $booking): bool
    {
        $name     = $booking->customer_name;
        $email    = $booking->customer_email;
        $package  = $booking->package?->name ?? $booking->custom_package_name ?? 'Custom Package';
        $ref      = $booking->booking_reference;
        $date     = $booking->travel_date;
        $total    = $booking->package_id 
            ? '$' . number_format($booking->total_price, 2) 
            : 'Custom Quote (TBD)';
        $people   = $booking->group_size;

        $isQuote  = (bool) $booking->is_quotation;
        $title    = $isQuote ? 'Quotation Request Received! 📋' : 'Booking Confirmed! ✅';
        $msg      = $isQuote 
            ? "Hi <strong>{$name}</strong>, your request for a quotation has been received. Our team will prepare a custom quote and contact you shortly."
            : "Hi <strong>{$name}</strong>, your booking has been received. Our team will review and confirm it shortly.";
        $subject  = $isQuote ? "Quotation Request Received — {$ref}" : "Booking Received — {$ref}";

        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>{$title}</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                {$msg}
            </p>
            <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin:0 0 24px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Reference</td>
                        <td style='padding:6px 0;color:#0f172a;font-weight:700;font-size:14px;text-align:right;font-family:monospace;'>{$ref}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Package</td>
                        <td style='padding:6px 0;color:#0f172a;font-weight:600;font-size:14px;text-align:right;'>{$package}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Travel Date</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$date}</td></tr>
                    <tr><td style='padding:6px 0;color:#64748b;font-size:14px;'>Group Size</td>
                        <td style='padding:6px 0;color:#0f172a;font-size:14px;text-align:right;'>{$people} people</td></tr>
                    <tr style='border-top:1px solid #bbf7d0;'>
                        <td style='padding:12px 0 6px;color:#064e3b;font-weight:700;font-size:15px;'>Total Est. Price</td>
                        <td style='padding:12px 0 6px;color:#064e3b;font-weight:800;font-size:18px;text-align:right;'>{$total}</td></tr>
                </table>
            </div>
            " . $this->btn(url('/book/my-bookings'), 'View My Requests') . "
            <p style='color:#94a3b8;font-size:13px;margin:24px 0 0;'>
                Questions? Reply to this email or contact us at " . config('mail.from.address') . ".
            </p>";

        return $this->send($email, $name, $subject, $body);
    }

    public function sendBookingStatusUpdate(\App\Models\Booking $booking): bool
    {
        $name    = $booking->customer_name;
        $email   = $booking->customer_email;
        $package = $booking->package?->name ?? 'Your package';
        $ref     = $booking->booking_reference;
        $status  = ucfirst(str_replace('_', ' ', $booking->status));

        $statusEmoji = match($booking->status) {
            'confirmed'   => '✅',
            'in_progress' => '🏔️',
            'completed'   => '🎉',
            'cancelled'   => '❌',
            default       => '📋',
        };

        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>{$statusEmoji} Booking Status Update</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                Hi <strong>{$name}</strong>, your booking status for <strong>{$package}</strong> has been updated.
            </p>
            <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin:0 0 24px;text-align:center;'>
                <p style='color:#64748b;font-size:13px;margin:0 0 8px;'>Booking Reference: <strong style='font-family:monospace;'>{$ref}</strong></p>
                <p style='font-size:22px;font-weight:800;color:#064e3b;margin:0;'>{$statusEmoji} {$status}</p>
            </div>
            " . $this->btn(url('/book/my-bookings'), 'View Booking Details');

        return $this->send($email, $name, "Booking Update: {$status} — {$ref}", $body);
    }

    public function sendOrderConfirmation(\App\Models\Order $order): bool
    {
        $name  = $order->customer_name;
        $email = $order->customer_email;
        $ref   = $order->order_number;
        $total = '$' . number_format($order->total, 2);

        $itemsHtml = '';
        foreach ($order->items ?? [] as $item) {
            $itemsHtml .= "<tr>
                <td style='padding:8px 0;color:#475569;font-size:14px;'>{$item->product_name}</td>
                <td style='padding:8px 0;color:#475569;font-size:14px;text-align:center;'>×{$item->quantity}</td>
                <td style='padding:8px 0;color:#0f172a;font-weight:600;font-size:14px;text-align:right;'>\${$item->total_price}</td>
            </tr>";
        }

        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>Order Confirmed! 📦</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 16px;'>
                Hi <strong>{$name}</strong>, thank you for your order! We've received it and will process it shortly.
            </p>
            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin:0 0 16px;'>
                <p style='color:#64748b;font-size:13px;margin:0 0 12px;'>Order: <strong style='font-family:monospace;color:#0f172a;'>{$ref}</strong></p>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    {$itemsHtml}
                    <tr style='border-top:1px solid #e2e8f0;'>
                        <td colspan='2' style='padding:12px 0 6px;font-weight:700;color:#064e3b;'>Total</td>
                        <td style='padding:12px 0 6px;font-weight:800;color:#064e3b;font-size:18px;text-align:right;'>{$total}</td>
                    </tr>
                </table>
            </div>
            <p style='color:#475569;font-size:14px;margin:0 0 24px;'>
                Shipping to: <strong>{$order->shipping_name}</strong>, {$order->shipping_address}, {$order->shipping_city}
            </p>
            " . $this->btn(url('/cart/success'), 'Track Your Order') . "
            <p style='color:#94a3b8;font-size:13px;margin:24px 0 0;'>
                Questions? Contact us at " . config('mail.from.address') . ".
            </p>";

        return $this->send($email, $name, "Order Confirmed — {$ref}", $body);
    }

    public function sendContactReply(\App\Models\ContactMessage $message, string $replyText): bool
    {
        $fromName = config('mail.from.name', 'PathToSnow Nepal');
        $replyHtml = nl2br(htmlspecialchars($replyText));
        $originalHtml = nl2br(htmlspecialchars($message->message));

        $body = "
            <h2 style='color:#064e3b;margin:0 0 16px;font-size:22px;'>Re: {$message->subject}</h2>
            <p style='color:#475569;line-height:1.7;margin:0 0 24px;'>
                Hi <strong>{$message->name}</strong>, thank you for contacting PathToSnow Nepal. Here is our response to your message:
            </p>
            <div style='background:#f0fdf4;border-left:4px solid #059669;padding:16px 20px;border-radius:0 12px 12px 0;margin:0 0 24px;'>
                <p style='color:#0f172a;line-height:1.8;margin:0;font-size:15px;'>{$replyHtml}</p>
            </div>
            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin:0 0 24px;'>
                <p style='color:#94a3b8;font-size:12px;margin:0 0 8px;text-transform:uppercase;letter-spacing:0.05em;'>Your original message</p>
                <p style='color:#64748b;font-size:14px;line-height:1.7;margin:0;'>{$originalHtml}</p>
            </div>
            <p style='color:#475569;font-size:14px;'>
                Warm regards,<br/><strong>{$fromName}</strong>
            </p>";

        return $this->send($message->email, $message->name, "Re: {$message->subject}", $body);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function btn(string $url, string $label): string
    {
        return "<div style='text-align:center;margin:24px 0;'>
            <a href='{$url}' style='display:inline-block;background:#059669;color:#ffffff;text-decoration:none;font-weight:700;font-size:15px;padding:14px 32px;border-radius:12px;letter-spacing:0.01em;'>
                {$label}
            </a>
        </div>";
    }

    private function wrap(string $title, string $content): string
    {
        $appName = config('mail.from.name', 'PathToSnow Nepal');
        $year    = date('Y');
        $fromEmail = config('mail.from.address', 'hello@pathtosnow.com');
        $appUrl  = config('app.url', 'https://pathtosnow.com');

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8"/>
            <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
            <title>{$title}</title>
        </head>
        <body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 16px;">
                <tr><td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 4px 32px rgba(0,0,0,0.08);">
                        <!-- Header -->
                        <tr>
                            <td style="background:linear-gradient(135deg,#064e3b 0%,#065f46 50%,#0c4a6e 100%);padding:32px 40px;text-align:center;">
                                <a href="{$appUrl}" style="text-decoration:none;">
                                    <div style="color:#ffffff;font-size:26px;font-weight:900;letter-spacing:-0.5px;">{$appName}</div>
                                    <div style="color:#6ee7b7;font-size:12px;margin-top:4px;letter-spacing:0.05em;">NEPAL'S #1 HIMALAYAN TRAVEL PLATFORM</div>
                                </a>
                            </td>
                        </tr>
                        <!-- Body -->
                        <tr>
                            <td style="padding:40px 40px 32px;">
                                {$content}
                            </td>
                        </tr>
                        <!-- Footer -->
                        <tr>
                            <td style="background:#f8fafc;padding:24px 40px;border-top:1px solid #e2e8f0;text-align:center;">
                                <p style="color:#94a3b8;font-size:12px;margin:0 0 6px;">&copy; {$year} {$appName}. All rights reserved.</p>
                                <p style="color:#94a3b8;font-size:12px;margin:0;">
                                    <a href="mailto:{$fromEmail}" style="color:#059669;text-decoration:none;">{$fromEmail}</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td></tr>
            </table>
        </body>
        </html>
        HTML;
    }
}
