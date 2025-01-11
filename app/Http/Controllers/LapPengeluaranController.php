<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Pengeluaran;
use App\Models\purchase;
use Illuminate\Http\Request;
use App\Exports\pengeluaranExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LapPengeluaranController extends Controller
{
    public function pengeluaran(Request $request)
	{
		$startDate = $request->input('start');
        $endDate = $request->input('end');

		if ($startDate && !$endDate) {
			return redirect('reports/pengeluaran');
		}

		if (!$startDate && $endDate) {
			return redirect('reports/pengeluaran');
		}

		if ($startDate && $endDate) {
			if (strtotime($endDate) < strtotime($startDate)) {
				return redirect('reports/pengeluaran');
			}

			$earlier = new \DateTime($startDate);
			$later = new \DateTime($endDate);
			$diff = $later->diff($earlier)->format("%a");
			
			if ($diff >= 31) {
				return redirect('reports/pengeluaran');
			}
		} else {
			$currentDate = date('Y-m-d');
			$startDate = date('Y-m-01', strtotime($currentDate));
			$endDate = date('Y-m-t', strtotime($currentDate));
		}
		$startDate = $startDate;
        $endDate = $endDate;

        $reports = [];
        $pengeluaran = 0;
        $total_pengeluaran = 0;

        while (strtotime($startDate) <= strtotime($endDate)) {
            $date = $startDate;
            $startDate = date('Y-m-d', strtotime("+1 day", strtotime($startDate)));

            $pengeluaran = Pengeluaran::where('tanggal_bayar', 'LIKE', "%$date%")->sum('total'); 
			$purchase = Purchase::where('tanggal_beli', 'LIKE', "%$date%")->sum('total'); 

            $total_pengeluaran += $pengeluaran + $purchase;

            $row = [];
            $row['date'] = $date;
            $row['pengeluaran'] = $pengeluaran + $purchase;
            $reports[] = $row;
		}
		
		if ($exportAs = $request->input('export')) {
			if (!in_array($exportAs, ['xlsx', 'pdf'])) {
				return redirect()->route('reports.pengeluaran');
			}

			if ($exportAs == 'xlsx') {
				$fileName = 'report-pengeluaran_'. $startDate .'_'. $endDate .'.xlsx';

				return Excel::download(new PengeluaranExport($reports, $total_pengeluaran), $fileName);
			}

			if ($exportAs == 'pdf') {
				$startDate = $request->input('start');
				$fileName = 'report-pengeluaran_'. $startDate .'_'. $endDate .'.pdf';
				$pdf = PDF::loadView('reports.exports.pengeluaran-pdf', compact('reports','total_pengeluaran','startDate','endDate'));

				return $pdf->download($fileName);
			}
        }

        // dd($reports);

		return view('reports.pengeluaran', compact('reports','total_pengeluaran','startDate','endDate'));
	}
}
