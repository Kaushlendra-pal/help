{{--   Show all user --}}
    public function index()
    {
        $user = Teacher::all();
        return response()->json([
            'status' => 'true',
            'message' => 'all user data present',
            'user' => $user,
        ]);

        if ($user->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'error fetching data',
                'error' => $user->error()->all(),
            ]);
        }
    }

   
      {{-- Store a newly created . --}}
     
    public function store(Request $req)
    {
        $valid =  Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'phone' => 'required|digits:10',
            'subject' => 'required',
            'joining_date' => 'required',
            'role'=> 'required|exists:roles,name'
        ]);

        if ($valid->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Validation Error',
                'error' => $valid->errors()->all(),
            ], 401);
        }
         $user_model = User::create([
            'name' => $req->name,
            'email'       => $req->email,
            'password'       => $req->password,
        ]);

        $teacher_model = Teacher::create([
            'user_id'=>$user_model->id,
            'phone' => $req->phone,
            'subject' => $req->subject,
            'joining_date' => $req->joining_date,
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Register successfully',
            'user_m' => $user_model->only(['id','name','email']),
            'teacher_detail' => $teacher_model,
        ], 200);

        if ($user->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'data not save Error',
                'error' => $user->errors()->all(),
            ], 401);
        }
    }
  
    
     {{-- Display the specified resource. --}}
     
    public function show(string $id)
    {
        $user = Teacher::findOrFail($id);
        return response()->json([
            'status' => 'true',
            'message' => 'Single user data',
            'user' => $user,
        ], 200);

        if ($user->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'data not show Error',
                'error' => $user->errors()->all(),
            ], 401);
        }
    }

      {{-- Show the form for editing the specified resource. --}}
     
    public function edit(string $id)
    {
        $user  = Teacher::findOrFail($id);
        return response()->json([
            'status' => 'true',
            'message' => 'Edit user data',
            'user' => $user,
        ]);

        if ($user->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Edit user not found ',
                'error' => $user->error()->all(),
            ]);
        }
    }

      {{-- Update the specified resource in storage. --}}
     
   public function update(Request $req, $id)
{
    $teacher = Teacher::find($id);
    if (!$teacher) {
        return response()->json([
            'status' => 'false',
            'message' => 'Teacher not found',
        ], 404);
    }

    $user = User::find($teacher->user_id);

    $validate = Validator::make($req->all(), [
        'name'          => 'required|string',
        'email'         => 'required|email|unique:users,email,' . $user->id,
        'password'      => 'required',
        'phone'         => 'required|digits:10',
        'subject'       => 'required',
        'joining_date'  => 'required|date',
    ]);

    if ($validate->fails()) {
        return response()->json([
            'status'  => 'false',
            'message' => 'Validation error',
            'errors'  => $validate->errors()->all(),
        ], 422);
    }

    // 4. Update user table
    $user->update([
        'name'     => $req->name,
        'email'    => $req->email,
        'password' => bcrypt($req->password),
    ]);

    // 5. Update teacher table
    $teacher->update([
        'phone'        => $req->phone,
        'subject'      => $req->subject,
        'joining_date' => $req->joining_date,
    ]);

    return response()->json([
        'status' => 'true',
        'message' => 'Teacher updated successfully',
        'user'    => $user,
        'teacher' => $teacher,
    ], 200);
}
          {{-- Delete User --}}
    public function destroy(string $id)
    {
        $teacher = User::find($id);
        if(!$teacher){
            return response()->json([
                  'status'=>'false',
                  '$message'=>'teacher not found'
            ]);
        }
        
        $teacher->delete();
        return response()->json([
            'status' => true,
            'message' => 'Teacher deleted successfully'
        ], 200);
    }

