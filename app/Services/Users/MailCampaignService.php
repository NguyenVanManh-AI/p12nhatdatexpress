<?php

namespace App\Services\Users;

use App\Enums\User\CampaignStatus;
use App\Jobs\User\SendCampaignDetailMail;
use App\Models\User;
use App\Models\User\CustomerParam;
use App\Models\UserMailCampaign;
use Carbon\Carbon;

class MailCampaignService
{
    /**
     * Get all campaigns for a user
     * @param User $user
     * @param array $queries = []
     * 
     * return $campaigns
     */
    public function index(User $user, array $queries = [])
    {
        $page = (int) data_get($queries, 'page') ?: 1;
        $itemsPerPage = (int) data_get($queries, 'items') ?: 10;

        $campaigns = $user->mailCampaigns()
            ->with('details')
            ->latest()
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $campaigns;
    }

    /**
     * Get all campaign details
     * @param UserMailCampaign $campaign
     * @param array $queries = []
     * 
     * return $details
     */
    public function getDetails(UserMailCampaign $campaign, array $queries = [])
    {
        $page = (int) data_get($queries, 'page') ?: 1;
        $itemsPerPage = (int) data_get($queries, 'items') ?: 10;

        $details = $campaign->details()
            ->latest('receipt_status')
            ->skip(($page - 1) * $itemsPerPage)
            ->paginate($itemsPerPage);

        return $details;
    }

    /**
     * update campaign
     * @param User $user
     * @param array $data
     *
     * @return UserMailCampaign $campaign
     */
    public function create(User $user, array $data)
    {
        $campaignData = [
            'user_id' => $user->id,
            'campaign_name' => data_get($data, 'campaign_name'),
            'mail_template_id' => data_get($data, 'mail_template_id'),
            'start_date' => data_get($data, 'is_birthday') ? null : data_get($data, 'start_date'),
            'is_birthday' => data_get($data, 'is_birthday') ? true : false,
            'status' => CampaignStatus::PENDING,
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => $user->id,
            'updated_by' => $user->id
        ];

        if (count(data_get($data, 'customers', []))) {
            $customerIds = data_get($data, 'customers');
        } else {
            $customerIds = [];
            $campaignData['cus_status'] = data_get($data, 'cus_status');
            $campaignData['cus_source'] = data_get($data, 'cus_source');
            $campaignData['cus_job'] = data_get($data, 'cus_job');
            $campaignData['province_id'] = data_get($data, 'province_id');
            $campaignData['date_from'] = data_get($data, 'date_from');
            $campaignData['date_to'] = data_get($data, 'date_to');
        }

        $campaign = UserMailCampaign::create($campaignData);
        $this->syncCustomers($campaign, $customerIds);
        $this->createDetails($campaign);
        $this->checkShouldSendNow($campaign);

        return $campaign;
    }

    /**
     * update campaign
     * @param User $user
     * @param UserMailCampaign $campaign
     * @param array $data
     *
     * @return UserMailCampaign $campaign
     */
    public function update(User $user, UserMailCampaign $campaign, array $data)
    {
        $campaignData = [
            'campaign_name' => data_get($data, 'campaign_name'),
            'mail_template_id' => data_get($data, 'mail_template_id'),
            'start_date' => data_get($data, 'is_birthday') ? null : data_get($data, 'start_date'),
            'is_birthday' => data_get($data, 'is_birthday') ? true : false,
            'customer' => null,
            'cus_status' => null,
            'cus_source' => null,
            'cus_job' => null,
            'province_id' => null,
            'date_from' => null,
            'date_to' => null,
            'updated_at' => time(),
            'updated_by' => $user->id
        ];

        if (count(data_get($data, 'customers', []))) {
            $customerIds = data_get($data, 'customers');
        } else {
            $customerIds = [];
            $campaignData['cus_status'] = data_get($data, 'cus_status');
            $campaignData['cus_source'] = data_get($data, 'cus_source');
            $campaignData['cus_job'] = data_get($data, 'cus_job');
            $campaignData['province_id'] = data_get($data, 'province_id');
            $campaignData['date_from'] = data_get($data, 'date_from');
            $campaignData['date_to'] = data_get($data, 'date_to');
        }

        $campaign->update($campaignData);
        $this->syncCustomers($campaign, $customerIds);
        $this->createDetails($campaign);
        $this->checkShouldSendNow($campaign);

        return $campaign;
    }

    /**
     * Get campaign form param
     * @param User $user
     *
     * @return array $params
     */
    public function getFormParams(User $user)
    {
        $customerParams = CustomerParam::select('id', 'param_name', 'param_type')->get();
        $mailTemplates = $user->mailTemplates()
                    ->select('id', 'mail_header')
                    ->get();
        $customers = $user->customers()
                ->select('id', 'fullname', 'email')
                ->where('is_deleted', false)
                ->get()
                ->transform(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'label' => "[$customer->fullname] $customer->email",
                    ];
                });

        $params = [
            'provinces' => get_cache_province(),
            'sources' => $customerParams->where('param_type', 'CF'),
            'statuses' => $customerParams->where('param_type', 'CS'),
            'jobs' => $customerParams->where('param_type', 'JB'),
            'mail_templates' => $mailTemplates,
            'customers' => $customers
        ];

        return $params;
    }

    /**
     * Sync customers
     * @param UserMailCampaign $campaign
     * @param array $customerIds
     *
     * @return void
     */
    public function syncCustomers($campaign, $customerIds): void
    {
        $campaign->customers()->sync($customerIds);
    }

    /**
     * Create campaign details
     * @param UserMailCampaign $campaign
     *
     * @return void
     */
    public function createDetails(UserMailCampaign $campaign): void
    {
        // maybe check changed param or check diff customers
        // clear all details for now
        $campaign->details()->forceDelete();

        $user = $campaign->user;

        if (!$user || !$user->mailConfigs->count()) return;

        $mailConfigs = $user->mailConfigs()
            ->get();

        $customers = $this->getCustomers($campaign);

        foreach ($customers as $i => $customer) {
            $configIndex = $i % $mailConfigs->count();
            $config = $mailConfigs[$configIndex];

            $campaign->details()->create([
                'user_mail_config_id' => $config->id,
                'cus_id' => $customer->id,
                'cus_mail' => $customer->email, // if do not need should remove
                'receipt_status' => $customer->is_disabled_notification ? 3 : 0, // Đã hủy đăng ký || Chờ gửi
            ]);
        }
    }

    /**
     * Send mail immediately
     * @param UserMailCampaign $campaign
     * 
     * @return void
     */
    public function checkShouldSendNow(UserMailCampaign $campaign)
    {
        if (!$campaign->isSendNow() || $campaign->status != CampaignStatus::PENDING || !$campaign->details->count()) return;

        $campaign->update([
            'status' => CampaignStatus::SENDED
        ]);

        foreach ($campaign->details()->get() as $detail) {
            SendCampaignDetailMail::dispatch($detail->id)->delay(30);
        }
    }

    /**
     * Get customers
     * @param UserMailCampaign $campaign
     *
     * @return array|Illuminate\Support\Collection $customers
     */
    public function getCustomers(UserMailCampaign $campaign)
    {
        if (count($campaign->customers)) {
            // send to specifically customers
            $customers = $campaign->customers;
        } else {
            if (!$campaign->user) return [];

            $jobId = $campaign->cus_job;
            $sourceId = $campaign->cus_source;
            $statusId = $campaign->cus_status;
            $provinceId = $campaign->province_id;
            $fromDate = $campaign->date_from ? Carbon::parse($campaign->date_from)->startOfDay()->timestamp : null;
            $toDate = $campaign->date_to ? Carbon::parse($campaign->date_to)->endOfDay()->timestamp : null;

            $customers = $campaign->user
                ->customers()
                ->select('customer.*')
                ->when($jobId, function ($query, $jobId) {
                    return $query->where('customer.job', $jobId);
                })
                ->when($sourceId, function ($query, $sourceId) {
                    return $query->where('customer.cus_source', $sourceId);
                })
                ->when($statusId, function ($query, $statusId) {
                    return $query->where('customer.cus_status', $statusId);
                })
                ->when($fromDate, function ($query, $fromDate) {
                    return $query->where('customer.created_at', '>=', $fromDate);
                })
                ->when($toDate, function ($query, $toDate) {
                    return $query->where('customer.created_at', '<=', $toDate);
                })
                ->when($provinceId, function ($query, $provinceId) {
                    return $query->leftJoin('customer_location', 'customer_location.cus_id', '=', 'customer.id')
                        ->where('customer_location.province_id', $provinceId);
                })
                ->get();
        }

        return $customers;
    }
}
