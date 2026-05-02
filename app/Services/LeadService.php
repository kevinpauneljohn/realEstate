<?php

namespace App\Services;

use App\Lead;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class LeadService
{
    public function datatable($leads)
    {
        return DataTables::of($leads)

            /* ===============================
             | DATE INQUIRED
             =============================== */
            ->editColumn('date_inquired', function (Lead $lead) {
                return optional($lead->date_inquired)->format('m-d-Y h:i a');
            })

            /* ===============================
             | LAST CONTACTED
             =============================== */
            ->addColumn('last_contacted', function (Lead $lead) {
                if ($lead->LogTouches->count() > 0) {
                    return '<span class="text-muted small">'
                        . e($lead->LogTouches->last()->date->diffForHumans())
                        . '</span>';
                }
                return '<span class="text-muted small">—</span>';
            })

            /* ===============================
             | NAME (CRM STYLE)
             =============================== */
            ->addColumn('fullname', function (Lead $lead) {
                return '
                <div class="d-flex flex-column">
                    <a href="' . route('leads.show', $lead->id) . '" class="fw-semibold text-primary">
                        ' . e($lead->fullname) . '
                    </a>
                    <small class="text-muted">
                        ' . e($lead->email ?? 'No email') . '
                    </small>
                </div>';
            })

            /* ===============================
             | PHONE
             =============================== */
            ->editColumn('mobileNo', function (Lead $lead) {
                if (!$lead->mobileNo) {
                    return '<span class="text-muted">—</span>';
                }

                return '<a href="tel:' . e($lead->mobileNo) . '" class="text-decoration-none">'
                    . e($lead->mobileNo)
                    . '</a>';
            })

            /* ===============================
             | EMAIL (HIDDEN / SUBTEXT READY)
             =============================== */
            ->editColumn('email', function (Lead $lead) {
                return $lead->email
                    ? '<a href="mailto:' . e($lead->email) . '">' . e($lead->email) . '</a>'
                    : '<span class="text-muted">—</span>';
            })

            /* ===============================
             | SOURCE
             =============================== */
            ->editColumn('point_of_contact', function (Lead $lead) {
                return '<span class="badge badge-light">'
                    . e($lead->point_of_contact ?? 'N/A')
                    . '</span>';
            })

            /* ===============================
             | IMPORTANT (STAR)
             =============================== */
            ->editColumn('important', function (Lead $lead) {
                if ($lead->important) {
                    return '<i class="fas fa-star text-warning"></i>';
                }
                return '';
            })

            /* ===============================
             | STATUS BADGE
             =============================== */
            ->editColumn('lead_status', function (Lead $lead) {
                return $this->statusBadge($lead->lead_status);
            })

            /* ===============================
             | ASSIGNED TO
             =============================== */
            ->editColumn('assigned_to', function (Lead $lead) {

                if (auth()->user()->hasRole(['admin','account manager','super admin'])) {

                    $html = '<select class="form-control form-control-sm assigned_to" id="' . $lead->id . '">';
                    $html .= '<option value="">Unassigned</option>';

                    $users = User::whereHas('roles', function ($q) {
                        $q->whereIn('name', ['online warrior', 'account manager']);
                    })->get();

                    foreach ($users as $user) {
                        $selected = $lead->online_warrior_id === $user->id ? 'selected' : '';
                        $html .= '<option value="' . $user->id . '" ' . $selected . '>'
                            . e($user->username)
                            . '</option>';
                    }

                    $html .= '</select>';
                    return $html;
                }

                return $lead->warrior
                    ? e($lead->warrior->fullname)
                    : '<span class="text-muted">—</span>';
            })

            /* ===============================
             | ACTIONS (CLEAN CRM)
             =============================== */
            ->addColumn('action', function (Lead $lead) {

                $buttons = '<div class="btn-group btn-group-sm">';

                if (auth()->user()->can('view lead')) {
                    $buttons .= '
                    <a href="' . route('leads.show', $lead->id) . '"
                       class="btn btn-light"
                       title="Open">
                        <i class="fas fa-folder-open"></i>
                    </a>';
                }

                if (auth()->user()->can('edit lead')) {
                    $buttons .= '
                    <a href="' . route('leads.edit', $lead->id) . '"
                       class="btn btn-light"
                       title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';
                }

                if (
                    auth()->user()->can('delete lead') &&
                    $lead->sales()->count() < 1
                ) {
                    $buttons .= '
                    <button class="btn btn-light text-danger delete-lead-btn"
                            id="' . $lead->id . '"
                            title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>';
                }

                $buttons .= '</div>';

                return $buttons;
            })

            ->rawColumns([
                'fullname',
                'mobileNo',
                'email',
                'important',
                'lead_status',
                'assigned_to',
                'last_contacted',
                'action',
                'point_of_contact'
            ])

            ->make(true);
    }

    /* ===============================
     | STATUS BADGE MAPPER
     =============================== */
    private function statusBadge(string $status): string
    {
        return match ($status) {
            'New' => '<span class="badge badge-primary">New</span>',
            'Hot' => '<span class="badge badge-danger">Hot</span>',
            'Warm' => '<span class="badge badge-warning">Warm</span>',
            'Cold' => '<span class="badge badge-info">Cold</span>',
            'Reserved' => '<span class="badge badge-primary">Reserved</span>',
            'Closed / Sold' => '<span class="badge badge-success">Closed / Sold</span>',
            default => '<span class="badge badge-secondary">' . e($status) . '</span>',
        };
    }
}
