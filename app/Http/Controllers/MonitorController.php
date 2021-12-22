<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\User;

class MonitorController extends Controller
{
    public function index()
    {
        $monitors = auth()->user()->monitors;

        return inertia('monitors/index', [
            'monitors' => $monitors,
        ]);
    }

    public function show(Monitor $monitor)
    {
        $this->checkMonitorOwnership($monitor);

        return inertia('monitors/show', [
            'monitor' => $monitor,
        ]);
    }

    public function create()
    {
        return inertia('monitors/create-or-update', [
            'types' => Monitor::TYPES,
        ]);
    }

    public function store()
    {
        $this->validateRequest();

        $monitor = new Monitor();
        $monitor->type = request()->type;
        $monitor->name = request()->name;

        $configuration = $monitor->configuration ?? [];

        $configuration['host'] = request()->host;
        $configuration['keyword'] = request()->keyword;
        $configuration['fails'] = request()->fails;
        $configuration['port'] = request()->port;

        $monitor->configuration = $configuration;

        $monitor->user()->associate(auth()->user());
        $monitor->save();

        return redirect()->route('monitors.index')->with('success', 'Monitor sucessfuly created.');
    }

    public function edit(Monitor $monitor)
    {
        $this->checkMonitorOwnership($monitor);

        return inertia('monitors/create-or-update', [
            'monitor' => $monitor,
            'types' => Monitor::TYPES,
        ]);
    }

    public function update(Monitor $monitor)
    {
        $this
            ->checkMonitorOwnership($monitor)
            ->validateRequest();

        $monitor->type = request()->type;
        $monitor->name = request()->name;

        $configuration = $monitor->configuration ?? [];

        $configuration['host'] = request()->host;
        $configuration['keyword'] = request()->keyword;
        $configuration['fails'] = request()->fails;
        $configuration['port'] = request()->port;

        $monitor->configuration = $configuration;
        $monitor->save();

        return redirect()->route('monitors.show', $monitor)->with('success', 'Monitor sucessfuly updated.');
    }

    public function delete(Monitor $monitor)
    {
        $this->checkMonitorOwnership($monitor);

        return inertia('monitors/delete', [
            'monitor' => $monitor,
        ]);
    }

    public function destroy(Monitor $monitor)
    {
        $this->checkMonitorOwnership($monitor);

        $monitor->heartbeats()->delete();
        $monitor->delete();

        return redirect()->route('monitors.index')->with('success', 'Monitor sucessfuly deleted.');
    }

    protected function checkMonitorOwnership(Monitor $monitor, User $user = null)
    {
        $userMonitors = ($user ?? auth()->user())->monitors()->select('id')->pluck('id');

        if (! $userMonitors->contains($monitor->id)) {
            abort(403, '');
        }

        return $this;
    }

    protected function validateRequest()
    {
        request()->validate([
            'type' => ['required'],
            'name' => ['required'],
            'host' => ['required_if:type,1,2,3'],
            'keyword' => ['required_if:type,2'],
            'fails' => ['required_if:type,2'],
            'port' => ['required_if:type,3'],
        ]);
    }
}
