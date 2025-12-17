
-----------------------------------------Register APi-----------------------------------------------------------------------------------------------------------
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
    
--------------------------------Login API-----------------------------------------------------------------------------------------------------------------------

    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ], 200);
    }
------------------------------------------------------Logout Api------------------------------------------------------------------------------------------------
    public function logout(Request $req)
    {
        $req->user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout successful.'
        ]);
    }
