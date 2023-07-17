<?php

namespace App\Http\Controllers\User\Mail;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Mail\AddCampaignRequest;
use App\Http\Requests\User\Mail\EditCampaignRequest;
use App\Models\AdminConfig;
use App\Models\User\Customer;
use App\Models\UserMailCampaign;
use App\Services\Users\MailCampaignService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    private MailCampaignService $campaignService;
    public $campaignGuide;

    public function __construct()
    {
        $this->campaignService = new MailCampaignService;
        $this->campaignGuide = AdminConfig::firstWhere('config_code', 'N010');
    }

    public function index()
    {
        $user = Auth::guard('user')->user();

        $campaigns = $this->campaignService->index($user, request()->all());

        return view('user.mail.campaigns.index', [
            'campaigns' => $campaigns
        ]);
    }

    public function create()
    {
        $user = Auth::guard('user')->user();

        if (!$user->mailConfigs->count()) {
            Toastr::error('Hãy thêm cấu hình mail');
            return redirect(route('user.config-mail'));
        };

        $params = $this->campaignService->getFormParams($user);
        $params['guide'] = $this->campaignGuide;
        $params['campaign'] = new UserMailCampaign([
            'mail_template_id' => request()->user_mail_template_id
        ]);

        return view('user.mail.campaigns.add', $params);
    }

    public function store(AddCampaignRequest $request)
    {
        $user = Auth::guard('user')->user();

        if (!$user->mailConfigs->count()) {
            Toastr::error('Hãy thêm cấu hình mail');
            return redirect(route('user.config-mail'));
        };

        $this->campaignService->create($user, $request->all());

        Toastr::success('Tạo chiến dịch thành công');

        return redirect(route('user.campaigns.index'));
    }

    public function edit($id)
    {
        $user = Auth::guard('user')->user();
        $campaign = $user->mailCampaigns()
            ->findOrFail($id);

        if (!$campaign->canEdit()) return redirect(route('user.campaigns.index'));

        $params = $this->campaignService->getFormParams($user);
        $params['guide'] = $this->campaignGuide;
        $params['campaign'] = $campaign;

        return view('user.mail.campaigns.edit', $params);
    }

    public function update($id, EditCampaignRequest $request)
    {
        $user = Auth::guard('user')->user();
        $campaign = $user->mailCampaigns()
            ->findOrFail($id);

        $this->campaignService->update($user, $campaign, $request->all());

        Toastr::success('Cập nhật chiến dịch thành công');

        return $campaign->canEdit()
            ? back()
            : redirect(route('user.campaigns.index'));
    }

    public function destroy($id)
    {
        $user = Auth::guard('user')->user();
        $campaign = $user->mailCampaigns()
            ->findOrFail($id);

        $campaign->update([
            'is_deleted' => true,
            'updated_by' => $user->id,
            'updated_at' => time()
        ]);

        Toastr::success('Xóa chiến dịch thành công');

        return back();
    }

    public function viewDetails($id)
    {
        $user = Auth::guard('user')->user();
        $campaign = $user->mailCampaigns()
            ->findOrFail($id);
        
        $details = $this->campaignService->getDetails($campaign, request()->all());

        return view('user.mail.campaigns.detail', [
            'details' => $details
        ]);
    }

    public function unsubscribe($token)
    {
        $customer = Customer::firstWhere('disabled_notification_token', $token);

        if($customer) {
            $customer->update([
                'is_disabled_notification' => true,
                'disabled_notification_token' => null
            ]);
        }

        Toastr::success('Hủy đăng ký nhận email thành công.');
        return redirect(route('home.index'));
    }
}
