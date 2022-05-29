<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Kaca\Contracts\CheckboxEntries;
use Kaca\Contracts\Receipt\CreatesReceipts;
use Kaca\Helpers\ReceiptGoodCollection;
use Kaca\Http\Request\CreateReceiptRequest;
use Kaca\Models\Receipt;
use Kaca\Models\ReceiptGood;
use function view;

class ReceiptsController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the receipts.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request): View
    {
        $this->authorize('view', new Receipt());

        $receipts = Receipt::query();
        if ($request->has('order_id')) {
            $receipts->where('order_id', $request->order_id);
        }
        $receipts = $receipts->with(['receiptGoods'])->latest()->paginate();
        return view('kaca::receipts.index', compact('receipts'));
    }

    /**
     * Display the create receipt view.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request): View
    {
        $this->authorize('preview', new Receipt());

        $validated = $request->validate([
            'goods' => ['nullable', 'array',],
            'goods.*.code' => ['required', 'string',],
            'goods.*.name' => ['required', 'string', 'min:1',],
            'goods.*.quantity' => ['required', 'numeric', 'min:1',],
            'goods.*.price' => ['required', 'numeric', 'min:0.01',],
        ]);
        $totalSum = collect($validated['goods'] ?? [])->sum(function ($good) {
            return ($good['price'] ?? 0) * ($good['quantity'] ?? 1);
        });

        return view('kaca::receipts.create', compact('totalSum'));
    }

    /**
     * Handle an incoming receipt request.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateReceiptRequest $request, CreatesReceipts $createsReceipts): RedirectResponse
    {
        $this->authorize('create', new Receipt());

        $goods = ReceiptGoodCollection::make($request->goods)
            ->map(function (array $data) {
                return new ReceiptGood($data);
            });
        $receipt = $createsReceipts
            ->with([
                'order_id' => $request->get('order_id'),
                'reverse_compatibility_data' => $request->get('reverse_compatibility_data'),
            ])->create(
                $request->user(),
                $request->get('id', Str::uuid()->toString()),
                $request->get('deliveries'),
                $goods
            );

        return redirect(request('redirect_to', route('kaca.receipts.show', $receipt)))
            ->with('message', 'Чек успішно відправлений на опрацювання');
    }

    /**
     * Show the receipt view.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, Receipt $receipt)
    {
        $this->authorize('view', $receipt);

        $shift = $receipt->shift;
        $cashier = $shift->cashier;
        $entries = app(CheckboxEntries::class)->paginateWith($receipt);

        $this->authorize('view', $receipt);

        if ($request->wantsJson()) {
            return response()->json($receipt->toArray());
        }

        return view('kaca::receipts.show', compact('receipt', 'shift', 'cashier', 'entries'));
    }
}
