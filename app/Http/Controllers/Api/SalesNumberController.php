<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\SalesNumber;
use Examyou\RestAPI\ApiResponse;
use App\Http\Requests\Api\SalesNumber\IndexRequest;
use App\Http\Requests\Api\SalesNumber\StoreRequest;
use App\Http\Requests\Api\SalesNumber\UpdateRequest;
use App\Http\Requests\Api\SalesNumber\DeleteRequest;
class SalesNumberController extends ApiBaseController
{
    protected $model = SalesNumber::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function index()
    {
        $this->validate();

        $this->parseRequest()
            ->addIncludes()
            ->addFilters()
            ->addOrdering();

        if (request()->filled('party')) {
            $this->setQuery(
                $this->getQuery()->where('ledger_id', request()->party)
            );
        }

        $results = $this->addPaging()
            ->modify()
            ->getResults()
            ->toArray();

        $meta = $this->getMetaData();

        return ApiResponse::make(null, $results, $meta);
    }

    public function show(...$args)
    {
        // Get the $id from the first argument
        $id = $args[0];
    
        // Retrieve the sales number using the provided $id
        $sale = SalesNumber::find($id);
    
        if (!$sale) {
            return response()->json(['error' => 'Sale not found'], 404);
        }
    
        return response()->json($sale);
    }
    
    
  


}
