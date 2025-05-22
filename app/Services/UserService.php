<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserService
{


 /**
     * جلب المستخدمين الذين لديهم دور "manager".
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getManagers()
    {
        return User::role('manager')->get();
    }
    /**
     * إنشاء مستخدم جديد.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
  public function createUser(array $data, User $creator): User
{
    // التحقق من أن الدور الممنوح مقبول حسب دور منشئ المستخدم
    if ($creator->hasRole('hr') && !in_array(strtolower($data['role']), ['manager', 'employee'])) {
        throw ValidationException::withMessages([
            'role' => 'HR can only create users with role manager or employee.',
        ]);
    }

    // منع تعيين دور admin و hr معًا (لو كانت أدوار متعددة)
    if (isset($data['role']) && is_array($data['role']) && count(array_intersect($data['role'], ['admin', 'hr'])) > 1) {
        throw ValidationException::withMessages([
            'role' => 'A user cannot be assigned both admin and hr roles at the same time.',
        ]);
    }

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

    $user->assignRole($data['role']); // تعيين دور واحد فقط

    return $user;
}


    /**
     * تحديث بيانات الموظف العامة.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\User
     */
    public function updateEmployeeData(User $user, array $data, User $editor): User
{
    // إذا كان المحرر HR، لا يسمح بتعديل أو تعيين دور admin أو hr
    if ($editor->hasRole('hr')) {
        if (isset($data['role']) && in_array(strtolower($data['role']), ['admin', 'hr'])) {
            throw ValidationException::withMessages([
                'role' => 'HR cannot assign admin or hr roles.',
            ]);
        }
        // لا يسمح بتعديل مستخدم admin أو hr
        if ($user->hasRole('admin') || $user->hasRole('hr')) {
            throw ValidationException::withMessages([
                'user' => 'HR cannot update users with admin or hr roles.',
            ]);
        }
    }

    // إذا كان المحرر مدير، يمنع تعديل المستخدمين خارج القسم أو أدوار أعلى/مساوية
    if ($editor->hasRole('manager')) {
        if ($user->department_id !== $editor->department_id) {
            throw ValidationException::withMessages([
                'user' => 'Manager can only update users in their own department.',
            ]);
        }
        if ($user->hasRole('admin') || $user->hasRole('hr') || $user->hasRole('manager')) {
            throw ValidationException::withMessages([
                'user' => 'Manager cannot update Admin, HR, or Manager users.',
            ]);
        }
        if (isset($data['role']) && in_array(strtolower($data['role']), ['admin', 'hr', 'manager'])) {
            throw ValidationException::withMessages([
                'role' => 'Manager cannot assign admin, hr, or manager roles.',
            ]);
        }
    }

    // تحديث الدور إذا أُرسل
    if (isset($data['role'])) {
        $user->syncRoles([$data['role']]);
        unset($data['role']);
    }

    $user->update($data);

    return $user->fresh();
}


    /**
     * حذف المستخدم.
     *
     * @param  \App\Models\User  $user
     * @return bool|null
     */

public function deleteUser(User $userToDelete, User $actor): bool|null
{
    // Admin يقدر يحذف الجميع
    if ($actor->hasRole('admin')) {
        return $userToDelete->delete();
    }

    // HR لا يستطيع حذف admin أو hr
    if ($actor->hasRole('hr')) {
        if ($userToDelete->hasRole('admin') || $userToDelete->hasRole('hr')) {
            throw ValidationException::withMessages([
                'user' => 'HR cannot delete users with admin or hr roles.',
            ]);
        }
        return $userToDelete->delete();
    }

    // Manager يحذف موظفين قسمه فقط ومن أدوار أقل
    if ($actor->hasRole('manager')) {
        if ($userToDelete->department_id !== $actor->department_id) {
            throw ValidationException::withMessages([
                'user' => 'Manager can only delete users in their own department.',
            ]);
        }
        if ($userToDelete->hasRole('admin') || $userToDelete->hasRole('hr') || $userToDelete->hasRole('manager')) {
            throw ValidationException::withMessages([
                'user' => 'Manager cannot delete Admin, HR, or Manager users.',
            ]);
        }
        return $userToDelete->delete();
    }

    // المستخدمين العاديين لا يملكون صلاحية الحذف
    throw ValidationException::withMessages([
        'user' => 'You do not have permission to delete users.',
    ]);
}

    /**
 * إرجاع المستخدمين الذين يحق للمستخدم الحالي رؤيتهم.
 *
 * @param  \App\Models\User  $user
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getUsersVisibleToPaginated(User $user, int $perPage = 15)
{
    $query = User::with(['department', 'manager', 'roles']);

    if ($user->hasRole('manager')) {
        $query->where('department_id', $user->department_id)
              ->where('id', '!=', $user->id)
              ->whereDoesntHave('roles', function ($q) {
                  $q->whereIn('name', ['admin', 'hr', 'manager']);
              });
    }

    if ($user->hasRole('hr')) {
        $query->whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'hr']);
        });
    }

    // ترجع النتيجة مع pagination
    return $query->paginate($perPage);
}




}
