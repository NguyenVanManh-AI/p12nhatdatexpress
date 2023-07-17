<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MailBox;
use Illuminate\Support\Facades\Auth;

class MailBoxController extends Controller
{
    /**
     * User pin/unpin mail
     *
     * @return Response
     */
    public function pin(MailBox $mail)
    {
        $user = Auth::guard('user')->user();
        $this->authorizeForUser($user, 'pin', $mail);

        $mail->update([
            'mailbox_pin' => !$mail->mailbox_pin
        ]);

        return response()->json([
            'success' => true,
            'message' => $mail->mailbox_pin ? 'Ghim thành công' : 'Bỏ ghim thành công',
            'data' => [
                'is_pinned' => $mail->mailbox_pin ? true : false
            ]
        ]);
    }
}
