<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Purchase;
use App\Models\Pengeluaran;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\LabarugiExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LabarugiController extends Controller
{
    public function laba_rugi(Request $request)
	{
		$startDate = $request->input('start');
        $endDate = $request->input('end');

		if ($startDate && !$endDate) {
			return redirect('reports/laba_rugi');
		}

		if (!$startDate && $endDate) {
			return redirect('reports/laba_rugi');
		}

		if ($startDate && $endDate) {
			if (strtotime($endDate) < strtotime($startDate)) {
				return redirect('reports/laba_rugi');
			}

			$earlier = new \DateTime($startDate);
			$later = new \DateTime($endDate);
			$diff = $later->diff($earlier)->format("%a");
			
			if ($diff >= 31) {
				return redirect('reports/laba_rugi');
			}
		} else {
			$currentDate = date('Y-m-d');
			$startDate = date('Y-m-01', strtotime($currentDate));
			$endDate = date('Y-m-t', strtotime($currentDate));
		}
		$startDate = $startDate;
        $endDate = $endDate;

        $reports = [];
        $laba_rugi = 0;
        $total_laba_rugi = 0;

        while (strtotime($startDate) <= strtotime($endDate)) {
            $date = $startDate;
            $startDate = date('Y-m-d', strtotime("+1 day", strtotime($startDate)));

            $revenue = Transaction::where('created_at', 'LIKE', "%$date%")->sum('total_price'); 
            $purchase = Purchase::where('tanggal_beli', 'LIKE', "%$date%")->sum('total'); 
            $pengeluaran = Pengeluaran::where('tanggal_bayar', 'LIKE', "%$date%")->sum('total'); 

            $laba_rugi = $revenue - $purchase - $pengeluaran;
            $total_laba_rugi += $laba_rugi;

            $row = [];
            $row['date'] = $date;
            $row['revenue'] = $revenue;
            $row['purchase'] = $purchase;
            $row['pengeluaran'] = $pengeluaran;
            $row['laba_rugi'] = $laba_rugi;
            $reports[] = $row;
		}
		
		if ($exportAs = $request->input('export')) {
			if (!in_array($exportAs, ['xlsx', 'pdf'])) {
				return redirect()->route('reports.laba_rugi');
			}

			if ($exportAs == 'xlsx') {
				$fileName = 'report-laba_rugi_'. $startDate .'_'. $endDate .'.xlsx';

				return Excel::download(new LabarugiExport($reports, $total_laba_rugi), $fileName);
			}

			if ($exportAs == 'pdf') {
				$startDate = $request->input('start');
				$fileName = 'report-laba_rugi_'. $startDate .'_'. $endDate .'.pdf';
				$pdf = PDF::loadView('reports.exports.laba_rugi-pdf', compact('reports','total_laba_rugi','startDate','endDate'));

				return $pdf->download($fileName);
			}
        }

        // dd($reports);

		return view('reports.laba_rugi', compact('reports','total_laba_rugi','startDate','endDate'));
	}
}
