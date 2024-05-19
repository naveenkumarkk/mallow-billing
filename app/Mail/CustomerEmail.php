<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerEmail;
    public $invoiceDetails;
    protected $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerEmail, $invoiceDetails, $pdfPath)
    {
        $this->customerEmail = $customerEmail;
        $this->invoiceDetails = $invoiceDetails;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invoice from Mallow Tech!')
        ->view('emails.invoice')
        ->with([
            'invoiceDetails' => $this->invoiceDetails,
        ])
        ->attach($this->pdfPath, [
            'as' => 'invoice.pdf',
            'mime' => 'application/pdf',
        ]);
    }
}
