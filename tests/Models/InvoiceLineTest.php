<?php
namespace Tests\Models;

use Einvoicing\AllowanceOrCharge;
use Einvoicing\Invoice;
use Einvoicing\InvoiceLine;
use PHPUnit\Framework\TestCase;

final class InvoiceLineTest extends TestCase {
    /** @var Invoice */
    private $invoice;

    protected function setUp(): void {
        $this->invoice = (new Invoice)->setRoundingMatrix(['' => 2]);
    }

    public function testInvoiceLine() {
        $charge = (new AllowanceOrCharge())->setAmount(8.0)->markAsPercentage()->setReason('Påslag');

        $line = (new InvoiceLine())->setPrice(189.36)->setQuantity(2.0)->setVatRate(25)->addCharge($charge);

        $this->invoice->addLine($line);

        $this->assertEquals(189.36, $line->getPrice());
        $this->assertEquals(378.72, $line->getNetAmountBeforeAllowancesCharges());
        $this->assertEquals(409.0176, $line->getNetAmount());
    }
}
