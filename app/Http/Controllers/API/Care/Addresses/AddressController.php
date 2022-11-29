<?php

namespace App\Http\Controllers\API\Care\Addresses;

use Illuminate\Http\Request;
use App\Http\Requests\API\Care\Addresses\AddressStoreRequest;

use App\Http\Controllers\Controller;

use App\Models\Addresses\Address;
use DB;

class AddressController extends Controller
{

    /**
    * Save an address to DB
    *
    * @param App\Http\Requests\API\Care\Addresses\AddressStoreRequest $request
    * @return Illuminate\Http\Response
    */
    public function store(AddressStoreRequest $request)
    {
        DB::beginTransaction();
            $body = $request->except('default');
            $body['default'] = !Address::first();

            $newAddress = $request->user()->addresses()->create($body);
        DB::commit();

        return response()->json([
            'address' => $newAddress->getUserAddresses(),
            'message' => 'Address successfully added'
        ]);
    }


    /**
    * Update a specific address
    *
    * @param App\Http\Requests\API\Care\Addresses\AddressStoreRequest $request
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function update(AddressStoreRequest $request, $id)
    {
        DB::beginTransaction();
            $address = $request->user()->addresses()->where('id', $id);

            if ($request->default) {
                $request->user()->addresses()
                        ->where('default', true)
                        ->update(['default' => false]);
            }
            
            $address->update($request->all());
        DB::commit();

        return response()->json([
            'message' => "Address successfully updated!",
            'address' => $address->first()->getUserAddresses()
        ]);
    }


    /**
    * Delete a specific address
    *
    * @param Illuminate\Http\Request $request
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
            $request->user()->addresses()
                ->where('id', $id)
                ->delete();
        DB::commit();

        return response()->json(['message' => 'Address successfully deleted']);
    }


    /**
    * Set a specific address as the default address for checking out
    *
    * @param Illuminate\Http\Request $request
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function setAsDefault(Request $request, $id)
    {
        DB::beginTransaction();
            $addresses = $request->user()->addresses;

            foreach ($addresses as $address) {
                $address->update(['default' => false]);
            }

            $address = Address::find($id);
            $address->update(['default' => true]);
        DB::commit();

        return response()->json([
            'message' => 'Address successfully set as default'
        ]);
    }

}
