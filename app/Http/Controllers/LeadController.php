<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Desk;
use App\Models\Lead;
use App\Models\LeadComment;
use App\Models\PlatformUser;
use App\Models\RetentionStatus;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    public function showLeads(Request $request)
    {

        $perPage = $request->input('per_page', 10);

        $user = Auth::user();

        $leadsQuery = Lead::with(['user', 'status']);


        if ($user->hasRole('head')) {
            $leadsQuery->whereHas('user', function ($query) use ($user) {
                $query->where('desk_id', $user->desk_id);
            });
        }


        $sortBy = $request->input('sortBy');
        $sortOrder = $request->input('sortOrder');
        if ($sortBy === 'status_sort') {
            $leadsQuery->orderByRaw('CASE WHEN retention_status IS NULL THEN status ELSE NULL END ASC, retention_status ASC');
        }

        if (!$sortOrder) {
            if ($sortBy != 'status_sort') {
                $leadsQuery->orderBy('created_at', 'desc');
            }
        }

        if ($sortBy) {
            switch ($sortBy) {
                case 'value':
                    $leadsQuery->orderBy('lead_value', $sortOrder);
                    break;

                case 'date_added':
                    $leadsQuery->orderBy('created_at', $sortOrder);
                    break;

                case 'date_attached':
                    $leadsQuery->orderBy('user_id_updated_at', $sortOrder);
                    break;

                case 'comment_attached':
                    $leadsQuery->orderBy('note_updated_at', $sortOrder);
                    break;
            }
        }


        if ($request->has('team_id') && $request->input('team_id') != 0) {
            $leadsQuery->whereHas('user', function ($query) use ($request) {
                $query->where('team_id', $request->input('team_id'));
            });
        }

        if ($request->has('desk_id') && $request->input('desk_id') != 0) {
            $leadsQuery->whereHas('user', function ($query) use ($request) {
                $query->where('desk_id', $request->input('desk_id'));
            });
        }

        if ($request->has('sales_id')) {
            $salesIds = $request->input('sales_id');

            // Фильтр для свободных сейлов
            if (in_array('free', (array)$salesIds)) {
                $leadsQuery->orWhereNull('user_id');
            }

            $numericSalesIds = array_filter((array)$salesIds, 'is_numeric');

            if (!empty($numericSalesIds)) {
                $leadsQuery->orWhereIn('user_id', $numericSalesIds);
            }
        }

        if ($request->has('status_prefixed_id')) {
            $statusIds = $request->input('status_prefixed_id');

            $statusFilter = [];
            $retentionFilter = [];

            foreach ((array)$statusIds as $prefixed_id) {
                if (Str::startsWith($prefixed_id, 'status_')) {
                    $statusFilter[] = str_replace('status_', '', $prefixed_id);
                } elseif (Str::startsWith($prefixed_id, 'retention_')) {
                    $retentionFilter[] = str_replace('retention_', '', $prefixed_id);
                }
            }

            if (!empty($statusFilter) || !empty($retentionFilter)) {
                $leadsQuery->where(function ($query) use ($statusFilter, $retentionFilter) {
                    if (!empty($statusFilter)) {
                        $query->orWhere(function ($q) use ($statusFilter) {
                            $q->whereIn('status', $statusFilter)->whereNull('retention_status');
                        });
                    }

                    if (!empty($retentionFilter)) {
                        foreach ($retentionFilter as $retention) {
                            $query->orWhere('retention_status', $retention);
                        }
                    }
                });
            }
        }

        if ($request->has('country_id') && $request->input('country_id') != 0) {
            $leadsQuery->where('country_id', $request->input('country_id'));
        }

        if ($request->has('unique_user_id_updated_at') && !empty($request->input('unique_user_id_updated_at'))) {
            $dateRange = explode(' - ', $request->input('unique_user_id_updated_at'));
            if (count($dateRange) == 2) {
                $startDate = $dateRange[0] . ' 00:00:00';
                $endDate = $dateRange[1] . ' 23:59:59';
                $leadsQuery->whereBetween('user_id_updated_at', [$startDate, $endDate]);
            }
        }

        if ($request->has('dateRange') && !empty($request->input('dateRange'))) {
            $dateRange = explode(' - ', $request->input('dateRange'));
            if (count($dateRange) == 2) {
                $startDate = $dateRange[0] . ' 00:00:00';
                $endDate = $dateRange[1] . ' 23:59:59';
                $leadsQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
        }


        if ($request->has('searchId')) {
            $searchId = $request->input('searchId');
            $leadsQuery->where('id', '=', $searchId);
        }

        if ($request->has('searchEmail')) {
            $searchEmail = $request->input('searchEmail');
            $leadsQuery->where('email', 'LIKE', "%$searchEmail%");
        }

        if ($request->has('searchPhone')) {
            $searchPhone = $request->input('searchPhone');
            $leadsQuery->where('phone', 'LIKE', "%$searchPhone%");
        }

        if ($request->has('searchName')) {
            $searchName = $request->input('searchName');
            $leadsQuery->where('name', 'LIKE', "%$searchName%");
        }

        if ($request->has('searchAffiliate')) {
            $searchAffiliate = $request->input('searchAffiliate');
            $leadsQuery->where('Affiliate', 'LIKE', "%$searchAffiliate%");
        }

        if ($request->has('searchAdvert')) {
            $searchAdvert = $request->input('searchAdvert');
            $leadsQuery->where('Advert', 'LIKE', "%$searchAdvert%");
        }

        if ($user->hasRole('sale') || $user->hasRole('retention_manager')) {
            $id = $user->id;
            $leadsQuery->where('user_id', $id);
        }

        if ($user->hasRole('teamlead') || $user->hasRole('retention_teamlead')) {
            $leadsQuery->whereHas('user', function ($query) use ($user) {
                $query->where('team_id', $user->team_id);
            });
        }

//        $leadsQuery->whereHas('user', function ($query) {
//            $query->where('deleted', false);
//        });

        $leads = $leadsQuery->paginate($perPage);

        $leadsCount = $leads->total();

        // Получение всех необходимых данных для фильтров
        $countries = Country::all();

        $statuses = Status::all()->map(function ($status) {
            $status->prefixed_id = 'status_' . $status->id;
            return $status;
        });

        $retention_statuses = RetentionStatus::all()->map(function ($status) {
            $status->prefixed_id = 'retention_' . $status->id;
            return $status;
        });

        $all_statuses = $statuses->concat($retention_statuses);
        $statuses = Status::all();
        $retention_statuses = RetentionStatus::all();

        $users = User::query();
        $users->where('deleted', false);
        $teams = Team::query();
        $desks = Desk::query();

        if ($user->hasRole('super-admin')) {
            // ничего не фильтруем, так как super-admin может видеть всех
            $users = $users->get();
            $teams = $teams->get();
            $desks = $desks->get();
        } elseif ($user->hasRole('head')) {

            $users = $users->where('desk_id', $user->desk_id)
                ->get();
            $teams = $teams->where('desk_id', $user->desk_id)->get();
            $desks = $desks->where('desk_id', $user->desk_id)->get();
        } elseif ($user->hasRole('retention_teamlead')) {
            $users = $users->where('team_id', $user->team_id)
                ->get();
            $teams = $teams->where('team_id', $user->team_id)->get();
            $desks = $desks->where('desk_id', $user->desk_id)->get();
        } elseif ($user->hasRole('teamlead')) {
//        } elseif ($user->hasRole('teamlead') || $user->hasRole('retention_manager')) { - ритены видят других ритенов
            $users = $users->where('team_id', $user->team_id)
                ->get();
            $teams = $teams->where('team_id', $user->team_id)->get();
            $desks = $desks->where('desk_id', $user->desk_id)->get();
        } elseif ($user->hasRole('retention_manager')) {
        }
        $myLeads = false;
        return view('super-admin.leads2', compact('leads',
            'statuses', 'users', 'countries', 'teams', 'desks', 'myLeads',
            'retention_statuses', 'all_statuses', 'leadsCount'));
    }


    public function getLeads(Request $request, $userId)
    {
        $perPage = $request->input('per_page', 10);
        $leads = Lead::whereNull('user_id')
            ->orWhere('user_id', $userId)
            ->orderByRaw('CASE WHEN user_id = ? THEN 0 ELSE 1 END', [$userId])
            ->paginate($perPage);

        return view('super-admin.userLeads', compact('leads', 'userId'));
    }

//    контроллер для назначения лидов, который сейчас не используется,
//    чтобы использовать на раскоментировать конпку на виде, где отображаются все пользователи
    public function leadsAssign(Request $request)
    {

        $userId = $request->input('userId');
        //лиды с галочокой
        $leads = $request->input('assigned_leads', []);
        //все лиды на странице
        $allLeadsOnPage = $request->input('all_leads_on_page', []);
        //лиды без галочки
        $leadsToNullify = array_diff($allLeadsOnPage, $leads);

        //лидам без галочки ставиться null в user_id
        Lead::whereIn('id', $leadsToNullify)->update(['user_id' => null]);

        //лиды в базе данных где user_id == userId
        $leadsWithUserId = Lead::where('user_id', $userId)->get();

        //массив user_id с бд
        $ids = $leadsWithUserId->pluck('id')->toArray();

        //массив id лидов которые есть в $leads но нет в $ids
        $leadsNotInIds = array_diff($leads, $ids);

        Lead::whereIn('id', $leadsNotInIds)->update([
            'user_id' => $userId,
            'user_id_updated_at' => now() // Обновляем дату
        ]);

        if ($request->input('paginationUrl')) {
            $redirectTo = $request->input('paginationUrl');
            return redirect($redirectTo);
        }
        return back()->with('success', 'Leads successfully assigned!');
    }


    public function massleadsAssign(Request $request)
    {

        $userId = $request->input('userId');
        $leads = explode(',', $request->input('assigned_leads', ''));

        $currentUserId = auth()->user()->id;

        foreach ($leads as $leadId) {
            $lead = Lead::find($leadId);

            if ($lead) {
                $originalUserId = $lead->user_id;

                $lead->user_id = $userId;
                $lead->user_id_updated_at = now();
                $lead->created_by = $currentUserId;
                $lead->save();

                if ($originalUserId !== $userId) {
                    $lead->logChange('user_id_changed', $originalUserId, $userId);
                }
            }
        }

        if ($request->input('paginationUrl')) {
            $redirectTo = $request->input('paginationUrl');
            return redirect($redirectTo);
        }

        return back()->with('success', 'Лиды сохранены');
    }


    public function leadsAssignPlus(Request $request)
    {
        $cur_user = Auth::user();


        $check = false;

        if ($cur_user->hasRole('retention_manager') || $cur_user->hasRole('sale')) {
            $check = true;
        }
        //пресечение внесений изменений для сейлов и ритенов для страницы лида
        if ($check) {
            $previousUrl = $request->headers->get('referer');

            if ($previousUrl) {
                $parsedUrl = parse_url($previousUrl);

                if (isset($parsedUrl['path'])) {
                    $cleanedPath = preg_replace('#/[\d]+$#', '', $parsedUrl['path']);

                    if ($cleanedPath == '/showLeadPage') {
                        $lead_id = ($request->input('lead_id'));
                        $lead = Lead::where('id', $lead_id)->first();

                        if ($lead->user_id != auth()->user()->id) {
                            return redirect()->route('showLeads');
                        }
                    }
                }
            }
        }

        $email = $request->input('email');
        $leadCombaindeStatuses = $request->input('lead_combined_statuses');
        $leadStatuses = $request->input('lead_statuses', []);
        $leadNotes = $request->input('lead_notes', []);
        $leadPayments = $request->input('lead_payments', []);
        $leadUsers = $request->input('lead_user', []);
        $leadRetentionStatuses = $request->input('lead_retention_statuses', []);
        $idsArray = json_decode($request->input('selectedLeads'), true);


        //переприсваивание лидов
        //оставить только это, статусы и темки для страницы лида
        //это надо только для массового присваивания лидов
//        if (!empty($leadUsers)) {
//            foreach ($leadUsers as $leadId => $user) {
//                $lead = Lead::find($leadId);
//                if ($check) {
//                    if ($lead->user_id != $cur_user->id) {
//                        continue;
//                    }
//                }
//
//                if ($lead && $lead->user_id != $user) {
//                    $oldStatus = $lead->user_id;
//                    $lead->user_id = ($user && $user !== 'null') ? $user : null;
//                    $lead->user_id_updated_at = now();
//                    $lead->save();
//
//                    if ($user != 'null') {
//                        $lead->logChange('user_id_changed', $oldStatus, $user);
//                    }
//
//                }
//            }
//        }
        //статусы начало

        //так как все статусы меняеются фетчем, при отправке формы их не надо менять
        //но при массовом изминении все работает через форму и чтобы предотвратить
        //ошибки и баги происходит фильтрация лидов, которым надо менять статус
        //то есть меняется статус только у выбранных лидов


        if ($request->input('isMass') === 'true') {

            if (!empty($leadCombaindeStatuses)) {
                $leadCombaindeStatuses = collect($leadCombaindeStatuses)->filter(function ($value, $key) use ($idsArray) {
                    return in_array((string)$key, $idsArray);
                })->all();
            }

            if (!empty($leadStatuses)) {
                $leadStatuses = collect($leadStatuses)->filter(function ($value, $key) use ($idsArray) {
                    return in_array((string)$key, $idsArray);
                })->all();
            }

            if (!empty($leadRetentionStatuses)) {
                $leadRetentionStatuses = collect($leadRetentionStatuses)->filter(function ($value, $key) use ($idsArray) {
                    return in_array((string)$key, $idsArray);
                })->all();
            }

            $cur_user = Auth::user();
            if ($leadRetentionStatuses) {

                foreach ($leadRetentionStatuses as $lead => $status) {
                    $lead = Lead::find($lead);

                    if ($lead && $lead->status != $status) {
                        $oldStatus = $lead->status;
                        $lead->retention_status = $status;
                        $lead->save();
                        $lead->logChange('retention_status_changed', $oldStatus, $status);
                    }
                }
            }

            if ($leadStatuses) {

                foreach ($leadStatuses as $lead => $status) {
                    $lead = Lead::find($lead);

                    if ($lead && $lead->status != $status) {
                        $oldStatus = $lead->status;
                        $lead->status = $status;
                        $lead->save();
                        $lead->logChange('status_changed', $oldStatus, $status);
                    }
                }
            }


            if (!$cur_user->hasRole('super-admin') && !$cur_user->hasRole('head')) {

                if (!empty($leadStatuses)) {

                    foreach ($leadStatuses as $leadId => $status) {
                        $lead = Lead::find($leadId);

                        if ($lead && $lead->status != $status) {
                            $oldStatus = $lead->status;

                            if ($lead->status == 24 && $status != 24) {
                                $lead->retention_status = null;
                            }

                            if ($status == 24) {
                                $lead->logChange('status_changed', $oldStatus, $status);
                                $lead->logChange('placed_on_retention');
                                $lead->retention_status = 1;
                                $lead->user_id = 102; //retention_pool
                            }
                            $lead->status = $status;
                            $lead->save();

                        }
                    }
                }
            } else {
                if (!empty($leadCombaindeStatuses)) {

                    foreach ($leadCombaindeStatuses as $leadId => $combinedStatus) {
                        $lead = Lead::find($leadId);

                        // Декодирование статуса
                        $type = strpos($combinedStatus, "retention_") === 0 ? 'retention' : 'status';
                        $statusId = (int)str_replace(['status_', 'retention_'], '', $combinedStatus); // Получаем ID статуса

                        if ($type == 'retention') {
                            $oldStatus = $lead->retention_status;
                            $lead->status = 24;
                            $lead->retention_status = $statusId;
                            $lead->save();
                            $lead->logChange('retention_status_changed', $oldStatus, $statusId);
                        } else {
                            $oldStatus = $lead->status;

//                            if ($lead->status == 24 && $statusId != 24) {
                            if ($statusId != 24) {
                                $lead->retention_status = null;
                                $lead->user_id = 101; // добавление в sale_pool
                                $lead->save();
                            }

                            if ($statusId == 24) {
                                $lead->logChange('placed_on_retention');
                                $lead->retention_status = 1;
                                $lead->user_id = 102; // добавление в retention_pool
                                $lead->save();
                            }

                            if ($lead->status != $statusId) {
                                $lead->status = $statusId;
                                $lead->save();
                                $lead->logChange('status_changed', $oldStatus, $statusId);
                            }
                        }
                    }
                }
            }
        }

        //добавление ценности лида
        if (!empty($leadPayments)) {
            foreach ($leadPayments as $leadId => $payment) {

                if ($this->prohibit_changing($leadId)) {
                    continue;
                }
                $lead = Lead::find($leadId);

                if ($lead && $lead->payment !== $payment && $payment) {
                    if (!is_numeric($payment)) {
                        return redirect()->back()->with('errors', 'Сюда нельзя писать букавы, пишите циферы!');
                    }
                    $lead->update([
                        'payment' => $payment,
                    ]);

                    $lead->leadPayments()->create([
                        'amount' => $payment,
                        'user_id' => $cur_user->id,
                    ]);

                    $cur_user->balance += $payment;

                    $cur_user->save();

                    $lead->logChange('payment_added', $payment);
                }
            }
        }

        //добавление коментария лида
        if (!empty($leadNotes)) {

            foreach ($leadNotes as $leadId => $note) {
                if ($this->prohibit_changing($leadId)) {
                    continue;
                }
                $lead = Lead::find($leadId);

                $note = nl2br(strip_tags($note));

                if ($lead && $note) {
                    $lead->update([
                        'note' => $note,
                        'note_updated_at' => now()
                    ]);

                    $lead->leadComments()->create([
                        'user_id' => auth()->id(),
                        'body' => $note
                    ]);


                    $note = 1;

                    $lead->logChange('comment_added', $note);
                }
            }
        }

        $cur_user = Auth::user();


        //страница лида
        $is_lead_page = $request->input('is_lead_page');

        if ($is_lead_page) {

            //изменение почты лида
            if ($email) {

                $lead = Lead::find($request->input("lead_id"));

                if ($this->prohibit_changing($request->input("lead_id"))) {
                    return redirect()->route('showLeads');
                }

                $current_email = $lead->email;

                if ($current_email != $email) {
                    $lead_with_email = Lead::where('email', $email)->first();

                    if ($lead_with_email) {
                        return redirect()->back()->with('errors', 'Лид с такой почтой уже сущестувет!');
                    }

                    $platform_user = PlatformUser::where('email', $current_email)->first();

                    if ($platform_user) {
                        $platform_user->email = $email;
                        $platform_user->save();
                    }

                    $lead->email = $email;
                    $lead->save();
                }

            }

            $id = $request->input('lead_id');
            $lead = Lead::find($id);

            if (!$lead) {
                return redirect()->back()->with('errors', 'Лид не найден!');
            }

            if ($request->filled('affiliate')) {
                $lead->affiliate = $request->input('affiliate');
            }

            if ($request->filled('advert')) {
                $lead->advert = $request->input('advert');
            }

            if ($request->filled('name')) {
                $lead->name = $request->input('name');
            }

            if ($request->filled('phone')) {
                $lead->phone = $request->input('phone');
            }

//            if ($request->filled('country_id')) {
//                $lead->country_id = $request->input('country_id');
//            }

            $lead->save();

        }

        if ($request->input('paginationUrl')) {
            $redirectTo = $request->input('paginationUrl');
            return redirect($redirectTo);
        }

        return back()->with('success', 'Ліди успішно збережені!');
    }

    public function creationLeadPage()
    {

        $users = User::where('role', '!=', 'super-admin')->get();
        $countries = Country::all();
        $statuses = Status::all();
        return view('super-admin.create-lead', compact('users', 'countries', 'statuses'));
    }

    public function createLead(Request $request)
    {
        try {
            $request->phone = cleanPhoneNumber($request->phone);

            $lead = Lead::where('phone', $request->phone)->first();

            if ($lead) {
                $userId = Auth::user()->id;
                Log::info('ПИЗДЕЦ!', ['user' => $userId, 'lead' => $lead->id, 'phone' => $request->phone, 'lead_phone' => $lead->phone]);
                return redirect()->back()->with('errors', 'Лид c таким номером уже существует!');
            }

            $request->validate([
                'name' => 'required',
                'email' => 'required|unique:leads',
                'phone' => 'required|numeric|unique:leads',
                'status_id' => 'required',
                'country_id' => 'required|integer',
            ]);

            $sales_id = $request->sales_id;
            if ($sales_id == 'free') {
                $sales_id = null;
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $sales_id,
                'country_id' => $request->country_id,
                'Affiliate' => $request->Affiliate,
                'Advert' => $request->Advert,
                'lead_value' => $request->lead_value,
                'status' => $request->status_id,
            ];

            if ($sales_id !== null) {
                $data['created_by'] = auth()->user()->id;
                $data['user_id_updated_at'] = now();
            }

            $lead = Lead::create($data);

            if ($sales_id !== null) {
                $lead->logChange('user_id_changed', null, $sales_id);
            }

            return redirect()->back()->with('success', 'Лід додано!');
        } catch (\Exception $e) {
            Log::error('СМЭРТЬ!', [
                'phone' => $request->phone,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return redirect()->back()->with('errors', 'Произошла ошибка при создании лида!');
        }
    }

    public function deleteLead($leadId)
    {
        $lead = Lead::find($leadId);

        if ($lead) {
            $lead->delete();
            return redirect()->back()->with('success', 'Лид успешно удален!');
        } else {
            return redirect()->back()->with('errors', 'Лид не найден!');
        }
    }

    public function deleteMassLeads(Request $request)
    {
        $leadIds = $request->input('leadIds');


        $leadIds = array_filter($leadIds, function ($value) {
            return is_numeric($value);
        });


        if (!$leadIds || !is_array($leadIds)) {
            return redirect()->back()->with('errors', 'Неверные данные!');
        }

        $deletedCount = Lead::whereIn('id', $leadIds)->delete();

        if ($deletedCount > 0) {
            return redirect()->back()->with('success', "Успешно удалено {$deletedCount} лидов.");
        } else {
            return redirect()->back()->with('errors', 'Лиды не найдены!');
        }
    }

    public function leadsImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leadsFile' => 'required|mimes:csv,txt'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('leadsFile');
        $filename = time() . '-' . $file->getClientOriginalName();
        $location = storage_path('uploads/leads');
        $file->move($location, $filename);

        // Чтение CSV-файла
        $filepath = $location . '/' . $filename;
        $file = fopen($filepath, 'r');
        $headers = fgetcsv($file);

        $errors = [];

        // Получение и обработка выбранного статуса для импортированных лидов
        $defaultStatus = $request->input('defaultStatus');
        $type = strpos($defaultStatus, "retention_") === 0 ? 'retention' : 'status';
        $statusId = (int)str_replace(['status_', 'retention_'], '', $defaultStatus);

        while (($row = fgetcsv($file)) !== FALSE) {
            $leadData = [
                'name' => !empty($row[0]) ? $row[0] : null,
                'phone' => !empty($row[1]) ? cleanPhoneNumber($row[1]) : null,
                'email' => !empty($row[2]) ? $row[2] : null,
                'country_id' => null,
                'Affiliate' => !empty($row[4]) ? $row[4] : null,
            ];

            if ($type == 'retention') {
                $leadData['retention_status'] = $statusId;
            } else {
                $leadData['status'] = $statusId;
            }

            if (Lead::where('email', $leadData['email'])->exists()) {
                continue;
            }

            if (Lead::where('phone', $leadData['phone'])->exists()) {
                continue;
            }

            // Поиск страны и установка её ID
            $country = DB::table('countries')->where('country', $row[3])->first();
            if ($country) {
                $leadData['country_id'] = $country->country_id;
            } else {
                // Здесь используется json_encode для корректного отображения ошибки.
                $error = json_encode("Страна {$row[3]} не найдена.");
                $errors[] = json_decode($error);
                continue;
            }

            Lead::create($leadData);
        }

        fclose($file);
        unlink($filepath);

        if (!empty($errors)) {
            return redirect()->back()->with('errors', 'Ошибке при импорте!');
        }

        return back()->with('success', 'Лиды успешно импортированы!');
    }

    public function deleteComment($id)
    {
        $comment = LeadComment::find($id);

        if ($this->prohibit_changing($comment->lead_id)) {
            return redirect()->route('showLeads');
        }

        if ($comment) {
            $comment->delete();
            return redirect()->back()->with('success', 'Коментар видалено!');
        } else {
            return redirect()->back()->with('errors', 'Коментарий не найден!');
        }
    }

    public function addComment(Request $request, $leadId)
    {
        $lead = Lead::find($leadId);

        if (!$lead) {
            return back()->with('errors', 'Лид не найден!');
        }

        $request->validate([
            'comment' => 'required|string'
        ]);

        $lead->leadComments()->create([
            'user_id' => auth()->id(),
            'body' => $request->comment,
        ]);

        return back()->with('success', 'Комментарий успешно добавлен!');
    }

    public function getComments($leadId)
    {
        if (!Auth::user()->hasPermissionTo('see users comments')) {
            Log::info('Request data received in store method:', ['data' => false]);
            return response()->json('отказано');
        }
        Log::info('Request data received in store method:', ['data' => true]);

        $comments = LeadComment::where('lead_id', $leadId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        $commentsArray = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'user_name' => $comment->user->name,  // добавлено имя пользователя
                'lead_id' => $comment->lead_id,
                'body' => $comment->body,
                'created_at' => $comment->created_at,
            ];
        });
        return response()->json($commentsArray);
    }

    public function setStatus(Request $request)
    {
        $leadId = $request->input('lead_id');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }

        $newStatusId = $request->input('status');

        $lead = Lead::find($leadId);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found!'], 404);
        }

        $oldStatus = $lead->status;

        $lead->status = $newStatusId;

        $lead->save();


        $lead->logChange('status_changed', $oldStatus, $newStatusId);


        if ($newStatusId == 24) {
            $lead->logChange('placed_on_retention');
            $lead->retention_status = 1;
            $lead->user_id = 102;  // переключение на пользователя с ID 102
            $lead->user_id_updated_at = now();
            $lead->save();
        } else {
            $statusObject = Status::find($newStatusId);

            if ($statusObject) {
                $color = $statusObject->color;
            }

            return response()->json(['message' => 'Status updated successfully!', 'color' => $color, 'leadId' => $leadId]);
        }

        return response()->json(['message' => 'Lead not found!'], 404);
    }

    public function setRetentionStatus(Request $request)
    {
        $leadId = $request->input('leadId');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }

        $newStatusId = $request->input('retentionStatusId');

        $lead = Lead::find($leadId);

        if ($lead) {

            $oldStatus = $lead->retention_status;

            DB::table('leads')->where('id', $leadId)->update(['retention_status' => $newStatusId]);

            $lead->logChange('retention_status_changed', $oldStatus, $newStatusId);

            $statusObject = RetentionStatus::find($newStatusId);
            if ($statusObject) {
                $color = $statusObject->color;
            }

            return response()->json(['message' => $newStatusId, 'color' => $color, 'leadId' => $leadId]);
        }

        return response()->json(['message' => 'Lead not found!'], 404);

    }

    public function setCombinedStatus(Request $request)
    {
        $curUser = $request->input('curUser');
        $leadId = $request->input('leadId');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }

        $selectedStatus = $request->input('selectedStatus');
        $lead = Lead::find($leadId);

        if (!$lead) {
            return response()->json(['message' => 'Lead not found!'], 404);
        }

        // Декодирование статуса
        $type = Str::startsWith($selectedStatus, 'retention_') ? 'retention' : 'status';
        $statusId = (int)str_replace(['status_', 'retention_'], '', $selectedStatus);

        //изменение ретеншн статуса
        if ($type == 'retention') {
            $oldStatus = $lead->retention_status;

            if ($lead->retention_status != $statusId) {
                $lead->retention_status = $statusId;
                $lead->user_id_updated_at = now();
                if ($lead->status != 24) {
                    $lead->status = 24;
                }
                $lead->save();
                $lead->logChange('retention_status_changed', $oldStatus, $statusId);
            }
        } else {
            //изменение статуса и перекидание в sale_pool
            $oldStatus = $lead->status;

            if ($lead->status == 24 && $statusId != 24) {
                // if ($statusId != 24) {
                $lead->retention_status = null;
                $lead->user_id = 101; // добавление в sale_pool
                $lead->user_id_updated_at = now();
                $lead->save();
            }

            //изменениея статуса на депозит и перекидание в retention_pool
            if ($statusId == 24) {
                $lead->logChange('placed_on_retention');
                $lead->retention_status = 1;
                $lead->user_id = 102; // добавление в retention_pool
                $lead->user_id_updated_at = now();

            }
            //простоое изменение статуса
            if ($lead->status != $statusId) {
                $lead->status = $statusId;
                $lead->logChange('status_changed', $oldStatus, $statusId);
                $lead->save();
            }
        }

        $color = null; // Инициализация переменной для цвета

        // Если это статус удержания
        if ($type == 'retention') {
            $statusObject = RetentionStatus::find($statusId);
            if ($statusObject) {
                $color = $statusObject->color;
            }
        } // Если это обычный статус
        else {
            $statusObject = Status::find($statusId);
            if ($statusObject->id == 24) {
                $color = 'rgba(192, 192, 192, 0.5)';
            } elseif ($statusObject) {
                $color = $statusObject->color;
            }
        }

        return response()->json([
            'message' => 'Status updated successfully',
            'userId' => $lead->user_id,
            'color' => $color, // Добавляем цвет в ответ
            'retentionStatusUpdated' => true
        ]);
    }

    public function reassignment(Request $request)
    {
        $leadId = $request->input('leadId');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }

        $newUser = $request->input('selectedUser');

        $lead = Lead::find($leadId);

        if ($lead) {

            $oldUSer = $lead->user_id;

//            DB::table('leads')->where('id', $leadId)->update(['user_id' => $newUser]);
            if ($newUser === 'null') {
                $newUser = null;
            }

            DB::table('leads')
                ->where('id', $leadId)
                ->update([
                    'user_id' => $newUser,
                    'user_id_updated_at' => now()
                ]);

            $lead->logChange('user_id_changed', $oldUSer, $newUser);

            return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'Lead not found!'], 404);

    }

    public function changeCountry(Request $request)
    {
        $leadId = $request->input('leadId');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }

        $selectedCountry = $request->input('selectedCountry');

        $lead = Lead::find($leadId);

        if ($lead) {

//            $oldUSer = $lead->user_id;

            DB::table('leads')->where('id', $leadId)->update(['country_id' => $selectedCountry]);

//            $lead->logChange('user_id_changed', $oldUSer, $newUser);

            return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'Lead not found!'], 404);

    }

    public function addCommentFetch(Request $request)
    {

        $leadId = $request->input('leadId');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }

        $note = $request->input('comment');

        $lead = Lead::find($leadId);

        if ($lead) {

            if ($this->prohibit_changing($leadId)) {
                return response()->json([false]);
            }

            $note = nl2br(strip_tags($note));

            if ($note) {
                $lead->update([
                    'note' => $note,
                    'note_updated_at' => now()
                ]);

                $lead->leadComments()->create([
                    'user_id' => auth()->id(),
                    'body' => $note
                ]);


                $note = 1;

                $lead->logChange('comment_added', $note);
            }

            return response()->json(['message' => 'Lead not found!']);
        }

        return response()->json(['message' => 'Lead not found!'], 404);
    }

    public function addValueFetch(Request $request)
    {
        $leadId = $request->input('leadId');

        $payment = $request->input('value');

        if ($this->prohibit_changing($leadId)) {
            return response()->json([false]);
        }
        $lead = Lead::find($leadId);
        $cur_user = Auth::user();

        if ($lead && $lead->payment !== $payment && $payment) {
            if (!is_numeric($payment)) {
                return response()->json([false]);
            }
            $lead->update([
                'payment' => $payment,
            ]);

            $lead->leadPayments()->create([
                'amount' => $payment,
                'user_id' => $cur_user->id,
            ]);

            $cur_user->balance += $payment;

            $cur_user->save();

            $lead->logChange('payment_added', $payment);
        }
        return response()->json(['value' => $lead->lead_value + $payment]);
    }

    private function prohibit_changing($lead_id)
    {
        $cur_user = Auth::user();

        if ($cur_user->hasRole('retention_manager') || $cur_user->hasRole('sale')) {

            $lead = Lead::where('id', $lead_id)->first();

            if ($lead->user_id != $cur_user->id) {
                return true;
            }
        }
    }

}
