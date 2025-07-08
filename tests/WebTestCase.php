<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
abstract class WebTestCase extends BaseWebTestCase
{
    protected static KernelBrowser $client;
}
