<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\Notifications\ContactTestNotification;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = auth()->user()
            ->contacts()
            ->orderBy('created_at', 'DESC')
            ->get();

        return inertia('contacts/index', [
            'contacts' => $contacts,
            'types' => Contact::TYPES,
        ]);
    }

    public function create()
    {
        return inertia('contacts/create-or-update', [
            'types' => Contact::TYPES,
        ]);
    }

    public function store()
    {
        $this->validateRequest();
        $contact = $this->fillFromRequest(new Contact());

        return redirect()->route('contacts.index')->with('success', 'Contact sucessfuly created.');
    }

    public function edit(Contact $contact)
    {
        $this->checkContactOwnership($contact);

        return inertia('contacts/create-or-update', [
            'contact' => $contact,
            'types' => Contact::TYPES,
        ]);
    }

    public function update(Contact $contact)
    {
        $this
            ->checkContactOwnership($contact)
            ->validateRequest();

        $contact = $this->fillFromRequest($contact);

        return redirect()->route('contacts.index')->with('success', 'Contact sucessfuly updated.');
    }

    public function delete(Contact $contact)
    {
        $this->checkContactOwnership($contact);

        return inertia('contacts/delete', [
            'contact' => $contact,
        ]);
    }

    public function destroy(Contact $contact)
    {
        $this->checkContactOwnership($contact);

        $contact->monitors()->sync([]);
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact sucessfuly deleted.');
    }

    public function notify(Contact $contact)
    {
        $this->checkContactOwnership($contact);

        try {
            $contact->notifyNow(new ContactTestNotification());
        } catch (\Throwable $th) {
            throw $th;

            return redirect()->route('contacts.index')->with('error', 'An error occurred while sending the message');
        }

        return redirect()->route('contacts.index')->with('success', 'A test message has been sent.');
    }

    protected function checkContactOwnership(Contact $contact, User $user = null)
    {
        $userContacts = ($user ?? auth()->user())->contacts()->select('id')->pluck('id');

        if (! $userContacts->contains($contact->id)) {
            abort(403);
        }

        return $this;
    }

    protected function validateRequest()
    {
        request()->validate([
            'type' => ['required'],
            'name' => ['required'],

            'discordWebhook' => ['required_if:type,' . Contact::TYPE_DISCORD],
        ]);
    }

    protected function fillFromRequest(Contact $contact)
    {
        $contact->type = request()->type;
        $contact->name = request()->name;

        $configuration = collect($contact->configuration ?? []);

        $configuration['discord_webhook'] = request()->discordWebhook;

        $configuration = $configuration->filter();

        $contact->configuration = $configuration;

        $contact->user()->associate(auth()->user());
        $contact->save();

        return $contact;
    }
}
