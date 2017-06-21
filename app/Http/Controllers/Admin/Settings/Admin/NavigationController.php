<?php

namespace App\Http\Controllers\Admin\Settings\Admin;

use App\Http\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\NavigationAdmin;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Admin\AdminController;

class NavigationController extends AdminController
{
    /**
     * Display a listing of navigation.
     *
     * @return Response
     */
    public function index()
    {
        save_resource_url();

        return $this->showIndex('settings.admin.navigation.index');
    }

    /**
     * Show the form for creating a new navigation.
     *
     * @return Response
     */
    public function create()
    {
        return $this->view('settings.admin.navigation.add_edit')
            ->with('parents', NavigationAdmin::getAllLists());
    }

    /**
     * Store a newly created navigation in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, NavigationAdmin::$rules, NavigationAdmin::$messages);

        $inputs = $request->all();
        $inputs['is_hidden'] = boolval($request->has('is_hidden'));
        $inputs['url_parent_id'] = ($inputs['url_parent_id'] == 0 ? $inputs['parent_id'] : $inputs['url_parent_id']);

        $row = $this->createEntry(NavigationAdmin::class, $inputs);

        if ($row) {
            $row->updateUrl()->save();
        }

        return redirect_to_resource();
    }

    /**
     * Display the specified navigation.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $navigation = NavigationAdmin::findOrFail($id);

        return $this->view('settings.admin.navigation.show')->with('item', $navigation);
    }

    /**
     * Show the form for editing the specified navigation.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $navigation = NavigationAdmin::findOrFail($id);

        return $this->view('settings.admin.navigation.add_edit')
            ->with('item', $navigation)
            ->with('parents', NavigationAdmin::getAllLists());
    }

    /**
     * Update the specified navigation in storage.
     *
     * @param         $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, NavigationAdmin::$rules, NavigationAdmin::$messages);

        $inputs = $request->all();
        $inputs['is_hidden'] = boolval($request->has('is_hidden'));

        $navigation = NavigationAdmin::findOrFail($id);
        $navigation = $this->updateEntry($navigation, $inputs);
        $navigation->updateUrl()->save();

        return redirect_to_resource();
    }

    /**
     * Remove the specified navigation from storage.
     *
     * @param         $id
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $navigation = NavigationAdmin::findOrFail($id);
        $this->deleteEntry($navigation, $request);

        return redirect_to_resource();
    }

    /**
     * Return the data formatted for the table
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTableData()
    {
        $items = $this->getTableRows();

        return Datatables::of($items)->editColumn('parent', function ($row) {
            return ($row->parent) ? $row->parent->title : '-';
        })->editColumn('is_hidden', function ($row) {
            return ($row->is_hidden == 1 ? 'Yes' : '');
        })->addColumn('action', function ($row) {
            return action_row($this->selectedNavigation->url, $row->id, $row->title, ['edit', 'delete']);
        })->make(true);
    }

    /**
     * Get the resource items
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function getTableRows()
    {
        return NavigationAdmin::with('parent')->get();
    }
}