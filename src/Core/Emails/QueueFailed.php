<?php

namespace Vnnit\Core\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueueFailed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $connectionName;
    /**
     * @var \Illuminate\Contracts\Queue\Job
     */
    private $job;
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * Create a new message instance.
     *
     * @param JobFailed $event
     */
    public function __construct(JobFailed $event)
    {
        $this->connectionName = $event->connectionName;
        $this->job = $event->job;
        $this->exception = $event->exception;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('[Error] Queue job failed')
            ->text('core::email.queue_failed_mail', [
                'connection_name' => $this->connectionName,
                'job'             => $this->job->resolveName(),
                'exception'       => $this->exception
            ]);
    }
}
