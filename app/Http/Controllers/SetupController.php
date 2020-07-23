<?php

namespace App\Http\Controllers;

use App\Events\EnvironmentSaved;
use App\Repositories\DomainRepository;
use App\Events\User\UserWasCreated;
use App\Notifications\Account\NewAccount;
use App\Models\Domain;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Models\Account;
use App\Factory\AccountFactory;
use App\Events\SetupFinished;
use App\Helpers\Setup\DatabaseManager;
use App\Helpers\Setup\EnvironmentManager;
use App\Helpers\Setup\FinalInstallManager;
use App\Helpers\Setup\InstalledFileManager;
use App\Helpers\Setup\PermissionsChecker;
use App\Helpers\Setup\RequirementsChecker;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use App\Factory\UserFactory;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @var EnvironmentManager
     */
    protected $environmentManager;

    /**
     * @var PermissionsChecker
     */
    protected $permissions;

    /**
     * @var RequirementsChecker
     */
    protected $requirements;

    /**
     * SetupController constructor.
     * @param DatabaseManager $databaseManager
     * @param EnvironmentManager $environmentManager
     * @param PermissionsChecker $checker
     * @param RequirementsChecker $requirementsChecker
     */
    public function __construct(
        DatabaseManager $databaseManager,
        EnvironmentManager $environmentManager,
        PermissionsChecker $checker,
        RequirementsChecker $requirementsChecker
    ) {
        $this->databaseManager = $databaseManager;
        $this->environmentManager = $environmentManager;
        $this->permissions = $checker;
        $this->requirements = $requirementsChecker;
    }

    /**
     * Display the requirements page.
     *
     * @return \Illuminate\View\View
     */
    public function requirements()
    {
        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        return view('setup.requirements', compact('requirements', 'phpSupportInfo'));
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        $response = $this->databaseManager->migrateAndSeed();

        return redirect()->route('setup.final')
                         ->with(['message' => $response]);
    }


    /**
     * Create the user and account.
     *
     * @return \Illuminate\View\View
     */
    public function user()
    {
        return view('setup.user');
    }

    public function permissions()
    {
        $permissions = $this->permissions->check(
            config('installer.permissions')
        );

        return view('setup.permissions', compact('permissions'));
    }

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('setup.welcome');
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        return view('setup.environment');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->environmentManager->getEnvContent();

        return view('setup.environment-wizard', compact('envConfig'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentClassic()
    {
        $envConfig = $this->environmentManager->getEnvContent();

        return view('setup.environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->environmentManager->saveFileClassic($input);

        event(new EnvironmentSaved($input));

        return $redirect->route('setup.environmentClassic')
                        ->with(['message' => $message]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('texts.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('setup.environment-wizard')->withInput()->withErrors($validator->errors());
        }

        if (!$this->checkDatabaseConnection($request)) {
            return $redirect->route('setup.environment-wizard')->withInput()->withErrors(
                [
                    'database_connection' => trans('texts.environment.wizard.form.db_connection_failed'),
                ]
            );
        }

        $results = $this->environmentManager->saveFileWizard($request);

        event(new EnvironmentSaved($request));

        return $redirect->route('setup.database')
                        ->with(['results' => $results]);
    }

    /**
     * Processes the newly saved user and account
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(Request $request, Redirector $redirect)
    {
        $rules = config('installer.user.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('texts.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('setup.user')->withInput()->withErrors($validator->errors());
        }

        // create domain
        $domain = (new DomainRepository(new Domain))->create($request->all());

        // create account
        $account = AccountFactory::create($domain->id);
        $account = (new AccountRepository(new Account))->save($request->all(), $account);

        // set default account
        $domain->default_account_id = $account->id;
        $domain->save();

        $user_repo = new UserRepository(new User);

        // create new user
        $user = $user_repo->save($request->all(), UserFactory::create($domain->id));
        $user->attachUserToAccount($account, true);

        if ($user) {
            auth()->login($user, false);
            event(new UserWasCreated($user));
            $user->notify(new NewAccount($account));
        }

        return $redirect->route('setup.environment');
    }

    /**
     * TODO: We can remove this code if PR will be merged: https://github.com/RachidLaasri/LaravelInstaller/pull/162
     * Validate database connection with user credentials (Form Wizard).
     *
     * @param Request $request
     * @return bool
     */
    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config(
            [
                'database' => [
                    'default'     => $connection,
                    'connections' => [
                        $connection => array_merge(
                            $settings,
                            [
                                'driver'   => $connection,
                                'host'     => $request->input('database_hostname'),
                                'port'     => $request->input('database_port'),
                                'database' => $request->input('database_name'),
                                'username' => $request->input('database_username'),
                                'password' => $request->input('database_password'),
                            ]
                        ),
                    ],
                ],
            ]
        );

        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update installed file and display finished view.
     *
     * @param \RachidLaasri\LaravelInstaller\Helpers\InstalledFileManager $fileManager
     * @param \RachidLaasri\LaravelInstaller\Helpers\FinalInstallManager $finalInstall
     * @param \RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(
        InstalledFileManager $fileManager,
        FinalInstallManager $finalInstall,
        EnvironmentManager $environment
    ) {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        event(new SetupFinished);

        return view('setup.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
