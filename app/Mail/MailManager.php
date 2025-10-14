<?php

  namespace App\Mail;

  use Illuminate\Bus\Queueable;
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Mail\Mailable;
  use Illuminate\Queue\SerializesModels;

  class MailManager extends Mailable
  {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject($this->data['subject'])
        ->view('emails.notification')
        ->with([
          'body' => $this->data['body'],
          'periode' => $this->data['periode'],
          'report_data' => $this->data['report_data'],
        ]);
    }
  }
