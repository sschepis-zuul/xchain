<?php

namespace App\Repositories;

use App\Models\MonitoredAddress;
use \Exception;

/*
* MonitoredAddressRepository
*/
class MonitoredAddressRepository
{

    public function create($attributes) {
        if (!isset($attributes['active'])) { $attributes['active'] = true; }
        return MonitoredAddress::create($attributes);
    }

    public function findByAddress($address) {
        return MonitoredAddress::where('address', $address);
    }

    public function findByAddresses($addresses) {
        return MonitoredAddress::whereIn('address', $addresses);
    }

}