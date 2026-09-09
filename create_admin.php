<?php
$user = App\Models\User::firstOrCreate(
    ['email' => 'admin@signia.pe'],
    ['name' => 'Super Admin', 'password' => \Illuminate\Support\Facades\Hash::make('signia2026'), 'is_admin' => true]
);
if (!$user->agency) {
    App\Models\Agency::create([
        'user_id' => $user->id,
        'company_name' => 'Signia HQ',
        'balance' => 999999
    ]);
} else {
    $user->agency->update(['balance' => 999999, 'company_name' => 'Signia HQ']);
}
echo "Admin created successfully.\n";
exit();
