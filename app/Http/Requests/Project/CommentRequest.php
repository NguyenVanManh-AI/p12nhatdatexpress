<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\BaseRequest;
use App\Models\ProjectComment;
use App\Rules\CheckBlockedKeyWord;
use App\Rules\CheckBlockedUser;
use App\Rules\CheckUserLevel;
use Illuminate\Support\Facades\Auth;

class CommentRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // except / #@ <> ; = |
        $pattern = '/^[a-zA-Z!$%^&*()?\-_\[\]\',.{}\":~+ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s+]+$/';

        return [
            'content' => [
                new CheckUserLevel(2, 'bình luận'),
                new CheckBlockedUser('bình luận'),
                'required',
                'max:200',
                'regex:' . $pattern,
                new CheckBlockedKeyWord(),
                function ($attribute, $value, $fail) {
                    $user = Auth::guard('user')->user();

                    // người đăng có thể bình luận không giới hạn cho dự án của mình
                    if ($user->id == $this->project->created_by) return;

                    $duplicate = ProjectComment::where('user_id', $user->id)
                        ->showed()
                        ->firstWhere('project_id', $this->project->id);

                    if ($duplicate) return $fail('Mỗi tài khoản chỉ được đăng 1 bình luận trên 1 bài đăng');

                    $maxCommentsPerDay = config('constants.classified.comment.limit_per_day', 5);
                    $dayComments = ProjectComment::select('project_comment.*')
                        ->leftJoin('project', 'project.id', '=', 'project_comment.project_id')
                        ->where('project_comment.user_id', $user->id)
                        ->where('project.created_by', '!=', $user->id)
                        ->whereBetWeen('project_comment.created_at', [now()->startOfDay()->timestamp, now()->endOfDay()->timestamp])
                        ->count();
                    if ($dayComments >= $maxCommentsPerDay) return $fail("Mỗi tài khoản chỉ được đăng {$maxCommentsPerDay} bình luận trên ngày");
                },
            ]
        ];
    }

    public function messages()
    {
        $messages = config('constants.validate_message', []);

        $messages['regex'] = ':attribute chỉ bao gồm chữ';

        return $messages;
    }
}
