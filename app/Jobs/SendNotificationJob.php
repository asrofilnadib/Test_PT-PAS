<?php

  namespace App\Jobs;

  use App\Mail\MailManager;
  use Illuminate\Mail\Mailable;
  use Illuminate\Bus\Queueable;
  use Illuminate\Contracts\Queue\ShouldQueue;
  use Illuminate\Foundation\Bus\Dispatchable;
  use Illuminate\Queue\InteractsWithQueue;
  use Illuminate\Queue\SerializesModels;
  use Illuminate\Support\Facades\Http;
  use Illuminate\Support\Facades\Mail;

  class SendNotificationJob implements ShouldQueue
  {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      // --- Kirim Email ---
      Mail::to('nadib.nabit@gmail.com')->send(new MailManager($this->data));

      // --- Kirim Telegram ---
      $botToken = env('TELEGRAM_BOT_TOKEN');
      $chatID = env('TELEGRAM_CHAT_ID');
      $text = "📦 *Notifikasi Barang*\n\n"
        . "Barang: *{$this->data['nama_barang']}*\n"
        . "Jenis: *{$this->data['jenis_transaksi']}*\n"
        . "Jumlah: *{$this->data['qty']}*\n"
        . "Tanggal: " . now()->format('d M Y H:i');

      Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
        'chat_id' => $chatID,
        'text' => $text,
        'parse_mode' => 'Markdown',
      ]);
    }
  }
