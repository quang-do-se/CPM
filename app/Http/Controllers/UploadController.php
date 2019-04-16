<?php
/**
 * Created by PhpStorm.
 * User: q
 * Date: 4/13/19
 * Time: 5:25 PM
 */

namespace App\Http\Controllers;

use App\CPM\Objects\UploadedFile;
use App\CPM\Services\ICD9PhecodeUploadService;
use App\CPM\Services\ICD9UploadService;
use App\CPM\Services\PhecodeUploadService;
use App\Jobs\ProcessUploadedFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class UploadController extends Controller
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

    public function uploadICD9Landing()
    {
        return view('upload.icd9');
    }

    public function uploadICD9(Request $request)
    {
        $fileName = 'icd9_' . Carbon::now()->format('YmdHis') . '_' . session()->getId();

        $path = $request
            ->file('upload')
            ->storeAs('', $fileName);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setPath($path);
        $uploadedFile->setType(UploadedFile::ICD9);
        $uploadedFile->setHeader(true);

        ProcessUploadedFile::dispatch($uploadedFile);

        Flash::success('Upload ICD9 successfully. It may take up to 15 minutes to upload new data.');

        return view('upload.icd9');
    }

    public function uploadICD9PhecodeLanding()
    {
        return view('upload.icd9Phecode');
    }

    public function uploadICD9Phecode(Request $request, ICD9PhecodeUploadService $service)
    {
        $fileName = 'icd9Phecode_' . Carbon::now()->format('YmdHis') . '_' . session()->getId();

        $path = $request
            ->file('upload')
            ->storeAs('', $fileName);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setPath($path);
        $uploadedFile->setType(UploadedFile::ICD9_PHECODE);
        $uploadedFile->setHeader(true);

        ProcessUploadedFile::dispatch($uploadedFile);

        Flash::success('Upload ICD9 Phecode Map successfully. It may take up to 15 minutes to upload new data.');

        return view('upload.icd9Phecode');
    }

    public function uploadPhecodeLanding()
    {
        return view('upload.phecode');
    }

    public function uploadPhecode(Request $request, PhecodeUploadService $service)
    {
        $fileName = 'phecode_' . Carbon::now()->format('YmdHis') . '_' . session()->getId();

        $path = $request
            ->file('upload')
            ->storeAs('', $fileName);

        $uploadedFile = new UploadedFile();
        $uploadedFile->setPath($path);
        $uploadedFile->setType(UploadedFile::PHECODE);
        $uploadedFile->setHeader(true);

        ProcessUploadedFile::dispatch($uploadedFile);

        Flash::success('Upload Phecode successfully. It may take up to 15 minutes to upload new data.');

        return view('upload.phecode');
    }
}
