 {{-- Show all Three table data --}}
 public function index()
    {
        $teachers = Teacher::with('user.roles')->get();
        return view('teachers.index', compact('teachers'));
    }

      {{-- Show create form --}}
     
    public function create()
    {
        $roles = Role::pluck('name', 'id');
        return view('teachers.create', compact('roles'));
    }

      {{-- Store User + Teacher + Role --}}
     
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
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

        // Assign Role (Spatie)
        $user->assignRole($request->role);

        // Create Teacher
        Teacher::create([
            'user_id' => $user->id,
            'status'  => $request->status,
            'salary'  => $request->salary,
            'date'    => $request->date,
        ]);
        return redirect()->route('teachers.index')->with('success', 'Teacher created with role successfully');
    }

      {{-- Show edit form --}}
     
    public function edit($id)
    {
        $teacher = Teacher::with('user.roles')->findOrFail($id);
        $roles   = Role::pluck('name', 'id');

        return view('teachers.edit', compact('teacher', 'roles'));
    }

      {{-- Update User + Teacher + Role --}}
     
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user    = User::findOrFail($teacher->user_id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'role'   => 'required',
            'status' => 'required|string',
            'salary' => 'required|numeric',
            'date'   => 'required|date',
        ]);

        // Update User
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update password (optional)
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update Role
        $user->syncRoles([$request->role]);

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



    {{-- Blade code --}}
{{-- 
    <table>
    <thead>
        <tr>
            <th>#</th>
            <th>Teacher Name</th>
            <th>User Email</th>
            <th>Role(s)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($teachers as $teacher)
            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- Teacher table --}}
                <td>{{ $teacher->name ?? '-' }}</td>

                {{-- User table --}}
                <td>{{ $teacher->user->email ?? '-' }}</td>

                {{-- Roles table (Spatie) --}}
                <td>
                    @if ($teacher->user && $teacher->user->roles->count())
                        @foreach ($teacher->user->roles as $role)
                            <span>{{ $role->name }}</span>@if(!$loop->last), @endif
                        @endforeach
                    @else
                        No Role
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table> --}}
