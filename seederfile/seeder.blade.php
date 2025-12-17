     public function run(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // permission create
        $permissions = [
            'index employee',
            'create employee',
            'show employee',
            'edit employee',
            'delete employee',
        ];

        foreach ($permissions as $permissionName) {
            Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // role create
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $hrManagerRole = Role::create(['name' => 'HR Manager', 'guard_name' => 'web']);
        $hrManagerRole->givePermissionTo(['create employee', 'edit employee', 'delete employee',]);

        $managerRole = Role::create(['name' => 'Manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo(['index employee']);

        $employeeRole = Role::create(['name' => 'Employee', 'guard_name' => 'web']);
        $employeeRole->givePermissionTo(['show employee']);


        //  admin create
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'status'=>'Active',
            'salary'=>'40000',
        ]);
        $user->assignRole('Admin'); 
        
        //  admin create
        $user = User::create([
            'name' => 'HR Manager',
            'email' => 'hrmanager@gmail.com',
            'password' => Hash::make('123456789'),
            'status'=>'Active',
            'salary'=>'35000',
        ]);
        $user->assignRole('HR Manager'); 
        
        //  admin create
        $user = User::create([
            'name' => 'manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('123456789'),
            'status'=>'Active',
            'salary'=>'3000',
        ]);
        $user->assignRole('Manager'); 
        
        //  admin create
        $user = User::create([
            'name' => 'employee',
            'email' => 'employee@gmail.com',
            'password' => Hash::make('123456789'),
            'status'=>'Active',
            'salary'=>'2500',
        ]);
        $user->assignRole('Employee');
    }

