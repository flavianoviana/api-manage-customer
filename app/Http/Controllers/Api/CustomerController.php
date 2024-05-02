<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerDeleteRequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @param CustomerStoreRequest $request
     * @return JsonResponse
     */
    public function store(CustomerStoreRequest $request): JsonResponse
    {
        try {
            $data = [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'date_of_birth' => $request->get('date_of_birth'),
            ];

            $newCustomer = Customer::createCustomer($data);

            $response = [
                'message' => 'Customer registered successfully',
                'customerId' => $newCustomer->id
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {

            $response = [
                'message' => 'Failed to register the customer',
                'customerId' => null
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CustomerUpdateRequest $request
     * @return JsonResponse
     */
    public function update(CustomerUpdateRequest $request): JsonResponse
    {
        try {
            $data = [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'date_of_birth' => $request->get('date_of_birth'),
            ];

            $customerId = $request->get('id');

            $wasUpdated = Customer::updateCustomer($data, $customerId);

            $customer = $wasUpdated
                ? Customer::find($customerId)
                : null;

            $response = [
                'message' => 'Customer updated successfully',
                'fields' => [
                    'name' => $customer?->name,
                    'email' => $customer?->email,
                    'phone' => $customer?->phone,
                    'date_of_birth' => $customer?->date_of_birth,
                ]
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {

            $response = [
                'message' => 'Failed to update the customer',
                'fields' => []
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CustomerDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(CustomerDeleteRequest $request): JsonResponse
    {
        try {
            $customerId = $request->get('id');

            $wasDeleted = Customer::deleteCustomer($customerId);

            $responseMessage = $wasDeleted
                ? 'Customer deleted successfully'
                : 'Failed to delete the customer';

            $response = [
                'message' => $responseMessage,
                'wasDeleted' => $wasDeleted
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {

            $response = [
                'message' => 'Failed to delete the customer',
                'wasDeleted' => false
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
