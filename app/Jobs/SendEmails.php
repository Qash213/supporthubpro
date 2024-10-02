<?php

namespace App\Jobs;

use App\Mail\mailmailablesend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use DOMDocument;
use Twilio\Rest\Client;

class SendEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = 60;

    public $user;

    public $templateCode;

    public $ticketData;

    /**
     * Create a new job instance.
     */
    public function __construct($user,$templateCode,$ticketData)
    {
        $this->user = $user;
        $this->templateCode = $templateCode;
        $this->ticketData = $ticketData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $template = EmailTemplate::where('code', $this->templateCode)->first();

        $data = $this->ticketData;

        $body = $template->body;
        foreach($this->ticketData as $key => $value){
            $body = str_replace('{{'.$key.'}}' , $this->ticketData[$key] , $body);
            $body = str_replace('{{ '.$key.' }}' , $this->ticketData[$key] , $body);
        }

        $body = '<!DOCTYPE html><html><head><title>Email Content</title></head><body>' . $body . '</body></html>';
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($body);
        libxml_clear_errors();

        $text = '';
        foreach ($dom->getElementsByTagName('p') as $paragraph) {
            $text .= $paragraph->nodeValue . "\n";
        }

        foreach ($dom->getElementsByTagName('div') as $div) {
            $text .= $div->nodeValue . "\n";
        }

        $account_sid = setting('twilio_auth_id');
        $auth_token = setting('twilio_auth_token');
        $twilio_number = setting('twilio_auth_phone_number');

        $client = new Client($account_sid, $auth_token);

        $client->messages->create($this->user, [
            'from' => $twilio_number,
            'body' => $text
        ]);
    }
}
