<?php

namespace App\Http\Controllers\Api;

    use App\Models\Station;
    use App\Http\Controllers\ApiBaseController;
    use App\Http\Requests\Api\Station\IndexRequest;
    use App\Http\Requests\Api\Station\StoreRequest;
    use App\Http\Requests\Api\Station\UpdateRequest;
    use App\Http\Requests\Api\Station\DeleteRequest;
    use Illuminate\Http\Request;
    class StationController extends ApiBaseController
    {
        protected $model = Station::class;
        protected $indexRequest = IndexRequest::class;
        protected $storeRequest = StoreRequest::class;
        protected $updateRequest = UpdateRequest::class;
        protected $deleteRequest = DeleteRequest::class;
    }
