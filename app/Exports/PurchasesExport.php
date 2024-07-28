<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return a collection of purchases.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Purchase::with(['materials.material', 'user', 'approved', 'supplier'])->get();
    }

    /**
     * Map data for each row.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return array
     */
    public function map($purchase): array
    {
        $mapped = [];

        foreach ($purchase->materials as $index => $material) {
            $mapped[] = [
                'Purchase ID' => $index === 0 ? $purchase->id : '',
                'Description' => $index === 0 ? $purchase->description : '',
                'Requester User ID' => $index === 0 ? $purchase->user_id : '',
                'Requester Name' => $index === 0 ? $purchase->user->name ?? 'N/A' : '',
                'Supplier ID' => $index === 0 ? $purchase->supplier_id : '',
                'Supplier Name' => $index === 0 ? $purchase->supplier->name ?? 'N/A' : '',
                'Approved By User ID' => $index === 0 ? $purchase->approved_by : '',
                'Approved By Name' => $index === 0 ? $purchase->approved->name ?? 'N/A' : '',
                'Approved At' => $index === 0 ? $purchase->approved_at : '',
                'Status' => $index === 0 ? $purchase->status : '',
                'Created At' => $index === 0 ? $purchase->created_at : '',
                'Updated At' => $index === 0 ? $purchase->updated_at : '',
                'Material ID' => $material->material_id,
                'Material Name' => $material->material->name,
                'Quantity' => $material->qty,
            ];
        }

        return $mapped;
    }

    /**
     * Set the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Purchase ID',
            'Description',
            'Requester User ID',
            'Requester Name',
            'Supplier ID',
            'Supplier Name',
            'Approved By User ID',
            'Approved By Name',
            'Approved At',
            'Status',
            'Created At',
            'Updated At',
            'Material ID',
            'Material Name',
            'Quantity',
        ];
    }
}
