<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Helpers\ExcelHelper;
use App\Helpers\MoneyHelper;
use App\Http\Requests\ExportRequest;
use App\Models\Transaction;
use App\Utils\Transformer;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->wantsJson()) {
            return view('transactions', [
                'title' => 'Transactions',
            ]);
        }

        return DataTables::of(Transaction::query())
                            ->addColumn('actions', function (Transaction $transaction) {
                                return view('components.datatables.actions-button', [
                                    'item' => $transaction,
                                    'item_title' => 'Id ' . $transaction->id,
                                    'datatable' => '#dt-transactions',
                                    'delete_url' => route('transactions.destroy', ['transaction' => $transaction->id])
                                ]);
                            })
                            ->editColumn('total', function (Transaction $transaction) {
                                return MoneyHelper::convertToRupiah($transaction->total);
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();

            return Transformer::success('Success to delete transaction.');
        } catch (\Throwable $th) {
            return Transformer::failed('Failed to delete transaction.');
        }
    }

    /**
     * Export transactions data.
     *
     * @param   ExportRequest  $exportRequest
     *
     * @return  \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(ExportRequest $exportRequest)
    {
        if ($exportRequest->showView()) {
            return view('components.export-modal', [
                'action' => route('transactions.export'),
                'formats' => ExcelHelper::$allowed_export_formats,
            ]);
        }

        $payload = $exportRequest->only(['take', 'format']);
        
        return Excel::download(
            new TransactionsExport($payload['take']),
            ExcelHelper::formatExportName('transactions', $payload['format']),
        );
    }
}
