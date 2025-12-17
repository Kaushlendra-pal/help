{{-- Register Api --}}

 public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'=> 'required|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $req->name,
            'email'    => $req->email,
            'password' => Hash::make($req->password),
        ]);

         // Assign Role
        $user->syncRoles($req->role);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'user' => $user
        ], 201);
    }