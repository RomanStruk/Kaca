<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Kaca\Contracts\Receipt\CreatesReceipts;
use Kaca\Models\Receipt;
use Kaca\Models\ReceiptGood;

class RefundReceiptsController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Handle an incoming refund receipt request.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Receipt $receipt, CreatesReceipts $createsReceipts): RedirectResponse
    {
        $this->authorize('create', $receipt);

        $createsReceipts->with([
            'order_id' => $receipt->order_id,
            'reverse_compatibility_data' => $receipt->reverse_compatibility_data,
            'related_receipt_id' => $receipt->id,
        ]);

        $goods = $receipt->receiptGoods
            ->map(function (ReceiptGood $receiptGood) {
                $receiptGood = $receiptGood->replicate();
                $receiptGood->is_return = true;
                return $receiptGood;
            });

        $refundReceipt = $createsReceipts->create(
            $request->user(),
            Str::uuid()->toString(),
            $receipt->delivery,
            $goods,
            $receipt->receiptPayments->map->replicate()
        );

        return redirect(request('redirect_to', route('kaca.receipts.show', $refundReceipt)))
            ->with('message', 'Чек доданий в чергу на повернення!');
    }
}
