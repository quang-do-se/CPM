<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/15/19
 * Time: 4:33 PM
 */

namespace App\Http\Controllers;

use App\CPM\Repositories\ICD9PhecodeRepository;
use App\CPM\Repositories\ICD9Repository;
use App\CPM\Repositories\PhecodeRepository;
use Illuminate\Http\Request;

class SearchAPIController extends APIBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    const MAX_LIMIT = 10000;
    const MIN_LIMIT = 1000;

    public function getICD9Phecode(Request $request, ICD9PhecodeRepository $repository)
    {
        if ($request->query('limit') !== null) {
            $limit = (int) $request->query('limit');
            if ($limit > self::MAX_LIMIT) {
                $limit = self::MAX_LIMIT;
            }
        } else {
            $limit = self::MIN_LIMIT;
        }

        if ($request->query('offset') !== null) {
            $offset = (int) $request->query('offset');
        } else {
            $offset = 0;
        }

        $typeahead = false;

        if ($request->query('typeahead') !== null) {
            $typeahead = true;
        }

        $collection = collect();

        if ($request->query('phecode') !== null) {
            $collection = $repository->getByPhecode(
                $request->query('phecode'),
                $typeahead,
                $offset,
                $limit
            );
        }

        if ($request->query('icd9') !== null) {
            $collection = $repository->getByICD9(
                $request->query('icd9'),
                $typeahead,
                $offset,
                $limit
            );
        }

        return response()->json(
            $this->responseOK(
                'Retrieved data successfully',
                $collection->toArray()
            ),
            200
        );
    }

    public function getICD9(Request $request, ICD9Repository $repository)
    {
        if ($request->query('limit') !== null) {
            $limit = $request->query('limit');
            if ($limit > self::MAX_LIMIT) {
                $limit = self::MAX_LIMIT;
            }
        } else {
            $limit = self::MIN_LIMIT;
        }

        if ($request->query('offset') !== null) {
            $offset = $request->query('offset');
        } else {
            $offset = 0;
        }

        $typeahead = false;

        if ($request->query('typeahead') !== null) {
            $typeahead = true;
        }

        if ($request->query('code') !== null) {
            $collection = $repository->get(
                $request->query('code'),
                $typeahead,
                $offset,
                $limit
            );
        } else {
            $collection = $repository->getAll(
                $offset,
                $limit
            );
        }
        return response()->json(
            $this->responseOK(
                'Retrieved data successfully',
                $collection->toArray()
            ),
            200
        );
    }

    public function getPhecode(Request $request, PhecodeRepository $repository)
    {
        if ($request->query('limit') !== null) {
            $limit = $request->query('limit');
            if ($limit > self::MAX_LIMIT) {
                $limit = self::MAX_LIMIT;
            }
        } else {
            $limit = self::MIN_LIMIT;
        }

        if ($request->query('offset') !== null) {
            $offset = $request->query('offset');
        } else {
            $offset = 0;
        }

        $typeahead = false;

        if ($request->query('typeahead') !== null) {
            $typeahead = true;
        }

        if ($request->query('code') !== null) {
            $collection = $repository->get(
                $request->query('code'),
                $typeahead,
                $offset,
                $limit
            );
        } else {
            $collection = $repository->getAll(
                $offset,
                $limit
            );
        }
        return response()->json(
            $this->responseOK(
                'Retrieved data successfully',
                $collection->toArray()
            ),
            200
        );
    }
}
