<?php
return [
    
    // From which address is being sent
    'fromAddress' => 'support@mojsajt.com',
    
    // From which name is being sent
    'fromName' => 'Support',
    
    // Enable/disable verbose debug output
    'SMTPDebug' => true,
    
    // Specify main and backup SMTP servers
    'host' => 'smtp.gmail.com',
    
    // Enable/disable SMTP authentication
    'SMTPAuth' => true,
    
    // SMTP username
    'Username' => 'someemail@somedomain.com',
    
    // SMTP username
    'Password' => 'somepassword',
    
    // Enable TLS encryption, `ssl` also accepted
    'SMTPSecure' => 'ssl',
    
    // TCP port to connect to
    'Port' => 465
];

