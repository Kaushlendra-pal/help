
step 1:-{{-- php artisan make:notification UserRegisteredNotification --}}



{{-- step 2 :- controller --}}

$post =[
      'name'=>'he he'
];

$users = User::all();
forech($users as $user){
Notification::send($user,new UserRegisteredNotification($post));
}

 {{-- Step 3:- Mail---> UserRegisteredNotification  --}}
class UserRegisteredNotification extends Notification
{
    use Queueable;
    public $post;

    public function __construct($post)
    {
        $this->post=$post;
    }

    // Send via email
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    // Email content
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Our Application')
            ->line($this->post['name'])
            ->line('We are happy to have you with us!')
            ->action('Login Now', url('/login'))
            ->line('Thank you for joining us.');
    }
}
---------------------------------------------------------------------------------------------------------------------------------------------------------------
{{-- if you send on background notification Than --}}

{{-- step 1:- notification file --}}
        
implements ShouldQueue

{{-- Step 2: php artisan queue:table --}}
