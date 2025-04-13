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
     * تحديث بيانات المستخدم العامة.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\User
     */
    public function updateEmployeeData(User $user, array $data)
    {
        // تحديث البيانات العامة للمستخدم
        $user->update($data);
        return $user;
    }

    /**
     * تحديث البيانات الشخصية للمستخدم.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\User
     */
    public function updatePersonalData(User $user, array $data)
    {
        // تحديث البيانات الشخصية فقط مثل المؤهل العلمي، الحالة الاجتماعية، العنوان
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
