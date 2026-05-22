<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Examyou\RestAPI\Exceptions\ApiException;
use Examyou\RestAPI\Exceptions\UnauthorizedException;
use Vinkla\Hashids\Facades\Hashids;

class CheckPermission
{
    protected function isApiPathMatch(string $path, string $target)
    {
        return $path === "api/{$target}"
            || $path === "api/v1/{$target}"
            || str_starts_with($path, "api/{$target}/")
            || str_starts_with($path, "api/v1/{$target}/");
    }

    protected function userHasAnyPermission($user, array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($user->ability('admin', $permission)) {
                return true;
            }
        }

        return false;
    }

    protected function checkCustomPermission($request, $user)
    {
        $path = trim($request->path(), '/');

        if ($request->isMethod('get') && $this->isApiPathMatch($path, 'store-ledger')) {
            $type = strtolower((string) $request->query('type', ''));

            if ($type === 'supplier') {
                $permissions = ['suppliers_view', 'suppliers_create', 'suppliers_edit', 'purchases_view'];
            } else if ($type === 'customer' || $type === 'walkin' || $type === '') {
                $permissions = ['customers_view', 'customers_create', 'customers_edit', 'sales_view', 'sales_create', 'sales_edit'];
            } else {
                $permissions = [
                    'customers_view', 'customers_create', 'customers_edit',
                    'suppliers_view', 'suppliers_create', 'suppliers_edit',
                    'sales_view', 'sales_create', 'sales_edit',
                    'purchases_view',
                ];
            }

            if (!$this->userHasAnyPermission($user, $permissions)) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('post') && $path === 'api/v1/store-ledger/customer') {
            if (!$this->userHasAnyPermission($user, ['sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('get') && $this->isApiPathMatch($path, 'products')) {
            if (!$this->userHasAnyPermission($user, ['products_view', 'sales_view', 'sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('get') && $this->isApiPathMatch($path, 'sales-number')) {
            if (!$this->userHasAnyPermission($user, ['customers_view', 'customers_create', 'customers_edit', 'sales_view', 'sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('get') && $this->isApiPathMatch($path, 'shipping-list')) {
            if (!$this->userHasAnyPermission($user, ['customers_view', 'customers_create', 'customers_edit', 'sales_view', 'sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('get') && $this->isApiPathMatch($path, 'payment-mode')) {
            if (!$this->userHasAnyPermission($user, ['payment_in_view', 'payment_in_create', 'payment_in_edit', 'sales_view', 'sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('get') && $this->isApiPathMatch($path, 'receiptbank')) {
            if (!$this->userHasAnyPermission($user, ['payment_in_view', 'payment_in_create', 'payment_in_edit', 'sales_view', 'sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('post') && $path === 'api/sales/store') {
            $permission = $request->filled('selectedInvoice') && $request->selectedInvoice !== 'null'
                ? 'sales_edit'
                : 'sales_create';

            if (!$user->ability('admin', $permission)) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('post') && $path === 'api/sales/salesReturn') {
            $permission = $request->filled('selectedInvoice') && $request->selectedInvoice !== 'null'
                ? 'sales_returns_edit'
                : 'sales_returns_create';

            if (!$user->ability('admin', $permission)) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('post') && $path === 'api/sales/savepayment') {
            if (!$this->userHasAnyPermission($user, ['payment_in_create', 'payment_in_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if (
            $request->isMethod('get')
            && (
                str_starts_with($path, 'api/sales/getInvoiceDetails/')
                || str_starts_with($path, 'api/sales/getInvoiceItems/')
            )
        ) {
            if (!$this->userHasAnyPermission($user, ['sales_view', 'sales_create', 'sales_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        if ($request->isMethod('get') && str_starts_with($path, 'api/sales/getReturnInvoiceDetails/')) {
            if (!$this->userHasAnyPermission($user, ['sales_returns_view', 'sales_returns_create', 'sales_returns_edit'])) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            return true;
        }

        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // api.users.index.v1

        if (auth('api')->check()) {
            $user = auth('api')->user();

            if ($this->checkCustomPermission($request, $user)) {
                return $next($request);
            }

            $resourceRequests = ['index', 'store', 'update', 'show', 'destroy'];
            $urlArray = explode('.', $request->route()->action['as']);
            $resourceRequestString = $urlArray[2];

            if ($urlArray && $urlArray[1]) {
                $routePathString = str_replace('-', '_', $urlArray[1]);
            }

            if ($routePathString == 'pos' && !$user->ability('admin', 'pos_view')) {
                throw new UnauthorizedException("Don't have valid permission");
            }

            // Those route for which we don't want to check permission
            // We will check permission for those on controller level
            $skipResourcePath = ['payments'];

            if (in_array($resourceRequestString, $resourceRequests) && in_array($routePathString, $skipResourcePath) === false) {

                // Lang resource will have translations permission
                if ($routePathString == 'langs') {
                    $routePathString = "translations";
                }

                $permission = "";
                $requestFields = $request->fields;

                if (($resourceRequestString == 'index' || $resourceRequestString == 'show') && $requestFields != null) {
                    $permission = $routePathString . '_view';
                }

                if ($resourceRequestString == 'store') {
                    $permission = $routePathString . '_create';
                } else if ($resourceRequestString == 'update') {
                    $permission = $routePathString . '_edit';
                } else if ($resourceRequestString == 'destroy') {
                    $permission = $routePathString . '_delete';
                }

                if ($permission != "" && !$user->ability('admin', $permission)) {
                    throw new UnauthorizedException("Don't have valid permission");
                }
            }
        }

        return $next($request);
    }
}
