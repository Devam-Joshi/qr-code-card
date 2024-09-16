<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Volunteer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\QrCodeScannedNotification;

class VolunteerController extends Controller
{
    // Show form to create a volunteer
    public function create()
    {
        return view('volunteers.create');
    }

    // Store volunteer and generate QR code
    public function store(Request $request)
    {
        // Create a unique token for the QR code
        $qrCodeToken = uniqid('volunteer_');

        // Create the volunteer
        $volunteer = Volunteer::create([
            'name' => $request->name,
            'email' => $request->email,
            'qr_code_token' => $qrCodeToken,
        ]);

        // Generate QR code containing a URL to the scan endpoint
        $qrCodeUrl = route('volunteers.scan', ['token' => $qrCodeToken]);
        $qrCode = QrCode::size(300)->generate($qrCodeUrl);

        return view('volunteers.show', compact('volunteer', 'qrCode'));
    }

    // Display the volunteer with their QR code
    public function show($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $qrCode = QrCode::size(300)->generate(route('volunteers.scan', ['token' => $volunteer->qr_code_token]));

        return view('volunteers.show', compact('volunteer', 'qrCode'));
    }

    // Handle QR code scan
    public function scan($token)
    {
        $volunteer = Volunteer::where('qr_code_token', $token)->first();

        if (!$volunteer) {
            return response('Invalid QR code.', 404);
        }

        if ($volunteer->is_scanned) {
            // Send email to the admin (or handle this as needed)
            Mail::to('admin@example.com')->send(new QrCodeScannedNotification($volunteer));
            
            return response('Access denied. QR code has already been scanned.', 403);
        }

        // Mark the QR code as scanned
        $volunteer->is_scanned = true;
        $volunteer->save();

        return response('Access granted. Welcome!');
    }
}
