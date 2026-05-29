<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\User\IndexRequest;
use App\Http\Requests\Api\User\StoreRequest;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Requests\Api\User\DeleteRequest;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserWarehouse;
use App\Traits\UserTraits;
use Examyou\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends ApiBaseController
{
	use UserTraits;

	protected $model = User::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function __construct()
	{
		parent::__construct();

		$this->userType = "staff_members";
	}

	public function userByEmail(Request $request)
	{
		$validated = $request->validate([
			'email' => 'required|email',
		]);

		$warehouse = warehouse();
		$loggedUser = user();

		$query = User::with([
			'details.warehouse:id,name',
			'role:id,name,display_name',
			'warehouse:id,name',
			'userWarehouses:id,user_id,warehouse_id',
		])
			->where('email', $validated['email'])
			->where('user_type', $this->userType);

		if ($this->userType === 'staff_members' && $loggedUser) {
			$query->where('company_id', $loggedUser->company_id);
		}

		if ($warehouse) {
			$query->where(function ($builder) use ($warehouse) {
				$builder->whereHas('userWarehouses', function ($userWarehouseQuery) use ($warehouse) {
					$userWarehouseQuery->where('warehouse_id', $warehouse->id);
				})->orWhereHas('role', function ($roleQuery) {
					$roleQuery->where('name', 'admin');
				});
			});
		}

		$user = $query->first();

		if (!$user) {
			return response()->json([
				'message' => 'Email or Phone not available',
			], 404);
		}

		$user->makeVisible(['id', 'role_id', 'warehouse_id']);

		if ($user->details) {
			$user->details->makeVisible(['id', 'user_id', 'warehouse_id']);

			if ($user->details->warehouse) {
				$user->details->warehouse->makeVisible(['id']);
			}
		}

		if ($user->role) {
			$user->role->makeVisible(['id']);
		}

		if ($user->warehouse) {
			$user->warehouse->makeVisible(['id']);
		}

		if ($user->userWarehouses) {
			$user->userWarehouses->each(function ($userWarehouse) {
				$userWarehouse->makeVisible(['id', 'user_id', 'warehouse_id']);
			});
		}

		return ApiResponse::make('Data fetched', [
			'user' => $user,
		]);
	}

	public function publicShow($id)
	{
		$userId = $this->getIdFromHash($id);

		$user = User::with([
			'details.warehouse:id,name',
			'role:id,name,display_name',
			'warehouse:id,name',
			'userWarehouses:id,user_id,warehouse_id',
		])->find($userId);

		if (!$user) {
			return response()->json([
				'message' => 'User not found',
			], 404);
		}

		$user->makeVisible(['id', 'role_id', 'warehouse_id']);

		if ($user->details) {
			$user->details->makeVisible(['id', 'user_id', 'warehouse_id']);

			if ($user->details->warehouse) {
				$user->details->warehouse->makeVisible(['id']);
			}
		}

		if ($user->role) {
			$user->role->makeVisible(['id']);
		}

		if ($user->warehouse) {
			$user->warehouse->makeVisible(['id']);
		}

		if ($user->userWarehouses) {
			$user->userWarehouses->each(function ($userWarehouse) {
				$userWarehouse->makeVisible(['id', 'user_id', 'warehouse_id']);
			});
		}

		return ApiResponse::make('Data fetched', [
			'user' => $user,
		]);
	}

	public function publicUpdate(Request $request, $id)
	{
		$userId = $this->getIdFromHash($id);
		$user = User::find($userId);

		if (!$user) {
			return response()->json([
				'message' => 'User not found',
			], 404);
		}

		$validated = $request->validate([
			'name' => 'nullable|string|max:255',
			'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
			'phone' => 'nullable|string|max:30|unique:users,phone,' . $user->id,
			'address' => 'nullable|string',
			'status' => 'nullable|string|max:50',
			'shipping_address' => 'nullable|string',
			'tax_number' => 'nullable|string|max:255',
			'password' => 'nullable|string|min:8',
			'warehouse_id' => 'nullable',
			'role_id' => 'nullable',
			'warehouses' => 'nullable|array',
			'opening_balance' => 'nullable',
			'opening_balance_type' => 'nullable|string|max:50',
			'credit_period' => 'nullable',
			'credit_limit' => 'nullable',
		]);

		DB::transaction(function () use ($request, $user, $validated) {
			$user->name = $validated['name'] ?? $user->name;
			$user->email = $validated['email'] ?? $user->email;
			$user->phone = $validated['phone'] ?? $user->phone;
			$user->address = $validated['address'] ?? $user->address;
			$user->status = $validated['status'] ?? $user->status;
			$user->shipping_address = $validated['shipping_address'] ?? $user->shipping_address;
			$user->tax_number = $validated['tax_number'] ?? $user->tax_number;
			$user->user_type = 'staff_members';

			if (!empty($validated['password'])) {
				$user->password = $validated['password'];
			}

			if ($request->filled('warehouse_id')) {
				$user->warehouse_id = $this->normalizeIdentifier($request->warehouse_id);
			}

			if ($request->filled('role_id')) {
				$user->role_id = $this->normalizeIdentifier($request->role_id);
			}

			$user->save();

			if ($request->filled('role_id')) {
				DB::table('role_user')->where('user_id', $user->id)->delete();
				$user->attachRole($user->role_id);
			}

			if ($request->has('warehouses')) {
				UserWarehouse::where('user_id', $user->id)->delete();

				foreach ($request->warehouses as $selectedWarehouse) {
					$userWarehouse = new UserWarehouse();
					$userWarehouse->user_id = $user->id;
					$userWarehouse->warehouse_id = $this->normalizeIdentifier($selectedWarehouse);
					$userWarehouse->save();
				}
			}

			$warehouseId = $request->filled('warehouse_id')
				? $this->normalizeIdentifier($request->warehouse_id)
				: $user->warehouse_id;

			$userDetails = UserDetails::withoutGlobalScopes()->firstOrNew([
				'user_id' => $user->id,
				'warehouse_id' => $warehouseId,
			]);

			$userDetails->user_id = $user->id;
			$userDetails->warehouse_id = $warehouseId;
			$userDetails->opening_balance = $request->opening_balance === '' ? 0 : ($request->opening_balance ?? $userDetails->opening_balance ?? 0);
			$userDetails->opening_balance_type = $request->filled('opening_balance_type')
				? strtolower((string) $request->opening_balance_type)
				: ($userDetails->opening_balance_type ?: 'receive');
			$userDetails->credit_period = $request->credit_period === '' ? 0 : ($request->credit_period ?? $userDetails->credit_period ?? 0);
			$userDetails->credit_limit = $request->credit_limit === '' ? 0 : ($request->credit_limit ?? $userDetails->credit_limit ?? 0);
			$userDetails->save();
		});

		return $this->publicShow($id);
	}

	protected function normalizeIdentifier($value)
	{
		if ($value === null || $value === '') {
			return $value;
		}

		if (is_numeric($value)) {
			return (int) $value;
		}

		return Common::getIdFromHash($value);
	}
}
