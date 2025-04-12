<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * إنشاء مستخدم جديد.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function createUser(array $data)
    {
        // تحقق من أن المستخدم لا يمكنه أن يصبح "admin" و "hr" في نفس الوقت
        if ($data['role'] === 'admin' && $data['role'] === 'hr') {
            throw ValidationException::withMessages([
                'role' => 'HR cannot be assigned as Admin.',
            ]);
        }

        // تحقق إذا كان المستخدم لديه دور بالفعل
        // if ($data['role'] && User::whereHas('roles', function ($query) use ($data) {
        //     $query->where('name', $data['role']);
        // })->exists()) {
        //     throw ValidationException::withMessages([
        //         'role' => 'The user already has this role assigned.',
        //     ]);
        // }

        // إنشاء المستخدم
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'job_number' => $data['job_number'],
            'department_id' => $data['department_id'],
            'status' => $data['status'] ?? 'active',
            'marital_status' => $data['marital_status'] ?? null,
            'number_of_children' => $data['number_of_children'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'university' => $data['university'] ?? null,
            'graduation_year' => $data['graduation_year'] ?? null,
        ]);

        // تعيين الدور للمستخدم
        $user->assignRole($data['role']);  // تعيين دور واحد فقط للمستخدم

        return $user;
    }

    /**
     * تحديث بيانات المستخدم.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\User
     */
    public function updateUser(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    /**
     * حذف المستخدم.
     *
     * @param  \App\Models\User  $user
     * @return bool|null
     */
    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}
