   {{-- Show all teachers --}}

    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('teachers.index', compact('teachers'));
    }

      {{-- Show create form --}}
     
    public function create()
    {
        return view('teachers.create');
    }

      {{-- Store User + Teacher --}}
     
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'status'   => 'required|string',
            'salary'   => 'required|numeric',
            'date'     => 'required|date',
        ]);

        // Create User
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create Teacher
        Teacher::create([
            'user_id' => $user->id,
            'status'  => $request->status,
            'salary'  => $request->salary,
            'date'    => $request->date,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully');
    }

      {{-- Show edit form --}}
     
    public function edit($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teachers.edit', compact('teacher'));
    }

      {{-- Update User + Teacher --}}
     
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user    = User::findOrFail($teacher->user_id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|string',
            'salary' => 'required|numeric',
            'date'   => 'required|date',
        ]);

        // Update User
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update Teacher
        $teacher->update([
            'status' => $request->status,
            'salary' => $request->salary,
            'date'   => $request->date,
        ]);
        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully');
    }

      {{-- Delete User + Teacher --}}
     
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user    = User::findOrFail($teacher->user_id);

        $teacher->delete();
        $user->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully');
    }

----------------------------------------------------------------------------------------------------------------------------------------------------------------
    {{-- Display blade file --}}

    <h2>Teacher List</h2>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <a href="{{ route('teachers.create') }}">Add Teacher</a>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Salary</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $teacher->user->name }}</td>
            <td>{{ $teacher->user->email }}</td>
            <td>{{ $teacher->status }}</td>
            <td>{{ $teacher->salary }}</td>
            <td>{{ $teacher->date }}</td>
            <td>
                <a href="{{ route('teachers.edit',$teacher->id) }}">Edit</a>

                <form action="{{ route('teachers.destroy',$teacher->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

----------------------------------------------------------------------------------------------------------------------------------------------------------------
    {{-- Create form blade file  --}}

    <h2>Create Teacher</h2>

    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf

            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span>{{ $message }}</span> @enderror

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span> @enderror

            <label>Password</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror

            <label>Status</label>
            <select name="status">
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status') <span>{{ $message }}</span> @enderror

            <label>Salary</label>
            <input type="number" name="salary" value="{{ old('salary') }}">
            @error('salary') <span>{{ $message }}</span> @enderror

            <label>Date</label>
            <input type="date" name="date" value="{{ old('date') }}">
            @error('date') <span>{{ $message }}</span> @enderror

        <button>Save</button>
        <a href="{{ route('teachers.index') }}">Back</a>
    </form>
----------------------------------------------------------------------------------------------------------------------------------------------------------------
{{-- Edit blade file --}}

    <h2>Edit Teacher</h2>

    <form action="{{ route('teachers.update',$teacher->id) }}" method="POST">
        @csrf
        @method('PUT')

            <label>Name</label>
            <input type="text" name="name" value="{{ $teacher->user->name }}">
            @error('name') <span>{{ $message }}</span> @enderror

            <label>Email</label>
            <input type="email" name="email" value="{{ $teacher->user->email }}">
            @error('email') <span>{{ $message }}</span> @enderror

        {{-- Teacher Fields --}}
            <label>Status</label>
            <select name="status">
                <option value="active" {{ $teacher->status=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ $teacher->status=='inactive'?'selected':'' }}>Inactive</option>
            </select>
            @error('status') <span class="text-danger">{{ $message }}</span> @enderror

            <label>Salary</label>
            <input type="number" name="salary" value="{{ $teacher->salary }}">
            @error('salary') <span class="text-danger">{{ $message }}</span> @enderror

            <label>Date</label>
            <input type="date" name="date" value="{{ $teacher->date }}">
            @error('date') <span class="text-danger">{{ $message }}</span> @enderror

        <button>Update</button>
        <a href="{{ route('teachers.index') }}">Back</a>
    </form>
----------------------------------------------------------------------------------------------------------------------------------------------------------------
    {{--  --}}
