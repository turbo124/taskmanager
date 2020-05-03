   <?php
    namespace App\Http\Controllers;

    use App\Timer;
    use App\Factory\TimerFactory;
    use App\Repositories\TimerRepository;
    use App\Repositories\TaskRepository;
    use App\Project;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class TimerController extends Controller
    {
        private $task_repo;

        private $timer_repo;

        public function __constructor(TaskRepository $task_repo, TimerRepository $timer_repo)
        {
            $this->timer_repo = $timer_repo;
            $this->task_repo = $task_repo;
        }

        public function store(Request $request, int $id)
        {
            $data = $request->validate(['name' => 'required|between:3,100']);
            $task = $this->task_repo->findTaskById($id);

            $this->timer_repo->save(TimerFactory::create(auth()->user()->id, $account_id, $task), $request->all);

            return $timer->with('project')->find($timer->id);
        }

        public function running()
        {
            return Timer::with('task')->mine()->running()->first() ?? [];
        }

        public function stopRunning()
        {
            if ($timer = Timer::mine()->running()->first()) {
                $timer->update(['stopped_at' => new Carbon]);
            }

            return $timer;
        }
    }
