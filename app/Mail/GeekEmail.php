<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class GeekEmail extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $geek;
    public $license;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($geek)
    {
        $this->geek = $geek;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	$license_array = $this->geek->license_key;
        
        return $this->from('support@geeksoftware.nl')
		    ->subject('Aangeschafte licenties Geeksoftware Nederland')
                    ->view('mails.geek')
                    ->text('mails.geek_plain')
                    ->with(
                      [
                            'testVarOne' => $license_array,
                            'testVarTwo' => '2',
                      ]);
    }
}
