<?php

namespace App\Models;
/**
 * @uses Eloquent
 * @see http://laravel.com/docs/eloquent
 */
use Illuminate\Database\Eloquent\Model as Eloquent;


/**
 * Pretty basic user model. Does not do nothing at all
 */

class EmailQueue extends Eloquent {

    protected $table = 'email_queue';
    
    protected $fillable = [
        'addresses',
        'subject',
        'body',
        'alt_body',
        'from',
        'reply_to',
        'cc',
        'bcc',
        'attachments'
    ];
        
}