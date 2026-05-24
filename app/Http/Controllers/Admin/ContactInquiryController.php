<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactInquiryController extends Controller
{
    public function index(Request $request): Response
    {
        $source = $request->query('source');

        $inquiries = ContactInquiry::query()
            ->when($request->query('q'), function ($query, string $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('business_name', 'like', "%{$term}%")
                        ->orWhere('website', 'like', "%{$term}%")
                        ->orWhere('message', 'like', "%{$term}%");
                });
            })
            ->when($request->boolean('unread'), fn ($q) => $q->unread())
            ->when($source, fn ($q, string $source) => $q->ofSource($source))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/ContactInquiries/Index', [
            'inquiries' => $inquiries,
            'filters' => [
                'q' => $request->query('q'),
                'unread' => $request->boolean('unread'),
                'source' => $source,
            ],
            'unreadCount' => ContactInquiry::query()->unread()->count(),
        ]);
    }

    public function show(ContactInquiry $contactInquiry): Response
    {
        return Inertia::render('Admin/ContactInquiries/Show', [
            'inquiry' => $contactInquiry,
        ]);
    }

    public function markRead(ContactInquiry $contactInquiry): RedirectResponse
    {
        $contactInquiry->update(['read_at' => now()]);

        return redirect()
            ->route('admin.contact-inquiries.show', $contactInquiry)
            ->with('status', 'Marked as read.');
    }

    public function markUnread(ContactInquiry $contactInquiry): RedirectResponse
    {
        $contactInquiry->update(['read_at' => null]);

        return redirect()
            ->route('admin.contact-inquiries.show', $contactInquiry)
            ->with('status', 'Marked as unread.');
    }

    public function destroy(ContactInquiry $contactInquiry): RedirectResponse
    {
        $contactInquiry->delete();

        return redirect()
            ->route('admin.contact-inquiries.index')
            ->with('status', 'Inquiry deleted.');
    }
}
