

step 1 :-{{-- php artisan make:command AttandenceSend --command=attendence_send --}}
step 2 :- {{-- php artisan make:mail  daily_attendance  --}}

step 3 :- app/console/AttandenceSend

 public function handle()
{
    {{-- write logic here --}}
    $totalEmployees = Attendance::whereDate('created_at', Carbon::today())->count();

    if ($totalEmployees === 0) {
        $this->info('No attendance found for today.');
        return;
    }
    Mail::to('admin@gmail.com')->send(new daily_attendance($totalEmployees));
}

Step 4 :-  app/mail/daily_attendance

class daily_attendance extends Mailable
{
use Queueable, SerializesModels;

    public $totalEmployees;

    public function __construct($totalEmployees)
    {
        $this->totalEmployees = $totalEmployees;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Attendance Report',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_attendance',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


{{-- step 5 :- view/mail/daily_attendance  --}}

<p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>

<p><strong>Total Employees Present Today:</strong> {{ $totalEmployees }}</p>


{{-- Step 6 :- route/Console.php --}}

Schedule::command('attendence_send')->everyMinute();

---------------------------------------------------------------------------------------------------------------------------------------------------------------
{{-- command for run --}}

php artisan schedule:list    --> show timing runing this query

php artisan schedule:run     --> At a time file query one time

php artisan schedule:work     --> according to set time it can fire query