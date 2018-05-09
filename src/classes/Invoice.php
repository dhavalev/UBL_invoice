<?php

namespace Levaral\UBL\Invoice;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;

class Invoice implements XmlSerializable{

    /**
     * @var string
     */
    private $UBLVersionID = '2.0';

    /**
     * @var string
     */
    private $customizationId = 'OIOUBL-2.01';

    /**
     * @var int
     */
    private $id;
    /**
     * @var bool
     */
    private $copyIndicator = false;

    /**
     * @var \DateTime
     */
    private $issueDate;

    /**
     * @var \DateTime
     */
    private $dueDate;

    /**
     * @var string
     */

    private $invoiceTypeCode;
    /**
     * @var Party
     */
    private $accountingSupplierParty;
    /**
     * @var Party
     */
    private $accountingCustomerParty;
    /**
     * @var TaxTotal
     */
    private $taxTotal;
    /**
     * @var LegalMonetaryTotal
     */
    private $legalMonetaryTotal;
    /**
     * @var InvoiceLine[]
     */
    private $invoiceLines;
    /**
     * @var AllowanceCharge[]
     */
    private $allowanceCharges;


    function validate()
    {
        if ($this->id === null) {
            throw new \InvalidArgumentException('Missing invoice id');
        }

        if (!$this->issueDate instanceof \DateTime) {
            throw new \InvalidArgumentException('Invalid invoice issueDate');
        }

        if ($this->invoiceTypeCode === null) {
            throw new \InvalidArgumentException('Missing invoice invoiceTypeCode');
        }

        if ($this->accountingSupplierParty === null) {
            throw new \InvalidArgumentException('Missing invoice accountingSupplierParty');
        }

        if ($this->accountingCustomerParty === null) {
            throw new \InvalidArgumentException('Missing invoice accountingCustomerParty');
        }

        if ($this->invoiceLines === null) {
            throw new \InvalidArgumentException('Missing invoice lines');
        }

        if ($this->legalMonetaryTotal === null) {
            throw new \InvalidArgumentException('Missing invoice LegalMonetaryTotal');
        }

        if ($this->taxTotal === null) {
            throw new \InvalidArgumentException('Missing invoice taxTotal');
        }
    }

    function xmlSerialize(Writer $writer)
    {
        $cbc = '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
        $cac = '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';

        $this->validate();

        $writer->write([
            $cbc . 'UBLVersionID' => $this->getUBLVersionID(),
            $cbc . 'CustomizationID' => $this->getCustomizationID(),
            $cbc . 'ID' => $this->id,
            $cbc . 'CopyIndicator' => $this->copyIndicator ? 'true' : 'false',
            $cbc . 'IssueDate' => $this->issueDate->format('Y-m-d'),
            $cbc . 'DueDate' => $this->dueDate->format('Y-m-d'),
            $cbc . 'InvoiceTypeCode' => $this->invoiceTypeCode,
            $cac . 'AccountingSupplierParty' => [$cac . "Party" => $this->accountingSupplierParty],
            $cac . 'AccountingCustomerParty' => [$cac . "Party" => $this->accountingCustomerParty],
        ]);

        if ($this->allowanceCharges != null) {
            foreach ($this->allowanceCharges as $invoiceLine) {
                $writer->write([
                    Schema::CAC . 'AllowanceCharge' => $invoiceLine
                ]);
            }
        }

        $writer->write([
            Schema::CAC . 'TaxTotal' => $this->taxTotal
        ]);

        $writer->write([
            $cac . 'LegalMonetaryTotal' => $this->legalMonetaryTotal
        ]);

        foreach ($this->invoiceLines as $invoiceLine) {
            $writer->write([
                Schema::CAC . 'InvoiceLine' => $invoiceLine
            ]);
        }

    }

    /**
     * @return string
     */
    public function getUBLVersionID() {
        return $this->UBLVersionID;
    }

    /**
     * @param string $id
     * @return Invoice
     */
    public function setUBLVersionID($id) {
        $this->UBLVersionID = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomizationID() {
        return $this->customizationID;
    }

    /**
     * @param string $id
     * @return Invoice
     */
    public function setCustomizationID($id) {
        $this->customizationID = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Invoice
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCopyIndicator() {
        return $this->copyIndicator;
    }

    /**
     * @param boolean $copyIndicator
     * @return Invoice
     */
    public function setCopyIndicator($copyIndicator) {
        $this->copyIndicator = $copyIndicator;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDueDate() {
        return $this->dueDate;
    }

    /**
     * @param \DateTime $dueDate
     * @return Invoice
     */
    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getIssueDate() {
        return $this->issueDate;
    }

    /**
     * @param \DateTime $issueDate
     * @return Invoice
     */
    public function setIssueDate($issueDate) {
        $this->issueDate = $issueDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceTypeCode() {
        return $this->invoiceTypeCode;
    }

    /**
     * @param string $invoiceTypeCode
     * @return Invoice
     */
    public function setInvoiceTypeCode($invoiceTypeCode) {
        $this->invoiceTypeCode = $invoiceTypeCode;
        return $this;
    }

    /**
     * @return Party
     */
    public function getAccountingSupplierParty() {
        return $this->accountingSupplierParty;
    }

    /**
     * @param Party $accountingSupplierParty
     * @return Invoice
     */
    public function setAccountingSupplierParty($accountingSupplierParty) {
        $this->accountingSupplierParty = $accountingSupplierParty;
        return $this;
    }

    /**
     * @return Party
     */
    public function getAccountingCustomerParty() {
        return $this->accountingCustomerParty;
    }

    /**
     * @param Party $accountingCustomerParty
     * @return Invoice
     */
    public function setAccountingCustomerParty($accountingCustomerParty) {
        $this->accountingCustomerParty = $accountingCustomerParty;
        return $this;
    }

    /**
     * @return TaxTotal
     */
    public function getTaxTotal() {
        return $this->taxTotal;
    }

    /**
     * @param TaxTotal $taxTotal
     * @return Invoice
     */
    public function setTaxTotal($taxTotal) {
        $this->taxTotal = $taxTotal;
        return $this;
    }

    /**
     * @return LegalMonetaryTotal
     */
    public function getLegalMonetaryTotal() {
        return $this->legalMonetaryTotal;
    }

    /**
     * @param LegalMonetaryTotal $legalMonetaryTotal
     * @return Invoice
     */
    public function setLegalMonetaryTotal($legalMonetaryTotal) {
        $this->legalMonetaryTotal = $legalMonetaryTotal;
        return $this;
    }

    /**
     * @return InvoiceLine[]
     */
    public function getInvoiceLines() {
        return $this->invoiceLines;
    }

    /**
     * @param InvoiceLine[] $invoiceLines
     * @return Invoice
     */
    public function setInvoiceLines($invoiceLines) {
        $this->invoiceLines = $invoiceLines;
        return $this;
    }

    /**
     * @return AllowanceCharge[]
     */
    public function getAllowanceCharges() {
        return $this->allowanceCharges;
    }

    /**
     * @param AllowanceCharge[] $allowanceCharges
     * @return Invoice
     */
    public function setAllowanceCharges($allowanceCharges) {
        $this->allowanceCharges = $allowanceCharges;
        return $this;
    }

}