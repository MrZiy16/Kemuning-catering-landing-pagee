<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Customer::with('user')->paginate(10);
        $users = User::where('role', 'pelanggan')->get();
        return view('admin.customer.index', compact('customers', 'users'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        $users = User::where('role', 'pelanggan')->get();
        return view('admin.customer.create', compact('users'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:customer,user_id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'source' => 'required|in:online,offline'
        ]);

        Customer::create([
            'user_id' => $request->user_id,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'source' => $request->source
        ]);

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Customer berhasil ditambahkan!');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load('user', 'transaksi');
        return view('admin.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $users = User::where('role', 'pelanggan')->get();
        return view('admin.customer.edit', compact('customer', 'users'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:customer,user_id,' . $customer->id_customer . ',id_customer',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'source' => 'required|in:online,offline'
        ]);

        $customer->update([
            'user_id' => $request->user_id,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'source' => $request->source
        ]);

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Customer berhasil diperbarui!');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        // Cek apakah customer memiliki transaksi
        if ($customer->transaksi()->count() > 0) {
            return back()->with('error', 'Customer tidak dapat dihapus karena sudah memiliki transaksi!');
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')
                        ->with('success', 'Customer berhasil dihapus!');
    }

    /**
     * Get customer statistics
     */
    public function statistics()
    {
        $totalCustomers = Customer::count();
        $onlineCustomers = Customer::online()->count();
        $offlineCustomers = Customer::offline()->count();
        $totalTransaksi = Customer::sum('total_transaksi');

        return view('admin.customer.statistics', compact(
            'totalCustomers',
            'onlineCustomers',
            'offlineCustomers',
            'totalTransaksi'
        ));
    }

    /**
     * Export customers to CSV
     */
    public function export()
    {
        $customers = Customer::with('user')->get();

        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, [
            'ID Customer',
            'Nama',
            'Email',
            'No HP',
            'Alamat',
            'Source',
            'User ID',
            'Total Transaksi',
            'Total Belanja',
            'Tanggal Dibuat'
        ]);

        // Data
        foreach ($customers as $customer) {
            fputcsv($handle, [
                $customer->id_customer,
                $customer->nama,
                $customer->email,
                $customer->no_hp,
                $customer->alamat,
                $customer->source_text,
                $customer->user_id ?? '-',
                $customer->getTotalTransaksi(),
                'Rp ' . number_format($customer->getTotalBelanja(), 0, ',', '.'),
                $customer->created_at->format('Y-m-d H:i:s')
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Search customers
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $customers = Customer::where('nama', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('no_hp', 'like', "%{$query}%")
                            ->with('user')
                            ->paginate(15);

        $users = User::where('role', 'pelanggan')->get();
        return view('admin.customer.index', compact('customers', 'users'));
    }
}
