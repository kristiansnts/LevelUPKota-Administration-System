<?php

return [
    'roles' => [
        'super_admin' => 'super_admin',
        'guest' => 'guest',
    ],
    'resources' => [
        'mail' => [
            'label' => 'Catatan Arsip',
            'group' => 'Arsip',
            'slug' => 'mail',
        ],
        'transaction' => [
            'create' => 'Catat Transaksi',
            'edit' => 'Ubah Transaksi',
            'delete' => 'Hapus Transaksi',
        ],
        'paymentMethod' => [
            'create' => 'Tambah Metode Pembayaran',
            'edit' => 'Ubah Metode Pembayaran',
            'delete' => 'Hapus Metode Pembayaran',
        ],
        'transactionCategory' => [
            'create' => 'Tambah Kategori Transaksi',
            'edit' => 'Ubah Kategori Transaksi',
            'delete' => 'Hapus Kategori Transaksi',
        ],
        'transactionPeriod' => [
            'create' => 'Tambah Periode Transaksi',
            'edit' => 'Ubah Periode Transaksi',
            'delete' => 'Hapus Periode Transaksi',
        ],
    ],
];
