<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Validation\Rule;

class MonitorController extends Controller
{
    public function index()
    {
        $monitors = auth()->user()
            ->monitors()
            ->orderBy('created_at', 'DESC')
            ->with('latestHeartbeat')
            ->get();

        $monitors->each(fn ($monitor) => $monitor->append('uptime'));

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
        $contacts = auth()->user()
            ->contacts()
            ->orderBy('created_at', 'DESC')
            ->get()
            ->pluck('name', 'id');

        return inertia('monitors/create-or-update', [
            'types' => Monitor::TYPES,
            'contacts' => $contacts,
        ]);
    }

    public function store()
    {
        $this->validateRequest();
        $monitor = $this->fillFromRequest(new Monitor());

        return redirect()->route('monitors.index')->with('success', 'Monitor sucessfuly created.');
    }

    public function edit(Monitor $monitor)
    {
        $this->checkMonitorOwnership($monitor);

        $monitor->contactIds = $monitor->contacts->pluck('id');

        $contacts = auth()->user()
            ->contacts()
            ->orderBy('created_at', 'DESC')
            ->get()
            ->pluck('name', 'id');

        return inertia('monitors/create-or-update', [
            'monitor' => $monitor,
            'types' => Monitor::TYPES,
            'contacts' => $contacts,
        ]);
    }

    public function update(Monitor $monitor)
    {
        $this
            ->checkMonitorOwnership($monitor)
            ->validateRequest();

        $monitor = $this->fillFromRequest($monitor);

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
            abort(403, );
        }

        return $this;
    }

    protected function validateRequest()
    {
        $contacts = auth()->user()
            ->contacts()
            ->orderBy('created_at', 'DESC')
            ->select('id')->pluck('id');

        request()->validate([
            'type' => ['required'],
            'name' => ['required'],

            'host' => ['required_if:type,' . Monitor::TYPE_HTTP . ',' . Monitor::TYPE_KEYWORD . ',' . Monitor::TYPE_PORT],
            'keyword' => ['required_if:type,' . Monitor::TYPE_KEYWORD],
            'fails' => ['required_if:type,' . Monitor::TYPE_KEYWORD],
            'port' => ['required_if:type,' . Monitor::TYPE_PORT],

            'contacts.*' => ['nullable', Rule::in($contacts)],
        ]);
    }

    protected function fillFromRequest(Monitor $monitor)
    {
        $monitor->type = request()->type;
        $monitor->name = request()->name;

        $configuration = collect($monitor->configuration ?? []);

        $configuration['host'] = request()->host;
        $configuration['keyword'] = request()->keyword;
        $configuration['fails'] = request()->fails;
        $configuration['port'] = request()->port;

        $configuration = $configuration->filter();

        $monitor->configuration = $configuration;

        $monitor->user()->associate(auth()->user());
        $monitor->contacts()->sync(request()->contacts);
        $monitor->save();

        return $monitor;
    }
}
