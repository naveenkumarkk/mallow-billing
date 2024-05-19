<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerEmail;

class SendInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerEmail;
    protected $invoiceDetails;
    protected $pdfPath;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new CustomerEmail($this->customerEmail, $this->invoiceDetails, $this->pdfPath);
        Mail::to($this->customerEmail)->send($email);
    }
}
