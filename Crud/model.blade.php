{{-- Display all user --}}
public function index()
{
$users = User::all();
return view('users.index', compact('users'));
}

{{-- Show create form --}}

public function create()
{
return view('users.create');
}

{{-- Store User --}}
public function store(Request $request)
{
$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email',
'password' => 'required|min:6',
]);

User::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password),
]);

return redirect()->route('users.index')->with('success', 'User created successfully');
}

{{-- Show single user --}}

public function show($id)
{
$user = User::findOrFail($id);
return view('users.show', compact('user'));
}

{{-- Show edit form --}}

public function edit($id)
{
$user = User::findOrFail($id);
return view('users.edit', compact('user'));
}

{{-- Update user --}}

public function update(Request $request, $id)
{
$user = User::findOrFail($id);

$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email,' . $id,
]);

$user->update([
'name' => $request->name,
'email' => $request->email,
]);

return redirect()->route('users.index')->with('success', 'User updated successfully');
}

{{-- Delete user --}}

public function destroy($id)
{
$user = User::findOrFail($id);
$user->delete();

return redirect()->route('users.index')->with('success', 'User deleted successfully');
}


----------------------------------------------------------------------------------------------------------------------------------------------------------------
 {{-- Display blade code --}}
    <h2>User List</h2>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <a href="{{ route('users.create') }}">Add User</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('users.show',$user->id) }}">View</a>
                <a href="{{ route('users.edit',$user->id) }}">Edit</a>

                <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

----------------------------------------------------------------------------------------------------------------------------------------------------------------
{{-- Create form blade page --}}

    <h2>Create User</h2>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span>@enderror

            <label>Password</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror

        <button>Save</button>
        <a href="{{ route('users.index') }}">Back</a>
    </form>

----------------------------------------------------------------------------------------------------------------------------------------------------------------
    {{-- Edit page --}}

    <h2>Edit User</h2>

    <form action="{{ route('users.update',$user->id) }}" method="POST">
        @csrf
        @method('PUT')

            <label>Name</label>
            <input type="text" name="name" value="{{ $user->name }}">
            @error('name') <span>{{ $message }}</span> @enderror

            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}">
            @error('email') <span>{{ $message }}</span> @enderror

        <button>Update</button>
        <a href="{{ route('users.index') }}">Back</a>
    </form>

----------------------------------------------------------------------------------------------------------------------------------------------------------------
{{-- Show single user data --}}

    <h2>User Details</h2>

    <table>
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
    </table>

    <a href="{{ route('users.index') }}">Back</a>