<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Illuminate\Support\Facades\Validator;
use League\Csv\Statement;

class ImportController extends Controller
{ 
    /**
     * Import orders from a CSV file
     */
    public function orders(Request $request)
    { 
        // Validate file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid file upload.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Store the uploaded file temporarily
        $path = $request->file('file')->store('temp');

        // Read the CSV using League\Csv
        $csv = Reader::createFromPath(storage_path("app/{$path}"), 'r');
        $csv->setHeaderOffset(0); // use first row as header

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $orderId = $record['order_id'];
            $date = $record['date'];
            $time = $record['time'];

            // Combine date and time into one timestamp
            $orderDate = "{$date} {$time}";

            // Insert into database
            DB::table('orders')->updateOrInsert(
                ['order_id' => $orderId],
                ['order_date' => $orderDate, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return response()->json(['message' => 'Orders imported successfully.']);
    }

    /**
     * Import order items (order_details) from CSV
     */
    public function orderDetails(Request $request)
    {
        // Validate file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid file upload.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->file('file')->store('temp');
        $csv = Reader::createFromPath(storage_path("app/{$path}"), 'r');
        $csv->setHeaderOffset(0); // first row is header

        $records = $csv->getRecords();

        foreach ($records as $record) {
            DB::table('order_items')->updateOrInsert(
                ['order_details_id' => $record['order_details_id']],
                [
                    'order_id' => $record['order_id'],
                    'pizza_id' => $record['pizza_id'],
                    'quantity' => $record['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Order details imported successfully.']);
    }

    /**
     * Import pizzas from uploaded CSV.
     */
    public function pizza(Request $request)
    {
        // Validate file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt',
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid file upload.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->file('file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // first row as header

        $records = Statement::create()->process($csv);

        $errors = [];
        foreach ($records as $index => $record) {
            // Validator rules per row
            $validator = Validator::make($record, [
                'pizza_id' => 'required|string|max:255',
                'pizza_type_id' => 'required|string|max:255',
                'size' => 'required|in:S,M,L,XL,XXL',
                'price' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index + 2, // add 2 to account for 0-based index and header row
                    'errors' => $validator->errors()->all(),
                ];
                continue; // skip this row
            }

            DB::table('pizzas')->updateOrInsert(
                ['pizza_id' => $record['pizza_id']],
                [
                    'pizza_type_id' => $record['pizza_type_id'],
                    'size' => $record['size'],
                    'price' => $record['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Some rows failed validation.',
                'errors' => $errors,
            ], 422);
        }

        return response()->json(['message' => 'Pizzas imported successfully']);
    }


    /**
     * Import Pizza Type from uploaded CSV.
     */
    public function pizzaType(Request $request)
    {
        // Validate file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid file upload.',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        // Load CSV file using League\Csv
        $path = $request->file('file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // Use the first row as the header

        $records = Statement::create()->process($csv);
        $errors = [];

        foreach ($records as $index => $row) {
            // Validate each row
            $validator = Validator::make($row, [
                'pizza_type_id' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'ingredients' => 'required|string',
            ]);

            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index + 2, // +2 for header and 0-indexing
                    'errors' => $validator->errors()->all(),
                ];
                continue;
            }

            // Insert or update row
            DB::table('pizza_types')->updateOrInsert(
                ['pizza_type_id' => $row['pizza_type_id']],
                [
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'ingredients' => $row['ingredients'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Return response
        if (!empty($errors)) {
            return response()->json([
                'message' => 'Some rows failed validation.',
                'errors' => $errors,
            ], 422);
        }

        return response()->json(['message' => 'Pizza types imported successfully']);
    }
}