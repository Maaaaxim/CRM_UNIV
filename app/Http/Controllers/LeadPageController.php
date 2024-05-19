<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\RetentionStatus;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LeadPageController extends Controller
{
    public function showLeadPage(Request $request, $id)
    {

        $headers = [
            "created" => "Створено лід",
            "user_id_changed" => "Лід передано",
            "retention_status_changed" => "Змінено статус ретенша",
            "comment_added" => "Додано коментар",
            "status_changed" => "Змінено статус",
            "placed_on_retention" => "Передано на ретенш",
            "payment_added" => "Додано платіж",
            "added_balance" => "Баланс на біржі збільшено"
        ];

        $actions = [
            "created" => function ($change) {
                return " - ";
            },
            "user_id_changed" => function ($change) {
                return " от " . optional($change->oldUser)->name . " до " . optional($change->newUser)->name;
            },
            "retention_status_changed" => function ($change) {
                return " c " . optional($change->oldRetentionStatus)->name . " на " . optional($change->newRetentionStatus)->name;
            },
            "comment_added" => function ($change) {
                return " - ";
            },
            "status_changed" => function ($change) {
                return " c " . optional($change->oldStatus)->name . " на " . optional($change->newStatus)->name;
            },
            "placed_on_retention" => function ($change) {
                return " - ";
            },
            "payment_added" => function ($change) {
                return $change->old_value;
            },
            "added_balance" => function ($change) {
                return " на " . $change->old_value . "$";
            },
        ];


        $user = Auth::user();

        $lead = Lead::find($id);

        if ($user->hasRole('sale') || $user->hasRole('retention_manager')) {
            if ($user->id != $lead->user_id) {
                return redirect()->route('showLeads');
            }
        }
        if ($user->hasRole('teamlead') || $user->hasRole('retention_teamlead')) {
            $userIds = User::where('team_id', $user->team_id)->pluck('id');
            if ($user->id != $lead->user_id && !$userIds->contains($lead->user_id)) {
                return redirect()->route('showLeads');
            }
        }


        if (!$lead->viewed) {
            $lead->viewed = true;
            $lead->save();
        }

        $current_lead_id = $id;
        $referrer = request()->headers->get('referer');
        $parsedUrl = parse_url($referrer);
        $path = $parsedUrl['path'] ?? '';

        if ($path === '/myLeads' || $path === '/showLeads') {
            Cache::forget("leads_ids_$user->id");
            session(['previous_link' => $referrer]);
        }

        // Проверяем наличие ссылки в сессии
        $previousLink = session('previous_link');

        if ($previousLink) {
            // Если есть ссылка в сессии, заменяем текущий объект $request на данные из этой ссылки
            $parsedPreviousLink = parse_url($previousLink);
            parse_str($parsedPreviousLink['query'] ?? '', $previousParams);

            // Мержим запросы так, чтобы параметры из ссылки в сессии заменяли текущие параметры
            $request->merge($previousParams);
        }


        $leadsQuery = Lead::with(['user', 'status']);

//начало фильтров----------------------------

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
            $ussr_id = $user->id;
            $leadsQuery->where('user_id', $ussr_id);
        }

        if ($user->hasRole('teamlead') || $user->hasRole('retention_teamlead')) {
            $leadsQuery->whereHas('user', function ($query) use ($user) {
                $query->where('team_id', $user->team_id);
            });
        }
//конец фильтров----------------------------
        $ids = Cache::get("leads_ids_$user->id");

        if (!$ids) {
            $leads = $leadsQuery->get();
            $ids = $leads->pluck('id')->toArray();

            Cache::put("leads_ids_$user->id", $ids, 216000);

        }

        $currentIdIndex = array_search($id, $ids);

       $previousId = $ids[$currentIdIndex - 1] ?? null;
        $nextId = $ids[$currentIdIndex + 1] ?? null;

        $linkforward = $nextId ? route('showLeadPage', ['id' => $nextId]) : '#';
        $linkback = $previousId ? route('showLeadPage', ['id' => $previousId]) : '#';


        $lead = Lead::with([
            'user',
            'country',
            'createdBy',
            'status',
            'retention_status',
            'leadComments' => function ($query) {
                $query->orderBy('created_at', 'desc'); // или другое поле, по которому вы хотите сортировать
            },
            'changes' => function ($query) {
                $query->orderBy('change_date', 'desc');
            },
            'changes.user',
            'changes.oldUser',
            'changes.newUser',
            'changes.oldStatus',
            'changes.newStatus',
            'changes.oldRetentionStatus',
            'changes.newRetentionStatus',
        ])->find($id);


        if (!$lead) {
            return back()->with('errors', 'Лид не найден!');
        }

        $status = Status::find($lead->status);
        $comments = $lead->leadComments;
        $changes = $lead->changes;
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
        $users = User::where('deleted', false)->get();
//        $users = User::all();

        $notifications = Notification::where('user_id', Auth::user()->id)
            ->where('lead_id', $lead->id)
            ->where('time', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super-admin.leadPage', compact('lead', 'status',
            'comments', 'changes', 'linkforward', 'linkback', 'countries', 'all_statuses',
            'statuses', 'retention_statuses', 'users', 'headers', 'actions', 'notifications'));
    }


    public function showLeadComments($id)
    {
        $lead = Lead::with([
            'leadComments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'leadComments.user'
        ])->find($id);

        if (!$lead) {
            return back()->with('errors', 'Лид не найден!');
        }

        $comments = $lead->leadComments;
        return view('super-admin.leadComments', compact('comments', 'lead'));
    }

    public function showLeadPaymanets($id)
    {
        $lead = Lead::with(['leadPayments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$lead) {
            return back()->with('errors', 'Лид не найден!');
        }

        $payments = $lead->leadPayments;
        return view('super-admin.leadPayments', compact('payments', 'lead'));
    }


    public function showLeadHistory($id)
    {

        $lead = Lead::with([
            'changes' => function ($query) {
                $query->orderBy('change_date', 'desc');
            },
            'changes.user',
            'changes.oldUser',
            'changes.newUser',
            'changes.oldStatus',
            'changes.newStatus'
        ])->find($id);


        if (!$lead) {
            return back()->with('errors', 'Лид не найден!');
        }

        $changes = $lead->changes;

        return view('super-admin.leadChanges', compact('changes', 'lead'));
    }

    public function updateLead(Request $request)
    {
        $id = $request->input('lead_id');
        $lead = Lead::find($id);

        if (!$lead) {
            return redirect()->back()->with('errors', 'Лид не найден!');
        }

        // Обновляем поля, если они предоставлены
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

        if ($request->filled('country_id')) {
            $lead->country_id = $request->input('country_id');
        }

        $lead->save();

        return redirect()->back()->with('success', 'Лид успешно обновлён!');
    }

}
