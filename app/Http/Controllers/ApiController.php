<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\Country;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function showeApiKeys()
    {
        $apis = ApiKey::all();
        return view('super-admin.api', compact('apis'));
    }

    public function createApiKey(Request $request)
    {
        $generatedKey = bin2hex(random_bytes(32));

        $apiKey = ApiKey::create([
            'key' => $generatedKey,
            'name' => $request->api_name,
        ]);

        return redirect()->back()->with('success', 'Апи ключ успешно создан!');
    }

    public function deleteApiKey($id)
    {
        $api_key = ApiKey::find($id);

        if ($api_key) {
//            $apiKey = ApiKey::findOrFail($id);
//            $apiKey->logs()->delete();
            $api_key->delete();
            return redirect()->back()->with('success', 'Апи ключ успешно удален!');
        } else {
            return redirect()->back()->with('error', 'Апи ключ не найден!');
        }
    }

    public
    function store(Request $request): \Illuminate\Http\JsonResponse
    {

        try {
            $apiKeyValue = $request->header('X-API-Key');
            $apiKey = ApiKey::where('key', $apiKeyValue)->first();
//  'email' => 'required|email|unique:leads,email',
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:leads,email',
                'phone' => 'required|numeric|unique:leads,phone',
                'status_id' => 'required',
//                'country' => 'required',
            ]);

            \App\Models\Log::logApiActivity('Добавление лида', $apiKey->id);

            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('email')) {
                    if ($apiKey->id == 3) {
                        return response()->json(['message' => 'A lead with such an email exists.'], 409);

                    } else {
                        return response()->json(['message' => $errors], 409);

                    }
                }
                if ($errors->has('phone')) {
                    if ($apiKey->id == 3) {
                        return response()->json(['message' => 'A lead with such a phone number exists.'], 409);
                    } else {
                        return response()->json(['message' => 'Лид с таким телефоном существует.'], 409);
                    }
                }
            }


            if ($request->country_code) {

                $country = Country::where('code', $request->country_code)->first();

                if (!$country) {
                    if ($apiKey->id == 3) {
                        return response()->json(['message' => 'Country not found.'], 404);
                    } else {
                        return response()->json(['message' => 'Страна не найдена.'], 404);
                    }
                }

            } else {
                $country = Country::where('country', $request->country)->first();

                if (!$country) {
                    if ($apiKey->id == 3) {
                        return response()->json(['message' => 'Country not found.'], 404);
                    } else {
                        return response()->json(['message' => 'Страна не найдена.'], 404);
                    }
                }
            }


            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => cleanPhoneNumber($request->phone),
                'country_id' => $country->country_id,
                'Affiliate' => $request->Affiliate,
                'Advert' => $request->Advert,
                'lead_value' => $request->lead_value,
                'status' => $request->status_id,
                'retention_status' => $request->retention_status_id,
                'API' => $apiKey->id,
            ];

            $lead = Lead::create($data);

            if ($request->Comment) {

                $note = nl2br(strip_tags($request->Comment));

                if ($note) {
                    $lead->update([
                        'note' => $note,
                        'note_updated_at' => now()
                    ]);

                    $lead->leadComments()->create([
                        'user_id' => 44,
                        'body' => $note
                    ]);

                    $note = 1;

                    $lead->logChange('comment_added', $note);

                    Log::info('Lead comment created successfully.', ['lead_id' => $lead->id, 'data' => $data]);
                }
            }

            Log::info('Lead created successfully.', ['lead_id' => $lead->id, 'data' => $data]);

            if ($apiKey->id == 3) {
                return response()->json([
                    'message' => "Lead added successfully",
                    'id' => $lead->id
                ], 200);
            } else {
                return response()->json([
                    'message' => "Лид успешно добавлен",
                    'id' => $lead->id
                ], 200);
            }


        } catch (\Exception $e) {
            Log::error('Error creating lead.', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return response()->json(['message' => 'Произошла ошибка при создании лида.'], 500);
        }
    }

    public
    function get(Request $request)
    {
        $fromDate = $request->query('from');
        $toDate = $request->query('to');

        if ($fromDate && $toDate) {

            if ($this->validateDates($fromDate, $toDate)) {
                $fromDate = Carbon::parse($fromDate)->startOfDay();
                $toDate = Carbon::parse($toDate)->endOfDay();
            } else {
                return response()->json(['error' => 'Invalid date format'], 400);
            }
        }
        $apiKeyValue = $request->header('X-API-Key');
        $apiKey = ApiKey::where('key', $apiKeyValue)->first();

        \App\Models\Log::logApiActivity('Просмотр лидов', $apiKey->id);

        $leadsQuery = Lead::with('statusObject')
            ->select('id', 'status', 'lead_value')
            ->where('API', $apiKey->id);

        if ($fromDate && $toDate) {
            $leadsQuery->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $leads = $leadsQuery->get();

        $formattedLeads = $leads->map(function ($lead) {
            return [
                'id' => $lead->id,
                'status_name' => $lead->statusObject->name,
                'FTD' => !is_null($lead->lead_value) && $lead->lead_value !== ''
            ];
        });

        return response()->json($formattedLeads);
    }

// Метод для валидации дат
    private
    function validateDates($from, $to)
    {
        try {
            Carbon::parse($from);
            Carbon::parse($to);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}
