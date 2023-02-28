<?php

function isRole($user_id, $role_name)
{
    $roleModel = new App\Models\RoleModel();
    $userRolesModel = new App\Models\UserRolesModel();

    $roleData  = $roleModel->where('name', $role_name)->first();
    $userRoles = $userRolesModel->where('role_id', $roleData['id'])
                                ->where('user_id', $user_id)
                                ->first();

    if (!empty($userRoles))
    {
        return TRUE;
    }

    return 0; //"return FALSE" returns empty string
}

function getRoleIdByName($role_name)
{
    $roleModel = new App\Models\RoleModel();

    $roleData = $roleModel->where('name', $role_name)->first();

    return $roleData['id'];
}

function assignRole($user_id, $role = 'student')
{
    // $userRolesModel = new App\Models\UserRolesModel(); // Does not work with register function because we have another query running (inser user data) maybe

    if (gettype($role) == 'string')
    {
        $role_id = getRoleIdByName($role);
    }
    elseif (gettype($role) == 'integer')
    {
        $role_id = $role;
    }

    $db = \Config\Database::connect();
    $db->table('users_roles')->insert([
        'role_id' => $role_id,
        'user_id' => $user_id,
    ]);
}

// Get Users By Role
function get_users_by_role($role_name = null)
{
    $role_id = getRoleIdByName($role_name);
    
    $userRolesModel = new App\Models\UserRolesModel();
    $results = $userRolesModel->where('role_id', $role_id)->findAll();

    $role_users = array();
    $userModel = new App\Models\UserModel();
    foreach ($results as $row)
    {
        $user = $userModel->find($row['user_id']);
        $role_users[] = $user;
    }

    return $role_users;
}