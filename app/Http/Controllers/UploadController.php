<?php

namespace App\Http\Controllers;

use App\File;
use App\Jobs\Utils\UploadFile;
use App\Repositories\TaskRepository;
use App\Requests\UploadRequest;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\FileRepository;
use App\Task;
use App\Transformations\FileTransformable;
use App\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AttachmentCreated;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    use FileTransformable;

    private $fileRepository;
    private $taskRepository;

    public function __construct(FileRepositoryInterface $fileRepository, TaskRepositoryInterface $taskRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->taskRepository = $taskRepository;
    }

    public function index($entity, $task_id)
    {
        $class = "App\\" . ucfirst($entity);
        $entity = $class::find($task_id);

        $uploads = $this->fileRepository->getFilesForEntity($entity);

        $uploads = $uploads->map(function (File $file) {
            return $this->transformFile($file);
        })->all();

        return response()->json($uploads);
    }

    /**
     * @param UploadRequest $request
     * @return mixed
     */
    public function store(UploadRequest $request)
    {
        $class = "App\\" . ucfirst($request->entity_type);
        $obj = $class::where('id', $request->entity_id)->first();
        $user = Auth::user();
        $account = auth()->user()->account_user()->account;
        $arrAddedFiles = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $count => $file) {
                $file = UploadFile::dispatchNow($file, $user, $account, $obj);

                $arrAddedFiles[$count] = $file;
                $arrAddedFiles[$count]['user'] = $user->toArray();
            }

            return collect($arrAddedFiles)->toJson();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function destroy($id)
    {

        $file = $this->fileRepository->findFileById($id);
        $fileRepo = new FileRepository($file);
        $fileRepo->deleteFile();
        return response()->json('File deleted!');
    }
}
