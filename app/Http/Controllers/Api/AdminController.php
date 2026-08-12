<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\UserResource;
use App\Models\Appointment;
use App\Models\Enquiry;
use App\Models\Property;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats(Request $request)
    {
        return response()->json([
            'stats' => [
                'total_properties' => Property::count(),
                'active_properties' => Property::where('property_status', 'available')->count(),
                'sold_properties' => Property::where('property_status', 'sold')->count(),
                'rented_properties' => Property::where('property_status', 'rented')->count(),
                'total_users' => User::count(),
                'total_agents' => Agent::count(),
                'total_enquiries' => Enquiry::count(),
                'total_appointments' => Appointment::count(),
            ],
            'recent_enquiries' => Enquiry::with(['property', 'agent'])->orderBy('id', 'desc')->take(5)->get(),
            'recent_appointments' => Appointment::with(['property'])->orderBy('id', 'desc')->take(5)->get(),
        ]);
    }

    public function updateEnquiryStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contact_in_progress,resolved,archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update($validated);

        return response()->json([
            'message' => 'Enquiry status updated successfully',
            'data' => $enquiry,
        ]);
    }

    public function updateAppointmentStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
            'notes' => ['nullable', 'string'],
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($validated);

        return response()->json([
            'message' => 'Appointment status updated successfully',
            'data' => $appointment,
        ]);
    }

    public function togglePropertyPublish(int $id)
    {
        $property = Property::findOrFail($id);
        $property->update(['is_published' => !$property->is_published]);

        return response()->json([
            'message' => 'Property publish state toggled',
            'is_published' => $property->is_published,
        ]);
    }

    public function togglePropertyFeatured(int $id)
    {
        $property = Property::findOrFail($id);
        $property->update(['is_featured' => !$property->is_featured]);

        return response()->json([
            'message' => 'Property featured state toggled',
            'is_featured' => $property->is_featured,
        ]);
    }

    public function usersList()
    {
        $users = User::orderBy('id', 'desc')->get();
        return response()->json(['data' => UserResource::collection($users)]);
    }

    public function updateUserRole(Request $request, int $id)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,agent,user'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $validated['role']]);

        return response()->json([
            'message' => 'User role updated',
            'user' => new UserResource($user),
        ]);
    }
}
