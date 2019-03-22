<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\EmailQueue;
use Rakit\Validation\Validator;

/**
 * Description of Mailer
 *
 * @author marko
 */
class Mailer {
    
    /**
     * @var string 
     */
    protected $fromAddress;
    
    /**
     * @var string
     */
    protected $fromName;
    
    /**
     * @var array
     */
    protected $addresses = [];
    
    /**
     * @var string
     */
    protected $replyToAddress;
    
    /**
     * @var string
     */
    protected $replyToName;
    
    /**
     * @var array 
     */
    protected $ccs = [];
    
    /**
     * @var array
     */
    protected $bccs = [];
    
    /**
     * @var array
     */
    protected $attachments = [];
    
    /**
     * @var boolean 
     */
    protected $isHtml = true;
    
    /**
     * @var string
     */
    protected $subject = '';
    
    /**
     * @var string
     */
    protected $body = '';
    
    /**
     * @var string 
     */
    protected $altBody = '';
    
    /**
     * @var string
     */
    protected $responseMessageSuccess = 'Message has been sent.';
    
    /**
     * @var string
     */
    protected $responseMessageFailure = 'Message could not be sent.';
    
    /**
     * @var array
     */
    protected $config = [];
    
    public function __construct() {
        $this->config = include __DIR__ . '../../config/mail.php';
        $this->fromAddress = $this->getConfig('fromAddress');
        $this->fromName = $this->getConfig('fromName');
    }
    
    /**
     * @return string
     */
    public function getFromAddress(): string {
        return $this->fromAddress;
    }

    /**
     * @return string
     */
    public function getFromName(): string {
        return $this->fromName;
    }

    /**
     * @return array
     */
    public function getAddresses(): array {
        return $this->getValidAddress($this->addresses);
    }

    /**
     * @return string
     */
    public function getReplyToAddress() {
        return $this->replyToAddress;
    }

    /**
     * @return string
     */
    public function getReplyToName() {
        return $this->replyToName;
    }

    /**
     * @return array
     */
    public function getCcs(): array {
        return $this->getValidAddress($this->ccs);
    }

    /**
     * @return array
     */
    public function getBccs(): array {
        return $this->getValidAddress($this->bccs);
    }

    /**
     * @return array
     */
    public function getAttachments(): array {
        if (!is_array($this->attachments)) {
            $this->attachments = [$this->attachments];
        }
        return $this->attachments;
    }

    /**
     * @return bool
     */
    public function getIsHtml(): bool {
        return $this->isHtml;
    }

    /**
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getAltBody() {
        return $this->altBody;
    }

    /**
     * @return string
     */
    public function getResponseMessageSuccess() {
        return $this->responseMessageSuccess;
    }

    /**
     * @return string
     */
    public function getResponseMessageFailure() {
        return $this->responseMessageFailure;
    }

    /**
     * @param array $addresses
     * @return array
     */
    public function getValidAddress($addresses): array {
        if (!is_array($addresses)) {
            $addresses = [
                [
                    'address' => $addresses,
                    'name' => ''
                ]
            ];
        } else {
            foreach ($addresses as &$address) {
                if (!is_array($address)) {
                    $address = [
                        'address' => $address,
                        'name' => ''
                    ];
                } else {
                    if (!isset($address['address'])) {
                        $address['address'] = '';
                    }
                    if (!isset($address['name'])) {
                        $address['name'] = '';
                    }
                }
            }
        }
        return $addresses;
    }   
    
    /**
     * @param string $config_key
     * @return string|array
     */
    protected function getConfig($config_key = null) {
        if (!empty($config_key) && isset($this->config[$config_key])) {
            return $this->config[$config_key];
        }
        return $this->config;
    }

    /**
     * @param string $fromAddress
     * @return $this
     */
    public function setFromAddress($fromAddress) {
        $this->fromAddress = $fromAddress;
        return $this;
    }

    /**
     * @param string $fromName
     * @return $this
     */
    public function setFromName($fromName) {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @param array $addresses
     * @return $this
     */
    public function setAddresses(array $addresses) {
        $this->addresses = $this->getValidAddress($addresses);
        return $this;
    }
    
    /**
     * @param string $address
     * @param string $name
     * @return $this
     */
    public function setAddress($address, $name = '') {
        $this->addresses[] = [
            'address' => $address,
            'name' => $name
        ];
        return $this;
    }

    /**
     * @param string $replyToAddress
     * @return $this
     */
    public function setReplyToAddress($replyToAddress) {
        $this->replyToAddress = $replyToAddress;
        return $this;
    }

    /**
     * @param string $replyToName
     * @return $this
     */
    public function setReplyToName($replyToName) {
        $this->replyToName = $replyToName;
        return $this;
    }

    /**
     * @param array $ccs
     * @return $this
     */
    public function setCcs(array $ccs) {
        $this->ccs = $this->getValidAddress($ccs);
        return $this;
    }

    /**
     * @param array $bccs
     * @return $this
     */
    public function setBccs(array $bccs) {
        $this->bccs = $this->getValidAddress($bccs);
        return $this;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments(array $attachments) {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * @param bool $isHtml
     * @return $this
     */
    public function setIsHtml(bool $isHtml) {
        $this->isHtml = $isHtml;
        return $this;
    }

    /** 
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $altBody
     * @return $this
     */
    public function setAltBody($altBody) {
        $this->altBody = $altBody;
        return $this;
    }

    /** 
     * @param string $responseMessageSuccess
     * @return $this
     */
    public function setResponseMessageSuccess($responseMessageSuccess) {
        $this->responseMessageSuccess = $responseMessageSuccess;
        return $this;
    }

    /**
     * @param string $responseMessageFailure
     */
    public function setResponseMessageFailure($responseMessageFailure) {
        $this->responseMessageFailure = $responseMessageFailure;
        return $this;
    }
        
    public function send()
    {
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug  = $this->getConfig('SMTPDebug');                                       
            $mail->isSMTP();                                            
            $mail->Host       = $this->getConfig('Host'); 
            $mail->SMTPAuth   = $this->getConfig('SMTPAuth');                                   
            $mail->Username   = $this->getConfig('Username');                   
            $mail->Password   = $this->getConfig('Password');                               
            $mail->SMTPSecure = $this->getConfig('SMTPSecure');                                 
            $mail->Port       = $this->getConfig('Port');                                    

            //Recipients
            $mail->setFrom($this->getFromAddress(), $this->getFromName());
            
            foreach ($this->getAddresses() as $address) {
               $mail->addAddress($address['address'], $address['name']);   
            }
            
            if (!empty($this->getReplyToAddress())) {
                $mail->addReplyTo($this->getReplyToAddress(), $this->getReplyToName());
            }
            
            foreach ($this->getCcs() as $cc) {
                $mail->addCC($cc['address'], $cc['name']);
            }
            
            foreach ($this->getBccs() as $bcc) {
                $mail->addBCC($bcc['address'], $bcc['name']);
            }

            // Attachments
            foreach ($this->getAttachments() as $attachment) {
               $mail->addAttachment($attachment); 
            }

            // Content
            $mail->isHTML($this->getIsHtml());
            $mail->Subject = $this->getSubject();
            $mail->Body    = $this->getBody();
            $mail->AltBody = $this->getAltBody();

            $mail->send();
            
            return $this->responseMessageSuccess;
            
        } catch (Exception $e) {
            return $this->responseMessageFailure . " Mailer Error: {$mail->ErrorInfo}";
        }
    }
    
    /**
     * @param type $addresses
     * @param type $subject
     * @param type $body
     * @param type $altBody
     * @param type $from
     * @param type $replyTo
     * @param type $cc
     * @param type $bcc
     * @param type $attachments
     * @return boolean
     */
    public static function queueEmail($addresses, $subject, $body, $altBody = null, $from = null, $replyTo = null, $cc = null, $bcc = null, $attachments = null) 
    {
        $emailQueue = new EmailQueue();
        $emailQueue->addresses = $addresses;
        $emailQueue->subject = $subject;
        $emailQueue->body = $body;
        $emailQueue->alt_body = $altBody;
        $emailQueue->from = $from;
        $emailQueue->reply_to = $replyTo;
        $emailQueue->cc = $cc;
        $emailQueue->bcc = $bcc;
        $emailQueue->attachments = $attachments;
        $emailQueue->save();
        
        return true;
    }
    
    /**
     * @return type
     */
    public static function curlProcessEmailQueue() {
        $c = curl_init();
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/send_queued_emails.php';
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);  // Follow the redirects (needed for mod_rewrite)
        curl_setopt($c, CURLOPT_HEADER, false);         // Don't retrieve headers
        curl_setopt($c, CURLOPT_NOBODY, true);          // Don't retrieve the body
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);  // Return from curl_exec rather than echoing
        curl_setopt($c, CURLOPT_FRESH_CONNECT, true);   // Always ensure the connection is fresh

        // Timeout super fast once connected, so it goes into async.
        curl_setopt( $c, CURLOPT_TIMEOUT, 1 );

        return curl_exec( $c );
    }
}
