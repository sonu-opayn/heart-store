<?php

namespace App\HeartStore\Services;

use App\HeartStore\Services\BaseService;
use Spatie\Permission\Models\Role;

class RoleService extends BaseService 
{
	const ADMIN = 'Admin';
	const CUSTOMER = 'Customer';

	public static function getRolesConst() 
	{
		return [
			self::ADMIN,
			self::CUSTOMER
		];
	} 

	/**
	 * Get all roles
	 */
	public function getRoles()
	{
		return Role::where('name', '!=', self::ADMIN)->select(['id', 'name'])->orderBy('name', 'asc')->get();
	}

	public function getRoleList()
	{
		return Role::where('name', '!=', self::ADMIN)->orderBy('name', 'asc')->pluck('name')->toArray();
	}
}