<?php

namespace App\Http\Controllers\Web\Backend\CMS\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch latest subscriptions and paginate (10 per page)
        $subscriptions = Subscription::latest()->paginate(10);

        // Return the list view with subscriptions
        return view('backend.layouts.subscriptions.list', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new subscription.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('backend.layouts.subscriptions.add');
    }

    /**
     * Store a newly created subscription in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //  Validate the input
        $request->validate([
            'email' => 'required|email|unique:subscriptions,email',
        ]);

        //  Create a new subscription record
        Subscription::create([
            'email' => $request->email,
        ]);

        //  Redirect to subscription list with success message
        return redirect()->route('web-subscriptions.index')
            ->with('success', 'Subscription added successfully.');
    }

    /**
     * Show the form for editing the specified subscription.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        //  Retrieve the subscription by ID or fail with 404
        $subscription = Subscription::findOrFail($id);

        //  Pass the subscription object to the edit view
        return view('backend.layouts.subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified subscription in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        //  Retrieve the subscription or fail if not found
        $subscription = Subscription::findOrFail($id);

        //  Validate the email input
        $request->validate([
            'email' => 'required|email|unique:subscriptions,email,' . $subscription->id,
        ]);

        // Update the subscription email
        $subscription->update([
            'email' => $request->email,
        ]);

        // Redirect back to subscription list with success message
        return redirect()->route('web-subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified subscription from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //  Retrieve the subscription by ID or fail with 404
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();


        return redirect()->route('web-subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }
}
