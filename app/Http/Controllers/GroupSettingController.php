<?php

namespace App\Http\Controllers;

use App\Factory\GroupSettingFactory;
use App\Filters\GroupSettingFilter;
use App\Http\Requests\SignupRequest;
use App\Requests\GroupSetting\StoreGroupSettingRequest;
use App\Requests\GroupSetting\UpdateGroupSettingRequest;
use App\GroupSetting;
use App\Repositories\GroupSettingRepository;
use App\Requests\SearchRequest;
use App\Settings\GroupSettings;
use App\Transformations\GroupSettingTransformable;
use App\Traits\UploadableTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class GroupSettingController
 * @package App\Http\Controllers
 */
class GroupSettingController extends Controller
{
    use DispatchesJobs;
    use UploadableTrait;
    use GroupSettingTransformable;

    protected GroupSettingRepository $group_setting_repo;

    /**
     * GroupSettingController constructor.
     * @param GroupSettingRepository $group_setting_repo
     */
    public function __construct(GroupSettingRepository $group_setting_repo)
    {
        $this->group_setting_repo = $group_setting_repo;
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SearchRequest $request)
    {
        $group_settings = (new GroupSettingFilter($this->group_setting_repo))->filter(
            $request,
            auth()->user()->account_user()->account
        );

        return response()->json($group_settings);
    }

    /**
     * @param StoreGroupSettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGroupSettingRequest $request)
    {
        $group_setting = GroupSettingFactory::create(auth()->user()->account_user()->account_id, auth()->user()->id);
        $group_setting = $this->group_setting_repo->save($request->except('settings'), $group_setting);
        $group_setting = (new GroupSettings)->save($group_setting, $request->settings);

        if (!$group_setting) {
            return response()->json('Unable to save group');
        }

        return response()->json($this->transformGroupSetting($group_setting));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        return response()->json($this->transformGroupSetting($this->group_setting_repo->findGroupSettingById($id)));
    }

    /**
     * @param int $id
     * @param UpdateGroupSettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, UpdateGroupSettingRequest $request)
    {
        $group_setting = $this->group_setting_repo->findGroupSettingById($id);
        $group_setting = $this->group_setting_repo->save($request->except('settings'), $group_setting);
        $group_setting = (new GroupSettings)->save($group_setting, (object)$request->settings);
        return response()->json($this->transformGroupSetting($group_setting));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function archive(int $id)
    {
        $group_setting = $this->group_setting_repo->findGroupSettingById($id);
        $group_setting->delete();
        return response()->json([], 200);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $tax_rate = GroupSetting::withTrashed()->where('id', '=', $id)->first();
        $this->group_setting_repo->newDelete($tax_rate);
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function filterGroups(Request $request)
    {
        $quotes = (new GroupSettingFilter($this->group_setting_repo))->filterBySearchCriteria(
            $request->all(),
            auth()->user()->account_user()->account_id
        );
        return response()->json($quotes);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        $group = GroupSetting::withTrashed()->where('id', '=', $id)->first();
        $this->group_setting_repo->restore($group);
        return response()->json([], 200);
    }
}
