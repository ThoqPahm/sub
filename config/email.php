<?php
return [

    /**
     * Email configuration.
     *
     * By defining transports separately from delivery profiles you can easily
     * re-use transport configuration across multiple profiles.
     *
     * You can specify multiple configurations for production, development and
     * testing.
     *
     * Each transport needs a `className`. Valid options are as follows:
     *
     *  Mail   - Send using PHP mail function
     *  Smtp   - Send using SMTP
     *  Debug  - Do not send the email, just return the result
     *
     * You can add custom transports (or override existing transports) by adding the
     * appropriate file to src/Mailer/Transport.  Transports should be named
     * 'YourTransport.php', where 'Your' is the name of the transport.
     */
    'EmailTransport' => [
        'default' => [
            'className' => 'Mail'
        ],
        'smtp' => [
            'className' => 'Smtp',
            // The following keys are used in SMTP transports
            'host' => '',
            'port' => '',
            'timeout' => 30,
            'username' => '',
            'password' => '',
            'client' => null,
            'tls' => false,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
            'context' => [
                'ssl' => [ 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]
            ]

        ],
        'mail2' => [
            'className' => 'PHPMailerMail'
        ],
        'sendmail' => [
            'className' => 'PHPMailerSendmail'
        ],
    ],
    /**
     * Email delivery profiles
     *
     * Delivery profiles allow you to predefine various properties about email
     * messages from your application and give the settings a name. This saves
     * duplication across your application and makes maintenance and development
     * easier. Each profile accepts a number of keys. See `Cake\Mailer\Email`
     * for more information.
     */
    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => ['no_reply@localhost' => 'AdLinkFly'],
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
        'smtp' => [
            'transport' => 'smtp',
            'from' => ['no_reply@localhost' => 'AdLinkFly'],
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
        'mail2' => [
            'transport' => 'mail2',
            'from' => ['no_reply@localhost' => 'AdLinkFly'],
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
        'sendmail' => [
            'transport' => 'sendmail',
            'from' => ['no_reply@localhost' => 'AdLinkFly'],
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
    ],
];
