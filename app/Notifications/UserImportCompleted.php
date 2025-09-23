<?php

namespace App\Notifications;

use App\Models\UserImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserImportCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public $userImport;

    public function __construct(UserImport $userImport)
    {
        $this->userImport = $userImport;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('User Import Completed')
                    ->line('Your user import has been completed successfully.')
                    ->line('Total Rows: ' . $this->userImport->total_rows)
                    ->line('Successful: ' . $this->userImport->successful_rows)
                    ->line('Failed: ' . $this->userImport->failed_rows)
                    ->action('View Import Details', url('/admin/imports/' . $this->userImport->id));
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'User import completed successfully',
            'import_id' => $this->userImport->id,
            'total_rows' => $this->userImport->total_rows,
            'successful_rows' => $this->userImport->successful_rows,
            'failed_rows' => $this->userImport->failed_rows,
            'url' => '/admin/imports/' . $this->userImport->id
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'User import completed successfully',
            'import_id' => $this->userImport->id,
        ]);
    }
}
