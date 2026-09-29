<?php

namespace App\Mail;

use App\Models\CrmInvoice;
use App\Models\CrmInvoiceSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrmInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $settings;
    public $mailSubject;
    protected $pdfBinary;

    public function __construct(CrmInvoice $invoice, CrmInvoiceSetting $settings, string $subject, string $pdfBinary)
    {
        $this->invoice = $invoice;
        $this->settings = $settings;
        $this->mailSubject = $subject;
        $this->pdfBinary = $pdfBinary;
    }

    public function build()
    {
        return $this->subject($this->mailSubject)
            ->view('crm_invoice.email')
            ->attachData($this->pdfBinary, $this->invoice->invoice_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
