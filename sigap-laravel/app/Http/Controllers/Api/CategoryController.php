<?php

   namespace App\Http\Controllers\Api;

   use App\Http\Controllers\Controller;
    use App\Models\ReportCategory;

   class CategoryController extends Controller
   {
       public function index()
       {
        $categories = ReportCategory::all();

           return response()->json([
               'data' => $categories,
           ]);
       }
   }
