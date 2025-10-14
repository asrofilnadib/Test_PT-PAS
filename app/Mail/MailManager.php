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
      $subject = $this->data['subject'] ?? ('Notifikasi Barang: ' . ($this->data['nama_barang'] ?? ''));
      $body = $this->data['body'] ?? (($this->data['nama_barang'] ?? ' - ') . ' ' . ($this->data['jenis_barang'] ?? ' - '));


      return $this->subject($subject)
        ->view('emails.notification')
        ->with([
          'body' => $body,
          'periode' => $this->data['periode'],
          'report_data' => $this->data['report_data'],
        ]);
    }
  }
