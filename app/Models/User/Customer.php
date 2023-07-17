<?php

namespace App\Models\User;

use App\Models\Classified\Classified;
use App\Models\CustomerLocation;
use App\Traits\Models\AdminHistoryTrait;
use App\Traits\Models\SoftTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;
    use AdminHistoryTrait;
    use SoftTrashed;

    protected $table = 'customer';
    protected $guarded = [];
    public $timestamps = false;

    protected $filterable = [
        'user_id',
        'fullname',
        'phone_number',
        'email',
        'birthday',
        'job',
        'contact_link',
        'created_date',
        'image_url',
        'is_deleted',
        'cus_status',
        'cus_source',
        'classified_id',
        'is_disabled_notification',
        'disabled_notification_token',
        'created_at',
        'updated_at',
        'note'
    ];

    public static function list(Request $request)
    {
        $user = Auth::guard('user')->user();
        $columns = ['customer.*',
            'clo.*',
            'customer.id as id',
            'pro.province_name',
            'dis.district_name',
            'cpa.param_name as job_name',
            'cpa2.param_name as source',
            'cpa3.param_name as status',
            'cla.classified_name'];
        $customers =  SELF::select($columns)
            ->where('customer.is_deleted', '<>', '1')
            ->where('customer.user_id', $user->id)
            ->leftJoin('customer_location as clo', 'customer.id', 'clo.cus_id')
            ->leftJoin('province as pro', 'clo.province_id', 'pro.id')
            ->leftJoin('district as dis', 'clo.district_id', 'dis.id')
            ->leftJoin('customer_param as cpa', 'customer.job', 'cpa.id')
            ->leftJoin('customer_param as cpa2', 'customer.cus_source', 'cpa2.id')
            ->leftJoin('customer_param as cpa3', 'customer.cus_status', 'cpa3.id')
            ->leftJoin('classified as cla', 'customer.classified_id', 'cla.id')
            ->orderBy('customer.id', 'desc');

        $request->fullname?$customers->where('customer.fullname', $request->fullname):null;
        $request->phone_number?$customers->where('customer.phone_number', $request->phone_number):null;
        $request->province?$customers->where('clo.province_id', $request->province):null;
        $request->district?$customers->where('clo.district_id', $request->district):null;
        $request->source?$customers->where('cpa2.id', $request->source):null;
        $request->status?$customers->where('cpa3.id', $request->status):null;
        $request->job?$customers->where('cpa.id', $request->job):null;
        $request->from_date?$customers->where('customer.created_at','>=', strtotime($request->from_date)):null;
        $request->to_date?$customers->where('customer.created_at','<=', strtotime($request->to_date) + 86399):null;
        return $customers;
    }

    /**
     * Relationships
     */
    public function location(): HasOne
    {
        return $this->hasOne(CustomerLocation::class, 'cus_id');
    }

    public function classified(): BelongsTo
    {
        return $this->belongsTo(Classified::class);
    }

    /**
     * Attributes
     */
    public function getDisabledNotificationTokenAttribute($value)
    {
        $token = $value;

        if (!$value) {
            $token = md5(uniqid($this->email) . date('d-m-Y H:i:s'));
            $this->disabled_notification_token = $token;
            $this->save();
        }

        return $token;
    }
}
