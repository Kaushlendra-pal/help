   {{-- Normal according role redirect in breeze --}}
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

         $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin_dashboard'); 
    } elseif ($user->role === 'user') {
        return redirect()->route('user_dashboard'); 
    }
        return redirect()->intended(route('/login', absolute: false));
    }
    
--------------------------------------------------------------------------------------------------------------------------------------------------------------- 

   {{-- Login Code in Spatie according role redirect --}}
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

       $user = Auth::user();

        if($user->hasRole('Admin')){
             return redirect()->route('admin_dashboard');
        } 
        
        if($user->hasRole('HR Manager')){
             return redirect()->route('hr_dashboard');
        }
        
        if($user->hasRole('Manager')){
             return redirect()->route('manager_dashboard');
        } 
        
        if($user->hasRole('Employee')){
             return redirect()->route('employee_dashboard');
        }
        
        return redirect()->intended(route('dashboard', absolute: false));
    }
---------------------------------------------------------------------------------------------------------------------------------------------------------------

{{-- Api login manually --}}   
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