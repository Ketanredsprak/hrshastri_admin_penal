<?php

namespace App\Mail;

use App\Models\Form16Record;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Form16Send extends Mailable
{
    use Queueable, SerializesModels;

    public Form16Record $record;
    public string $attachmentPath;
    public string $emailSubject;

    public function __construct(Form16Record $record, string $attachmentPath, string $emailSubject)
    {
        $this->record = $record;
        $this->attachmentPath = $attachmentPath;
        $this->emailSubject = $emailSubject;
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
            ->view('email.form16_send')
            ->with(['record' => $this->record])
            ->attach($this->attachmentPath, [
                'as' => sprintf('Form16_%s_%s.pdf', $this->record->financial_year, $this->record->employee_code),
                'mime' => 'application/pdf',
            ]);
    }
}
