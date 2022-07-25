<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepartmentChangeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $department;
    public $date;
    public $job;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name,$department,$date)
    {
        $this->first_name = $first_name;
        $this->department = $department;
        $this->date = $date;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from("hrsystem@gmail.com")->subject('Mail from HR System')->view('emails.send')
            ->with([
                'first_name'=>$this->first_name,
                'department'=>$this->department,
                'date'=>$this->date
            ]);
    }
}
