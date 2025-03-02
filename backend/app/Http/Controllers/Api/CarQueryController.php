<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CarQueryController extends Controller
{
    private $baseUrl = 'https://www.carqueryapi.com/api/0.3/';

    public function getMakes()
    {
        try {
            $response = Http::get($this->baseUrl, [
                'cmd' => 'getMakes'
            ]);
            
            // Extract JSON from JSONP response
            $jsonString = $this->extractJsonFromJsonp($response->body());
            $data = json_decode($jsonString);
            
            if (!$data || !isset($data->Makes)) {
                return response()->json(['error' => 'Invalid response format from car API'], 500);
            }
            
            return response()->json(['Makes' => $data->Makes]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching car makes: ' . $e->getMessage()], 500);
        }
    }

    public function getModels(Request $request)
    {
        try {
            $make = $request->query('make');
            
            if (!$make) {
                return response()->json(['error' => 'Make parameter is required'], 400);
            }

            $response = Http::get($this->baseUrl, [
                'cmd' => 'getModels',
                'make' => $make
            ]);
            
            // Extract JSON from JSONP response
            $jsonString = $this->extractJsonFromJsonp($response->body());
            $data = json_decode($jsonString);
            
            if (!$data || !isset($data->Models)) {
                return response()->json(['error' => 'Invalid response format from car API'], 500);
            }
            
            return response()->json(['Models' => $data->Models]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching car models: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Extract JSON data from JSONP response
     * 
     * @param string $jsonp
     * @return string
     */
    private function extractJsonFromJsonp($jsonp)
    {
        // Remove any callback wrapper and extract just the JSON data
        if (preg_match('/[^(]*\((.*)\)$/s', $jsonp, $matches)) {
            return $matches[1];
        }
        
        // If no callback pattern found, return as is
        return $jsonp;
    }
}