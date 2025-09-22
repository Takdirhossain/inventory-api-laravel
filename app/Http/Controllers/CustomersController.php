<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Sales;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class CustomersController extends Controller
{
    public function addCustomer(Request $request)
    {
        try {
            $customer = new Customers;
            $customer->name = $request->name;
            $customer->save();
            return response()->json(['message' => 'Customer added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding expense: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getCustomer(){
        $customer = Customers::all();
        if(sizeof($customer) == 0){
            return response()->json(['error' => 'No data found'], Response::HTTP_NOT_FOUND);
        }
        return $customer;
    }




    public function getCustomerWithSum(Request $request)
    {
        $params  = $request->input('params', []);
        $page    = $params['page']     ?? $request->input('page', 1);
        $perPage = $params['per_page'] ?? $request->input('per_page', 10);
        $name    = $params['search']   ?? $request->input('name', '');
    
        $customers = Customers::with(['sales' => function ($query) {
            $query->orderByDesc('created_at');
        }]);
    
        if ($name) {
            $customers->where('name', 'like', '%' . $name . '%');
        }
    
        // এখানে paginate করলাম
        $customers = $customers->paginate($perPage, ['*'], 'page', $page);
    
        // এখন প্রতিটা কাস্টমারকে map করলাম
        $customersTransformed = $customers->getCollection()->map(function ($customer) {
            $totalBuy = $customer->sales->sum('price');
            $totalPay = $customer->sales->sum('pay');
            $totalDue = $customer->sales->sum('due');
    
            $purchaseCylinders = [
                'twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->twelve_kg),
                'twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->twentyfive_kg),
                'thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->thirtythree_kg),
                'thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->thirtyfive_kg),
                'fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->fourtyfive_kg),
                'others_kg'      => $customer->sales->sum(fn($s) => (int)$s->others_kg),
            ];
    
            $emptyCylinders = [
                'empty_twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_twelve_kg),
                'empty_twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_twentyfive_kg),
                'empty_thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->empty_thirtythree_kg),
                'empty_thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_thirtyfive_kg),
                'empty_fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_fourtyfive_kg),
                'empty_others_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_others_kg),
            ];
    
            return [
                'id'                => $customer->id,
                'name'              => $customer->name,
                'total_purchase'    => $totalBuy,
                'total_paid'        => $totalPay,
                'total_due'         => $totalDue,
                'purchase_cylinders'=> $purchaseCylinders,
                'empty_cylinders'   => $emptyCylinders,
            ];
        });
    
        // Pagination এর collection override করে দিলাম
        $customers->setCollection($customersTransformed);
    
        // Grand totals সব পেইজ মিলিয়ে বের করতে চাইলে আলাদা query চালাতে হবে
        $allCustomers = Customers::with('sales');
        if ($name) {
            $allCustomers->where('name', 'like', '%' . $name . '%');
        }
        $allData = $allCustomers->get();
    
        $customersWithSumAll = $allData->map(function ($customer) {
            return [
                'total_purchase' => $customer->sales->sum('price'),
                'total_paid'     => $customer->sales->sum('pay'),
                'total_due'      => $customer->sales->sum('due'),
                'purchase_cylinders' => [
                    'twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->twelve_kg),
                    'twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->twentyfive_kg),
                    'thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->thirtythree_kg),
                    'thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->thirtyfive_kg),
                    'fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->fourtyfive_kg),
                    'others_kg'      => $customer->sales->sum(fn($s) => (int)$s->others_kg),
                ],
                'empty_cylinders' => [
                    'empty_twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_twelve_kg),
                    'empty_twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_twentyfive_kg),
                    'empty_thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->empty_thirtythree_kg),
                    'empty_thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_thirtyfive_kg),
                    'empty_fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_fourtyfive_kg),
                    'empty_others_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_others_kg),
                ],
            ];
        });
    
        $grandTotals = [
            'purchase' => $customersWithSumAll->sum('total_purchase'),
            'paid'     => $customersWithSumAll->sum('total_paid'),
            'due'      => $customersWithSumAll->sum('total_due'),
            'purchase_cylinders' => [
                'twelve_kg'      => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['twelve_kg']),
                'twentyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['twentyfive_kg']),
                'thirtythree_kg' => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['thirtythree_kg']),
                'thirtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['thirtyfive_kg']),
                'fourtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['fourtyfive_kg']),
                'others_kg'      => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['others_kg']),
            ],
            'empty_cylinders' => [
                'empty_twelve_kg'      => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_twelve_kg']),
                'empty_twentyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_twentyfive_kg']),
                'empty_thirtythree_kg' => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_thirtythree_kg']),
                'empty_thirtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_thirtyfive_kg']),
                'empty_fourtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_fourtyfive_kg']),
                'empty_others_kg'      => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_others_kg']),
            ],
        ];
    
        return response()->json([
            'status' => 'success',
            'data'   => [
                'customers'    => $customers,   // এখানে pagination data (links, meta সহ)
                'grand_totals' => $grandTotals, // সব কাস্টমারের টোটাল
            ]
        ], 200);
    }



    public function lastCustomers( Request $request){
        $limit = $request->input('limit');
            try {
                if($limit){
                    $recentCustomer = Customers::latest()->take(5)->get();
                    return response()->json($recentCustomer, Response::HTTP_OK);
                }
                $customer = Customers::all();
                return $customer;
            } catch (\Exception $e) {
                \Log::error('Error getting last customer: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }


    }


    public function updateCustomer( Request $request, $id){

        try {
            $customer = Customers::find($id);
            $customer->name = $request->name;
            $customer->save();
            return response()->json(['message' => 'Customer updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error updating expense: '. $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteCustomer($id){
        try{
            $customer = Customers::find($id);
            if (!$customer) {
                return "No data found for the given ID";
            }
            $customer->delete();
            return "Delete success";
        }
        catch(\Exception $e){
            \Log::error("Error deleting:". $e->getMessage());
            return response()->json(['error' => 'Failed to Delete customer'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function allCustomers()
    {
        $customers = Customers::orderBy('name', 'asc')->get();
    
        return response()->json([
            'data' => $customers
        ], Response::HTTP_OK);
    }

    public function details(Request $request, $id)
    {
        try {
            $customer = Customers::find($id);
    
            if (!$customer) {
                return response()->json([
                    'error' => 'No data found for the given ID'
                ], Response::HTTP_NOT_FOUND);
            }
    
            // Date range filter
            $query = Sales::where('customer_id', $id);
    
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate   = Carbon::parse($request->end_date)->endOfDay();
    
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
    
            $sales = $query->limit(20)->get();
    
            if ($sales->isEmpty()) {
                return response()->json([
                    'data' => $customer,
                    'analytics' => [],
                    'message' => 'No sales found for this period'
                ], Response::HTTP_OK);
            }
    
            // Aggregations
            $totalSalesCount = $sales->count();
            $totalQuantity = [
                '12kg'  => $sales->sum('twelve_kg'),
                '25kg'  => $sales->sum('twentyfive_kg'),
                '33kg'  => $sales->sum('thirtythree_kg'),
                '35kg'  => $sales->sum('thirtyfive_kg'),
                '45kg'  => $sales->sum('fourtyfive_kg'),
                'others'=> $sales->sum('others_kg'),
            ];
    
            $emptyReturn = [
                '12kg'  => $sales->sum('empty_twelve_kg'),
                '25kg'  => $sales->sum('empty_twentyfive_kg'),
                '33kg'  => $sales->sum('empty_thirtythree_kg'),
                '35kg'  => $sales->sum('empty_thirtyfive_kg'),
                '45kg'  => $sales->sum('empty_fourtyfive_kg'),
                'others'=> $sales->sum('empty_others_kg'),
            ];
    
            $totalAmount = $sales->sum('price');
            $totalPaid   = $sales->sum('pay');
            $totalDue    = $sales->sum('due');
    
            // Final response
            return response()->json([
                'customer' => $customer,
                'analytics' => [
                    'sales_count'   => $totalSalesCount,
                    'quantities'    => $totalQuantity,
                    'empty_return'  => $emptyReturn,
                    'total_amount'  => $totalAmount,
                    'total_paid'    => $totalPaid,
                    'total_due'     => $totalDue,
                ],
                'sales' => $sales,
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            \Log::error("Error fetching analytics: " . $e->getMessage());
    
            return response()->json([
                'error' => 'Failed to fetch analytics'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function dashboard(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
    
        $dateFilter = '';
        if ($startDate && $endDate) {
            $dateFilter = "WHERE date BETWEEN '{$startDate}' AND '{$endDate}'";
        }
    
        $total = DB::select("
            SELECT 
                SUM(CAST(price AS UNSIGNED)) AS total_sale,
                SUM(CAST(pay AS UNSIGNED)) AS total_paid,
                SUM(CAST(due AS UNSIGNED)) AS total_due
            FROM sales
            {$dateFilter}
        ");
    
        $stock = DB::select("
            SELECT 
                SUM(CAST(twelve_kg AS UNSIGNED)) AS stock_12kg,
                SUM(CAST(twentyfive_kg AS UNSIGNED)) AS stock_25kg,
                SUM(CAST(thirtythree_kg AS UNSIGNED)) AS stock_33kg,
                SUM(CAST(thirtyfive_kg AS UNSIGNED)) AS stock_35kg,
                SUM(CAST(fourtyfive_kg AS UNSIGNED)) AS stock_45kg,
                SUM(CAST(others_kg AS UNSIGNED)) AS stock_others
            FROM sales
            {$dateFilter}
        ");
    
        $empty = DB::table('products')
            ->selectRaw("
                SUM(CAST(empty_twelve_kg AS UNSIGNED)) AS empty_12kg,
                SUM(CAST(empty_twentyfive_kg AS UNSIGNED)) AS empty_25kg,
                SUM(CAST(empty_thirtythree_kg AS UNSIGNED)) AS empty_33kg,
                SUM(CAST(empty_thirtyfive_kg AS UNSIGNED)) AS empty_35kg,
                SUM(CAST(empty_fourtyfive_kg AS UNSIGNED)) AS empty_45kg,
                SUM(CAST(empty_others_kg AS UNSIGNED)) AS empty_others
            ")
            ->where('is_package', 0)
            ->first();
    
        $saleRatio = DB::select("
            SELECT 
                SUM(CAST(twelve_kg AS UNSIGNED)) AS sale_12kg,
                SUM(CAST(twentyfive_kg AS UNSIGNED)) AS sale_25kg,
                SUM(CAST(thirtythree_kg AS UNSIGNED)) AS sale_33kg,
                SUM(CAST(thirtyfive_kg AS UNSIGNED)) AS sale_35kg,
                SUM(CAST(fourtyfive_kg AS UNSIGNED)) AS sale_45kg,
                SUM(CAST(others_kg AS UNSIGNED)) AS sale_others
            FROM sales
            {$dateFilter}
        ");
    
        $ratio = (array)$saleRatio[0];
        arsort($ratio);
        $mostSoldCylinder = key($ratio);

        $recentActivity = Sales::limit(10)->orderBy('id', 'desc')->get();
    
        $topCustomers = DB::select("
            SELECT 
                customer_id, 
                customer_name, 
                SUM(CAST(price AS UNSIGNED)) AS total_bought
            FROM sales
            {$dateFilter}
            GROUP BY customer_id, customer_name
            ORDER BY total_bought DESC
            LIMIT 10
        ");
    
        return response()->json([
            'total_sale' => $total[0]->total_sale ?? 0,
            'total_paid' => $total[0]->total_paid ?? 0,
            'total_due' => $total[0]->total_due ?? 0,
            'current_stock' => $stock[0],
            'empty_cylinders' => $empty,
            'sale_ratio' => $saleRatio[0],
            'most_sold_cylinder' => $mostSoldCylinder,
            'top_customers' => $topCustomers,
            'recent_activity' => $recentActivity
        ]);
    }
   
public function inactiveCustomers(Request $request)
{
    // parse date range (default last 3 months)
    $start = $request->input('start_date');
    $end   = $request->input('end_date');

    if (! $start || ! $end) {
        $end   = now();
        $start = now()->subMonths(3);
    } else {
        $start = Carbon::parse($start);
        $end   = Carbon::parse($end);
    }

    $perPage = (int) $request->input('per_page', 15);

    // === Option 1: MySQL 8+ (recommended if available) ===
    // Uses REGEXP_REPLACE to remove any non-numeric except dot (safer for currency symbols)
    $totalPriceSub = function ($query) {
        $query->from('sales')
            ->selectRaw(
                "COALESCE(SUM(CAST(REGEXP_REPLACE(price, '[^0-9.]', '') AS DECIMAL(15,2))), 0)"
            )
            ->whereColumn('sales.customer_id', 'customers.id');
    };

    $totalPaySub = function ($query) {
        $query->from('sales')
            ->selectRaw(
                "COALESCE(SUM(CAST(REGEXP_REPLACE(pay, '[^0-9.]', '') AS DECIMAL(15,2))), 0)"
            )
            ->whereColumn('sales.customer_id', 'customers.id');
    };


    $customers = Customers::select('customers.*')
        ->selectSub($totalPriceSub, 'total_price')
        ->selectSub($totalPaySub, 'total_pay')
        ->whereDoesntHave('sales', function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
        })
        ->paginate($perPage);

    $customers->getCollection()->transform(function ($c) {
        $c->total_price = (float) $c->total_price;
        $c->total_pay   = (float) $c->total_pay;
        $c->due         = round($c->total_price - $c->total_pay, 2);
        return $c;
    });

    return response()->json([
        'status' => true,
        'data' => $customers,
        'date_range' => [
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
        ],
    ]);
}

}
