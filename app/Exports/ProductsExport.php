<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Product::with('category');

        // Apply filters
        if (isset($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (isset($this->filters['is_active'])) {
            $query->where('is_active', $this->filters['is_active']);
        }

        if (isset($this->filters['low_stock'])) {
            $query->where('stock', '<=', 10);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'Product Name',
            'Category',
            'Price (Rp)',
            'Stock',
            'Status',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->name,
            $product->category->name ??  '-',
            number_format($product->price, 0, ',', '.'),
            $product->stock,
            $product->is_active ? 'Active' : 'Inactive',
            $product->created_at->format('d M Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Products';
    }
}
