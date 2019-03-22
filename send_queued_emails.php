<?php

require_once "vendor/autoload.php";
require_once "app/config/database.php";

use App\Helpers\Mailer;
use App\Models\EmailQueue;
use App\Models\EmailSent;

ob_end_clean();
header("Connection: close");
ignore_user_abort(true);
ob_start();
echo('Some status message');
$size = ob_get_length();
header("Content-Length: $size");
header("Content-Encoding: none");

ob_end_flush();

flush();
// connection is closed at this point

// start actual processing here
send_queued_emails();

function send_queued_emails() 
{
    // read one item from the queue
    $emailQueue = EmailQueue::first();
    

    // if no more datasets are found, exit the function
    if ($emailQueue->count() == 0) {
        return; 
    }

    // mail the queried data
    $mailer = new Mailer();
    $mailer->setAddress($emailQueue->addresses)
            ->setSubject($emailQueue->subject)
            ->setBody($emailQueue->body)
            ->send();

    $emailSent = new EmailSent();
    $emailSent->addresses   = $emailQueue->addresses;
    $emailSent->subject     = $emailQueue->subject;
    $emailSent->body        = $emailQueue->body;
    $emailSent->alt_body    = $emailQueue->alt_body;
    $emailSent->from        = $emailQueue->from;
    $emailSent->reply_to     = $emailQueue->reply_to;
    $emailSent->cc          = $emailQueue->cc;
    $emailSent->bcc         = $emailQueue->bcc;
    $emailSent->attachments = $emailQueue->attachments;
    $emailSent->save();
    
    // delete the email from the queue
    $emailQueue->delete();

    // recursively call the function
    send_queued_emails();
};

