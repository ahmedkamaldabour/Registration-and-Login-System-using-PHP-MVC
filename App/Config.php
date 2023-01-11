<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{
    /**
     * Database host
     *
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     *
     * @var string
     */
    const DB_NAME = 'mvclogin';

    /**
     * Database user
     *
     * @var string
     */
    const DB_USER = 'Ahmed';

    /**
     * Database password
     *
     * @var string
     */
    const DB_PASSWORD = '2543245';

    /**
     * Show or hide error messages on screen
     *
     * @var boolean
     */
    const SHOW_ERRORS = true;

    const SECRET_KEY = 'jKWhY9qZO1IVdg9czCD5UpeULIECYGS3';

    const MAILGUN_API_KEY = 'd283d96c6c5e75e3c9435a113a0f17f7-48d7d97c-101b5e5e';

    const MAILGUN_DOMAIN = 'sandbox1ec2905cd7e74092a8437afed8a09748.mailgun.org';
}
