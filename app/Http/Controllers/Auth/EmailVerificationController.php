<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\EmailVerificationMail;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        // Generate a verification token
        $verificationToken = Hash::make($user->email . now());

        // Save token (for demo purposes, saving it to the user record)
         $user->remember_token = $verificationToken;
         $user->save();

        // Send email
        // Mail::send('emails.verify-email', ['token' => $verificationToken, 'user' => $user], function ($message) use ($user) {
        //     $message->to($user->email)->subject('Email Verification');
        // });

        Mail::to($user->email)->queue(new EmailVerificationMail($verificationToken, $user));

        return response()->json(['message' => 'Verification email sent.'], 200);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
        ]);
       // return $validator->validated()->email;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
       // return  $request->token == $user->remember_token?response()->json(['message' => 'Both equal'], 400):"";

        if (!$user || $request->token !== trim($user->remember_token)) {
            return response()->json(['message' => 'Invalid token or email.'], 400);
        }

        $user->email_verified_at = Carbon::now();
        $user->remember_token = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }
}
