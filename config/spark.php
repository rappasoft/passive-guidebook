<?php

use App\Models\User;
use Spark\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | Spark Path
    |--------------------------------------------------------------------------
    |
    | This configuration option determines the URI at which the Spark billing
    | portal is available. You are free to change this URI to a value that
    | you prefer. You shall link to this location from your application.
    |
    */

    'path' => 'billing',

    'terms_url' => '/terms-of-service',

    /*
    |--------------------------------------------------------------------------
    | Spark Middleware
    |--------------------------------------------------------------------------
    |
    | These are the middleware that requests to the Spark billing portal must
    | pass through before being accepted. Typically, the default list that
    | is defined below should be suitable for most Laravel applications.
    |
    */

    'middleware' => ['web', 'auth', \App\Http\Middleware\IsNotFree::class],

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | These configuration values allow you to customize the branding of the
    | billing portal, including the primary color and the logo that will
    | be displayed within the billing portal. This logo value must be
    | the absolute path to an SVG logo within the local filesystem.
    |
    */

    // 'brand' =>  [
    //     'logo' => realpath(__DIR__.'/../public/svg/billing-logo.svg'),
    //     'color' => 'bg-gray-800',
    // ],

    /*
    |--------------------------------------------------------------------------
    | Proration Behavior
    |--------------------------------------------------------------------------
    |
    | This value determines if charges are prorated when making adjustments
    | to a plan such as incrementing or decrementing the quantity of the
    | plan. This also determines proration behavior if changing plans.
    |
    */

    'prorates' => true,

    /*
    |--------------------------------------------------------------------------
    | Spark Date Format
    |--------------------------------------------------------------------------
    |
    | This date format will be utilized by Spark to format dates in various
    | locations within the billing portal, such as while showing invoice
    | dates. You should customize the format based on your own locale.
    |
    */

    'date_format' => 'F j, Y',

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Spark's features are optional. You may disable the features by
    | removing them from this array. By removing features from this array
    | you can customize your Spark experience for your own application.
    |
    */

    'features' => [
        // Features::billingAddressCollection(['required' => true]),
        // Features::mustAcceptTerms(), // TODO
        // Features::euVatCollection(['home-country' => 'BE']),
        Features::invoiceEmails(['custom-addresses' => true]),
        Features::paymentNotificationEmails(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Invoice Configuration
    |--------------------------------------------------------------------------
    |
    | The following configuration options allow you to configure the data that
    | appears in PDF invoices generated by Spark. Typically, this data will
    | include a company name as well as your company contact information.
    |
    */

    'invoice_data' => [
        'vendor' => 'Rappasoft LLC',
        'product' => 'Passive Guidebook',
        'street' => '199 Clay Pitts Rd.', // TODO: PO Box?
        'location' => 'East Northport, NY', // TODO: PO Box?
        'phone' => '631-334-6650', // TODO: Google Number?
    ],

    /*
    |--------------------------------------------------------------------------
    | Spark Billable
    |--------------------------------------------------------------------------
    |
    | Below you may define billable entities supported by your Spark driven
    | application. The Stripe edition of Spark currently only supports a
    | single billable model entity (team, user, etc.) per application.
    |
    | In addition to defining your billable entity, you may also define its
    | plans and the plan's features, including a short description of it
    | as well as a "bullet point" listing of its distinctive features.
    |
    */

    'billables' => [

        'user' => [
            'model' => User::class,

            'trial_days' => 7,

            'default_interval' => 'yearly',

            'plans' => [
                [
                    'name' => \App\Models\Plan::TIER_1_NAME,
                    'short_description' => '',
                    'monthly_id' => env('SPARK_TIER_1_MONTHLY_PLAN', 'price_id'),
                    'yearly_id' => env('SPARK_TIER_1_YEARLY_PLAN', 'price_id'),
                    'features' => [
                        'Unlimited Savings Accounts',
                        'Unlimited Dividend Stocks',
                        'All Social Casinos',
                        '-- Bank Integration',
                        '-- Custom Passive Income Sources',
                    ],
                ],
                [
                    'name' => \App\Models\Plan::TIER_2_NAME,
                    'short_description' => '',
                    'monthly_id' => env('SPARK_TIER_2_MONTHLY_PLAN', 'price_id'),
                    'yearly_id' => env('SPARK_TIER_2_YEARLY_PLAN', 'price_id'),
                    'features' => [
                        'All Tier 1+',
                        'Bank Integrations',
                        'One-Time Passive Income',
                        'Custom Passive Income Sources',
                    ],
                ],
            ],
        ],
    ],
];
