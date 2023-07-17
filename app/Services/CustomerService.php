<?php

namespace App\Services;

use App\Helpers\SystemConfig;
use App\Models\User;
use App\Models\User\Customer;
use Illuminate\Support\Facades\File;

class CustomerService
{
    /**
     * get customer from id
     * @param User $user
     * @param string $id
     * 
     * @return Customer $customer
     */
    public function find(User $user, $id)
    {
        $customer = $user->customers()
            ->find($id);

        return $customer;
    }

    /**
     * create customer | for send advise now
     * @param User $user
     * @param array $data
     * 
     * @return Customer $customer
     */
    public function create(User $user, array $data)
    {
        $user->customers()->create([
            'fullname' => data_get($data, 'fullname'),
            'phone_number' => data_get($data, 'phone_number'),
            'email' => data_get($data, 'email'),
            'note' => data_get($data, 'note'),
            'classified_id' => data_get($data, 'classified_id'),
            'created_at' => time()
        ]);
    }

    /**
     * Update customer
     * @param Customer $customer
     * @param array $data
     * 
     * @return Customer $customer
     */
    public function updateCustomer(Customer $customer, array $data)
    {
        $customer->update([
            'fullname' => data_get($data, 'fullname'),
            'phone_number' => data_get($data, 'phone_number'),
            'email' => data_get($data, 'email'),
            'birthday' => strtotime(data_get($data, 'birthday')),
            'job' => data_get($data, 'job'),
            'cus_status' => data_get($data, 'status'),
            'cus_source' => data_get($data, 'source'),
            'note' => data_get($data, 'note'),
            'updated_at' => time()
        ]);

        $avatar = data_get($data, 'avatar');
        $this->updateAvatar($customer, $avatar);

        $this->createOrUpdateLocation($customer, $data);
    }

    /**
     * Update customer avatar
     * @param Customer $customer
     * @param $avatar
     * 
     * @return Customer $customer
     */
    public function updateAvatar(Customer $customer, $avatar)
    {
        if ($avatar && !isBase64($avatar)) return;

        if (File::exists(public_path($customer->image_url)))
            File::delete(public_path($customer->image_url));

        $imagePath = isBase64($avatar)
            ? base64ToFile($avatar, SystemConfig::userDirectory())
            : null;

        $customer->update([
            'image_url' => $imagePath,
        ]);
    }

    /**
     * create/update location
     * @param Customer $customer
     * @param array $data
     *
     * @return EventLocation $location
     */
    public function createOrUpdateLocation(Customer $customer, array $data)
    {
        $location = $customer->location()
            ->updateOrCreate([], [
                'address' => data_get($data, 'address'),
                'province_id' => data_get($data, 'province'),
                'district_id' => data_get($data, 'district'),
                'ward_id' => data_get($data, 'ward_id'),
                'map_longtitude' => data_get($data, 'map_longtitude'),
                'map_latitude' => data_get($data, 'map_latitude'),
            ]);

        return $location;
    }

    /**
     * check limit customers added per day
     * @param User $user
     * 
     * @return array
     */
    public function checkLimitCustomers(User $user)
    {
        $limitCustomers = config('constants.user.limit_add_customers_per_day', 5);
        $startTime = now()->startOfDay()->timestamp;
        $endTime = now()->endOfDay()->timestamp;
        $dayCustomers = $user->customers()
            ->whereBetween('created_at', [$startTime, $endTime])
            ->count();

        return [
            'success' => $dayCustomers < $limitCustomers,
            'message' => "Chỉ được thêm tối đa $limitCustomers khách hàng / ngày."
        ];
    }
}
