{{-- Controller dashboard --}}
public function user_dashboard()
{
$user = Ujob::all();
return view('user.dashboard', compact('user'));
}

{{-- Blade dashboard page make searchbar --}}
<form method="GET" action="{{ route('user.search') }}">
    <input type="text" name="search" placeholder="Search jobs by title..." value="{{ request()->search }}">
    <button type="submit">Search</button>
</form>

{{-- Make searchbar controller code --}}
public function search(Request $req){
$search = $req->search;
$user = Ujob::where('title', 'like', "$search%")->get();

return view('user.dashboard', compact('user'));
}

@forelse ($user as $user)
    <tr>
        <td>{{ $user->title }}</td>
        <td>{{ $user->location }}</td>
        <td>{{ $user->description }}</td>
        <td>{{ $user->type }}</td>
        <td>{{ $user->salary }}</td>
        <td><a href="{{ route('view_jobs', $user->id) }}">View</a></td>
        <td><a href="{{ route('apply_job', [$user->id, $user->title]) }}">Apply</a></td>
    </tr>
@empty
        <p>No jobs available right now.</p>
@endforelse