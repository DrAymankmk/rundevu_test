<?php

namespace App\Http\Controllers\AdminPanel\MainAdmin;

use App\Http\Controllers\Controller;
use App\Models\NotificationEvent;
use App\Models\NotificationRecipient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationRecipientController extends Controller
{
    public function index()
    {
        $recipients = NotificationRecipient::with('events')->latest()->get();
        $events = NotificationEvent::query()->where('is_active', true)->orderBy('key')->get();

        return view('main_admin.notificationRecipients.index', compact('recipients', 'events'));
    }

    public function store(Request $request)
    {
        $data = $this->validateRecipient($request);

        $recipient = NotificationRecipient::create([
            'email' => $data['email'],
            'label' => $data['label'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $recipient->syncEvents($data['events']);

        session()->flash('success', trans('messages.Added'));

        return redirect()->route('notification-recipients.index');
    }

    public function update(Request $request, $id)
    {
        $recipient = NotificationRecipient::findOrFail($id);
        $data = $this->validateRecipient($request, $recipient->id);

        $recipient->update([
            'email' => $data['email'],
            'label' => $data['label'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $recipient->syncEvents($data['events']);

        session()->flash('success', trans('messages.updated'));

        return redirect()->route('notification-recipients.index');
    }

    public function destroy($id)
    {
        NotificationRecipient::findOrFail($id)->delete();

        session()->flash('success', trans('messages.deleted'));

        return redirect()->route('notification-recipients.index');
    }

    private function validateRecipient(Request $request, ?int $recipientId = null): array
    {
        return $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('notification_recipients', 'email')->ignore($recipientId),
            ],
            'label' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'events' => 'required|array|min:1',
            'events.*' => 'integer|exists:notification_events,id',
        ]);
    }
}
