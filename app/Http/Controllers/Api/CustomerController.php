<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerDeleteRequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *    title="Manage Customer API",
 *    version="1.0.0",
 * ),
 */
class CustomerController extends Controller
{
    /**
     * @OA\Post(
     *  path="/api/v1/customer-store",
     *  description="Create a new customer",
     *  tags={"Customer"},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="name",nullable="false", type="string", example="Jose"),
     *               @OA\Property(property="email", nullable="false", type="email", example="jose@gmail.com"),
     *               @OA\Property(property="phone", nullable="false", type="string", example="999999999"),
     *               @OA\Property(property="date_of_birth", nullable="false",type="date", example="1999-01-01")
     *           ),
     *      ),
     *       @OA\Response(
     *           response=200,
     *           description="OK",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Customer registered successfully"),
     *               @OA\Property(property="customerId", type="integer", example="123"),
     *           ),
     *       ),
     *       @OA\Response(
     *              response=422,
     *              description="Unprocessable Entity",
     *              @OA\JsonContent(
     *                  type="object",
     *                  @OA\Property(property="message", type="string", example="The email field must be a valid email address."),
     *                  @OA\Property(
     *                      property="errors",
     *                      type="object",
     *                      @OA\Property(
     *                        property="email",
     *                        type="array",
     *                        @OA\Items(
     *                          example="The email field must be a valid email address.",
     *                        ),
     *                      ),
     *                  ),
     *              ),
     *       ),
     *       @OA\Response(
     *            response=500,
     *            description="Internal Server Error",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Failed to register the customer"),
     *                @OA\Property(property="customerId", type="integer", example="null"),
     *            ),
     *        ),
     * )
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
     * @OA\Post(
     *  path="/api/v1/customer-update",
     *  description="Update a customer",
     *  tags={"Customer"},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="id",nullable="false", type="integer", example="10"),
     *               @OA\Property(property="name",nullable="false", type="string", example="Jose"),
     *               @OA\Property(property="email", nullable="false", type="email", example="jose@gmail.com"),
     *               @OA\Property(property="phone", nullable="false", type="string", example="999999999"),
     *               @OA\Property(property="date_of_birth", nullable="false",type="date", example="1999-01-01")
     *           ),
     *      ),
     *     @OA\Response(
     *           response=200,
     *           description="OK",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Customer registered successfully"),
     *               @OA\Property(
     *                   property="fields",
     *                   type="object",
     *                   @OA\Property(property="name", type="string", example="Jose"),
     *                   @OA\Property(property="email", type="email", example="jose@gmail.com"),
     *                   @OA\Property(property="phone", type="string", example="999999999"),
     *                   @OA\Property(property="date_of_birth", type="date", example="1990-01-01"),
     *                ),
     *           ),
     *       ),
     *       @OA\Response(
     *             response=422,
     *             description="Unprocessable Entity",
     *             @OA\JsonContent(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="The selected id is invalid."),
     *                 @OA\Property(
     *                     property="errors",
     *                     type="object",
     *                     @OA\Property(
     *                       property="id",
     *                       type="array",
     *                       @OA\Items(
     *                         example="The selected id is invalid.",
     *                       ),
     *                     ),
     *                 ),
     *             ),
     *         ),
     *          @OA\Response(
     *            response=500,
     *            description="Internal Server Error",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Failed to update the customer"),
     *                @OA\Property(property="fields", type="object", example="[]"),
     *
     *            ),
     *        ),
     * )
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
     * @OA\Post(
     *  path="/api/v1/customer-delete",
     *  description="Delete a customer",
     *  tags={"Customer"},
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="id",nullable="false", type="integer", example="123")
     *           ),
     *      ),
     *     @OA\Response(
     *           response=200,
     *           description="OK",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Customer deleted successfully"),
     *               @OA\Property(property="wasDeleted", type="boolean", example="true"),
     *           ),
     *       ),
     *          @OA\Response(
     *            response=422,
     *            description="Unprocessable Entity",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="The selected id is invalid."),
     *                @OA\Property(
     *                    property="errors",
     *                    type="object",
     *                    @OA\Property(
     *                      property="id",
     *                      type="array",
     *                      @OA\Items(
     *                        example="The selected id is invalid.",
     *                      ),
     *                    ),
     *                ),
     *            ),
     *        ),
     *          @OA\Response(
     *            response=500,
     *            description="Internal Server Error",
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="message", type="string", example="Failed to delete the customer"),
     *                @OA\Property(property="wasDeleted", type="boolean", example="false"),
     *            ),
     *        ),
     * )
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
