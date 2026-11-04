<?php

declare(strict_types=1);

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Facturama\Tests\Model\Catalog;

use Facturama\Client;
use Facturama\Model\Catalog\PaymentForm;
use Facturama\Tests\Support\LiveApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class PaymentFormTest extends LiveApiTestCase
{
    public function testPaymentFormApi(): void
    {
        $this->assertNotEmpty($this->client->get('catalogs/PaymentForms'));
    }

    #[DataProvider('getPaymentForms')]
    public function testPaymentForms(\stdClass $paymentForm): void
    {
        $this->assertTrue(property_exists($paymentForm, 'Name'));
        $this->assertSame($paymentForm->Name, PaymentForm::findByName($paymentForm->Name)['name']);

        $this->assertTrue(property_exists($paymentForm, 'Value'));
        $this->assertSame($paymentForm->Value, PaymentForm::findByValue($paymentForm->Value)['value']);
    }

    public function testNoUnusedPaymentForms(): void
    {
        $apiPaymentForms = self::getPaymentForms();
        $paymentForms = PaymentForm::CATALOG_PAYMENT_FORM;

        foreach ($apiPaymentForms as $paymentForm) {
            $paymentForm = reset($paymentForm);
            if (false === $index = array_search($paymentForm->Name, array_column(PaymentForm::CATALOG_PAYMENT_FORM, 'name'), true)) {
                $this->fail(\sprintf('Payment form with name "%s" was not found', $paymentForm->Name));
            }

            unset($paymentForms[$index]);
        }

        $this->assertEmpty($paymentForms);
    }

    /**
     * return \stdClass[]|\Generator
     */
    public static function getPaymentForms(): iterable
    {
        $client = new Client(getenv('api_username') ?: null, getenv('api_password') ?: null);

        foreach ($client->get('catalogs/PaymentForms') as $paymentForm) {
            yield [$paymentForm];
        }
    }
}
