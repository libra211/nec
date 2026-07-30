<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscriber::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('source', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $subscribers = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function export(): StreamedResponse
    {
        $subscribers = Subscriber::orderByDesc('created_at')->get();

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Name', 'Email', 'Source', 'Status', 'Created At']);

            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->id,
                    $subscriber->name,
                    $subscriber->email,
                    $subscriber->source,
                    $subscriber->status,
                    $subscriber->created_at,
                ]);
            }

            fclose($handle);
        }, 'subscribers_' . date('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);

        $this->logActivity('subscriber_exported', "Exported " . $subscribers->count() . " subscribers to CSV");
    }

    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        $this->logActivity('subscriber_deleted', "Deleted subscriber: {$subscriber->email}", $subscriber);

        return redirect()->route('admin.subscribers.index')->with('success', 'Subscriber deleted.');
    }
}
