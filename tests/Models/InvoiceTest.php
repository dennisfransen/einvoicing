<?php
namespace Tests\Models;

use Einvoicing\AllowanceOrCharge;
use Einvoicing\Invoice;
use Einvoicing\InvoiceLine;
use Einvoicing\Models\InvoiceTotals;
use PHPUnit\Framework\TestCase;
use const PHP_EOL;

final class InvoiceTest extends TestCase {
    /** @var Invoice */
    private $invoice;

    protected function setUp(): void {
        $this->invoice = (new Invoice)->setRoundingMatrix(['' => 2]);
    }

    public function testBaseInvoice(): void {
        $charge = (new AllowanceOrCharge())->setAmount(8.0)->markAsPercentage()->setReason('Påslag');

        $this->invoice->addLine((new InvoiceLine())->setPrice(190.0)->setQuantity(1.0)->setVatRate(25)->addCharge($charge));
        $this->invoice->addLine((new InvoiceLine())->setPrice(189.36)->setQuantity(1.0)->setVatRate(25)->addCharge($charge));
        $this->invoice->addLine((new InvoiceLine())->setPrice(2547.0)->setQuantity(1.0)->setVatRate(25)->addCharge($charge));
        $this->invoice->addLine((new InvoiceLine())->setPrice(410.0)->setQuantity(1.5)->setVatRate(25));
        $this->invoice->addLine((new InvoiceLine())->setPrice(451.0)->setQuantity(3.0)->setVatRate(25));
        $this->invoice->addLine((new InvoiceLine())->setPrice(451.0)->setQuantity(8.0)->setVatRate(25));

        $this->invoice->addLine((new InvoiceLine())->setPrice(190.0)->setQuantity(4.5)->setVatRate(25)->addCharge($charge));
        $this->invoice->addLine((new InvoiceLine())->setPrice(189.36)->setQuantity(2.5)->setVatRate(25)->addCharge($charge));
        $this->invoice->addLine((new InvoiceLine())->setPrice(510.0)->setQuantity(40.0)->setVatRate(25));
        $this->invoice->addLine((new InvoiceLine())->setPrice(324.13)->setQuantity(32.0)->setVatRate(25));

        $totals = $this->invoice->getTotals();

        $this->assertEquals(40943.3, $totals->taxExclusiveAmount);
        $this->assertEquals(10235.83,  $totals->vatAmount);
        $this->assertEquals(51179.13, $totals->taxInclusiveAmount);
    }
}
