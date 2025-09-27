<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';

class EmailManager {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }
    
    private function configureMailer() {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = SMTP_PORT;
            
            // Default sender
            $this->mailer->setFrom(FROM_EMAIL, FROM_NAME);
            
        } catch (Exception $e) {
            error_log('Email configuration failed: ' . $e->getMessage());
        }
    }
    
    public function sendEmail($to, $subject, $body, $isHTML = true) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            
            if ($isHTML) {
                $this->mailer->AltBody = strip_tags($body);
            }
            
            $this->mailer->send();
            
            return ['success' => true, 'message' => 'Email sent successfully'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Email failed: ' . $e->getMessage()];
        }
    }
    
    public function sendContactFormNotification($data) {
        $subject = 'New Contact Form Submission - ' . ($data['name'] ?? 'Unknown');
        
        $body = $this->buildContactEmailTemplate($data);
        
        return $this->sendEmail(FROM_EMAIL, $subject, $body, true);
    }
    
    public function sendNewsletterWelcome($email, $name = '') {
        $subject = 'Welcome to Adil GFX Newsletter!';
        
        $body = $this->buildNewsletterWelcomeTemplate($name);
        
        return $this->sendEmail($email, $subject, $body, true);
    }
    
    public function sendAutoReply($to, $name, $form_type) {
        $subject = 'Thank you for contacting Adil GFX';
        
        $body = $this->buildAutoReplyTemplate($name, $form_type);
        
        return $this->sendEmail($to, $subject, $body, true);
    }
    
    private function buildContactEmailTemplate($data) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>New Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #FF0000; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #555; }
                .value { margin-top: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>New Contact Form Submission</h1>
                </div>
                <div class="content">
                    <div class="field">
                        <div class="label">Name:</div>
                        <div class="value">' . htmlspecialchars($data['name'] ?? 'Not provided') . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Email:</div>
                        <div class="value">' . htmlspecialchars($data['email'] ?? 'Not provided') . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Phone:</div>
                        <div class="value">' . htmlspecialchars($data['phone'] ?? 'Not provided') . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Service:</div>
                        <div class="value">' . htmlspecialchars($data['service'] ?? 'Not specified') . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Budget:</div>
                        <div class="value">' . htmlspecialchars($data['budget'] ?? 'Not specified') . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Timeline:</div>
                        <div class="value">' . htmlspecialchars($data['timeline'] ?? 'Not specified') . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Message:</div>
                        <div class="value">' . nl2br(htmlspecialchars($data['message'] ?? 'No message provided')) . '</div>
                    </div>
                    <div class="field">
                        <div class="label">Submitted:</div>
                        <div class="value">' . date('Y-m-d H:i:s') . '</div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    private function buildNewsletterWelcomeTemplate($name) {
        $greeting = !empty($name) ? "Hi {$name}," : "Hi there,";
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Welcome to Adil GFX Newsletter</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #FF0000 0%, #FF6B35 50%, #F7931E 100%); color: white; padding: 30px; text-align: center; }
                .content { padding: 30px; background: #f9f9f9; }
                .cta { background: #FF0000; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Welcome to Adil GFX!</h1>
                    <p>Thanks for subscribing to our newsletter</p>
                </div>
                <div class="content">
                    <p>' . $greeting . '</p>
                    <p>Welcome to the Adil GFX community! You\'re now part of an exclusive group that gets:</p>
                    <ul>
                        <li>🎨 Weekly design tips and tutorials</li>
                        <li>📈 YouTube growth strategies</li>
                        <li>🎁 Free design templates and resources</li>
                        <li>💡 Behind-the-scenes insights</li>
                        <li>🚀 Early access to new services</li>
                    </ul>
                    <p>As a welcome gift, here are 5 free YouTube thumbnail templates to get you started:</p>
                    <a href="' . SITE_URL . '/free-templates" class="cta">Download Free Templates</a>
                    <p>Have a project in mind? I\'d love to help bring your vision to life!</p>
                    <p>Best regards,<br>Adil<br>Professional Designer</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    private function buildAutoReplyTemplate($name, $form_type) {
        $greeting = !empty($name) ? "Hi {$name}," : "Hi there,";
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Thank you for contacting Adil GFX</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #FF0000 0%, #FF6B35 50%, #F7931E 100%); color: white; padding: 30px; text-align: center; }
                .content { padding: 30px; background: #f9f9f9; }
                .cta { background: #FF0000; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Thank You!</h1>
                    <p>Your message has been received</p>
                </div>
                <div class="content">
                    <p>' . $greeting . '</p>
                    <p>Thank you for reaching out! I\'ve received your ' . $form_type . ' and I\'m excited to learn more about your project.</p>
                    <p><strong>What happens next?</strong></p>
                    <ul>
                        <li>⚡ I\'ll review your requirements within 2 hours</li>
                        <li>📞 You\'ll receive a personalized response with next steps</li>
                        <li>🎨 We\'ll discuss your vision and create something amazing together</li>
                    </ul>
                    <p>In the meantime, feel free to:</p>
                    <a href="' . SITE_URL . '/portfolio" class="cta">View My Portfolio</a>
                    <p>For urgent projects, you can also reach me directly on WhatsApp:</p>
                    <a href="https://wa.me/' . str_replace('+', '', WHATSAPP_NUMBER) . '" class="cta">WhatsApp Me</a>
                    <p>Looking forward to working with you!</p>
                    <p>Best regards,<br>Adil<br>Professional Designer</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}
?>