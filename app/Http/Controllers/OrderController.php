<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Helpers\ExcelHelper;
use App\Http\Requests\ExportRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Utils\Transformer;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->wantsJson()) {
            return view('orders', ['title' => 'Orders']);
        }

        return DataTables::of(Order::query())
                            ->addColumn('actions', function (Order $order) {
                                return view('components.datatables.actions-button', [
                                    'item' => $order,
                                    'item_title' => 'Id ' . $order->id,
                                    'datatable' => '#dt-orders',
                                    'show_modal' => '#detail-order-modal',
                                    'show_url' => route('orders.show', ['order' => $order->id]),
                                    'delete_url' => route('orders.destroy', ['order' => $order->id]),
                                ]);
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $details = $order->detail_orders()->with('thumbnail:product_id,path')->get();
        $buyer = $order->user;
        $transaction = $order->transaction;

        return view('components.order.detail-order-modal', [
            'order' => $order,
            'details' => $details,
            'buyer' => $buyer,
            'transaction' => $transaction,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();

            return Transformer::success('Success to delete order data.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to delete order data.');
        }
    }

    /**
     * Update status order to finish.
     *
     * @param   Order  $order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function finish(Order $order)
    {
        try {
            $order->update([
                'status' => 'delivered'
            ]);

            return Transformer::success('Success to update order status.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to update order status.');
        }
    }

    /**
     * Export Orders data.
     *
     * @param   ExportRequest  $exportRequest
     *
     * @return  \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(ExportRequest $exportRequest)
    {
        if ($exportRequest->showView()) {
            return view('components.export-modal', [
                'action' => route('orders.export'),
                'formats' => ExcelHelper::$allowed_export_formats,
            ]);
        }

        $payload = $exportRequest->all();

        return Excel::download(
            new OrdersExport($payload['take']),
            ExcelHelper::formatExportName('orders', $payload['format'])
        );
    }
}
