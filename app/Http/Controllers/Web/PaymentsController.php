<?php
declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Models\Invoice;
use App\Services\PaymentsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PaymentsController extends Controller
{
    public function __construct(private readonly PaymentsService $payments)
    {
    }

    public function index(): View
    {
        $payments = $this->payments->paginate();
        return view('admin.payments.index', compact('payments'));
    }

    public function store(Invoice $invoice, StorePaymentRequest $request): RedirectResponse
    {
        $this->payments->store($invoice, $request->validated());
        return back()->with('status', __('messages.success_created'));
    }

    public function data()
    {
        return DataTables::eloquent(\App\Models\Payment::query())
            ->toJson();
    }
}


