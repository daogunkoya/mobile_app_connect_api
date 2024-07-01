<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Mail\PasswordUpdated;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;


class PasswordResetController extends Controller
{
    // Send reset password link to user email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent.']);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    // Reset the password
    public function reset(ResetPasswordRequest $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Check if the provided email matches the authenticated user's email
        if ($user->email !== $request->email) {
            return (new ErrorResource('Unauthorized.'))->response()->setStatusCode(401);
        }

        // Check if the old password matches the current password
        if (!Hash::check($request->old_password, $user->password)) {
            return (new ErrorResource('Old password is incorrect.'))->response()->setStatusCode(400);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        Mail::to($user->email)->queue(new PasswordUpdated());

        return new SuccessResource(['message' => 'Password updated successfully.']);
    }
}
